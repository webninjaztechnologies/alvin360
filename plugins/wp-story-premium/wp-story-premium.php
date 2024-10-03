<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpuzman.com/
 * @since             1.0.0
 * @package           Wpstory_Premium
 *
 * @wordpress-plugin
 * Plugin Name:       WP Story Premium
 * Plugin URI:        https://codecanyon.net/item/wp-story-premium/27546341/
 * Description:       Create your own Instagram style stories.
 * Version:           3.5.0
 * Author:            wpuzman
 * Author URI:        https://codecanyon.net/user/wpuzman/
 * License:           license purchased
 * License URI:       http://themeforest.net/licenses/regular_extended
 * Text Domain:       wp-story-premium
 * Domain Path:       /languages
 * Tested up to:      6.4.2
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
define( 'WPSTORY_PREMIUM_VERSION', '3.5.0' );

/**
 * License notification version.
 * Display licence notice to unregistered installations.
 * Increase number to force display notice again.
 */
define( 'WPSTORY_LICENSE_NOTICE_VERSION', 5 );

define( 'WPSTORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPSTORY_DIR', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-story-premium-activator.php
 */
function wpstory_premium_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-story-premium-activator.php';
	Wpstory_Premium_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-story-premium-deactivator.php
 */
function wpstory_premium_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-story-premium-deactivator.php';
	Wpstory_Premium_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wpstory_premium_activate' );
register_deactivation_hook( __FILE__, 'wpstory_premium_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpstory-premium.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wpstory_premium_run() {

	$plugin = new Wpstory_Premium();
	$plugin->run();

}

wpstory_premium_run();
