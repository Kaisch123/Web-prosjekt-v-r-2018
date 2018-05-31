<?php
/**
 * @package Fotografie
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'Pages:', 'fotografie' ) . '</span>',
				'after'     => '</div>',
				'separator' => '<span class="sep"></span>',
			) );
		?>
	</div>
	<footer class="entry-footer">
		<?php
		echo get_the_term_list( $post->ID, 'jetpack-portfolio-type', '<span class="cat-links"><span>' . esc_html__( 'Categories: ', 'fotografie' ) . '</span>', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'fotografie' ), '</span>' );

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_term_list( $post->ID, 'jetpack-portfolio-tag', '', esc_html__( ', ', 'fotografie' ) );
		if ( $tags_list ) :
			echo '<span class="tags-links"><span>' . esc_html__( 'Tags: ', 'fotografie' ) . '</span>' . $tags_list . '</span>'; // WPCS: XSS OK.
		endif; ?>

		<?php edit_post_link( esc_html__( 'Edit', 'fotografie' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
</article><!-- #post-## -->
