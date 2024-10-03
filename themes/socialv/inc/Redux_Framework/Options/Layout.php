<?php

/**
 * SocialV\Utility\Redux_Framework\Options\Layout class
 *
 * @package socialv
 */

namespace SocialV\Utility\Redux_Framework\Options;

use Redux;
use SocialV\Utility\Redux_Framework\Component;

class Layout extends Component
{

	public function __construct()
	{
		$this->set_widget_option();
	}

	protected function set_widget_option()
	{
		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Customizer Settings', 'socialv'),
			'id'    => 'layout',
			'icon'  => 'custom-customizer',
			'desc'  => esc_html__('Change the default layout mode , colors of your site.', 'socialv'),
			'fields' => array(
				array(
					'id'       => 'socialv_enable_switcher',
					'type'     => 'button_set',
					'title'     => esc_html__('Show Switcher', 'socialv'),
					'subtitle'     => esc_html__('The style switcher is only for preview on front-end', 'socialv'),
					'options' => array(
						'yes' =>  esc_html__('Yes', 'socialv'),
						'no' =>  esc_html__('No', 'socialv'),
					),
					'default' => 'yes'
				),

				array(
					'id'        => 'socialv_layout_mode_options',
					'type'     => 'button_set',
					'title'     => esc_html__('Select Layout Mode', 'socialv'),
					'subtitle'      => esc_html__('Select a Mode to quickly apply pre-defined all pages', 'socialv'),
					'options'   => array(
						'dark' => esc_html__('Dark', 'socialv'),
						'light' => esc_html__('Light', 'socialv')
					),
					'default'   => 'light'
				),
				array(
					'id'       => 'socialv_frontside_switcher',
					'type'     => 'button_set',
					'title'     => esc_html__('Show Front Customizer Switcher', 'socialv'),
					'subtitle'     => esc_html__('The style front customizer switcher is only for preview on front-end', 'socialv'),
					'options' => array(
						'yes' =>  esc_html__('Yes', 'socialv'),
						'no' =>  esc_html__('No', 'socialv'),
					),
					'default' => 'yes'
				),
				array(
					'id'        => 'is_admin_switcher',
					'type'      => 'checkbox',
					'title' => esc_html__('Admin Switcher', 'socialv'),
					'desc'      =>  esc_html__('Showing switcher option only for admin on frontend.', 'socialv'),
					'required'     => array('socialv_frontside_switcher', '=', 'no'),
					'default'   => '0',
					"class"	=> "socialv-sub-fields"
				),
				array(
					'id'        => 'customizer_non_selected_page',
					'type'     => 'select',
					'multi'    => true,
					'data'     => 'pages',
					'title'      =>  esc_html__('Select page', 'socialv'),
					'subtitle'      =>  esc_html__('Only Selected Paged for not showing front customizer option on a page', 'socialv'),
					'required'     => array('socialv_frontside_switcher', '=', 'yes'),
					"class"	=> "socialv-sub-fields"
				),
				array(
					'id' => 'is_language_direction',
					'type' => 'checkbox',
					'title' => esc_html__('Language Direction', 'socialv'),
					'desc' => esc_html__('Language switch direction in the Live Style Customizer panel.', 'socialv'),
					'required'     => array('socialv_frontside_switcher', '=', 'yes'),
					'default' => '1',
				),
			)
		));
	}
}
