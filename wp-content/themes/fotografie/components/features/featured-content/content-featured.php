<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package Fotografie
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php
	// Output the featured image.
	if ( has_post_thumbnail() ) {
		$thumbnail = 'fotografie-featured';

		if ( 'fluid' === get_theme_mod( 'fotografie_layout_type' ) ) {
			$thumbnail = 'fotografie-featured-fluid';
		}

		$layout = get_theme_mod( 'fotografie_featured_content_layout', 'layout-three' );

		if ( 'layout-two' === $layout ) {
			$thumbnail = 'fotografie-hero-image';
		}

		the_post_thumbnail( $thumbnail );
	} else {
		echo '<a href=' . esc_url( get_permalink() ) .'><img src="' .  trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb.jpg"/></a>';
	}
	?>
	</a>

	<header class="entry-header">
		<?php
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' );
		?>
		<div class="entry-meta">
			<?php fotografie_date(); ?>
		</div><!-- .entry-meta -->
	</header>
</article>
