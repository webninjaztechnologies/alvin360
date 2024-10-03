<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Logo class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Logo extends Component
{
    public $socialv_option;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        add_action('wp_enqueue_scripts', array($this, 'socialv_logo_options'), 20);
        add_action('wp_enqueue_scripts', array($this, 'socialv_logo_url'), 20);
    }

    public function socialv_logo_options()
    {
        $logo_var = $logo = "";
        if (function_exists('get_field') && class_exists('Redux')) {
            $is_yes = function_exists('get_field') ? get_field('acf_key_logo_switch') : '';
            $acf_logo_text = function_exists('get_field') ? get_field('verticle_header_color') : '';
            if ($is_yes === 'yes' && !empty($acf_logo_text)) {
                $logo = $acf_logo_text;
            } else{
                if(!empty($this->socialv_option['verticle_header_color']) && isset($this->socialv_option['verticle_header_color'])){
                    $logo = $this->socialv_option['verticle_header_color'];
                        $logo_var .= "[data-mode=light] .navbar-brand.socialv-logo .logo-title{
                        color : $logo !important;
                    }";
                }
                if(!empty($this->socialv_option['verticle_dark_header_color']) && isset($this->socialv_option['verticle_dark_header_color'])){
                    $logo = $this->socialv_option['verticle_dark_header_color'];
                        $logo_var .= "[data-mode=dark] .navbar-brand.socialv-logo .logo-title{
                        color : $logo !important;
                    }";
                    
                }
            }

            if ($is_yes === 'no') {
                $logo_var .= ".navbar-brand.socialv-logo{
                    display : none !important;
                }";
            }
        }

        if (!empty($this->socialv_option["logo-dimensions"]["width"]) && $this->socialv_option["logo-dimensions"]["width"] != "px") {
            $logo_width = $this->socialv_option["logo-dimensions"]["width"];
            $logo_var .= '.navbar-brand.socialv-logo img { width: ' . $logo_width . ' !important; }.sidebar-mini .navbar-brand.socialv-logo img { width: auto !important; }';
        }

        if (!empty($this->socialv_option["logo-dimensions"]["height"]) && $this->socialv_option["logo-dimensions"]["height"] != "px") {
            $logo_height = $this->socialv_option["logo-dimensions"]["height"];
            $logo_var .= '.navbar-brand.socialv-logo img { height: ' . $logo_height . ' !important; }.sidebar-mini .navbar-brand.socialv-logo img { height: auto !important; }';
        }

        if (!empty($logo_var)) {
            wp_add_inline_style('socialv-global', $logo_var);
        }
    }


    public function socialv_logo_url()
    {
        $dark_logo = "";
        if (function_exists('get_field') && class_exists('Redux')) {
            $acf_logo = function_exists('get_field') ? get_field('header_dark_logo') : '';

            if (!empty($acf_logo['url'])) {
                $dark_logo = $acf_logo['url'];
            } else if (isset($this->socialv_option['socialv_verticle_dark_logo'])) {
                if (!empty($this->socialv_option['socialv_verticle_dark_logo']['url'])) {
                    $dark_logo = $this->socialv_option['socialv_verticle_dark_logo']['url'];
                }
            }
        }

        if (!empty($dark_logo)) {
            $logo = '[data-mode=dark] .navbar-brand.socialv-logo img { content: url(' . $dark_logo . ') }';
            wp_add_inline_style('socialv-global', $logo);
        }
    }
}
