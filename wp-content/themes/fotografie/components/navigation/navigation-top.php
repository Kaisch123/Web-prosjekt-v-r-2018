<?php
/**
 * Primary Menu Template
 *
 * @package  Fotografie
 */

?>
	<div class="menu-toggle-wrapper">
		<button id="menu-toggle" class="menu-toggle" aria-controls="top-menu" aria-expanded="false"></span><span class="menu-label"><?php esc_html_e( 'Menu', 'fotografie' ); ?></span></button>
	</div><!-- .menu-toggle-wrapper -->
	<div id="site-header-menu" class="site-header-menu">
		<div class="wrapper">
			<?php if ( has_nav_menu( 'menu-1' ) ) : ?>

				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'fotografie' ); ?>">
					<?php
						wp_nav_menu( array(
								'container'      => '',
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'menu nav-menu',
							)
						);
					?>

			<?php else : ?>

				<nav id="site-navigation" class="main-navigation default-page-menu" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'fotografie' ); ?>">
					<?php wp_page_menu(
						array(
							'menu_class' => 'primary-menu-container',
							'before'     => '<ul id="menu-primary-items" class="menu nav-menu">',
							'after'      => '</ul>',
						)
					); ?>

			<?php endif; ?>

				<div class="mobile-search-wrapper">
					<?php get_search_form(); ?>
				</div><!-- .search-wrapper -->

			</nav><!-- .main-navigation -->
		</div><!-- .wrapper -->
	</div><!-- .site-header-menu -->
