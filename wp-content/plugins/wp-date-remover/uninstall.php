<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://selmamariudottir.com
 * @since      1.0.0
 *
 * @package    Wp_Date_Remover
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
delete_option('wp-date-remover');
delete_option('wdr_admin_notices_show');

/*======= DELETE TERM META WP DATE REMOVER ======= */
global $wpdb;
$wdr_delete_query = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."termmeta`");
foreach($wdr_delete_query as $wdr_delete_meta)
{
	$wpdb->query("DELETE FROM ".$wpdb->prefix."termmeta WHERE meta_key = 'wp-date-remover'");
}
/*======= \DELETE TERM META WP DATE REMOVER ======= */
