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
	// Important Links.
	class Fotografie_Important_Links extends WP_Customize_Control {
		public $type = 'important-links';

		public function render_content() {
			// Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links.
			$important_links = array(
				'theme_instructions' => array(
					'link'  => esc_url( 'https://catchthemes.com/theme-instructions/fotografie/' ),
					'text'  => esc_html__( 'Theme Instructions', 'fotografie' ),
					),
				'support' => array(
					'link'  => esc_url( 'https://catchthemes.com/support/' ),
					'text'  => esc_html__( 'Support', 'fotografie' ),
					),
				'changelog' => array(
					'link'  => esc_url( 'https://catchthemes.com/changelogs/fotografie-theme/' ),
					'text'  => esc_html__( 'Changelog', 'fotografie' ),
					),
				'facebook' => array(
					'link'  => esc_url( 'https://www.facebook.com/catchthemes/' ),
					'text'  => esc_html__( 'Facebook', 'fotografie' ),
					),
				'twitter' => array(
					'link'  => esc_url( 'https://twitter.com/catchthemes/' ),
					'text'  => esc_html__( 'Twitter', 'fotografie' ),
					),
				'gplus' => array(
					'link'  => esc_url( 'https://plus.google.com/+Catchthemes/' ),
					'text'  => esc_html__( 'Google+', 'fotografie' ),
					),
				'pinterest' => array(
					'link'  => esc_url( 'http://www.pinterest.com/catchthemes/' ),
					'text'  => esc_html__( 'Pinterest', 'fotografie' ),
					),
			);

			foreach ( $important_links as $important_link ) {
				echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . $important_link['text'] . ' </a></p>'; // WPCS: XSS OK.
			}
		}
	}

	//Custom control for dropdown category multiple select
	class Fotografie_Multi_Categories extends WP_Customize_Control {
		public $type = 'dropdown-categories';

		public $name;

		public function render_content() {
			$dropdown = wp_dropdown_categories(
				array(
					'name'             => $this->name,
					'echo'             => 0,
					'hide_empty'       => false,
					'show_option_none' => false,
					'hide_if_empty'    => false,
					'show_option_all'  => esc_html__( 'All Categories', 'fotografie' ),
				)
			);

			$dropdown = str_replace( '<select', '<select multiple = "multiple" style = "height:95px;" ' . $this->get_link(), $dropdown );

			printf( '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',esc_html( $this->label ), $dropdown ); // WPCS: XSS OK.
			echo '<p class="description">' . esc_html__( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'fotografie' ) . '</p>';
		}
	}

	//Custom control for any note, use label as output description
	class Fotografie_Note_Control extends WP_Customize_Control {
		public $type = 'description';

		public function render_content() {
			echo '<h2 class="description">' .  $this->label . '</h2>'; // WPCS: XSS OK.
		}
	}
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/* Hero Content Selector */
	$wp_customize->add_setting( 'fotografie_single_image_position', array(
		'sanitize_callback' => 'fotografie_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'fotografie_single_image_position', array(
		'label'           => esc_html__( 'Use Featured Image as Header image in Single Page/Posts', 'fotografie' ),
		'section'         => 'fotografie_theme_options',
		'type'            => 'checkbox',
	) );

	/* Add option to JetPack Portfolio Section */
	/* Portfolio Number */
	$wp_customize->add_setting( 'fotografie_portfolio_option', array(
		'default'           => 'homepage',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_portfolio_option', array(
		'choices'  => fotografie_section_visibility_options(),
		'label'    => esc_html__( 'Enable on', 'fotografie' ),
		'section'  => 'jetpack_portfolio',
		'type'     => 'select',
		'priority' => 1,
	) );

	$wp_customize->add_setting( 'fotografie_portfolio_number', array(
		'default'           => '3',
		'sanitize_callback' => 'fotografie_sanitize_select',
	) );

	$wp_customize->add_control( 'fotografie_portfolio_number', array(
		'label'    => esc_html__( 'Number of items to show on frontpage', 'fotografie' ),
		'section'  => 'jetpack_portfolio',
		'type'     => 'radio',
		'choices'  => array(
			'3'  => esc_html__( '3', 'fotografie' ),
			'6'  => esc_html__( '6', 'fotografie' ),
			'9'  => esc_html__( '9', 'fotografie' ),
			'12' => esc_html__( '12', 'fotografie' ),
		),
		'priority' => 100,
	) );

	// Reset all settings to default
	$wp_customize->add_section( 'fotografie_reset_all', array(
		'description'   => esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'fotografie' ),
		'title'         => esc_html__( 'Reset all settings', 'fotografie' ),
		'priority'      => 998,
	) );

	$wp_customize->add_setting( 'fotografie_reset_all_settings', array(
		'sanitize_callback' => 'fotografie_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'fotografie_reset_all_settings', array(
		'label'    => esc_html__( 'Check to reset all settings to default', 'fotografie' ),
		'section'  => 'fotografie_reset_all',
		'type'     => 'checkbox',
	) );
	// Reset all settings to default end

	$wp_customize->add_section( 'Fotografie_Important_Links', array(
		'priority'      => 999,
		'title'         => esc_html__( 'Important Links', 'fotografie' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'Fotografie_Important_Links', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Fotografie_Important_Links( $wp_customize, 'Fotografie_Important_Links', array(
		'label'     => __( 'Important Links', 'fotografie' ),
		'section'   => 'Fotografie_Important_Links',
		'type'      => 'Fotografie_Important_Links',
	) ) );
	//Important Links End
}
add_action( 'customize_register', 'fotografie_customize_register' );

if ( ! function_exists( 'fotografie_customize_preview_js' ) ) :
	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	function fotografie_customize_preview_js() {
		wp_enqueue_script( 'fotografie_customizer', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/customizer.min.js', array( 'customize-preview' ), '20170616', true );
	}
endif;
add_action( 'customize_preview_init', 'fotografie_customize_preview_js' );

/**
 * Include Active Callbacks
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/active-callbacks.php';


/**
 * Include Theme Options
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/theme-options.php';

/**
 * Include Hero Content
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/hero-content.php';

/**
 * Include Featured Content
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/featured-content.php';

/**
 * Include Customizer Helper Functions
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/helpers.php';

/**
 * Include Sanitization functions
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/sanitize-functions.php';

// Add Upgrade to Pro Button.
require trailingslashit( get_template_directory() ) . 'inc/customizer/upgrade-button/class-customize.php';

if ( ! function_exists( 'fotografie_reset_data' ) ) :
	/**
	 * Function to reset date with respect to condition
	 */
	function fotografie_reset_data() {
		if ( get_theme_mod( 'fotografie_reset_all_settings' ) ) {
			remove_theme_mods();
		}
	}
endif;
add_action( 'customize_save_after', 'fotografie_reset_data' );

if ( ! function_exists( 'fotografie_sort_sections_list' ) ) :
	/**
	 * Alphabetically sort theme options sections
	 *
	 * @param  wp_customize object $wp_customize wp_customize object.
	 */
	function fotografie_sort_sections_list( $wp_customize ) {
		foreach ( $wp_customize->sections() as $section_key => $section_object ) {
			if ( false !== strpos( $section_key, 'fotografie_' ) && 'fotografie_reset_all' !== $section_key && 'Fotografie_Important_Links' !== $section_key ) {
				$options[] = $section_key;
			}
		}

		sort( $options );

		$priority = 1;
		foreach ( $options as  $option ) {
			$wp_customize->get_section( $option )->priority = $priority++;
		}
	}
endif;
add_action( 'customize_register', 'fotografie_sort_sections_list' );
