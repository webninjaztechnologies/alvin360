<?php

namespace IMT\Admin\Classes;

use IMT\Admin\Classes\Settings\General;
use Redux;

class IMT_Settings
{

    protected $opt_name = "imt_settings";
    protected $page_slug = "_imt_options";
    private $is_customizer;
    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function init()
    {
        $this->is_customizer = is_customize_preview();
        add_action('after_setup_theme', array($this, 'action_add_redux'));
        add_action('after_setup_theme', array($this, 'action_add_redux_widgets'));
        // remove admin notice for theme redux option page;
        add_action('admin_init', array($this, 'remove_admin_notices'));

        add_action('redux/page/' . $this->opt_name . '/enqueue', array($this, 'imt_redux_admin_styles'));
        add_action('wp_ajax_imt_save_redux_style_action', [$this, 'imt_save_redux_style']);
        add_action('wp_ajax_nopriv_imt_save_redux_style_action', [$this, 'imt_save_redux_style']);
        add_action("admin_enqueue_scripts", [$this, "imt_dequeue_unnecessary_scripts"], 11);

        /* redux fields overload */
        if (!$this->is_customizer) {
            add_filter('redux/imt_settings/field/class/dimensions', function () {
                return IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/dimensions/class-redux-dimensions.php';
            });
            add_filter('redux/imt_settings/field/class/spacing', function () {
                return IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/spacing/class-redux-spacing.php';
            });
            add_filter('redux/imt_settings/field/class/media', function () {
                return IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/media/class-redux-media.php';
            });
            add_filter('redux/imt_settings/field/class/raw', function () {
                return IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/raw/class-redux-raw.php';
            });
        }
    }

    function remove_admin_notices($screen)
    {
        if (isset($_GET['page']) && $_GET['page'] === '_imt_options') {
            require_once IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/dimensions/class-redux-dimensions.php';
            require_once IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/spacing/class-redux-spacing.php';
            require_once IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/media/class-redux-media.php';
            require_once  IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Classes/fields/raw/class-redux-raw.php';
        }
        return $screen;
    }
    function imt_dequeue_unnecessary_scripts($screen)
    {
        if ($screen == 'iqonic-moderation-tool_page__imt_options') {
            wp_deregister_style("select2");
            wp_deregister_script("select2");
        }
    }
    function imt_redux_admin_styles()
    {
        global $is_dark_mode;
        $root = '';

        // remove admin notice for theme redux option page;
        remove_all_actions("admin_notices");

        $js_url = IQONIC_MODERATION_TOOL_URL . 'includes/Admin/assets/js/redux-template.min.js';
        $version = IQONIC_MODERATION_TOOL_VERSION;

        $root_vars = [
            "--redux-sidebar-color:#121623",
            "--redux-top-header:#f5f7ff",
            "--submenu-border-color:#262b3b",
            "--border-color-light:#ededed",
            "--content-backgrand-color:#fff",
            "--sub-fields-back:#fff;",
            "--input-border-color:#d8e1f5",
            "--input-btn-back:#edeffc",
            "--input-back-color:#f5f7ff",
            "--white-color-nochage:#fff",
            "--redux-text-color:#69748c",/* font color */
            "--text-heading-color:#121623",
            "--submenu-hover-color:#fff",
            "--redux-primary-color:#de3a53",
            "--font-weight-medium:500", /* font weight */
            "--notice-yellow-back:#fbf5e2",
            "--notice-yellow-color:#f7a210",
            "--code-editor-active:#e6edff",
            "--notice-green-back:#d1f1be",
            "--redux-sidebar-color:#f5f7ff",
            "--active-tab-color:#f5f0f0",
            "--no-changeborder-color-light:#ededed",
            "--submenu-hover-color:#de3a53",
            "--submenu-active-color:#de3a53",
            "--submenu-border-color:#e5e9e7",
            "--redux-menu-lable:#aeb1b9",
            "--redux-menu-color:#353840",
            "--wp-content-back:#f0f0f1",
        ];

        wp_enqueue_style('redux-template', IQONIC_MODERATION_TOOL_URL . 'includes/Admin/assets/css/redux-template.min.css', array(), $version, 'all');
        wp_enqueue_style('redux-custom-font', IQONIC_MODERATION_TOOL_URL . 'includes/Admin/assets/css/vendor/redux-font/redux-custom-font.css', array(), $version, 'all');

        $root .= ':root{' . implode(";", $root_vars) . '}';
        $root .= '.redux-brand.logo { content: url( ' . IQONIC_MODERATION_TOOL_URL . 'includes/Admin/assets/images/logo.webp' . ' ) }';

        $is_dark_mode = get_option($this->page_slug . "_is_redux_dark_mode", true);

        if (!$is_dark_mode) {
            wp_add_inline_style("redux-template", $root);
        }
        wp_enqueue_script('underscore');
        wp_register_script('custom_redux_options', false);
        wp_localize_script('custom_redux_options', 'custom_redux_options_params', array(
            'ajaxUrl'       => admin_url('admin-ajax.php'),
            'root'          => $root,
            'action'        => "imt_save_redux_style_action",
            'is_dark_mode'  => $is_dark_mode ? true : false
        ));
        wp_enqueue_script('custom_redux_options');

        wp_enqueue_script('redux-template', $js_url, ['jquery'], $version, true);
    }

    public function imt_save_redux_style()
    {
        $is_dark_mode = isset($_GET['is_dark_mode']) && $_GET['is_dark_mode'] == 1 ? 1 : 0;
        update_option($this->page_slug . "_is_redux_dark_mode", $is_dark_mode);
    }

    public function action_add_redux()
    {
        // RDX Framework Barebones Sample Config File
        if (!class_exists('Redux')) {
            return;
        }

        $name = $GLOBALS["name"] ? $GLOBALS["name"] : esc_html__("Iqonic Moderation Tool", IQONIC_MODERATION_TEXT_DOMAIN);
        $version = IQONIC_MODERATION_TOOL_VERSION;

        $args = array(
            // TYPICAL -> Change these values as you need/desire
            'opt_name'             => $this->opt_name,
            // This is where your data is stored in the database and also becomes your global variable name.
            'display_name'         => $name,
            // Name that appears at the top of your panel
            'display_version'      => $version,
            // Version that appears at the top of your panel
            'menu_type'            => 'submenu',
            //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
            'allow_sub_menu'       => true,
            // Show the sections below the admin menu item or not
            'menu_title'           => esc_html__('Settings', IQONIC_MODERATION_TEXT_DOMAIN),
            'page_title'           => esc_html__('Settings', IQONIC_MODERATION_TEXT_DOMAIN),
            // You will need to generate a Google API key to use this feature.
            // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
            'google_api_key'       => '',
            // Set it you want google fonts to update weekly. A google_api_key value is required.
            'google_update_weekly' => false,
            // Must be defined to add google fonts to the typography module
            'async_typography'     => true,
            // Use a asynchronous font on the front end or font string
            //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
            'admin_bar'            => false,
            // Show the panel pages on the admin bar
            'admin_bar_icon'       => 'dashicons-admin-settings',
            // Choose an icon for the admin bar menu
            'admin_bar_priority'   => '',
            // Choose a priority for the admin bar menu
            'global_variable'      => '',
            // Set a different name for your global variable other than the opt_name
            'dev_mode'             => false,
            // Show the time the page took to load, etc
            'update_notice'        => false,
            // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
            'customizer'           => true,
            // Enable basic customizer support
            //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
            //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
            'class'                     => 'redux-content',
            // OPTIONAL -> Give you extra features
            'page_priority'        => 1,
            // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
            'page_parent'          => 'imt-admin-menu',
            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
            'page_permissions'     => 'manage_options',
            // Permissions needed to access the options panel.
            'menu_icon'            => "",
            // Specify a custom URL to an icon
            'last_tab'             => '',
            // Force your panel to always open to a specific tab (by id)
            'page_icon'            => 'icon-themes',
            // Icon displayed in the admin panel next to your menu_title
            'page_slug'            => '_imt_options',
            // Page slug used to denote the panel
            'save_defaults'        => true,
            // On load save the defaults to DB before user clicks save or not
            'default_show'         => false,
            // If true, shows the default value next to each field that is not the default value.
            'default_mark'         => '',
            // What to print by the field's title if the value shown is default. Suggested: *
            'show_import_export'   => false,
            // Shows the Import/Export panel when not used as a field.
            'show_options_object'       => true,
            'templates_path'           => !$this->is_customizer ? dirname(__FILE__) . '/templates/panel/' : '',
            'use_cdn'                   => true,
            // CAREFUL -> These options are for advanced use only
            'transient_time'       => 60 * MINUTE_IN_SECONDS,
            'output'               => true,
            // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
            'output_tag'           => true,
            // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
            'database'             => '',
            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
            'system_info'          => false,
            // REMOVE
            'hide_expand'            => true,
            // HINTS
            'hints'                => array(
                'icon'          => 'el el-question-sign',
                'icon_position' => 'right',
                'icon_color'    => 'lightgray',
                'icon_size'     => 'normal',
                'tip_style'     => array(
                    'color'   => 'light',
                    'shadow'  => true,
                    'rounded' => false,
                    'style'   => '',
                ),
                'tip_position'  => array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                ),
                'tip_effect'    => array(
                    'show' => array(
                        'effect'   => 'slide',
                        'duration' => '500',
                        'event'    => 'mouseover',
                    ),
                    'hide' => array(
                        'effect'   => 'slide',
                        'duration' => '500',
                        'event'    => 'click mouseleave',
                    ),
                ),
            )
        );

        Redux::set_args($this->opt_name, $args);
    }

    public function action_add_redux_widgets()
    {
        new General();
    }

    static function is_block_unblock_enable()
    {
        global $imt_settings;
        return isset($imt_settings["is_block_unblock_feature"]) && $imt_settings["is_block_unblock_feature"] == "enable";
    }
    static function is_report_enable()
    {
        global $imt_settings;
        if(empty($imt_settings)){
            $imt_settings = get_option('imt_settings');
        }
        return (isset($imt_settings["is_report_feature"]) && $imt_settings["is_report_feature"] == "enable") ? true : false ;
    }
    static function is_member_report_enable()
    {
        global $imt_settings;
        if(empty($imt_settings)){
            $imt_settings = get_option('imt_settings');
        }
        return (isset($imt_settings["is_user_report_feature"]) && $imt_settings["is_user_report_feature"] == "enable") ? true : false ;
    }
    static function is_activity_report_enable()
    {
        global $imt_settings;
       
        return isset($imt_settings["is_activity_report_feature"]) && $imt_settings["is_activity_report_feature"] == "enable";
    }
}
