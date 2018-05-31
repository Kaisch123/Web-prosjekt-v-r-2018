<?php
/**
 * Displays Breadcrumb
 *
 * @package CatchThemes
 * @subpackage Fotografie
 * @since 1.0
 * @version 1.0
 */

$enable_breadcrumb = get_theme_mod( 'fotografie_breadcrumb_option', 1 );

if ( $enable_breadcrumb ) :
	fotografie_breadcrumb();
endif; ?>
