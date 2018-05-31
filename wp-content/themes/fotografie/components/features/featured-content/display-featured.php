<?php
/**
 * The template for displaying featured content
 *
 * @package Fotografie
 */

$enable_content = get_theme_mod( 'fotografie_featured_content_option', 'homepage' );

if ( ! fotografie_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$number = get_theme_mod( 'fotografie_featured_content_number', 3 );

$post_list    = array();

// Get valid number of posts.
for ( $i = 1; $i <= $number; $i++ ) {
	$post_id = get_theme_mod( 'fotografie_featured_content_page_' . $i );

	if ( $post_id && '' !== $post_id ) {
		$post_list = array_merge( $post_list, array( $post_id ) );
	}
}

if ( empty( $post_list ) ) {
	return;
}

$args = array(
	'posts_per_page'      => $number,
	'post_type'           => 'page',
	'ignore_sticky_posts' => 1, // ignore sticky posts.
	'post__in'            => $post_list,
	'orderby'             => 'post__in',
);


$featured_posts = get_posts( $args );

$type = get_theme_mod( 'fotografie_featured_content_type', 'category' );

$title     = get_theme_mod( 'fotografie_featured_content_archive_title', esc_html__( 'Featured', 'fotografie' ) );
$sub_title = get_theme_mod( 'fotografie_featured_content_sub_title' );
$layout    = get_theme_mod( 'fotografie_featured_content_layout', 'layout-three' );
?>

<div id="featured-content-section" class="section <?php echo esc_attr( $layout ); ?>">
	<?php if ( '' !== $title || $sub_title ) : ?>
		<div class="blog-section-headline section-heading-wrap">
			<?php if ( '' !== $title ) : ?>
				<div class="page-title-wrapper">
					<h2 class="page-title section-title"><?php echo wp_kses_post( $title ); ?></h2>
				</div><!-- .page-title-wrapper -->
			<?php endif; ?>

			<?php if ( $sub_title ) : ?>
				<div class="taxonomy-description-wrapper">
					<p class="taxonomy-description section-subtitle"><?php echo wp_kses_post( $sub_title ); ?></p>
				</div><!-- .taxonomy-description-wrapper -->
			<?php endif; ?>
		</div><!-- .section-heading-wrap -->
	<?php endif; ?>

	<div class="featured-content-wrapper <?php echo esc_attr( $layout ); ?>">

		<?php
		foreach ( $featured_posts as $post ) {
			setup_postdata( $post );

			// Include the featured content template.
			get_template_part( 'components/features/featured-content/content', 'featured' );
		}

		wp_reset_postdata();
		?>
	</div><!-- .featured-content-wrapper -->
</div><!-- #featured-content-section -->
