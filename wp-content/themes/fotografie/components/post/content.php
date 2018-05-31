<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fotografie
 */

?>

<article id="post-<?php the_ID(); ?> post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	if ( 'fluid' === get_theme_mod( 'fotografie_layout_type' ) ) {
		$thumb = get_the_post_thumbnail_url( $post->ID, 'fotografie-featured-fluid' );
	}
	else {
		$thumb = get_the_post_thumbnail_url( $post->ID, 'fotografie-featured' );
	}

	if ( ! $thumb ) {
		$thumb = trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/no-thumb.jpg';
	}

	?>
	<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb ); ?>' )">
		<a class="cover-link" href="<?php the_permalink(); ?>"></a>
	</div>


	<div class="entry-container content-right">
		<div class="post-wrapper">
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			</header>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<?php if ( 'post' === get_post_type() ) : ?>
				<?php get_template_part( 'components/post/content', 'meta' ); ?>
			<?php endif; ?>
		</div><!-- .post-wrapper -->
	</div><!-- .entry-container -->
</article><!-- #post-<?php the_ID(); ?> -->
