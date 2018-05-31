<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Fotografie
 */

if ( ! function_exists( 'fotografie_custom_header_setup' ) ) :
/**
 * Set up the WordPress core custom header feature.
 *
 * @uses fotografie_header_style()
 */
function fotografie_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'fotografie_custom_header_args', array(
		'default-image'      => trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/header-image.jpg',
		'default-text-color' => 'ffffff',
		'width'              => 1920,
		'height'             => 689,
		'flex-height'        => true,
		'flex-width'         => true,
		'wp-head-callback'   => 'fotografie_header_style',
		'video'              => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-image.jpg',
			'thumbnail_url' => '%s/assets/images/header-image.jpg',
			'description'   => esc_html__( 'Default Header Image', 'fotografie' ),
		),
	) );
}
endif;
add_action( 'after_setup_theme', 'fotografie_custom_header_setup' );

if ( ! function_exists( 'fotografie_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog
	 *
	 * @see fotografie_custom_header_setup().
	 */
	function fotografie_header_style() {
		$header_text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail.
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
		if ( 'blank' === $header_text_color ) :
		?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif; // fotografie_header_style.

/**
 * Customize video play/pause button in the custom header.
 */
function fotografie_video_controls( $settings ) {
	$settings['l10n']['play'] = '<span class="screen-reader-text">' . esc_html__( 'Play background video', 'fotografie' ) . '</span>';
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . esc_html__( 'Pause background video', 'fotografie' ) . '</span>';
	return $settings;
}
add_filter( 'header_video_settings', 'fotografie_video_controls' );

