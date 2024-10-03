<?php

/**
 * Plugin Name:       Iqonic Reactions
 * Plugin URI:        https://iqonic.design/
 * Description:       Iqonic Reactions provides reactions to your buddypress posts and comments.
 * Version:           1.1.6
 * Author:            Iqonic Design.
 * Author URI:        https://iqonic.design/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iqonic-reactions
 * Domain Path:       /languages
 */

use IR\Classes\Iqonic_Reaction;
use IR\Classes\IR_Activator;
use IR\Classes\IR_Deactivator;

if (!defined('WPINC')) {
    die;
}

if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
$plugin_data = get_plugin_data(__FILE__);

define('IQONIC_REACTION_TEXT_DOMAIN', 'iqonic-reactions');
define('IQONIC_REACTION_PATH', plugin_dir_path(__FILE__));
define('IQONIC_REACTION_URL', plugins_url('/', __FILE__));
define('IQONIC_REACTION_VERSION',  $plugin_data['Version']);
define('IQONIC_REACTION_NAME',  $plugin_data['Name']);

if (!defined('IR_API_NAMESPACE')) {
    define('IR_API_NAMESPACE', "iqonic");
}

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
    die('Something went wrong');
}

register_activation_hook(__FILE__, [IR_Activator::class, 'activate']);
register_deactivation_hook(__FILE__,  [IR_Deactivator::class, 'deactivate']);

(new Iqonic_Reaction)->run();
