<?php
/**
 * SocialV\Utility\Dynamic_Style\Styles\HeaderSideArea class
 *
 * @package socialv
 */

namespace SocialV\Utility\Dynamic_Style\Styles;

use SocialV\Utility\Dynamic_Style\Component;
use function add_action;

class HeaderSideArea extends Component
{
	public $socialv_option;
	public function __construct()
	{        
		$this->socialv_option = get_option('socialv-options');
		add_action('wp_enqueue_scripts', array($this, 'socialv_header_sidearea_dynamic_style'), 20);
	}

	public function socialv_header_sidearea_dynamic_style()
	{
		$dynamic_css = '';
		if (isset($this->socialv_option['sidearea_background_type']) && $this->socialv_option['sidearea_background_type'] != 'default') {
			$type = $this->socialv_option['sidearea_background_type'];
			if ($type == 'color') {
				if (!empty($this->socialv_option['sidearea_background_color'])) {
					$dynamic_css .= '.sidebar{
						background-color : ' . $this->socialv_option['sidearea_background_color'] . '!important;
					}';
				}
			}

			if ($type == 'image') {
				if (!empty($this->socialv_option['sidearea_background_image']['url'])) {
					$dynamic_css .= '.sidebar{
						background-image : url(' . $this->socialv_option['sidearea_background_image']['url'] . ') !important;
					}';
				}
			}

			if ($type == 'transparent') {
				$dynamic_css .= '.sidebar{
					background : transparent !important;
				}';
			}
		}

		if (isset($this->socialv_option['sidearea_width']['width']) && !empty($this->socialv_option['sidearea_width']['width'])) {
			$dynamic_css .= '.sidebar{
				width : ' . $this->socialv_option['sidearea_width']['width'] . '!important;
			}';
		}

		if (!empty($dynamic_css)) {
			wp_add_inline_style('socialv-global', $dynamic_css);
		}
	}
}
