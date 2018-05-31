<?php
/**
 * Social Icons Widget
 *
 * @package Fotografie
 */


/**
 * Adds Fotografie Social Icons widget.
 *
 * @since Fotografie 1.0
 */
class Fotografie_Social_Icons_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	function __construct() {

		$this->defaults = array(
			'title' => '',
		);

		$widget_ops = array(
			'classname'   => 'ct-social-widget ctsocialwidget',
			'description' => esc_html__( 'Use this widget to add short information and Social Menu', 'fotografie' ),
		);

		$control_ops = array(
			'id_base' => 'ct-social',
		);

		parent::__construct(
			'ct-social', // Base ID
			esc_html__( 'CT: Social Icons', 'fotografie' ), // Name
			$widget_ops,
			$control_ops
		);
	}

	public function widget($args, $instance) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu )
			return;

		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu_class'  => 'social-links-menu',
			'depth'       => 1,
			'link_before' => '<span class="screen-reader-text">',
			'link_after'  => '</span>',
			'menu'        => $nav_menu,
		);

		?>
		<nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Links Menu', 'fotografie' ); ?>">
			<?php

			/**
			 * Filter the arguments for the Custom Menu widget.
			 *
			 * @since 4.2.0
			 *
			 * @param array    $nav_menu_args {
			 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
			 *
			 *     @type callback|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
			 *     @type mixed         $menu        Menu ID, slug, or name.
			 * }
			 * @param stdClass $nav_menu      Nav menu object for the current menu.
			 * @param array    $args          Display arguments for the current widget.
			 */
			wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args ) );
			?>
		</nav><!-- .social-navigation -->
		<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.', 'fotografie' ), esc_url( admin_url('nav-menus.php') ) ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'fotografie' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e( 'Select Menu:', 'fotografie' ); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<option value="0"><?php _e( '&mdash; Select &mdash;', 'fotografie' ); ?></option>
		<?php
			foreach ( $menus as $menu ) {
				echo '<option value="' . $menu->term_id . '"'
					. selected( $nav_menu, $menu->term_id, false )
					. '>'. esc_html( $menu->name ) . '</option>';
			}
		?>
			</select>
		</p>
		<?php
	}

}

/**
 * Initialize Social Icons Widget
 */
function fotografie_social_icons_init() {
	register_widget( 'Fotografie_Social_Icons_Widget' );
}
add_action( 'widgets_init', 'fotografie_social_icons_init' );
