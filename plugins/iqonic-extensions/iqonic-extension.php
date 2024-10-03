<?php

/**
 * Plugin Name:       Iqonic Extension
 * Plugin URI:        https://wordpress.iqonic.design/product/wp/socialv/
 * Description:       Iqonic Extension.
 * Version:           2.0.10
 * Author:            Iqonic Design
 * Author URI:        https://iqonic.design/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iqonic-extension
 * Domain Path:       /languages
 */

use Iqonic\Classes\Iqonic_Extension;
use Iqonic\Classes\Iqonic_Extension_Activator;
use Iqonic\Classes\Iqonic_Extension_Deactivator;

if (!defined('WPINC')) {
    die;
}
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

$plugin_data = get_plugin_data(__FILE__);

define('IQONIC_EXTENSION_TEXT_DOMAIN', 'iqonic-extension');
define('IQONIC_EXTENSION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('IQONIC_EXTENSION_PLUGIN_URL', plugins_url('/', __FILE__));
define('IQONIC_EXTENSION_VERSION',  $plugin_data['Version']);

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
    die('Something went wrong');
}

$GLOBALS['iqonic_config'] = require_once IQONIC_EXTENSION_PLUGIN_PATH . 'config.php';

register_activation_hook(__FILE__, [Iqonic_Extension_Activator::class, 'activate']);
register_deactivation_hook(__FILE__,  [Iqonic_Extension_Deactivator::class, 'deactivate']);

(new Iqonic_Extension)->run();
