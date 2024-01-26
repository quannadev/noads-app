<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dl.quanna.dev
 * @since             1.0.0
 * @package           Get_Link
 *
 * @wordpress-plugin
 * Plugin Name:       getlink
 * Plugin URI:        https://quanna.dev
 * Description:       get link for view
 * Version:           1.0.0
 * Author:            Quanna
 * Author URI:        https://dl.quanna.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       get-link
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
define( 'GET_LINK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-get-link-activator.php
 */
function activate_get_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-link-activator.php';
	Get_Link_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-get-link-deactivator.php
 */
function deactivate_get_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-link-deactivator.php';
	Get_Link_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_get_link' );
register_deactivation_hook( __FILE__, 'deactivate_get_link' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-get-link.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_get_link() {

	$plugin = new Get_Link();
	$plugin->run();
}
run_get_link();

