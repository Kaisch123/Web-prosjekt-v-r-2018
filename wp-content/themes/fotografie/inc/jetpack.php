<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.me/
 *
 * @package Fotografie
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.me/support/infinite-scroll/
 * See: https://jetpack.me/support/responsive-videos/
 */
function fotografie_jetpack_setup() {
	/**
	 * Setup Infinite Scroll using JetPack if navigation type is set
	 */
	$pagination_type = get_theme_mod( 'fotografie_pagination_type', 'default' );

	if ( 'infinite-scroll' === $pagination_type ) {
		add_theme_support( 'infinite-scroll', array(
			'container'      => '#infinite-post-wrap',
			'wrapper'        => false,
			'render'         => 'fotografie_infinite_scroll_render',
			'footer'         => false,
			'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
		) );
	}

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for JetPack Portfolio.
	add_theme_support( 'jetpack-portfolio', array(
		'title'          => true,
		'content'        => true,
		'featured-image' => true,
	) );

	// Add theme support for Content Options.
	add_theme_support( 'jetpack-content-options', array(
		'post-details'    => array(
			'stylesheet' => 'fotografie-style',
			'date'       => '.posted-on',
			'categories' => '.cat-links',
			'tags'       => '.tags-links',
			'author'     => '.byline',
			'comment'    => '.comments-link',
		),
		'featured-images' => array(
			'post'       => true,
			'page'       => true,
		),
	) );
}
add_action( 'after_setup_theme', 'fotografie_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function fotografie_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'components/post/content', get_post_format() );
	}
}

/**
 * Portfolio Title
 *
 * @param  string $before before title content.
 * @param  string $after after title content.
 */
function fotografie_portfolio_title( $before = '', $after = '' ) {
	$jetpack_portfolio_title = get_option( 'jetpack_portfolio_title', esc_html__( 'Projects', 'fotografie' ) );

	$title = '';

	if ( is_post_type_archive( 'jetpack-portfolio' ) ) {
		if ( isset( $jetpack_portfolio_title ) && '' !== $jetpack_portfolio_title ) {
			$title = $jetpack_portfolio_title;
		} else {
			$title = post_type_archive_title( '', false );
		}
	} elseif ( is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
		$title = single_term_title( '', false );
	}

	$title = $before . esc_html( $title ) . $after;

	echo $title;
}

/**
 * Portfolio Content
 *
 * @param  string $before before title content.
 * @param  string $after after title content.
 */
function fotografie_portfolio_content( $before = '', $after = '' ) {
	$jetpack_portfolio_content = get_option( 'jetpack_portfolio_content' );
	$title = '';

	if ( is_tax() && get_the_archive_description() ) {
		$title = $before . get_the_archive_description() . $after;
	} elseif ( isset( $jetpack_portfolio_content ) && '' !== $jetpack_portfolio_content ) {
		$content = convert_chars( convert_smilies( wptexturize( stripslashes( wp_kses_post( addslashes( $jetpack_portfolio_content ) ) ) ) ) );
		$title = $before . $content . $after;
	}

	echo $title;
}

/**
 * Show/Hide Featured Image on single posts view outside of the loop.
 */
function fotografie_jetpack_featured_image_display() {
	if ( ! function_exists( 'jetpack_featured_images_remove_post_thumbnail' ) ) {
		return true;
	} else {
		$options         = get_theme_support( 'jetpack-content-options' );
		$featured_images = ( ! empty( $options[0]['featured-images'] ) ) ? $options[0]['featured-images'] : null;

		$settings = array(
			'post-default' => ( isset( $featured_images['post-default'] ) && false === $featured_images['post-default'] ) ? '' : 1,
			'page-default' => ( isset( $featured_images['page-default'] ) && false === $featured_images['page-default'] ) ? '' : 1,
		);

		$settings = array_merge( $settings, array(
			'post-option'  => get_option( 'jetpack_content_featured_images_post', $settings['post-default'] ),
			'page-option'  => get_option( 'jetpack_content_featured_images_page', $settings['page-default'] ),
		) );

		if ( ( ! $settings['post-option'] && is_single() )
			|| ( ! $settings['page-option'] && is_singular() && is_page() ) ) {
			return false;
		} else {
			return true;
		}
	}
}
