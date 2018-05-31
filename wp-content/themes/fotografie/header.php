<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fotografie
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<div class="site-inner">
		
		
		<?php 
echo do_shortcode('[smartslider3 slider=3]');
?>

		<header id="masthead" class="site-header" role="banner">

			<?php get_template_part( 'components/header/header', 'media' ); ?>

			<div class="site-header-main">

				<?php get_template_part( 'components/header/site', 'branding' ); ?>

				<?php get_template_part( 'components/header/site', 'search' ); ?>

				<?php get_template_part( 'components/navigation/navigation', 'top' ); ?>

			</div><!-- .site-header-main -->

		</header>

		<?php get_template_part( 'components/features/breadcrumb/breadcrumb' ); ?>

		<div id="content" class="site-content">

			<?php get_template_part( 'components/features/portfolio/portfolio' ); ?>

			<?php get_template_part( 'components/features/hero-content/content', 'hero' ); ?>

			<?php get_template_part( 'components/features/featured-content/display', 'featured' ); ?>
