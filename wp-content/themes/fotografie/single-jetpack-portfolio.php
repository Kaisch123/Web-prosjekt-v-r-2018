<?php
/**
 * The Template for displaying all single projects
 *
 * @package Fotografie
 */

get_header(); ?>

	<div class="wrapper singular-section page-section">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'components/features/portfolio/content', 'portfolio-single' );

					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'fotografie' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next post:', 'fotografie' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'fotografie' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous post:', 'fotografie' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );

					get_template_part( 'components/post/content', 'comments' );

				endwhile; // End of the loop.
				?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div><!-- .page-section -->
<?php
get_footer();
