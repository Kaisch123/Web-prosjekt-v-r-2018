<?php
/**
 * Fotografie Hero Content Options
 * @package Fotografie
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fotografie_hero_content_options( $wp_customize ) {
	$wp_customize->add_section( 'fotografie_hero_content_options', array(
		'title' => esc_html__( 'Hero Content Options', 'fotografie' ),
		'panel' => 'fotografie_theme_options',
	) );

	$wp_customize->add_setting( 'fotografie_hero_content_visibility', array(
		'default'           => 'homepage',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_hero_content_visibility', array(
		'choices'  => fotografie_section_visibility_options(),
		'label'    => esc_html__( 'Enable on', 'fotografie' ),
		'section'  => 'fotografie_hero_content_options',
		'type'     => 'select',
	) );

	$wp_customize->add_setting( 'fotografie_hero_content', array(
		'default'           => '0',
		'sanitize_callback' => 'fotografie_sanitize_post',
	) );

	$wp_customize->add_control( 'fotografie_hero_content', array(
		'label'   => esc_html__( 'Page', 'fotografie' ),
		'section' => 'fotografie_hero_content_options',
		'type'    => 'dropdown-pages',
	) );
}
add_action( 'customize_register', 'fotografie_hero_content_options' );
