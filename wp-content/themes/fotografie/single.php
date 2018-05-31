<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Fotografie
 */

get_header(); ?>

	<div class="wrapper singular-section">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'components/post/content', 'single' );

					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next', 'fotografie' ) . '</span> ' .
							'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'fotografie' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous', 'fotografie' ) . '</span> ' .
							'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'fotografie' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );

					get_template_part( 'components/post/content', 'comments' );

				endwhile; // End of the loop.
				?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php get_sidebar(); ?>
	</div><!-- .wrapper -->
<?php
get_footer();
