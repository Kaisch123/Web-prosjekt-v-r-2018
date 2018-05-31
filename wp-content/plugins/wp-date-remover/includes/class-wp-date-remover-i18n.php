<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://selmamariudottir.com
 * @since      1.0.0
 *
 * @package    Wp_Date_Remover
 * @subpackage Wp_Date_Remover/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Date_Remover
 * @subpackage Wp_Date_Remover/includes
 * @author     Selma Mariudottir <selma@selmamariudottir.com>
 */
class Wp_Date_Remover_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-date-remover',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
