<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Loader class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Loader extends Component
{
    public $socialv_option;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        add_action('wp_enqueue_scripts', array($this, 'socialv_loader_options'), 20);
    }

    public function socialv_loader_options()
    {
        $loader_var = "";
        if (isset($this->socialv_option['loader_bg_color'])) {
            $loader_var = $this->socialv_option['loader_bg_color'];
            if (!empty($loader_var)) {
                $loader_css = "
                    #loading {
                        background : $loader_var !important;
                    }";
            }
        }
        if (!empty($this->socialv_option["loader-dimensions"]["width"]) && $this->socialv_option["loader-dimensions"]["width"] != "px") {
            $loader_width = $this->socialv_option["loader-dimensions"]["width"];
            $loader_css .= '#loading img { width: ' . $loader_width . ' !important; }';
        }

        if (!empty($this->socialv_option["loader-dimensions"]["height"]) && $this->socialv_option["loader-dimensions"]["height"] != "px") {
            $loader_height = $this->socialv_option["loader-dimensions"]["height"];
            $loader_css .= '#loading img { height: ' . $loader_height . ' !important; }';
        }
        if (!empty($loader_css)) {
            wp_add_inline_style('socialv-global', $loader_css);
        }
    }
}
