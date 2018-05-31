<?php

if( ! function_exists( 'fotografie_posts_list' ) ) {

	/**
	 * Create array of posts used for customizer
	 *
	 * @return string array of posts
	 */
	function fotografie_posts_list() {

		$args = array(
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => 'post_title',
			'cache_results'  => true,
		);

		$posts  = get_posts( $args );
		$output['0']= esc_html__( '-- Select --', 'fotografie' );

		if( ! empty($posts) ) {
			foreach( (array) $posts as $post ) {
				/* translators: 1: post id. */
		$output[ $post->ID ] = ! empty( $post->post_title ) ? $post->post_title : sprintf( __( '#%d (no title)', 'fotografie' ), $post->ID );
			}
		}
		return $output;
	}

}


/**
 * Returns an array of visibility options for featured sections
 *
 * @since Fotografie 1.1
 */
function fotografie_section_visibility_options() {
	$options = array(
		'homepage'    => esc_html__( 'Homepage / Frontpage', 'fotografie' ),
		'entire-site' => esc_html__( 'Entire Site', 'fotografie' ),
		'disabled'    => esc_html__( 'Disabled', 'fotografie' ),
	);

	return apply_filters( 'fotografie_section_visibility_options', $options );
}

/**
 * Returns an array of section types
 *
 * @since Fotografie 1.1
 */
function fotografie_section_type_options() {
	$options = array(
		'demo'     => esc_html__( 'Demo', 'fotografie' ),
		'page'     => esc_html__( 'Page', 'fotografie' ),
	);

	return apply_filters( 'fotografie_section_type_options', $options );
}

/**
 * Returns an array of color schemes registered for catchresponsive.
 *
 * @since Fotografie 1.1
 */
function fotografie_get_pagination_types() {
	$pagination_types = array(
		'default'         => esc_html__( 'Default(Older Posts/Newer Posts)', 'fotografie' ),
		'numeric'         => esc_html__( 'Numeric', 'fotografie' ),
		'infinite-scroll' => esc_html__( 'Infinite Scroll', 'fotografie' ),
	);

	return apply_filters( 'fotografie_get_pagination_types', $pagination_types );
}
