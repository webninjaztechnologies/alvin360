<?php

namespace IR\Classes;

use IR\Admin\Classes\IR_Settings;
use IR\Admin\Classes\IR_Admin;
use IR\Rest_API\Api_Handler;
use IR\Shortcodes\Shortcodes;

class Iqonic_Reaction
{
    protected $loader;
    protected $plugin_name;
    protected $shortcodes;
    protected $version;

    public function __construct()
    {
        if (defined('IQONIC_REACTION_VERSION')) {
            $this->version = IQONIC_REACTION_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'iqonic-reactions';
        new IR_Admin();

        $this->class_ir_rest_api();
        $this->load_ir_dependencies();
        $this->set_ir_locale();
        $this->define_ir_hooks();
        add_action('init', array($this, 'iqonic_templates_init'));
    }

    public function class_ir_rest_api()
    {
       (new Api_Handler())->init();
    }
    public function load_ir_dependencies()
    {
        $this->loader = new IR_Loader();
        $this->shortcodes = new Shortcodes();
        $this->shortcodes->init();
        ir_register_template_stack('get_stylesheet_directory', 10);
        ir_register_template_stack('ir_get_template_directory', 12);
        ir_register_template_stack('ir_get_plugin_directory', 12);

        if (!class_exists("Redux"))
            require_once "Redux_Panel.php";
        require_once IQONIC_REACTION_PATH . '/includes/ReduxCore/framework.php';

        if (class_exists("Redux")) {
            $settings = new IR_Settings();
            $settings->init();
        }
    }

    public function iqonic_templates_init()
    {

        ir_get_template_part("templates/reaction");

        ir_get_template_part("templates/reaction", "button");
        ir_get_template_part("templates/reaction", "count");
        ir_get_template_part("templates/reactions-list");

        // reaction action
        ir_get_template_part("templates/reaction-box/reaction-box", "close");
        ir_get_template_part("templates/reaction-box/reaction-box");
        ir_get_template_part("templates/reaction-box/reaction-list");

        //comments action
        ir_get_template_part("templates/comments-reaction/comment-reaction");

        //comment templates
        ir_get_template_part("templates/comments-reaction/ir-comment-box/comment-reaction-box", "close");
        ir_get_template_part("templates/comments-reaction/ir-comment-box/comment-reaction-box");
        ir_get_template_part("templates/comments-reaction/ir-comment-box/comment-reaction-list");
    }

    public function set_ir_locale()
    {
        $plugin_i18n = new IR_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    public function define_ir_hooks()
    {
        $this->loader->add_action('init', $this->shortcodes, 'ir_dependent_style');
        $this->loader->add_action('wp_footer', $this->shortcodes, 'ir_dependent_scripts');
        $this->loader->add_action('admin_enqueue_scripts', $this->shortcodes, 'register_admin_repeater_scripts');
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
