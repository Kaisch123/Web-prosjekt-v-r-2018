<?php
/**
 * The static front page template
 *
 * @package Fotografie
 */

if ( 'posts' === get_option( 'show_on_front' ) ) :

	get_template_part( 'index' );

else :

	get_header();

	?>
		<div class="singular-section">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'components/page/content', 'page' );

						get_template_part( 'components/post/content', 'comments' );

					endwhile; // End of the loop.
					?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .singular-section -->
	<?php
	if ( get_theme_mod( 'fotografie_enable_static_page_posts' ) ) {
	?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php get_template_part( 'components/features/recent-posts/recent-posts' ); ?>
				
			</main><!-- #main -->
		</div><!-- #primary -->
	<?php
	}
 
get_footer();

endif;
