<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Banner class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;
use function SocialV\Utility\socialv;

class Layout extends Component
{
    public $socialv_option;
    private $version;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        $this->version = socialv()->get_version();
        $this->socialv_layout_mode();
    }

    public function socialv_layout_mode()
    {
        add_action('wp_footer', array($this, 'socialv_layout_switcher_js'), 15);
        add_action('wp_enqueue_scripts', array($this, 'socialv_layout_js_css'));
        if (isset($this->socialv_option['socialv_frontside_switcher'])) {
            $options = $this->socialv_option['socialv_frontside_switcher'];
            if ($options == "yes") {
                add_action('wp_footer', [$this, 'socialv_frontend_customizer']);
            } else {
                $admin_switcher = $this->socialv_option['is_admin_switcher'];
                $current_user = is_user_logged_in() ? wp_get_current_user() : '';
                if (!empty($current_user)) {
                    if ($admin_switcher == '1' && in_array('administrator', (array) $current_user->roles)) {
                        add_action('wp_footer', [$this, 'socialv_frontend_customizer']);
                    } else {
                        add_action('init', [$this, 'socialv_layout_clearcookie']);
                    }
                }
            }
        }
        add_action('wp_head', [$this, 'socialv_layout_option_settings']);
    }

    public function socialv_layout_switcher_js()
    { ?>
        <script>
            var isSitePressExists = <?php echo class_exists('SitePress') ? 'true' : 'false'; ?>;
            var is_rtlExits = <?php echo (function_exists('is_rtl') && is_rtl()) ? 'true' : 'false'; ?>;
        </script>
<?php
        wp_enqueue_script('layout-switcher', get_template_directory_uri() . '/assets/js/layout.min.js', array('jquery'), $this->version, true);
    }

    public function socialv_layout_js_css()
    {
        wp_enqueue_style('layout-switcher', get_template_directory_uri() . '/assets/css/layout.min.css', array(), $this->version, 'all');
        wp_enqueue_script('utility', get_template_directory_uri() . '/assets/js/vendor/utility.js', array('jquery'), $this->version, true);
        wp_enqueue_script('setting', get_template_directory_uri() . '/assets/js/vendor/setting.js', array('jquery'), $this->version, true);
        $color_var = '';
        $setting_options = json_decode((get_option('setting_options')), true);
        if (isset($_COOKIE['socialv-setting']) && !empty($_COOKIE['socialv-setting'])) {
            $color = json_decode(stripslashes($_COOKIE['socialv-setting']), true);
            $color = $color['setting']['theme_color']['colors']['--{{prefix}}primary'];
        } else {
            if (!empty($setting_options['setting']['theme_color']['colors']['--{{prefix}}primary'])) {
                $color = $setting_options['setting']['theme_color']['colors']['--{{prefix}}primary'];
            }
        }
        if ((get_post_type() == 'lp_course') && class_exists('LearnPress')) {
            if (learn_press_get_current_user()) {
                if (learn_press_get_course()) {
                    $course = learn_press_get_course();
                    $user   = learn_press_get_current_user();
                    $course_data = $user->get_course_data($course->get_id());
                    $course_results = $course_data->calculate_course_results();
                    $color_var .= '--course-progress-bar: ' . $course_results['result'] . '%;';
                }
            }
        }
        if (!empty($color)) {
            $color_var .= '--color-theme-primary: ' . $color . ';';
            $color_var .= '--color-theme-primary-dark: ' . $color . '0c;';
            $color_var .= '--color-theme-primary-light: ' . $color . 'ff;';
            if (!empty($color_var)) {
                $color_var = ":root{" . $color_var . "}";
                 wp_add_inline_style('layout-switcher', $color_var);
            }
        }
    }

    public function socialv_frontend_customizer()
    {
        $page_id = get_queried_object_id();
        if (isset($this->socialv_option['socialv_frontside_switcher']) && $this->socialv_option['socialv_frontside_switcher'] == 'yes') {
            $nonrestricted = (!empty($this->socialv_option['customizer_non_selected_page'])) ? $this->socialv_option['customizer_non_selected_page'] : '';
            if (!empty($nonrestricted) && in_array($page_id, $nonrestricted)) {
            } else {
                get_template_part('template-parts/footer/frontend-customizer');
            }
        } else {
            get_template_part('template-parts/footer/frontend-customizer');
        }
    }

    function socialv_layout_option_settings()
    {
        $path = get_template_directory_uri() . '/assets/css/';
        $setting_options = '{
    "saveLocal": "cookieStorage",
    "storeKey": "socialv-setting",
    "setting": {
        "theme_scheme_direction": {
            "value": "ltr"
        },
        "theme_color": {
            "colors": {
                "--{{prefix}}primary": "#2f65b9"
            },
            "value": "theme-color-default"
        },
        "header_navbar": {
            "value": "default"
        },
        "sidebar_color": {
            "value": "sidebar-white"
        },
        "sidebar_type": {
            "value": []
        },
        "sidebar_menu_style": {
            "value": "navs-rounded-all"
        },
        "theme_scheme": {
            "value": "light"
        }
    },
    "theme_scheme_direction": "ltr",
    "theme_color": "theme-color-default",
    "header_navbar": "default",
    "sidebar_color": "sidebar-white",
    "sidebar_type": {},
    "sidebar_menu_style": "navs-rounded-all",
    "theme_scheme": "light"
}';

        if (isset($_POST['setting_options'])) {
            $setting_options = stripslashes($_POST['setting_options']);
            update_option('setting_options', $setting_options);
        } elseif (get_option('setting_options') === false) {
            add_option('setting_options', stripslashes($setting_options));
        } else {
            $setting_options = get_option('setting_options');
            $current_language_direction = is_rtl() ? 'rtl' : 'ltr';
            $setting_options_array = json_decode($setting_options, true);
            $setting_options_array['setting']['theme_scheme_direction']['value'] = $current_language_direction;
            $setting_options_array['theme_scheme_direction'] = $current_language_direction;
            $setting_options = json_encode($setting_options_array);
            // Save the updated options
            update_option('setting_options', $setting_options);
            $setting_options = (get_option('setting_options') == null) ? stripslashes($setting_options) : get_option('setting_options');
        }

        echo "<meta name='setting_options' content='$setting_options' data-version='$this->version' data-path='$path'></meta>";
    }
    public function socialv_layout_clearcookie()
    {
        if (isset($_COOKIE['socialv-setting'])) {
            setcookie('socialv-setting', '', time() - 3600, '/');
        }
    }
}
