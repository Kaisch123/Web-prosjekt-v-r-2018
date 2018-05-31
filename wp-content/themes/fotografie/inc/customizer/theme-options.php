<?php
/**
 * Fotografie Theme Options
 *
 * @package Fotografie
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fotografie_theme_options( $wp_customize ) {
	$wp_customize->add_panel( 'fotografie_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'fotografie' ),
		'priority' => 130,
	) );

	// Breadcrumb Option.
	$wp_customize->add_section( 'fotografie_breadcrumb_options', array(
		'description'   => esc_html__( 'Breadcrumbs are a great way of letting your visitors find out where they are on your site with just a glance. You can enable/disable them on homepage and entire site.', 'fotografie' ),
		'panel'         => 'fotografie_theme_options',
		'title'         => esc_html__( 'Breadcrumb', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_breadcrumb_option', array(
		'default'           => 1,
		'sanitize_callback' => 'fotografie_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'fotografie_breadcrumb_option', array(
		'label'    => esc_html__( 'Check to enable Breadcrumb', 'fotografie' ),
		'section'  => 'fotografie_breadcrumb_options',
		'type'     => 'checkbox',
	) );

	// Layout Options.
	$wp_customize->add_section( 'fotografie_layout_options', array(
		'title' => esc_html__( 'Layout Options', 'fotografie' ),
		'panel' => 'fotografie_theme_options',
	) );

	/* Layout Type */
	$wp_customize->add_setting( 'fotografie_layout_type', array(
		'default'           => 'boxed',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_layout_type', array(
		'label'    => esc_html__( 'Site Layout', 'fotografie' ),
		'section'  => 'fotografie_layout_options',
		'type'     => 'radio',
		'choices'  => array(
			'fluid' => esc_html__( 'Fluid', 'fotografie' ),
			'boxed' => esc_html__( 'Boxed', 'fotografie' ),
		),
	) );

	/* Default Layout */
	$wp_customize->add_setting( 'fotografie_default_layout', array(
		'default'           => 'no-sidebar',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_default_layout', array(
		'description' => esc_html__( 'Layout for Singular Post Types like Post, Page', 'fotografie' ),
		'label'       => esc_html__( 'Singular Content Layout', 'fotografie' ),
		'section'     => 'fotografie_layout_options',
		'type'        => 'radio',
		'choices'     => array(
			'left-sidebar' => esc_html__( 'Left Sidebar ( Primary Sidebar, Content )', 'fotografie' ),
			'no-sidebar'   => esc_html__( 'No Sidebar', 'fotografie' ),
		),
	) );

	// Excerpt Options.
	$wp_customize->add_section( 'fotografie_excerpt_options', array(
		'panel'     => 'fotografie_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_excerpt_length', array(
		'default'           => '45',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'fotografie_excerpt_length', array(
		'description' => esc_html__( 'Excerpt length. Default is 45 words', 'fotografie' ),
		'input_attrs' => array(
			'min'   => 10,
			'max'   => 200,
			'step'  => 5,
			'style' => 'width: 60px;',
			),
		'label'    => esc_html__( 'Excerpt Length (words)', 'fotografie' ),
		'section'  => 'fotografie_excerpt_options',
		'type'     => 'number',
		)
	);

	$wp_customize->add_setting( 'fotografie_excerpt_more_text', array(
		'default'           => esc_html__( 'Continue reading', 'fotografie' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'fotografie_excerpt_more_text', array(
		'label'    => esc_html__( 'Read More Text', 'fotografie' ),
		'section'  => 'fotografie_excerpt_options',
		'type'     => 'text',
	) );

	// Homepage / Frontpage Options.
	$wp_customize->add_section( 'fotografie_homepage_options', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'fotografie' ),
		'panel'       => 'fotografie_theme_options',
		'title'       => esc_html__( 'Homepage / Frontpage Options', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_latest_posts_title', array(
		'default'           => esc_html__( 'News', 'fotografie' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'fotografie_latest_posts_title', array(
		'label'   => esc_html__( 'Latest Posts Title', 'fotografie' ),
		'section' => 'fotografie_homepage_options',
	) );

	$wp_customize->add_setting( 'fotografie_front_page_category', array(
		'default'           => array(),
		'sanitize_callback' => 'fotografie_sanitize_category_list',
	) );

	$wp_customize->add_control( new Fotografie_Multi_Categories( $wp_customize, 'fotografie_front_page_category', array(
		'label'           => esc_html__( 'Select Categories', 'fotografie' ),
		'name'            => 'fotografie_front_page_category',
		'section'         => 'fotografie_homepage_options',
		'type'            => 'dropdown-categories',
	) ) );

    // Disable Recent post in static frontpage
    $wp_customize->add_setting( 'fotografie_enable_static_page_posts', array(
        'sanitize_callback' => 'fotografie_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'fotografie_enable_static_page_posts', array(
        'label'           => esc_html__( 'Check to enable Latest Posts on Static Frontpage', 'fotografie' ),
        'section'         => 'fotografie_homepage_options',
        'type'            => 'checkbox',
    ) );

	// Pagination Options.
	$pagination_type = get_theme_mod( 'fotografie_pagination_type', 'default' );

	$nav_desc = '';

	/**
	* Check if navigation type is Jetpack Infinite Scroll and if it is enabled
	*/
	$nav_desc = sprintf(
		wp_kses(
			__( 'Infinite Scroll Options requires %1$sJetPack Plugin%2$s with Infinite Scroll module Enabled.', 'fotografie' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="https://wordpress.org/plugins/jetpack/">',
		'</a>'
	);

	$nav_desc .= '&nbsp;' . sprintf(
		wp_kses(
			__( 'Once Jetpack is installed, Infinite Scroll Settings can be found %1$shere%2$s', 'fotografie' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=jetpack#/settings' ) ) . '">',
		'</a>'
	);

	$wp_customize->add_section( 'fotografie_pagination_options', array(
		'description'   => $nav_desc,
		'panel'         => 'fotografie_theme_options',
		'title'         => esc_html__( 'Pagination Options', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_pagination_type', array(
		'default'           => 'default',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_pagination_type', array(
		'choices'  => fotografie_get_pagination_types(),
		'label'    => esc_html__( 'Pagination type', 'fotografie' ),
		'section'  => 'fotografie_pagination_options',
		'type'     => 'select',
	) );

	/* Scrollup Options */
	$wp_customize->add_section( 'fotografie_scrollup', array(
		'panel'    => 'fotografie_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_disable_scrollup', array(
		'sanitize_callback' => 'fotografie_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'fotografie_disable_scrollup', array(
		'label'     => esc_html__( 'Disable Scroll Up', 'fotografie' ),
		'section'   => 'fotografie_scrollup',
		'type'      => 'checkbox',
	) );
}
add_action( 'customize_register', 'fotografie_theme_options' );
