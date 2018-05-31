<?php
/**
 * Site Search template
 *
 * @package  Fotografie
 */

?>

<div class="site-search">
	<button class="search-toggle" aria-expanded="false">
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'fotografie' ); ?></span>
	</button><!-- .search-toggle -->
	<div class="search-wrapper">
		<?php get_search_form(); ?>
	</div><!-- .search-wrapper -->
</div><!-- .site-search -->
