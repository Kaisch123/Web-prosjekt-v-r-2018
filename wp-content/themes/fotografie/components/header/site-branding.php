<?php
/**
 * Site Branding with Site title and Tagline template
 *
 * @package  Fotografie
 */

?>

	<div class="site-branding">
		<div class="wrapper">
			<?php fotografie_the_custom_logo(); ?>
			<div id="site-details">
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
				endif; ?>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div><!-- #site-details -->
		</div><!-- .wrapper -->
	</div><!-- .site-branding -->
