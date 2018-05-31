<?php
	$recent = new WP_Query( array(
		'posts_per_page'      => 4,
		'no_found_rows'       => true,
	) );
?>

<?php if ( $recent->have_posts() ) : ?>
	<div id="front-page-recent-posts" class="recent-posts">
		
		<?php
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
		?>
		<div id="infinite-post-wrap" class="post-archive">
			<?php
			while ( $recent->have_posts() ) : $recent->the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'components/post/content', get_post_format() );

			endwhile;

			wp_reset_postdata();
			?>

			<div class="posts-navigation front-page-navigation">
				<div class="nav-links">
					<a class="more-recent-posts button" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
						<?php esc_html_e( 'More Posts', 'fotografie' ); ?>
					</a>
				</div><!-- .nav-links -->
			</div><!-- .posts-navigation -->

		</div><!-- .post-archive -->
	</div>
<?php endif;
