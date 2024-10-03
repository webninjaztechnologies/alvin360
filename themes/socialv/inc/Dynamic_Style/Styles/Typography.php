<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Banner class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Typography extends Component
{
    public $socialv_option;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        add_action('wp_enqueue_scripts', array($this, 'socialv_fontstyle_dynamic_style'), 20);
    }

    public function socialv_fontstyle_dynamic_style()
    {

        $font_dynamic_css = '';
        // Change font 1
        if (isset($this->socialv_option['change_font']) && $this->socialv_option['change_font'] == 1) {
            // body
            if (isset($this->socialv_option["body_font"]["font-family"])) {
                $body_family = $this->socialv_option["body_font"]["font-family"];
            }
            if (isset($this->socialv_option["body_font"]["font-backup"])) {
                $body_backup = $this->socialv_option["body_font"]["font-backup"];
            }
            $font_dynamic_css .= (!empty($body_family) && !empty($body_backup)) ? ':root { --global-font-family: ' . $body_family . ',' .  $body_backup . '  !important; } body { font-family: ' . $body_family . ',' .  $body_backup . ' !important; }' : '';

            if (!empty($this->socialv_option["body_font"]["font-size"])) {
                $body_size = $this->socialv_option["body_font"]["font-size"];
                $font_dynamic_css .= ':root { --global-font-size: ' . $body_size . '  !important; } body { font-size: ' . $body_size . ' !important; }';
            }

            if (!empty($this->socialv_option["body_font"]["font-weight"])) {
                $body_weight = $this->socialv_option["body_font"]["font-weight"];
                $font_dynamic_css .= ':root {--font-weight-body : ' . $body_weight . ' !important; } body { font-weight: ' . $body_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h1_font"]["font-family"])) {
                $h1_family = $this->socialv_option["h1_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h1_family . ' !important; } h1 { font-family: ' . $h1_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h1_font"]["font-size"])) {
                $h1_size = $this->socialv_option["h1_font"]["font-size"];
                $font_dynamic_css .= 'h1 { font-size: ' . $h1_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h1_font"]["font-weight"])) {
                $h1_weight = $this->socialv_option["h1_font"]["font-weight"];
                $font_dynamic_css .= 'h1 { font-weight: ' . $h1_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h2_font"]["font-family"])) {
                $h2_family = $this->socialv_option["h2_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h2_family . '  !important; } h2 { font-family: ' . $h2_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h2_font"]["font-size"])) {
                $h2_size = $this->socialv_option["h2_font"]["font-size"];
                $font_dynamic_css .= 'h2 { font-size: ' . $h2_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h2_font"]["font-weight"])) {
                $h2_weight = $this->socialv_option["h2_font"]["font-weight"];
                $font_dynamic_css .= 'h2 { font-weight: ' . $h2_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h3_font"]["font-family"])) {
                $h3_family = $this->socialv_option["h3_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h3_family . '  !important; } h3 { font-family: ' . $h3_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h3_font"]["font-size"])) {
                $h3_size = $this->socialv_option["h3_font"]["font-size"];
                $font_dynamic_css .= 'h3 { font-size: ' . $h3_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h3_font"]["font-weight"])) {
                $h3_weight = $this->socialv_option["h3_font"]["font-weight"];
                $font_dynamic_css .= 'h3 { font-weight: ' . $h3_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h4_font"]["font-family"])) {
                $h4_family = $this->socialv_option["h4_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h4_family . '  !important; } h4 { font-family: ' . $h4_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h4_font"]["font-size"])) {
                $h4_size = $this->socialv_option["h4_font"]["font-size"];
                $font_dynamic_css .= 'h4 { font-size: ' . $h4_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h4_font"]["font-weight"])) {
                $h4_weight = $this->socialv_option["h4_font"]["font-weight"];
                $font_dynamic_css .= 'h4 { font-weight: ' . $h4_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h5_font"]["font-family"])) {
                $h5_family = $this->socialv_option["h5_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h5_family . '  !important; } h5 { font-family: ' . $h5_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h5_font"]["font-size"])) {
                $h5_size = $this->socialv_option["h5_font"]["font-size"];
                $font_dynamic_css .= 'h5 { font-size: ' . $h5_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h5_font"]["font-weight"])) {
                $h5_weight = $this->socialv_option["h5_font"]["font-weight"];
                $font_dynamic_css .= 'h5 { font-weight: ' . $h5_weight . ' !important; }';
            }

            if (!empty($this->socialv_option["h6_font"]["font-family"])) {
                $h6_family = $this->socialv_option["h6_font"]["font-family"];
                $font_dynamic_css .= ':root { --highlight-font-family: ' . $h6_family . '  !important; } h6 { font-family: ' . $h6_family . ' !important; }';
            }
            if (!empty($this->socialv_option["h6_font"]["font-size"])) {
                $h6_size = $this->socialv_option["h6_font"]["font-size"];
                $font_dynamic_css .= 'h6 { font-size: ' . $h6_size . ' !important; }';
            }
            if (!empty($this->socialv_option["h6_font"]["font-weight"])) {
                $h6_weight = $this->socialv_option["h6_font"]["font-weight"];
                $font_dynamic_css .= 'h6 { font-weight: ' . $h6_weight . ' !important; }';
            }
        }
        if (!empty($font_dynamic_css)) {
            wp_add_inline_style('socialv-global', $font_dynamic_css);
        }
    }
}
