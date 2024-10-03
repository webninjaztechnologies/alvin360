<?php

namespace Iqonic\Classes;

use Iqonic\Acf;
use Iqonic\Controls;
use Iqonic\Custom_Post\Metabox\Gallery;
use Iqonic\Elementor;

class Iqonic_Extension
{

    protected $loader;

    protected $plugin_name;

    protected $version;

    public function __construct()
    {
        if (defined('IQONIC_EXTENSION_VERSION')) {
            $this->version = IQONIC_EXTENSION_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'iqonic-extension';

        $this->load_dependencies();
        $this->load_acf_dependencies();
        $this->set_locale();
        $this->define_register_controls();
        $this->register_custom_helper();
        $this->define_elementor_hooks();
        $this->define_redux_hooks();
        $this->define_shortcodes();
    }

    public function load_dependencies()
    {
        $this->loader = new Iqonic_Extension_Loader();
    }

    public function load_acf_dependencies()
    {
        if (function_exists('get_field')) {
            new Acf\General();
        }
        new Acf\MetaBox();
    }

    public function set_locale()
    {
        $plugin_i18n = new Iqonic_Extension_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    public function define_register_controls()
    {
        $this->loader->add_action('elementor/controls/controls_registered', $this, 'register_controls');
    }

    public function register_controls()
    {
        if (class_exists('\Elementor\Base_Data_Control')) {
            $controls_manager = \Elementor\Plugin::$instance->controls_manager;
        }
    }
    // If u want to load a function only in the front end.

    public function define_elementor_hooks()
    {

        $plugin_elementor = new Elementor\Iqonic_Extension_Elementor($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('elementor/init', $plugin_elementor, 'elementor_init');
        $this->loader->add_action('elementor/widgets/register', $plugin_elementor, 'include_widgets');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_elementor, 'editor_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_elementor, 'editor_enqueue_scripts');
        $this->loader->add_filter('elementor/frontend/builder_content_data', $plugin_elementor, 'load_used_items', 10, 2);
       $this->loader->add_action("wp_enqueue_scripts", $plugin_elementor, 'iqonic_enqueue_dependent_scripts', 20);

    }

    public function define_redux_hooks()
    {
        if (!class_exists("Redux"))
            require_once "Redux_Panel.php";
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/ReduxCore/framework.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/footer-logo.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/social_media.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/recent-post.php';
        if (function_exists('buddypress')) {
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/recently-active-members.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/latest-activity-feed.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/friend-suggestions.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/group-suggestions.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/group-author.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/iqonic-navigation-menu.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/user-profile.php';
        }
        if (in_array('learnpress/learnpress.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/courses.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/course-categories.php';
            require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Widget/course-tags.php';
        }
    }

    public function define_shortcodes()
    {
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Shortcode/user-login.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Shortcode/user-register.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Shortcode/user-forgot-password.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . '/includes/Shortcode/user-resend-verification-email.php';


    }

    public function register_custom_helper()
    {
        require_once IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Utils/ajax_helper.php';
        require_once IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Utils/animation_helpers.php';
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
