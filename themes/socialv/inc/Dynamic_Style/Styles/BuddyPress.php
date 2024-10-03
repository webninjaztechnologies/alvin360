<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\BuddyPress class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class BuddyPress extends Component
{
    public $socialv_option;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        add_action('wp_enqueue_scripts', array($this, 'socialv_banner_dynamic_style'), 20);
    }

    public function is_socialv_banner()
    {
        $is_bredcrumb = (isset($this->socialv_option['bp_display_banner']) && $this->socialv_option['bp_display_banner'] == "no") ? false : true;
        return $is_bredcrumb;
    }

    public function socialv_banner_dynamic_style()
    {
        if (!$this->is_socialv_banner()) {
            return;
        }
        $dynamic_css = '';
        if (isset($this->socialv_option['bp_display_banner_title'])) {
            if ($this->socialv_option['bp_display_banner_title'] == 'yes') {
                $dynamic = $this->socialv_option['bp_banner_title_color'];
                $dynamic_css .= !empty($dynamic) ? '.socialv-bp-banner .socialv-bp-banner-title .title { color: ' . $dynamic . ' !important; }' : '';
            }
        }

        if (!empty($this->socialv_option['bp_page_default_banner_image']['url'])) {
            $bnurl = $this->socialv_option['bp_page_default_banner_image']['url'];
            $dynamic_css .= '.socialv-bp-banner { background-image: url(' . $bnurl . ') !important; }';
        }
        if (!empty($dynamic_css)) {
            wp_add_inline_style('socialv-global', $dynamic_css);
        }
    }
}
