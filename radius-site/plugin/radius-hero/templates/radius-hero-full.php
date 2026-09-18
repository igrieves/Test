<?php
/**
 * Template Name: Radius Hero (full page)
 *
 * Renders the Radius landing page on its own, with no theme chrome and no
 * builder. wp_head and wp_footer stay in so plugins and analytics still run.
 *
 * @package radius-hero
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>html,body{margin:0;padding:0;background:#17120E}</style>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'radius-hero-page' ); ?>>
<?php
echo radius_hero_markup(); // phpcs:ignore WordPress.Security.EscapeOutput
wp_footer();
?>
</body>
</html>
