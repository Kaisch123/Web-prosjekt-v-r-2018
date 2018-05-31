<?php
/**
 * The template for displaying the Portfolio type page.
 *
 * @package Fotografie
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header blog-section-headline">
				<div class="page-title-wrapper">
					<?php fotografie_portfolio_title( '<h1 class="page-title">', '</h1>' ); ?>
				</div><!-- .page-title-wrapper -->
				<div class="taxonomy-description-wrapper">
					<?php fotografie_portfolio_content( '<div class="taxonomy-description">', '</div>' ); ?>
				</div><!-- .taxonomy-description-wrapper -->
			</header>

			<div id="infinite-post-wrap" class="portfolio-wrapper portfolio-archive section three-columns">

				<?php /* Start the Loop */ ?>
				<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'components/features/portfolio/content', 'portfolio' );
				endwhile;
				?>

			</div><!-- #infinite-post-wrap -->

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'components/post/content', 'none' ); ?>

		<?php endif; ?>

		</main>
	</div>
<?php
get_footer();
