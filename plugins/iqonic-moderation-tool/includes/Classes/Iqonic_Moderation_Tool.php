<?php

namespace IMT\Classes;

use IMT\Admin\Classes\IMT_Admin;
use IMT\Admin\Classes\IMT_Report;
use IMT\Admin\Classes\IMT_Settings;
use IMT\Shortcodes\Shortcodes;

class Iqonic_Moderation_Tool
{

    protected $loader;

    protected $plugin_name;

    protected $shortcodes;

    protected $version;

    public function __construct()
    {
        if (defined('IQONIC_MODERATION_TOOL_VERSION')) {
            $this->version = IQONIC_MODERATION_TOOL_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'iqonic-moderation-tool';

        $this->load_imt_dependencies();
        $this->set_imt_locale();
        $this->define_imt_hooks();
        new IMT_Admin();
        $report = new IMT_Report();
        $report->init();
    }

    public function load_imt_dependencies()
    {
        $this->loader = new IMT_Loader();
        $this->shortcodes = new Shortcodes();
        $this->shortcodes->init();

        imt_register_template_stack('get_stylesheet_directory', 10);
        imt_register_template_stack('imt_get_template_directory', 12);
        imt_register_template_stack('imt_get_plugin_directory', 12);

        if (!class_exists("Redux"))
            require_once "Redux_Panel.php";
        require_once IQONIC_MODERATION_TOOL_PATH . '/includes/ReduxCore/framework.php';

        if (class_exists("Redux")) {
            $settings = new IMT_Settings();
            $settings->init();
        }
    }

    public function set_imt_locale()
    {
        $plugin_i18n = new IMT_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    public function define_imt_hooks()
    {
        // shortcodes
        $this->loader->add_action('wp_footer', $this->shortcodes, 'imt_dependent_scripts');
    }

    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_name(): string
    {
        return $this->plugin_name;
    }

    public function get_loader()
    {
        return $this->loader;
    }

    public function get_version(): string
    {
        return $this->version;
    }
}
