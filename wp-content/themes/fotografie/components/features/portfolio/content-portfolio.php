<?php
/**
 * The template used for displaying projects on index view
 *
 * @package Fotografie
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="hentry">
	<div class="portfolio-thumbnail post-thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php
			if ( has_post_thumbnail() ) {
				$thumbnail = 'fotografie-featured';

				if ( 'fluid' === get_theme_mod( 'fotografie_layout_type' ) ) {
					$thumbnail = 'fotografie-featured-fluid';
				}

				the_post_thumbnail( $thumbnail );
			} else {
				echo '<img src="' .  trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb.jpg"/>';
			}
			?>
		</a>
	</div>

	<header class="portfolio-entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<?php echo get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '<span class="portfolio-entry-meta entry-meta">', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'fotografie' ), '</span>' ); ?>
	</header>
</article>
