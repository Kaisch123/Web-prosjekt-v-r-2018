<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://selmamariudottir.com
 * @since             1.0.0
 * @package           Wp_Date_Remover
 *
 * @wordpress-plugin
 * Plugin Name:       WP Date Remover
 * Plugin URI:        http://wpdateremover.com/
 * Description:       WP Date Remover allows you to quickly and easily remove the time and date from specific post categories.
 * Version:           1.1.1
 * Author:            Selma Mariudottir
 * Author URI:        http://selmamariudottir.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-date-remover
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-date-remover-activator.php
 */
function activate_wp_date_remover() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-date-remover-activator.php';
	Wp_Date_Remover_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-date-remover-deactivator.php
 */
function deactivate_wp_date_remover() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-date-remover-deactivator.php';
	Wp_Date_Remover_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_date_remover' );
register_deactivation_hook( __FILE__, 'deactivate_wp_date_remover' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-date-remover.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_date_remover() {

	$plugin = new Wp_Date_Remover();
	$plugin->run();

}
run_wp_date_remover();
