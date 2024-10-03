<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Banner class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Breadcrumb extends Component
{
    public $socialv_option;
    public function __construct()
    {       
        $this->socialv_option = get_option('socialv-options');
        add_action('wp_enqueue_scripts', array($this, 'socialv_breadcrumb_dynamic_style'), 20);
        add_action('wp_enqueue_scripts', array($this, 'socialv_featured_hide'), 20);
    }

    public function is_socialv_breadcrumb()
    {
        $is_bredcrumb = true;
        $page_id = (function_exists('is_shop') && is_shop()) ? wc_get_page_id('shop') : get_queried_object_id();
        $breadcrumb_page_option = get_post_meta($page_id, 'display_banner', true);
        $breadcrumb_page_option = (!empty($breadcrumb_page_option)) ? $breadcrumb_page_option : "default";

        if ($breadcrumb_page_option != "default") {
            $is_bredcrumb = ($breadcrumb_page_option == 'no') ? false : true;
        } elseif ($this->socialv_option['display_banner'] == "no") { 
            $is_bredcrumb = false;
        }

        return $is_bredcrumb;
    }
    
    public function socialv_breadcrumb_dynamic_style()
    {
        if (!$this->is_socialv_breadcrumb()) {
            return;
        }
        $dynamic_css = '';
        if (isset($this->socialv_option['display_breadcrumb_title'])) {
            if ($this->socialv_option['display_breadcrumb_title'] == 'yes') {
                $dynamic = $this->socialv_option['breadcrumb_title_color'];
                $dynamic_css .= !empty($dynamic) ? '.socialv-breadcrumb .title { color: ' . $dynamic . ' !important; }' : '';
            }
        }
        if (isset($this->socialv_option['breadcrumb_back_type'])) {
            if ($this->socialv_option['breadcrumb_back_type'] == '1') {
                if (isset($this->socialv_option['breadcrumb_back_color']) && !empty($this->socialv_option['breadcrumb_back_color'])) {
                    $dynamic = $this->socialv_option['breadcrumb_back_color'];
                    $dynamic_css .= !empty($dynamic) ? '.socialv-breadcrumb { background-color: ' . $dynamic . ' !important; }' : '';
                }
            }
            if ($this->socialv_option['breadcrumb_back_type'] == '2') {
                if (isset($this->socialv_option['breadcrumb_back_image']['url'])) {
                    $dynamic = $this->socialv_option['breadcrumb_back_image']['url'];
                    $dynamic_css .= !empty($dynamic) ? '.socialv-breadcrumb { background-image: url(' . $dynamic . ') !important; }' : '';
                }
            }
        }
        if (!empty($dynamic_css)) {
            wp_add_inline_style('socialv-global', $dynamic_css);
        }
    }

    # hide featured image for post format
    public function socialv_featured_hide()
    {
        $featured_hide = '';
        $post_format = "";
        if (isset($this->socialv_option['posts_select'])) {
            $posts_format = $this->socialv_option['posts_select'];
            $post_format = get_post_format();

            if (isset($posts_format)){
                if (in_array(get_post_format(), $posts_format)) {
                    $featured_hide .= '.socialv-blog-main-list .format-' . $post_format . ' .socialv-blog-box .socialv-blog-image img { display: none !important; }';
                }
            }
            wp_add_inline_style('socialv-global', $featured_hide);
        }
    }

}
