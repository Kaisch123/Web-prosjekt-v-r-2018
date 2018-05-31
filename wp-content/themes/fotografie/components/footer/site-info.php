<div class="site-info">
	<?php
	$theme_data = wp_get_theme();

	$def_footer_text = sprintf( _x( 'Copyright &copy; %1$s %2$s. All Rights Reserved', '1: Year, 2: Site Title with home URL', 'fotografie' ), esc_attr( date_i18n( __( 'Y', 'fotografie' ) ) ), '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' ) . ' &#124; ' . $theme_data->get( 'Name' ) . '&nbsp;' . esc_html__( 'by', 'fotografie' ) . '&nbsp;<a target="_blank" href="' . $theme_data->get( 'AuthorURI' ) . '">' . esc_html( $theme_data->get( 'Author' ) ) . '</a>';

	echo $def_footer_text;
	?>
</div><!-- .site-info -->


