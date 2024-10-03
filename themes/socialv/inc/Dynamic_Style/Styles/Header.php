<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Header class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;


class Header extends Component
{
	public $socialv_option;
	public function __construct()
	{		
		$this->socialv_option = get_option('socialv-options');
		add_action('wp_enqueue_scripts', array($this, 'socialv_header_background_style'), 20);
	}

	public function is_socialv_header()
	{
		$is_header = true;
		$page_id = (function_exists('is_shop') && is_shop()) ? wc_get_page_id('shop') : get_queried_object_id();
		if(function_exists('bp_current_component') && bp_current_component() && function_exists('bp_core_get_directory_page_id')){
			$page_id = bp_core_get_directory_page_id();
		}
		 $header_page_option = get_post_meta($page_id, "display_header", true);
		$header_page_option = !empty($header_page_option) ? $header_page_option : "default";

		if ($header_page_option != 'default') {
			$is_header =  ($header_page_option == 'no') ? false : true;
		}
		if (is_404() && !$this->socialv_option['header_on_404']) {
			$is_header = false;
		}
		return $is_header;
	}

	public function socialv_header_background_style()
	{
		if (!$this->is_socialv_header()) return;
		$dynamic_css = '';
		// Default Header
		if ($this->socialv_option['socialv_header_background_type'] != 'default') {
			$type = $this->socialv_option['socialv_header_background_type'];
			if ($type == 'color') {
				if (!empty($this->socialv_option['socialv_header_background_color'])) {
					$dynamic_css .= 'header#default-header{
							background-color : ' . $this->socialv_option['socialv_header_background_color'] . '!important;
						}';
				}
			}
			if ($type == 'image') {
				if (!empty($this->socialv_option['socialv_header_background_image']['url'])) {
					$dynamic_css .= 'header#default-header{
							background-image : url(' . $this->socialv_option['socialv_header_background_image']['url'] . ') !important;
						}';
				}
			}
			if ($type == 'transparent') {
				$dynamic_css .= 'header#default-header{
						background : transparent !important;
					}';
			}
		}

		// Header sticky 
		if ($this->socialv_option['socialv_sticky_header_background_type'] != 'default') {
			$type = $this->socialv_option['socialv_sticky_header_background_type'];
			if ($type == 'color') {
				if (!empty($this->socialv_option['socialv_sticky_header_background_color'])) {
					$dynamic_css .= 'header#default-header.header-sticky{
							background-color : ' . $this->socialv_option['socialv_sticky_header_background_color'] . '!important;
						}';
					
				}
			}
			if ($type == 'image') {
				if (!empty($this->socialv_option['socialv_sticky_header_background_image']['url'])) {
					$dynamic_css .= 'header#default-header.header-sticky{
							background-image : url(' . $this->socialv_option['socialv_sticky_header_background_image']['url'] . ') !important;
						}';
				
				}
			}
			if ($type == 'transparent') {
				$dynamic_css .= 'header#default-header.header-sticky{
						background : transparent !important;
					}';
				
			}
		}

		if (!empty($dynamic_css)) {
			wp_add_inline_style('socialv-global', $dynamic_css);
		}
	}
}
