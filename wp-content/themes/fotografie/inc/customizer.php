<?php
/**
 * Fotografie Theme Customizer
 *
 * @package Fotografie
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fotografie_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section( 'fotografie_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'fotografie' ),
		'priority' => 130,
	) );

	/* Hero Content Selector */
	$wp_customize->add_setting( 'fotografie_single_image_position', array(
		'sanitize_callback' => 'fotografie_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'fotografie_single_image_position', array(
		'label'           => esc_html__( 'Check to move Single Page/Post Featured Image to header', 'fotografie' ),
		'section'         => 'fotografie_theme_options',
		'type'            => 'checkbox',
	) );
}
add_action( 'customize_register', 'fotografie_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function fotografie_customize_preview_js() {
	wp_enqueue_script( 'fotografie_customizer', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/customizer.js', array( 'customize-preview' ), '20170616', true );
}
add_action( 'customize_preview_init', 'fotografie_customize_preview_js' );
