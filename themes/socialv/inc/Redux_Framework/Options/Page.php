<?php

/**
 * SocialV\Utility\Redux_Framework\Options\Page class
 *
 * @package socialv
 */

namespace SocialV\Utility\Redux_Framework\Options;

use Redux;
use SocialV\Utility\Redux_Framework\Component;

class Page extends Component
{

	public function __construct()
	{
		$this->set_widget_option();
	}

	protected function set_widget_option()
	{
		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Search Page', 'socialv'),
			'desc' => esc_html__('This section contains options for search page', 'socialv'),
			'id'    => 'page',
			'icon'  => 'custom-Page-Setting',
			'fields' => array(

				array(
					'id'        => 'search_page',
					'type'      => 'image_select',
					'title'     => esc_html__('Search page Setting', 'socialv'),
					'subtitle'  => wp_kses(__('<br />Choose among these structures (Right Sidebar, Left Sidebar and No Sidebar) for your Search page.<br />To filling these column sections you should go to appearance > widget.<br />And put every widget that you want in these sections.', 'socialv'), array('br' => array())),
					'options'   => array(
						'1' => array(
							'title' => esc_html__('Full width', 'socialv'),
							'img' => get_template_directory_uri() . '/assets/images/redux/one-column-dark.png',
							'class' => 'one-column'
						),
						'4' => array(
							'title' => esc_html__('Right sidebar', 'socialv'),
							'img' => get_template_directory_uri() . '/assets/images/redux/right-sidebar-dark.png',
							'class' => 'right-sidebar'
						),
						'5' => array(
							'title' => esc_html__('Left sidebar', 'socialv'),
							'img' => get_template_directory_uri() . '/assets/images/redux/left-sidebar-dark.png',
							'class' => 'left-sidebar'
						),
					),
					'default'   => '1',
				),
				array(
					'id'        => 'display_search_pagination',
					'type'      => 'button_set',
					'title'     => esc_html__('Pagination', 'socialv'),
					'subtitle' => esc_html__('Turn on to display pagination on a search page.', 'socialv'),
					'options'   => array(
						'yes' => esc_html__('On', 'socialv'),
						'no' => esc_html__('Off', 'socialv')
					),
					'default'   => esc_html__('yes', 'socialv')
				),
				array(
					'id' 		=> 'searchpage_pagination_limit',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Show List Limit', 'socialv'),
					'subtitle'  => esc_html__('Enter a value for the pagination', 'socialv'),
					'required' 	=> array('display_search_pagination', '=', 'yes'),
					"class"	=> "socialv-sub-fields",
					'default' 	=> 5,
				),
			)
		));
	}
}
