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

	<header class="entry-header">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>

		<?php
		if ( 'post' === get_post_type() ) {
			get_template_part( 'components/post/content', 'meta' );
		}
		?>
	</header>

	<?php if ( has_post_thumbnail() && ! get_theme_mod( 'fotografie_single_image_position' ) && fotografie_jetpack_featured_image_display() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'fotografie' ), array(
					'span' => array(
						'class' => array(),
					),
				) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before'    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'Pages:', 'fotografie' ) . '</span>',
				'after'     => '</div>',
				'separator' => '<span class="sep"></span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php get_template_part( 'components/post/content', 'footer' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
