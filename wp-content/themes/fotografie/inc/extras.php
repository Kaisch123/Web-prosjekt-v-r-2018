<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fotografie
 */

if ( ! function_exists( 'fotografie_body_classes' ) ) :
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function fotografie_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Always add a front-page class to the front page.
		if ( is_front_page() && ! is_home() ) {
			$classes[] = 'page-template-front-page';
		}

		// Adds a class of (full-width|box) to blogs.
		if ( 'fluid' === get_theme_mod( 'fotografie_layout_type' ) ) {
			$classes[] = 'fluid-layout';
		} else {
			$classes[] = 'boxed-layout';
		}

		// Adds a class with respect to layout selected.
		$layout  = fotografie_get_theme_layout();

		if ( 'left-sidebar' === $layout ) {
			if ( is_active_sidebar( 'sidebar-1' ) ) {
				$classes[] = 'two-columns-layout content-right';
			}
		}

		return $classes;
	}
endif;
add_filter( 'body_class', 'fotografie_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function fotografie_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'fotografie_pingback_header' );

if ( ! function_exists( 'fotografie_excerpt_length' ) ) :
	/**
	 * Sets the post excerpt length to n words.
	 *
	 * function tied to the excerpt_length filter hook.
	 * @uses filter excerpt_length
	 *
	 * @since Fotografie 1.0
	 */
	function fotografie_excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		// Getting data from Customizer Options.
		$length	= get_theme_mod( 'fotografie_excerpt_length', 45 );

		return absint( $length );
	}
endif; //fotografie_excerpt_length
add_filter( 'excerpt_length', 'fotografie_excerpt_length' );


if ( ! function_exists( 'fotografie_excerpt_more' ) ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a option from customizer.
	 * @return string option from customizer prepended with an ellipsis.
	 */
	function fotografie_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		$more_tag_text = get_theme_mod( 'fotografie_excerpt_more_text',  esc_html__( 'Continue reading', 'fotografie' ) );

		$link = sprintf( '<a href="%1$s" class="more-link"><span>%2$s</span></a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			wp_kses_data( $more_tag_text ) . '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>'
		);

		return ' &hellip; ' . $link;
	}
endif;
add_filter( 'excerpt_more', 'fotografie_excerpt_more' );


if ( ! function_exists( 'fotografie_custom_excerpt' ) ) :
	/**
	 * Adds Continue reading link to more tag excerpts.
	 *
	 * function tied to the get_the_excerpt filter hook.
	 *
	 * @since Fotografie 1.0
	 */
	function fotografie_custom_excerpt( $output ) {

		if ( has_excerpt() && ! is_attachment() ) {
			$more_tag_text = get_theme_mod( 'fotografie_excerpt_more_text', esc_html__( 'Continue reading', 'fotografie' ) );

			$link = sprintf( '<a href="%1$s" class="more-link"><span>%2$s</span></a>',
				esc_url( get_permalink( get_the_ID() ) ),
				/* translators: %s: Name of current post */
				wp_kses_data( $more_tag_text ) . '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>'
			);

			$output .= ' &hellip; ' . $link;
		}

		return $output;
	}
endif; // fotografie_custom_excerpt.
add_filter( 'get_the_excerpt', 'fotografie_custom_excerpt' );


if ( ! function_exists( 'fotografie_more_link' ) ) :
	/**
	 * Replacing Continue reading link to the_content more.
	 *
	 * function tied to the the_content_more_link filter hook.
	 *
	 * @since Fotografie 1.0
	 */
	function fotografie_more_link( $more_link, $more_link_text ) {
		$more_tag_text = get_theme_mod( 'fotografie_excerpt_more_text', esc_html__( 'Continue reading', 'fotografie' ) );

		return str_replace( $more_link_text, wp_kses_post( $more_tag_text ), $more_link );
	}
endif; // fotografie_more_link.
add_filter( 'the_content_more_link', 'fotografie_more_link', 10, 2 );

/**
 * Remove first post from blog as it is already show via recent post template
 */
function fotografie_alter_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$cats 		= get_theme_mod( 'fotografie_front_page_category' );

		if ( is_array( $cats ) && ! in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'pre_get_posts', 'fotografie_alter_home' );

/**
 * Function to add Scroll Up icon
 */
function fotografie_scrollup() {
	$fotografie_disable_scrollup = get_theme_mod( 'fotografie_disable_scrollup' );

	if ( $fotografie_disable_scrollup ) {
		return;
	}

	echo '<a href="#masthead" id="scrollup" class="backtotop"><span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'fotografie' ) . '</span></a>' ;
}
add_action( 'wp_footer', 'fotografie_scrollup', 1 );

if ( ! function_exists( 'fotografie_content_nav' ) ) :
	/**
	 * Display navigation/pagination when applicable
	 *
	 * @since Fotografie 1.1
	 */
	function fotografie_content_nav() {
		global $wp_query;

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$pagination_type = get_theme_mod( 'fotografie_pagination_type', 'default' );

		/**
		 * Check if navigation type is Jetpack Infinite Scroll and if it is enabled, else goto default pagination
		 * if it's active then disable pagination
		 */
		if ( ( 'infinite-scroll' === $pagination_type ) && class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
			return false;
		}

		if ( 'numeric' === $pagination_type && function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'prev_text'          => esc_html__( 'Previous', 'fotografie' ),
				'next_text'          => esc_html__( 'Next', 'fotografie' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'fotografie' ) . ' </span>',
			) );
		} else {
			the_posts_navigation();
		}
	}
endif; // fotografie_content_nav

/**
 * Check if a section is enabled or not based on the $value parameter
 * @param  string $value Value of the section that is to be checked
 * @return boolean return true if section is enabled otherwise false
 */
function fotografie_check_section( $value ) {
	global $wp_query;

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	return ( 'entire-site' == $value  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'homepage' == $value ) );
}

/**
 * Return the first image in a post. Works inside a loop.
 * @param [integer] $post_id [Post or page id]
 * @param [string/array] $size Image size. Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).
 * @param [string/array] $attr Query string or array of attributes.
 * @return [string] image html
 *
 * @since Fotografie 1.1
 */

function fotografie_get_first_image( $postID, $size, $attr ) {
	ob_start();

	ob_end_clean();

	$image 	= '';

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $postID ) , $matches);

	if( isset( $matches [1] [0] ) ) {
		//Get first image
		$first_img = $matches [1] [0];

		return '<img class="pngfix wp-post-image" src="'. esc_url( $first_img ) .'">';
	}

	return false;
}

if ( ! function_exists( 'fotografie_get_theme_layout' ) ) :
	function fotografie_get_theme_layout() {
		$layout = '';

		if ( is_home() || is_archive() ) {
			// Disable layouts on front page when your latest posts is selected.
			return 'no-sidebar';
		}

		$layout = get_theme_mod( 'fotografie_default_layout', 'no-sidebar' );

		return $layout;
	}
endif;

/**
 * Display social Menu
 */
function fotografie_social_menu() {
	if ( has_nav_menu( 'social-menu' ) ) :
		?>
		<div id="header-menu-social" class="menu-social">

			<nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Links Menu', 'fotografie' ); ?>">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'social-menu',
						'link_before'    => '<span class="screen-reader-text">',
						'link_after'     => '</span>',
						'depth'          => 1,
					) );
				?>
			</nav><!-- .social-navigation -->
		</div>
	<?php endif;
}

if ( ! function_exists( 'fotografie_truncate_phrase' ) ) :
	/**
	 * Return a phrase shortened in length to a maximum number of characters.
	 *
	 * Result will be truncated at the last white space in the original string. In this function the word separator is a
	 * single space. Other white space characters (like newlines and tabs) are ignored.
	 *
	 * If the first `$max_characters` of the string does not contain a space character, an empty string will be returned.
	 *
	 * @since NepalBuzz 1.0
	 *
	 * @param string $text            A string to be shortened.
	 * @param integer $max_characters The maximum number of characters to return.
	 *
	 * @return string Truncated string
	 */
	function fotografie_truncate_phrase( $text, $max_characters ) {

		$text = trim( $text );

		if ( mb_strlen( $text ) > $max_characters ) {
			//* Truncate $text to $max_characters + 1
			$text = mb_substr( $text, 0, $max_characters + 1 );

			//* Truncate to the last space in the truncated string
			$text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		}

		return $text;
	}
endif; //fotografie_truncate_phrase


if ( ! function_exists( 'fotografie_get_the_content_limit' ) ) :
	/**
	 * Return content stripped down and limited content.
	 *
	 * Strips out tags and shortcodes, limits the output to `$max_char` characters, and appends an ellipsis and more link to the end.
	 *
	 * @since NepalBuzz 1.0
	 *
	 * @param integer $max_characters The maximum number of characters to return.
	 * @param string  $more_link_text Optional. Text of the more link. Default is "(more...)".
	 * @param bool    $stripteaser    Optional. Strip teaser content before the more text. Default is false.
	 *
	 * @return string Limited content.
	 */
	function fotografie_get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

		$content = get_the_content( '', $stripteaser );

		//* Strip tags and shortcodes so the content truncation count is done correctly
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

		//* Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		//* Truncate $content to $max_char
		$content = fotografie_truncate_phrase( $content, $max_characters );

		//* More link?
		if ( $more_link_text ) {
			$link   = apply_filters( 'get_the_content_more_link', sprintf( '<span class="more-button"><a href="%s" class="more-link">%s</a></span>', esc_url( get_permalink() ), $more_link_text ), $more_link_text );
			$output = sprintf( '<p>%s %s</p>', $content, $link );
		} else {
			$output = sprintf( '<p>%s</p>', $content );
			$link = '';
		}

		return apply_filters( 'fotografie_get_the_content_limit', $output, $content, $link, $max_characters );

	}
endif; //fotografie_get_the_content_limit
