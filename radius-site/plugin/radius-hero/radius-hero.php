<?php
/**
 * Plugin Name:       Radius Hero
 * Plugin URI:        https://radiuswebdesign.com
 * Description:       The cinematic scroll hero and landing page for Radius Web Design. Choose the "Radius Hero (full page)" page template, or use the [radius_hero] shortcode in a Divi Text module. Media lives in this plugin's assets folder, so no URLs need editing.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Radius Web Design
 * License:           GPL-2.0-or-later
 * Text Domain:       radius-hero
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RADIUS_HERO_VERSION', '1.1.0' );
define( 'RADIUS_HERO_DIR', plugin_dir_path( __FILE__ ) );
define( 'RADIUS_HERO_URL', plugin_dir_url( __FILE__ ) );
define( 'RADIUS_HERO_TEMPLATE', 'radius-hero-full.php' );

/**
 * The markup ships with placeholder media paths. Swap them for this plugin's
 * own assets directory so the page works wherever it is installed, on any
 * domain, with no URLs to edit by hand.
 */
function radius_hero_markup() {
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

/* ---------------------------------------------------------------------------
 * Route 1: the page template. The most reliable route, because it bypasses
 * the theme, the builder and the content filters entirely. A full-bleed
 * cinematic page does not want a builder wrapping it anyway.
 * ------------------------------------------------------------------------ */

function radius_hero_register_template( $templates ) {
	$templates[ RADIUS_HERO_TEMPLATE ] = __( 'Radius Hero (full page)', 'radius-hero' );
	return $templates;
}
add_filter( 'theme_page_templates', 'radius_hero_register_template' );

function radius_hero_load_template( $template ) {
	if ( ! is_singular() ) {
		return $template;
	}

	$chosen = get_post_meta( get_the_ID(), '_wp_page_template', true );

	if ( RADIUS_HERO_TEMPLATE !== $chosen ) {
		return $template;
	}

	$file = RADIUS_HERO_DIR . 'templates/' . RADIUS_HERO_TEMPLATE;

	return is_readable( $file ) ? $file : $template;
}
add_filter( 'template_include', 'radius_hero_load_template', 99 );

/* ---------------------------------------------------------------------------
 * Route 2: the shortcode. Works in a Divi TEXT module, the block editor and
 * the classic editor. It does NOT work in a Divi CODE module, because Divi's
 * Code module deliberately does not run shortcodes.
 * ------------------------------------------------------------------------ */

add_shortcode( 'radius_hero', 'radius_hero_markup' );

/**
 * wpautop runs on the_content before shortcodes expand and wraps the tag in a
 * paragraph, which puts a <p> around a full-page div. Drop it on posts that
 * use the shortcode.
 */
function radius_hero_protect_markup( $content ) {
	global $post;

	if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'radius_hero' ) ) {
		remove_filter( 'the_content', 'wpautop' );
	}

	return $content;
}
add_filter( 'the_content', 'radius_hero_protect_markup', 1 );

/* ---------------------------------------------------------------------------
 * Route 3: the standalone preview, for diagnosis. Visiting
 * yoursite.co.uk/?radius_hero=preview renders the page on its own, with no
 * theme, no builder and no page needed. If it works here but not on your
 * page, the plugin is fine and the problem is how the page is set up.
 * ------------------------------------------------------------------------ */

function radius_hero_preview() {
	if ( empty( $_GET['radius_hero'] ) || 'preview' !== $_GET['radius_hero'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'X-Robots-Tag: noindex, nofollow', true );

	echo '<!DOCTYPE html><html lang="en-GB"><head><meta charset="utf-8">';
	echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
	echo '<meta name="robots" content="noindex,nofollow">';
	echo '<title>Radius Hero preview</title>';
	echo '<style>html,body{margin:0;padding:0;background:#17120E}</style>';
	echo '</head><body>';
	echo radius_hero_markup(); // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</body></html>';
	exit;
}
add_action( 'template_redirect', 'radius_hero_preview' );

/* ------------------------------------------------------------------------ */

/**
 * Tell the user what is and is not ready, on the plugins screen.
 */
function radius_hero_admin_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'plugins' !== $screen->id ) {
		return;
	}

	$missing = array();

	foreach ( array( 'hero-scrub.mp4', 'hero-poster.jpg', 'step-01.jpg', 'step-02.jpg', 'step-03.jpg' ) as $asset ) {
		if ( ! file_exists( RADIUS_HERO_DIR . 'assets/' . $asset ) ) {
			$missing[] = $asset;
		}
	}

	printf(
		'<div class="notice notice-info"><p><strong>Radius Hero</strong> is active. Preview it at <a href="%1$s">%1$s</a>, or make a page and choose the <em>Radius Hero (full page)</em> template.</p>%2$s</div>',
		esc_url( home_url( '/?radius_hero=preview' ) ),
		$missing
			? '<p>' . esc_html( sprintf(
				/* translators: %s: comma separated list of file names */
				__( 'Still to add to the plugin assets folder: %s. The page shows its still hero until the video is there.', 'radius-hero' ),
				implode( ', ', $missing )
			) ) . '</p>'
			: ''
	);
}
add_action( 'admin_notices', 'radius_hero_admin_notice' );
