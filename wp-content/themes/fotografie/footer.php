<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fotografie
 */

?>

		</div>
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php get_template_part( 'components/footer/footer', 'widgets' ); ?>

			<div id="site-generator">
				<div class="wrapper">
					<?php get_template_part( 'components/footer/site', 'social' ); ?>

					<?php get_template_part( 'components/footer/site', 'info' ); ?>
				</div><!-- .wrapper -->
			</div><!-- #site-generator -->
		</footer>
	</div><!-- .site-inner -->
</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>
