<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://#
 * @since             1.0.0
 * @package           Ech_Professionals_List
 *
 * @wordpress-plugin
 * Plugin Name:       ECH Professionals List
 * Plugin URI:        https://#
 * Description:       This plugin creates shortcode to show all ECH professionals list with user filtering options and single professional profile page. It is integrated with global cms api. 
 * Version:           1.0.0
 * Author:            Toby Wong
 * Author URI:        https://#
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ech-professionals-list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ECH_PROFESSIONALS_LIST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ech-professionals-list-activator.php
 */
function activate_ech_professionals_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-professionals-list-activator.php';
	Ech_Professionals_List_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ech-professionals-list-deactivator.php
 */
function deactivate_ech_professionals_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ech-professionals-list-deactivator.php';
	Ech_Professionals_List_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ech_professionals_list' );
register_deactivation_hook( __FILE__, 'deactivate_ech_professionals_list' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ech-professionals-list.php';



/************* ^^^ Rewrite URL **************/
add_action('init', function() {
	add_rewrite_rule('healthcare-professionals\/(professional-profile)\/(\d+[\/]?)$', 'index.php?pagename=healthcare-professionals%2F$matches[1]&therapistid=$matches[2]', 'top');
	add_rewrite_rule('healthcare-professionals\/(specialty-categories)\/(\d+[\/]?)$', 'index.php?pagename=healthcare-professionals%2F$matches[1]&specialtyid=$matches[2]', 'top');
    flush_rewrite_rules();
});


add_filter('query_vars', function($query_vars) {
 	$query_vars[] = 'therapistid';
 	return $query_vars;
} );

add_filter('query_vars', function($query_vars) {
    $query_vars[] = 'specialtyid';
    return $query_vars;
} );


/************* (END) Rewrite URL **************/



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ech_professionals_list() {
	$plugin = new Ech_Professionals_List();
	$plugin->run();
}
run_ech_professionals_list();
