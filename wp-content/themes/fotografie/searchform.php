<?php
/**
 * Template for displaying search forms
 *
 * @package Fotografie
 */
?>


<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_attr_x( 'Search for:', 'label', 'fotografie' ); ?></span>

		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter keyword&hellip;', 'placeholder', 'fotografie' ); ?>" value="<?php the_search_query(); ?>" name="s" />
	</label>

	<button type="submit" class="search-submit"><span class="search-button-text"><?php echo esc_html_x( 'Search', 'submit button', 'fotografie' ); ?></span></button>
</form>
