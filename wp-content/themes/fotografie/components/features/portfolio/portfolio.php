<?php
/**
 * The template for displaying portfolio items
 *
 * @package Fotografie
 */

if ( ! post_type_exists( 'jetpack-portfolio' ) ) {
	// Bail if there is no portfolio content type.
	return;
}

$enable = get_theme_mod( 'fotografie_portfolio_option', 'homepage' );

if ( ! fotografie_check_section( $enable ) ) {
	// Bail if portfolio section is disabled.
	return;
}

$posts_per_page = get_theme_mod( 'fotografie_portfolio_number', 3 );

$args = array(
	'post_type'      => 'jetpack-portfolio',
	'posts_per_page' => $posts_per_page,
);

$project_query = new WP_Query( $args );

$jetpack_portfolio_title   = get_option( 'jetpack_portfolio_title', esc_html__( 'Projects', 'fotografie' ) );
$jetpack_portfolio_content = get_option( 'jetpack_portfolio_content' );

if ( $project_query -> have_posts() ) :
?>

<div id="portfolio-content-section" class="section three-columns">
	<?php if ( '' !== $jetpack_portfolio_title || $jetpack_portfolio_content ) : ?>
		<div class="blog-section-headline section-heading-wrap">
			<?php if ( '' !== $jetpack_portfolio_title ) : ?>
				<div class="page-title-wrapper">
					<h2 class="page-title section-title"><?php echo wp_kses_post( $jetpack_portfolio_title ); ?></h2>
				</div><!-- .section-heading-wrap -->
			<?php endif; ?>

			<?php if ( $jetpack_portfolio_content ) : ?>
				<div class="taxonomy-description-wrapper">
					<p class="taxonomy-description section-subtitle"><?php echo wp_kses_post( $jetpack_portfolio_content ); ?></p>
				</div><!-- .taxonomy-description-wrapper -->
			<?php endif; ?>
		</div><!-- .section-heading-wrap -->
	<?php endif; ?>

	<div class="portfolio-wrapper">
		<?php /* Start the Loop */ ?>
		<?php while ( $project_query -> have_posts() ) : $project_query -> the_post(); ?>

			<?php get_template_part( 'components/features/portfolio/content', 'portfolio' ); ?>

		<?php endwhile; ?>
	</div><!-- .portfolio-wrapper -->

<?php wp_reset_postdata(); ?>

</div><!-- #portfolio-content-section -->

<?php endif; ?>
