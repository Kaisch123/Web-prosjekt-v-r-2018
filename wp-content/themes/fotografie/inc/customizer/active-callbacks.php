<?php
/**
 * Active callbacks for Theme/Customzer Options
 *
 * @package Catch Themes
 * @subpackage Fotografie Pro
 * @since Fotografie Pro 1.1
 */

if( ! function_exists( 'fotografie_is_featured_content_active' ) ) :
	/**
	* Return true if featured content is active
	*
	* @since Fotografie Pro 1.1
	*/
	function fotografie_is_featured_content_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'fotografie_featured_content_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( 'entire-site' == $enable || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'homepage' == $enable ) );
	}
endif;
