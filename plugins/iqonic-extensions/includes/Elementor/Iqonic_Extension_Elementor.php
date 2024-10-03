<?php

namespace Iqonic\Elementor;

use Elementor\Plugin;
use Iqonic\Classes\Iqonic_Extension_Minify;


class Iqonic_Extension_Elementor
{
    private $plugin_name;

    private $version;
    private $widget_categories;


    protected $used_templates = [];

    protected $used_elements = [];
    private $minified_js = [];
    private $minified_css = [];
    private $iqonic_config = [];

    private $minified_post_css;
    private $minified_post_js;


    //FOR CSS AND JS MINIFY VARIABLES

    private $is_minify_class_exists;


    private $post_id;
    private $iqonic_plugin_url;
    private $iqonic_plugin_path;


    private $upload_dir;



    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->upload_dir = wp_get_upload_dir();
        $this->iqonic_plugin_url = IQONIC_EXTENSION_PLUGIN_URL . "includes/Elementor/";
        $this->iqonic_plugin_path = IQONIC_EXTENSION_PLUGIN_PATH . "includes/Elementor/";

        $this->widget_categories = ["iqonic-extension"];


        $this->is_minify_class_exists = class_exists("Iqonic\Classes\Iqonic_Extension_Minify");
        $this->iqonic_config = (function_exists('get_iqonic_config')) ? get_iqonic_config() : '';

        //  add_action('elementor/widget/render_content', array($this, 'widget_assets_on_render'), 10, 2);
        add_action('admin_bar_menu', array($this, 'regenerate_minify_css_js'), 999);
    }

    public function elementor_init()
    {
        Plugin::$instance->elements_manager->add_category(
            'iqonic-extension',
            [
                'title' => esc_html__('Iqonic', IQONIC_EXTENSION_TEXT_DOMAIN),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    public function iqonic_enqueue_dependent_scripts()
    {
        $this->post_id = get_queried_object_id();

        $upload_dir_path = $this->upload_dir['basedir'] . "/socialv";
        $upload_dir_url = site_url() . "/wp-content/uploads/socialv";

        $this->minified_post_css = $upload_dir_path . "/css/iqonic-post-{$this->post_id}.min.css";
        $this->minified_post_js = $upload_dir_path . "/js/iqonic-post-{$this->post_id}.min.js";

        if ($this->is_minify_class_exists && (!file_exists($this->minified_post_css) && !file_exists($this->minified_post_js))) {

            $general_scripts = isset($this->iqonic_config['general']['dependency']) ? $this->iqonic_config['general']['dependency'] : '';

            if (!empty($general_scripts)) {
                if (isset($general_scripts['css'])) {
                    foreach ($general_scripts['css'] as $css) {
                        wp_enqueue_style($css['name'], $this->iqonic_plugin_url . $css['src'], array(), $this->version, 'all');
                    }
                }
                if (isset($general_scripts['js'])) {
                    foreach ($general_scripts['js'] as $js) {
                        wp_enqueue_script($js['name'], $this->iqonic_plugin_url . $js['src'], array('jquery'), $this->version, true);
                    }
                }
            }
            add_action('elementor/widget/render_content', [$this, 'widget_assets_on_render'], 10, 2);
            add_action("wp_footer", [$this, "iqonic_generate_dependent_scripts"], 20);
        } else {
            $get_external_css = get_post_meta($this->post_id, "iqonic_external_css", true);
            $get_external_js = get_post_meta($this->post_id, "iqonic_external_js", true);

            if (!empty($get_external_css)) {
                $external_css = array_unique($get_external_css);
                foreach ($external_css as $handler => $css_src) {
                    wp_enqueue_style($handler, $css_src, array(), $this->version, 'all');
                }
            }
            if (!empty($get_external_js)) {
                $external_js = array_unique($get_external_js);
                foreach ($external_js as $handler => $js_src) {
                    wp_enqueue_script($handler, $js_src, array('jquery'), $this->version, true);

                }
            }
            
            wp_enqueue_style("iqonic-post-{$this->post_id}", $upload_dir_url . "/css/iqonic-post-{$this->post_id}.min.css", array(), $this->version, 'all');
            wp_enqueue_script("iqonic-post-{$this->post_id}", $upload_dir_url . "/js/iqonic-post-{$this->post_id}.min.js", array('jquery'), $this->version, true);

        }
    }

    public function iqonic_set_page_dependent_scripts($widget_name, $widget_category)
    {
        if (in_array($widget_category, $this->widget_categories)) {
            $dependent_scripts = get_post_meta($this->post_id, "iqonic_page_dependent_scripts_" . $this->post_id, true);
            if (!empty($dependent_scripts)) {
                $dependent_scripts[$widget_name] = $widget_category;
            } else {
                $dependent_scripts = [];
                $dependent_scripts[$widget_name] = $widget_category;
            }
            update_post_meta($this->post_id, "iqonic_page_dependent_scripts_" . $this->post_id, $dependent_scripts);
        }
    }
    public function widget_assets_on_render($content, $widget)
    {
        $widget_name = $widget->get_name();
        $widget_category = $widget->get_categories()[0];
        

        $upload_dir_path = $this->upload_dir['basedir'] . "/socialv";

        $css = $upload_dir_path . "/css/iqonic-post-{$this->post_id}.min.css";
        $js = $upload_dir_path . "/js/iqonic-post-{$this->post_id}.min.js";

        $this->iqonic_set_page_dependent_scripts($widget_name, $widget_category);
        if ($this->is_minify_class_exists && (!file_exists($css) && !file_exists($js))) {
            if ($widget->get_categories()[0] == 'iqonic-extension') {

                $all_script_config = $this->iqonic_config;

                if (isset($all_script_config[$widget->get_name()]['dependency']) && count($all_script_config[$widget->get_name()]['dependency']) > 0) {

                    $dir_path = plugin_dir_url(__DIR__) . 'Elementor/';
                    $dependency = $all_script_config[$widget->get_name()]['dependency'];

                    if (isset($dependency['js'])) {
                        foreach ($dependency['js'] as $js) {
                            wp_enqueue_script($js['name'], $dir_path . $js['src'], array('jquery'), $this->version, true);
                        }
                    }
                    if (isset($dependency['css'])) {
                        foreach ($dependency['css'] as $css) {
                            wp_enqueue_style($css['name'], $dir_path . $css['src'], array(), $this->version, 'all');
                        }
                    }
                }
            }
        }
        return $content;
    }
    public function iqonic_generate_dependent_scripts($elements)
    {
        $loaded_widgets = get_post_meta($this->post_id, "iqonic_page_dependent_scripts_" . $this->post_id, true);
        if (!empty($loaded_widgets))
            $this->iqonic_minify($this->post_id, $loaded_widgets);
    }
    public function iqonic_minify($id, $loaded_widgets)
    {
        $minify = new Iqonic_Extension_Minify();

        $css_uplaod_dir = $this->upload_dir['basedir'] . "/socialv/css";
        $js_uplaod_dir = $this->upload_dir['basedir'] . "/socialv/js";

        if (!file_exists($css_uplaod_dir))
            wp_mkdir_p($css_uplaod_dir);
        if (!file_exists($js_uplaod_dir))
            wp_mkdir_p($js_uplaod_dir);

        $config = $this->iqonic_config;

        $get_general_scripts = $this->get_general_scripts($this->iqonic_config, $minify);

        $css = $get_general_scripts['css'];
        $js = $get_general_scripts['js'];


        $external_css = $get_general_scripts['external_css'];
        $external_js = $get_general_scripts['external_js'];

        foreach ($loaded_widgets as $widget => $category) {
            if (isset($config[$widget]['dependency']) && count($config[$widget]['dependency']) > 0) {
                $dependency = $config[$widget]['dependency'];

                $path = $this->iqonic_plugin_path;
                $widget_scripts = $this->get_widget_scripts($dependency, $path, $minify);

                $css .= $widget_scripts['css'];
                $js .= $widget_scripts['js'];

                $external_css = array_merge($external_css, $widget_scripts['external_css']);
                $external_js = array_merge($external_js, $widget_scripts['external_js']);
            }
        }
        if (!empty($css)) {
            update_post_meta($id, "iqonic_external_css", $external_css);
            $put_css = $css_uplaod_dir . "/iqonic-post-$id.min.css";
            file_put_contents($put_css, $css);
        }

        if (!empty($js)) {
            update_post_meta($id, "iqonic_external_js", $external_js);
            $put_js = $js_uplaod_dir . "/iqonic-post-$id.min.js";
            file_put_contents($put_js, $js);
        }
    }
    public function get_widget_scripts($dependency, $path, $minify)
    {
        $js = $css = '';
        $external_js = $external_css = [];
        $plugin_url =  $this->iqonic_plugin_url;

        if (isset($dependency['js'])) {
            foreach ($dependency['js'] as $js_src) {
                if (isset($js_src['is_external']) && !$js_src['is_external']) {
                    if (!in_array($js_src['name'], $this->minified_js)) {
                        $this->minified_js[] = $js_src['name'];
                        $js .= $minify->iqonic_minfy_js($path . $js_src['src']);
                    }
                } else
                    $external_js[$js_src['name']] = $plugin_url . $js_src['src'];
            }
        }

        if (isset($dependency['css'])) {
            foreach ($dependency['css'] as $css_src) {
                if (isset($css_src['is_external']) && !$css_src['is_external']) {
                    if (!in_array($css_src['name'], $this->minified_css)) {
                        $this->minified_css[] = $css_src['name'];
                        $css .= $minify->iqonic_minify_css($path . $css_src['src']);
                    }
                } else
                    $external_css[$css_src['name']] = $plugin_url . $css_src['src'];
            }
        }

        return ["css" => $css, "js" => $js, "external_css" => $external_css, "external_js" => $external_js];
    }

    public function get_general_scripts($iqonic, $minify)
    {
        $css = $js = '';
        $external_css = $external_js = [];

        if (isset($iqonic['general']['dependency'])) {
            $general_script = $this->get_widget_scripts($iqonic['general']['dependency'], $this->iqonic_plugin_path, $minify);
            $css .= $general_script['css'];
            $js .= $general_script['js'];

            $external_css = array_merge($external_css, $general_script['external_css']);
            $external_js = array_merge($external_js, $general_script['external_js']);
        }

        return ["css" => $css, "js" => $js, "external_css" => $external_css, "external_js" => $external_js];
    }
    public function include_widgets()
    {
        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {
            $all_config = $this->iqonic_config;

            if (!empty($all_config)) {
                foreach ($all_config as $item) {
                    if (isset($item['class'])) {
                        Plugin::instance()->widgets_manager->register(new $item['class']);
                    }
                }
            }
        }
    }
    public function editor_enqueue_scripts()
    {
        if(file_exists($this->minified_post_js)) return;
        
        $all_script_config = $this->iqonic_config;

        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base') && !empty($all_script_config)) {
            foreach ($all_script_config as $key => $item) {
                if ("general" == $key || Plugin::$instance->preview->is_preview_mode()) {
                    if (isset($item['dependency']['js'])) {
                        foreach ($item['dependency']['js'] as $js) {
                            wp_enqueue_script($js['name'], plugin_dir_url(__FILE__) . $js['src'], array('jquery'), $this->version, true);
                        }
                    }
                }
            }
        }
    }
    public function editor_enqueue_styles()
    {
        if(file_exists($this->minified_post_css)) return;

        $all_style_config = $this->iqonic_config;

        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base') && !empty($all_style_config)) {
            foreach ($all_style_config as $key => $item) {
                if ("general" == $key || Plugin::$instance->preview->is_preview_mode()) {
                    if (isset($item['dependency']['css'])) {
                        foreach ($item['dependency']['css'] as $css) {

                            wp_enqueue_style($css['name'], plugin_dir_url(__FILE__) . $css['src'], array(), $this->version, 'all');
                        }
                    }
                }
            }
        }
    }
    public function load_used_items($data, $post_id)
    {
        if ($this->is_running_background()) {
            return $data;
        }
        if ($this->is_preview_mode()) {
            // used template stack
            $this->used_templates[] = $post_id;
            $this->used_elements[] = 'general';
            // used Elements stack
            $this->used_elements = array_merge($this->used_elements, $this->get_loaded_elements($data));
            $this->enqueue();
        }

        return $data;
    }

    public function enqueue()
    {
        if (file_exists($this->minified_post_css) && file_exists($this->minified_post_js)) return;

        $elements = $this->used_elements;

        if (!empty($elements)) {
            $config = $this->iqonic_config;
            foreach ($elements as $item) {
                if (isset($config[$item]['dependency']['js'])) {
                    foreach ($config[$item]['dependency']['js'] as $js) {
                        wp_enqueue_script($js['name'], plugin_dir_url(__FILE__) . $js['src'], array('jquery'), $this->version, true);
                    }
                }
                if (isset($config[$item]['dependency']['css'])) {
                    foreach ($config[$item]['dependency']['css'] as $css) {
                        wp_enqueue_style($css['name'], plugin_dir_url(__FILE__) . $css['src'], array(), $this->version, 'all');
                    }
                }
            }
        }
    }
    public function get_loaded_elements($elements): array
    {
        $collections = [];

        foreach ($elements as $element) {
            if (isset($element['elType']) && $element['elType'] == 'widget') {
                if ($element['widgetType'] === 'global') {
                    $document = Plugin::$instance->documents->get($element['templateID']);
                    if (is_object($document)) {
                        $collections = array_merge($collections, $this->get_loaded_elements($document->get_elements_data()));
                    }
                } else {
                    $collections[] = $element['widgetType'];
                }
            }

            if (!empty($element['Elements'])) {
                $collections = array_merge($collections, $this->get_loaded_elements($element['Elements']));
            }
        }

        return $collections;
    }
    public function is_running_background(): bool
    {
        if (wp_doing_cron()) {
            return true;
        }

        if (wp_doing_ajax()) {
            return true;
        }

        if (isset($_REQUEST['action'])) {
            return true;
        }

        return false;
    }
    public function is_preview_mode(): bool
    {
        if (isset($_REQUEST['elementor-preview'])) {
            return false;
        }

        if (isset($_REQUEST['action'])) {
            return false;
        }

        return true;
    }
    public function regenerate_minify_css_js($wp_admin_bar)
    {
        $this->post_id = get_queried_object_id();

        if ($this->post_id <= 0) return $wp_admin_bar;

        if (!is_admin()) {
            $args = array(
                'id'    => 'regenerate_minified_page_scripts',
                'title' => esc_html__('Regenerate Iqonic Scripts', IQONIC_EXTENSION_TEXT_DOMAIN),
            );

            $wp_admin_bar->add_node($args);

            $args = array(
                'id'    => 'regenerate_current_page_scripts',
                'parent' => 'regenerate_minified_page_scripts',
                'title' => esc_html__('Regenerate Current Page Scripts', IQONIC_EXTENSION_TEXT_DOMAIN),
                'href'  => '?post_id=' . $this->post_id . '&mode=current',
            );
            $wp_admin_bar->add_node($args);

            $args = array(
                'id'    => 'regenerate_all_pages_scripts',
                'parent' => 'regenerate_minified_page_scripts',
                'title' => esc_html__('Regenerate All Pages Scripts', IQONIC_EXTENSION_TEXT_DOMAIN),
                'href'  => '?post_id=' . $this->post_id . '&mode=all',
            );

            $wp_admin_bar->add_node($args);

            remove_all_minified_files();
        }
    }

}