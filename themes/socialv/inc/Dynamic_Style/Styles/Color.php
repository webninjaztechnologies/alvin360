<?php

/**
 * SocialV\Utility\Dynamic_Style\Styles\Banner class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class Color extends Component
{
	public $socialv_option;
	public function __construct()
	{
		$this->socialv_option = get_option('socialv-options');
		add_action('wp_enqueue_scripts', array($this, 'socialv_color_options'), 20);
	}

	public function socialv_color_options()
	{
		$color_var = "";
		if (class_exists('Redux') && $this->socialv_option['custom_color_switch'] == 'yes') {
			// Light Mode
			// Button Color
			$color_attrs = ':root { ';
			$color_vars = array(
				'success',
				'danger',
				'warning',
				'info',
				'orange',
			);

			foreach ($color_vars as $color_var_name) {
				if (isset($this->socialv_option[$color_var_name . '_color']) && !empty($this->socialv_option[$color_var_name . '_color'])) {
					$color = $this->socialv_option[$color_var_name . '_color'];
					$color_var .= "--color-theme-$color_var_name: $color !important;";
					$color_var .= "--color-theme-$color_var_name-dark: {$color}ff !important;";
					$color_var .= "--color-theme-$color_var_name-light: {$color}16 !important;";
				}
			}

			if (!empty($color_var)) {
				$color_attrs .= $color_var;
			}
			$color_attrs .= '}';
			if (!empty($color_attrs)) {
				wp_add_inline_style('socialv-global', $color_attrs);
			}

			// Light mode
			$light_attrs = '[data-mode=light] { ';

			if (isset($this->socialv_option['text_color']) && !empty($this->socialv_option['text_color'])) {
				$color = $this->socialv_option['text_color'];
				$color_var .= '--global-font-color: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['title_color']) && !empty($this->socialv_option['title_color'])) {
				$color = $this->socialv_option['title_color'];
				$color_var .= ' --global-font-title: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['parent_bg_color']) && !empty($this->socialv_option['parent_bg_color'])) {
				$color = $this->socialv_option['parent_bg_color'];
				$color_var .= '--color-theme-white-box: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['child_bg_color']) && !empty($this->socialv_option['child_bg_color'])) {
				$color = $this->socialv_option['child_bg_color'];
				$color_var .= '--global-body-bgcolor: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['light_comment_color']) && !empty($this->socialv_option['light_comment_color'])) {
				$color = $this->socialv_option['light_comment_color'];
				$color_var .= '--comment-font-color: ' . $color . ' !important;';
			}

			if (!empty($color_var)) {
				$light_attrs .= $color_var;
			}
			$light_attrs .= '}';
			if (!empty($light_attrs)) {
				wp_add_inline_style('socialv-global', $light_attrs);
			}


			// Dark Mode 
			$dark_attrs = '[data-mode=dark] { ';
			if (isset($this->socialv_option['dark_text_color']) && !empty($this->socialv_option['dark_text_color'])) {
				$color = $this->socialv_option['dark_text_color'];
				$color_var .= '--global-font-color: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['dark_title_color']) && !empty($this->socialv_option['dark_title_color'])) {
				$color = $this->socialv_option['dark_title_color'];
				$color_var .= ' --global-font-title: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['dark_parent_bg_color']) && !empty($this->socialv_option['dark_parent_bg_color'])) {
				$color = $this->socialv_option['dark_parent_bg_color'];
				$color_var .= '--color-theme-white-box: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['dark_child_bg_color']) && !empty($this->socialv_option['dark_child_bg_color'])) {
				$color = $this->socialv_option['dark_child_bg_color'];
				$color_var .= '--global-body-bgcolor: ' . $color . ' !important;';
			}
			if (isset($this->socialv_option['dark_comment_color']) && !empty($this->socialv_option['dark_comment_color'])) {
				$color = $this->socialv_option['dark_comment_color'];
				$color_var .= '--comment-font-color: ' . $color . ' !important;';
			}
			if (!empty($color_var)) {
				$dark_attrs .= $color_var;
			}
			$dark_attrs .= '}';
			if (!empty($dark_attrs)) {
				wp_add_inline_style('socialv-global', $dark_attrs);
			}
		}
	}
}
