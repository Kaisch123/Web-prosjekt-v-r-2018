<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fotografie
 */

if ( ! function_exists( 'fotografie_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function fotografie_posted_on() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( '%1$sPosted on%2$s %3$s', 'post date', 'fotografie' ),
			'<span class="screen-reader-text">',
			'</span>',
			$time_string
		);

		$byline = sprintf(
			esc_html_x( '%1$sby%2$s%3$s', 'post author', 'fotografie' ),
			'<span class="screen-reader-text">',
			' </span>',
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline">' . $byline . '</span>'; // WPCS: XSS OK.

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'fotografie' ), esc_html__( '1 Comment', 'fotografie' ), esc_html__( '% Comments', 'fotografie' ) );
			echo '</span>';
		}

	}
endif;

if ( ! function_exists( 'fotografie_date' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function fotografie_date() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( '%1$sPosted on%2$s %3$s', 'post date', 'fotografie' ),
			'<span class="screen-reader-text">',
			'</span>',
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'fotografie_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function fotografie_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'fotografie' ) );
			if ( $categories_list && fotografie_categorized_blog() ) {
				echo '<span class="cat-links"><span>' . esc_html__( 'Categories: ', 'fotografie' ) . '</span>' . $categories_list . '</span>'; // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'fotografie' ) );
			if ( $tags_list ) {
				echo '<span class="tags-links"><span>' . esc_html__( 'Tags: ', 'fotografie' ) . '</span>' . $tags_list . '</span>'; // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'fotografie' ), esc_html__( '1 Comment', 'fotografie' ), esc_html__( '% Comments', 'fotografie' ) );
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'fotografie' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function fotografie_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'fotografie_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'fotografie_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so fotografie_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so fotografie_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in fotografie_categorized_blog.
 */
function fotografie_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'fotografie_categories' );
}
add_action( 'edit_category', 'fotografie_category_transient_flusher' );
add_action( 'save_post',     'fotografie_category_transient_flusher' );

/**
 * Modify archive title
 *
 * @return string archive title
 */
function fotografie_the_archive_title() {
	if ( is_category() ) {
		/* translators: Category archive title. 1: Category name */
		$title = sprintf( esc_html__( '%1$sCategory: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		/* translators: Tag archive title. 1: Tag name */
		$title = sprintf( esc_html__( '%1$sTag: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		/* translators: Author archive title. 1: Author name */
		$title = sprintf( esc_html__( '%1$sAuthor: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		/* translators: Yearly archive title. 1: Year */
		$title = sprintf( esc_html__( '%1$sYear: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', get_the_date( _x( 'Y', 'yearly archives date format', 'fotografie' ) ) );
	} elseif ( is_month() ) {
		/* translators: Monthly archive title. 1: Month name and year */
		$title = sprintf( esc_html__( '%1$sMonth: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', get_the_date( _x( 'F Y', 'monthly archives date format', 'fotografie' ) ) );
	} elseif ( is_day() ) {
		/* translators: Daily archive title. 1: Date */
		$title = sprintf( esc_html__( '%1$sDay: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', get_the_date( _x( 'F j, Y', 'daily archives date format', 'fotografie' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'fotografie' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'fotografie' );
		}
	} elseif ( is_post_type_archive() ) {
		/* translators: Post type archive title. 1: Post type name */
		$title = sprintf( esc_html__( '%1$sArchives: %2$s%3$s', 'fotografie' ), '<span class="archive-title-type">', '</span>', post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s%2$s: %3$s%4$s', 'fotografie' ), '<span class="archive-title-type">', $tax->labels->singular_name,  '</span>', single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'fotografie' );
	} // End if().

	return $title;
}
add_filter( 'get_the_archive_title', 'fotografie_the_archive_title' );
