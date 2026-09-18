<?php
/**
 * Plugin Name:       Radius Hero
 * Plugin URI:        https://radiuswebdesign.com
 * Description:       The cinematic scroll hero and landing page for Radius Web Design. Drop the [radius_hero] shortcode into a Divi Code module, or any page, and it renders. Media lives in this plugin's assets folder, so no URLs need editing.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Radius Web Design
 * License:           GPL-2.0-or-later
 * Text Domain:       radius-hero
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Called directly, not through WordPress.
}

define( 'RADIUS_HERO_VERSION', '1.0.0' );
define( 'RADIUS_HERO_DIR', plugin_dir_path( __FILE__ ) );
define( 'RADIUS_HERO_URL', plugin_dir_url( __FILE__ ) );

/**
 * The markup ships with placeholder media paths. Swap them for this plugin's
 * own assets directory so the page works wherever the plugin is installed,
 * on any domain, with no URLs to edit by hand.
 */
function radius_hero_render() {
	$template = RADIUS_HERO_DIR . 'page.html';

	if ( ! is_readable( $template ) ) {
		return current_user_can( 'edit_posts' )
			? '<!-- Radius Hero: page.html is missing from the plugin folder. -->'
			: '';
	}

	$markup = file_get_contents( $template ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	if ( false === $markup ) {
		return '';
	}

	return str_replace(
		'/wp-content/uploads/radius/',
		esc_url_raw( RADIUS_HERO_URL . 'assets/' ),
		$markup
	);
}
add_shortcode( 'radius_hero', 'radius_hero_render' );

/**
 * wpautop runs on the_content at priority 10, before shortcodes expand at 11,
 * and wraps the shortcode tag in a paragraph. Harmless in most themes but it
 * puts a <p> around a full-page div, so drop it when this shortcode is on the
 * post. Checked against the raw post content, which is what exists this early.
 */
function radius_hero_protect_markup( $content ) {
	global $post;

	if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'radius_hero' ) ) {
		remove_filter( 'the_content', 'wpautop' );
	}

	return $content;
}
add_filter( 'the_content', 'radius_hero_protect_markup', 1 );

/**
 * Warn on the plugins screen if the hero video has not been added yet, since
 * that is the one file that cannot ship with the plugin.
 */
function radius_hero_admin_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'plugins' !== $screen->id ) {
		return;
	}

	if ( file_exists( RADIUS_HERO_DIR . 'assets/hero-scrub.mp4' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__(
			'Radius Hero: no hero-scrub.mp4 found in the plugin assets folder. The page will show its still hero until you add one.',
			'radius-hero'
		)
	);
}
add_action( 'admin_notices', 'radius_hero_admin_notice' );
