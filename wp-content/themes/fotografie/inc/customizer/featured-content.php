<?php
/**
 * Add Featured Content options
 *
 * @package Catch Themes
 * @subpackage Fotografie Pro
 * @since Fotografie 1.1
 */

/**
 * Add featured content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fotografie_featured_content_options( $wp_customize ) {
	$wp_customize->add_section( 'featured_content', array(
		'priority'      => 400,
		'title'			=> esc_html__( 'Featured Content', 'fotografie' ),
	) );

	$wp_customize->add_setting( 'fotografie_featured_content_option', array(
		'default'			=> 'homepage',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_featured_content_option', array(
		'choices'  	=> fotografie_section_visibility_options(),
		'label'    	=> esc_html__( 'Enable on', 'fotografie' ),
		'section'  	=> 'featured_content',
		'type'	  	=> 'select',
	) );

	$wp_customize->add_setting( 'fotografie_featured_content_layout', array(
		'default'			=> 'layout-three',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_featured_content_layout', array(
		'active_callback' => 'fotografie_is_featured_content_active',
		'choices'         => fotografie_featured_content_layout_options(),
		'label'           => esc_html__( 'Select Featured Content Layout', 'fotografie' ),
		'section'         => 'featured_content',
		'type'            => 'select',
	) );

	$wp_customize->add_setting( 'fotografie_featured_content_archive_title', array(
		'default'           => esc_html__( 'Featured', 'fotografie' ),
		'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control( 'fotografie_featured_content_archive_title', array(
		'active_callback' => 'fotografie_is_featured_content_active',
		'label'           => esc_html__( 'Featured Content Title', 'fotografie' ),
		'section'         => 'featured_content',
		'type'            => 'text',
	) );

	$wp_customize->add_setting( 'fotografie_featured_content_sub_title', array(
		'sanitize_callback'	=> 'wp_kses_post',
	) );

	$wp_customize->add_control( 'fotografie_featured_content_sub_title' , array(
			'active_callback' => 'fotografie_is_featured_content_active',
			'label'           => esc_html__( 'Featured Content Sub Title', 'fotografie' ),
			'section'         => 'featured_content',
			'type'            => 'text',
		)
	);

	$wp_customize->add_setting( 'fotografie_featured_content_number', array(
		'default'			=> 3,
		'sanitize_callback'	=> 'fotografie_sanitize_number_range',
	) );

	$wp_customize->add_control( 'fotografie_featured_content_number' , array(
			'active_callback' => 'fotografie_is_featured_content_active',
			'description'     => esc_html__( 'Save and refresh the page if No. of Featured Content is changed (Max no of Featured Content is 20)', 'fotografie' ),
			'input_attrs'     => array(
				'style' => 'width: 100px;',
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'label'           => esc_html__( 'No of Featured Content', 'fotografie' ),
			'section'         => 'featured_content',
			'type'            => 'number',
			'transport'       => 'postMessage',
		)
	);

	$number = get_theme_mod( 'fotografie_featured_content_number', 3 );

	// Loop for featured post content.
	for ( $i = 1; $i <= $number ; $i++ ) {
		$wp_customize->add_setting( 'fotografie_featured_content_page_' . $i, array(
			'sanitize_callback' => 'fotografie_sanitize_post',
		) );

		$wp_customize->add_control( 'fotografie_featured_content_page_' . $i, array(
			'active_callback' => 'fotografie_is_featured_content_active',
			'label'           => esc_html__( 'Featured Page', 'fotografie' ) . ' ' . $i,
			'section'         => 'featured_content',
			'type'            => 'dropdown-pages',
		) );
	} // End for().
}
add_action( 'customize_register', 'fotografie_featured_content_options' );


/**
 * Returns an array of featured content options
 *
 * @since Fotografie 1.1
 */
function fotografie_featured_content_layout_options() {
	$options = array(
		'layout-two'   => esc_html__( '2 columns', 'fotografie' ),
		'layout-three' => esc_html__( '3 columns', 'fotografie' ),
		'layout-four'  => esc_html__( '4 columns', 'fotografie' ),
	);

	return apply_filters( 'fotografie_featured_content_layout_options', $options );
}
