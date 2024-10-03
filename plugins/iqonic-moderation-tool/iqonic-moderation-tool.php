<?php

/**
 * Plugin Name:       Iqonic Moderation Tool
 * Plugin URI:        https://iqonic.design/
 * Description:       Iqonic Moderation Tool provides safe and supportive community for everyone. Report content or block other members and set rules to automatically hide content or suspend members who have been flagged a number of times.
 * Version:           1.2.10
 * Author:            Iqonic Design.
 * Author URI:        https://iqonic.design/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iqonic-moderation-tool
 * Domain Path:       /languages
 */

use IMT\Classes\Iqonic_Moderation_Tool;
use IMT\Classes\IMT_Activator;
use IMT\Classes\IMT_Deactivator;

if (!defined('WPINC')) {
    die;
}

define('IQONIC_MODERATION_TEXT_DOMAIN', 'iqonic-moderation-tool');
define('IQONIC_MODERATION_TOOL_PATH', plugin_dir_path(__FILE__));
define('IQONIC_MODERATION_TOOL_URL', plugins_url('/', __FILE__));
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $version = "1.0.0";
}

if (!defined('IQONIC_MODERATION_TOOL_VERSION')) {
    if (function_exists('get_plugin_data')) {
        $plugin = get_plugin_data(__FILE__);
        $version = $plugin["Version"];
        $GLOBALS['version'] = $version;
        $GLOBALS['name'] = $plugin["Name"];
    }
    define('IQONIC_MODERATION_TOOL_VERSION', $version);
}

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
    die('Something went wrong');
}

register_activation_hook(__FILE__, [IMT_Activator::class, 'activate']);
register_deactivation_hook(__FILE__,  [IMT_Deactivator::class, 'deactivate']);

(new Iqonic_Moderation_Tool)->run();
