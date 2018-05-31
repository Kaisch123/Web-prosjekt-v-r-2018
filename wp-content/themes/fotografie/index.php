<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fotografie
 */

get_header(); 

if ( have_posts() ) : ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php 
			if ( is_home() ) :
				$post_title = get_theme_mod( 'fotografie_latest_posts_title', esc_html__( 'News', 'fotografie' ) );

				if ( '' !== $post_title ) :
				?>
				<header class="blog-section-headline">
					<div class="page-title-wrapper">
						<h2 class="page-title"><?php echo esc_html( $post_title ); ?></h2>
					</div><!-- .page-title-wrapper -->
				</header>
			<?php
				endif;
			endif;
			?>

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

			<?php fotografie_content_nav(); ?>
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

<?php 
endif;

get_footer();
