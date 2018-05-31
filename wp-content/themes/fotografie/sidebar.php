<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fotografie
 */

$layout = get_theme_mod( 'fotografie_default_layout', 'no-sidebar' );

if ( 'no-sidebar' === $layout ) {
	return;
}

?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
