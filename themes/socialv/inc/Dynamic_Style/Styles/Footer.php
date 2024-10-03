<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Footer class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Footer extends Component
{
	public $socialv_option;
	public function __construct()
	{
		$this->socialv_option = get_option('socialv-options');
		add_action('wp_enqueue_scripts', array($this, 'socialv_footer_dynamic_style'), 20);
	}

	public function is_socialv_footer()
	{
		$is_footer = true;
		$page_id = (function_exists('is_shop') && is_shop()) ? wc_get_page_id('shop') : get_queried_object_id();
		if(function_exists('bp_current_component') && bp_current_component() ){
			$page_id = bp_core_get_directory_page_id();
		}
		$footer_page_option = get_post_meta($page_id, "display_footer", true);
		
		$footer_page_option = !empty($footer_page_option) ? $footer_page_option : "default";
		if ($footer_page_option != 'default') {
			$is_footer = ($footer_page_option == 'no') ? false : true;
		}
		if (is_404() && !$this->socialv_option['footer_on_404']) {
			$is_footer = false;
		}

		return $is_footer;
	}
	public function socialv_footer_dynamic_style()
	{
		if (!$this->is_socialv_footer()) {
			return;
		}

		$footer_css = '';
		if (function_exists('get_field') && get_field('field_footer_bg_color') && !empty(get_field('field_footer_bg_color'))) {
			$footer_bg_color = get_field('field_footer_bg_color');
			$footer_css .= ".footer {
								background-color: $footer_bg_color !important;
							}";
		} else {
			if (isset($this->socialv_option['change_footer_background']) && $this->socialv_option['change_footer_background'] == 'color' && !empty($this->socialv_option['footer_bg_color'])) {
				$footer_bg_color = $this->socialv_option['footer_bg_color'];
				$footer_css .= ".footer {
										background-color: $footer_bg_color !important;
									}";
			}
			if (isset($this->socialv_option['change_footer_background']) && $this->socialv_option['change_footer_background'] == 'image' && !empty($this->socialv_option['footer_bg_image']['url'])) {
				$footer_bg_image = $this->socialv_option['footer_bg_image'];
				$footer_css .= ".footer {
										background: url(" . $footer_bg_image['url'] . ") no-repeat !important;
										backgrouns-size: cover !important ;
									}";
			}
		}

		if (!empty($footer_css)) {
			wp_add_inline_style('socialv-global', $footer_css);
		}
	}
}
