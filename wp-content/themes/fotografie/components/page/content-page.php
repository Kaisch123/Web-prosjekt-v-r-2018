<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fotografie
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-container">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>

		<?php if ( has_post_thumbnail() && ! get_theme_mod( 'fotografie_single_image_position' ) && fotografie_jetpack_featured_image_display() ) : ?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif; ?>

		<div class="entry-content">
			<?php
				the_content();

				wp_link_pages( array(
					'before'    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'Pages:', 'fotografie' ) . '</span>',
					'after'     => '</div>',
					'separator' => '<span class="sep"></span>',
				) );
			?>
		</div>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'fotografie' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer>
	</div><!-- .entry-container -->
</article><!-- #post-## -->
