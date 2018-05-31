<?php
/**
 * Featured/Header Media Template
 *
 * @package Fotografie
 */

if ( is_singular() && ! is_front_page() ) :
	if ( has_post_thumbnail() && get_theme_mod( 'fotografie_single_image_position' ) && fotografie_jetpack_featured_image_display() ) : ?>
	<div class="custom-header-media">
		<img src="<?php the_post_thumbnail_url(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="" class="custom-header">
	</div><!-- .custom-header-media -->
<?php
	endif;
elseif ( has_custom_header() && is_front_page() ) : ?>
	<div class="custom-header-media">
		<?php the_custom_header_markup(); ?>
	</div><!-- .custom-header-media -->
<?php
elseif ( is_post_type_archive( 'jetpack-portfolio' ) ) : ?>
	<?php
	$jetpack_portfolio_featured_image = get_option( 'jetpack_portfolio_featured_image' );
	if ( '' !== $jetpack_portfolio_featured_image ) : ?>
		<div class="custom-header-media">
			<?php echo wp_get_attachment_image( (int) $jetpack_portfolio_featured_image, 'post-thumbnail' ); ?>
		</div><!-- .custom-header-media -->
	<?php
	endif;
endif; // End featured/header image check. ?>
