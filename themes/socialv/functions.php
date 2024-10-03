<?php

/**
 * SocialV functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package socialv
 */

define('SOCIALV_MINIMUM_WP_VERSION', '4.5');
define('SOCIALV_MINIMUM_PHP_VERSION', '7.0');

// Bail if requirements are not met.
if (version_compare($GLOBALS['wp_version'], SOCIALV_MINIMUM_WP_VERSION, '<') || version_compare(phpversion(), SOCIALV_MINIMUM_PHP_VERSION, '<')) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

//wizard setup
if (is_admin()) {
	require_once get_parent_theme_file_path('/inc/Merlin/vendor/autoload.php');
	require_once get_parent_theme_file_path('/inc/Merlin/class-merlin.php');
	require_once get_template_directory() . '/inc/import.php';
    require_once get_template_directory() . '/inc/class-socialv_tgm-plugin-activation.php';
}

// Include WordPress shims.
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require get_template_directory() . '/inc/wordpress-shims.php';



if (class_exists('Redux')) {
    // Only if the 'Redux' class exists
    global $socialv_option;
    $socialv_option = get_option('socialv-options');
}


// Setup autoloader (via Composer or custom).
if (file_exists(get_template_directory() . '/vendor/autoload.php')) {
    require get_template_directory() . '/vendor/autoload.php';
} else {
    /**
     * Custom autoloader function for theme classes.
     *
     * @access private
     *
     * @param string $class_name Class name to load.
     * @return bool True if the class was loaded, false otherwise.
     */
    function socialv_autoload($class_name)
    {
        $namespace = 'SocialV\Utility';

        if (strpos($class_name, $namespace . '\\') !== 0) {
            return false;
        }

        $parts = explode('\\', substr($class_name, strlen($namespace . '\\')));

        $path = get_template_directory() . '/inc';
        foreach ($parts as $part) {
            $path .= '/' . $part;
        }
        $path .= '.php';

        if (!file_exists($path)) {
            return false;
        }

        require_once $path;

        return true;
    }
    spl_autoload_register('socialv_autoload');
}

// Load the `socialv()` entry point function.
require get_template_directory() . '/inc/functions.php';
// Initialize the theme.
call_user_func('SocialV\Utility\socialv');

// Set BP to use wp_mail
add_filter('bp_email_use_wp_mail', '__return_true');
// Set messages to HTML for BP sent emails.
add_filter('wp_mail_content_type', function ($default) {
    if (did_action('bp_send_email')) {
        return 'text/html';
    }
    return $default;
});
// Use HTML template
add_filter(
    'bp_email_get_content_plaintext',
    function ($content, $property, $transform, $bp_email) {
        if (!did_action('bp_send_email')) {
            return $content;
        }
        return $bp_email->get_template('add-content');
    },
    10,
    4
);
