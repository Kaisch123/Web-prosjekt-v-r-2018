<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fotografie
 */

get_header(); ?>

	<?php
	if ( have_posts() ) : ?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<header class="page-header blog-section-headline">
					<div class="page-title-wrapper">
						<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
					</div><!-- .page-title-wrapper -->
					<div class="taxonomy-description-wrapper">
						<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
					</div><!-- .taxonomy-description-wrapper -->
				</header>

				<div id="infinite-post-wrap" class="post-archive">

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'components/post/content', get_post_format() );
						?>

					<?php endwhile; ?>

				</div><!-- .post-archive -->

				<?php the_posts_navigation(); ?>

			</main>
		</div><!-- #primary.content-area -->

	<?php else : ?>

		<div class="wrapper singular-section">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php get_template_part( 'components/post/content', 'none' ); ?>
				</main><!-- #main -->
			</div><!-- #primary.content-area -->
		</div><!-- .wrapper.singular-section -->

	<?php endif;

get_footer();
