<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
						<h1 class="page-title">
							<span class="archive-title-type"><?php esc_html_e( 'Search Results for: ', 'fotografie' ); ?></span>
							<span class="search-keyword"><?php echo get_search_query(); ?></span>
						</h1>
					</div><!-- .page-title-wrapper -->
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
		</div>

	<?php else : ?>

		<div class="wrapper singular-section">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?php get_template_part( 'components/post/content', 'none' ); ?>
				</main>
			</div>
		</div>

	<?php endif; ?>
	
<?php
get_footer();
