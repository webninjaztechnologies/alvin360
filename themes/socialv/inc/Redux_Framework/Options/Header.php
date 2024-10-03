<?php

/**
 * SocialV\Utility\Redux_Framework\Options\General class
 *
 * @package socialv
 */

namespace SocialV\Utility\Redux_Framework\Options;

use Redux;
use SocialV\Utility\Redux_Framework\Component;

class Header extends Component
{

	public function __construct()
	{
		$this->set_widget_option();
	}

	protected function set_widget_option()
	{
		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Header', 'socialv'),
			'id' => 'header',
			'icon' => 'custom-header-main',
			'customizer_width' => '500px',			
			'has_group_title' => __("Page Settings", "socialv"),

		));

		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Header', 'socialv'),
			'id'    => 'header_variation',
			'icon' => 'custom-header-main',		
			'subsection' => true,		
			'desc' => esc_html__('This section contains options for header .', 'socialv'),
			'fields' => array(
				array(
					'id' => 'header_layout',
					'type' => 'image_select',
					'title' => esc_html__('Header Style', 'socialv'),
					'subtitle' => esc_html__('Select the design variation that you want to use for site menu.', 'socialv'),
					'options' => array(
						'1' => array(
							'alt' => 'Style1',
							'img' => get_template_directory_uri() . '/assets/images/redux/header-1.png',
						),
						'2' => array(
							'alt' => 'Style2',
							'img' => get_template_directory_uri() . '/assets/images/redux/header-2.png',
						),
					),
					'default' => '1'
				),
				array(
					'id' => 'is_header_spacing',
					'type' => 'button_set',
					'title' => esc_html__('Header Container', 'socialv'),
					'subtitle'  =>  esc_html__('Adjust header width of your site pages.', 'socialv'),
					'options' => array(
						'container' => esc_html__('Container', 'socialv'),
						'container-fluid' => esc_html__('Full', 'socialv'),
					),
					'default' => 'container-fluid'
				),
				// --------main header background options start----------//

				array(
					'id'	 	=> 'socialv_header_background_type',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Background', 'socialv'),
					'subtitle' 	=> esc_html__('Select the variation for header background', 'socialv'),
					'options' 	=> array(
						'default' 		=> esc_html__('Default', 'socialv'),
						'color' 		=> esc_html__('Color', 'socialv'),
						'image' 		=> esc_html__('Image', 'socialv'),
						'transparent' 	=> esc_html__('Transparent', 'socialv')
					),
					'default' 	=> esc_html__('default', 'socialv')
				),

				array(
					'id' 		=> 'socialv_header_background_color',
					'type' 		=> 'color',
					'title' 		=> esc_html__('Background Color', 'socialv'),
					'subtitle' 		=> esc_html__('Choose background Color', 'socialv'),
					'required' 	=> array('socialv_header_background_type', '=', 'color'),
					"class"	=> "socialv-sub-fields",
					'mode' 		=> 'background',
					'transparent' => false
				),

				array(
					'id' 		=> 'socialv_header_background_image',
					'type' 		=> 'media',
					'url' 		=> false,
					'title' 		=> esc_html__('Background image', 'socialv'),
					'subtitle' 		=> esc_html__('Upload background image', 'socialv'),
					'required' 	=> array('socialv_header_background_type', '=', 'image'),
					"class"	=> "socialv-sub-fields",
					'read-only' => false,
					'subtitle' 	=> esc_html__('Upload background image for header.', 'socialv'),
				),

				// --------main header Background options end----------//

				array(
					'id' 		=> 'header_menu_limit',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Show Menu Limit', 'socialv'),
					'subtitle'  => esc_html__('Enter a value for the header menu range', 'socialv'),
					'default' 	=> 6,
				),
		
				// -------- header Language switch option ----------//
				array(
					'id'      => 'header_language_switch',
					 'type'    => 'button_set', 
					 'title'   => esc_html__('Display Language Switch', 'socialv'),
					 'options' => array(
						'yes' => esc_html__('On', 'socialv'),
						'no'  => esc_html__('Off', 'socialv')
					 ),
					 'default' => esc_html__('yes', 'socialv')
				),

				// -------- header Search ----------//
				array(
					'id' 		=> 'header_display_search',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Search', 'socialv'),
					'subtitle'  => esc_html__('Turn on to display the Search in header.', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					'default'	=> esc_html__('yes', 'socialv'),
				),

				array(
					'id' 		=> 'header_search_text',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Enter Placeholder Text', 'socialv'),
					'required' 	=> array('header_display_search', '=', 'yes'),
					'validate' 	=> 'text',
					"class"	=> "socialv-sub-fields",
					'default' 	=> esc_html__('Search here', 'socialv'),
				),


				array(
					'id' 		=> 'header_search_limit',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Show List Limit', 'socialv'),
					'subtitle'  => esc_html__('Enter a value for the text range', 'socialv'),
					'required' 	=> array('header_display_search', '=', 'yes'),
					"class"	=> "socialv-sub-fields",
					'default' 	=> 5,
				),					


				// -------- header Friend Request ----------//

				array(
					'id' 		=> 'header_display_frndreq',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Friend Requests', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					'default'	=> esc_html__('yes', 'socialv'),
				),

				// -------- header Messages ----------//

				array(
					'id' 		=> 'header_display_messages',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Messages', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					'default'	=> esc_html__('yes', 'socialv'),
				),

				// -------- header Notification ----------//

				array(
					'id' 		=> 'header_display_notification',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Notifications', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					'default'	=> esc_html__('yes', 'socialv'),
				),

				array(
					'id' 		=> 'header_notification_limit',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Show List Limit', 'socialv'),
					'required' 	=> array('header_display_notification', '=', 'yes'),
					'subtitle'  => esc_html__('Enter a value for the text range', 'socialv'),
					"class"	=> "socialv-sub-fields",
					'default' 	=> 10,
				),


				// -------- header Cart ----------//

				array(
					'id'        => 'display_header_cart_button',
					'type'      => 'button_set',
					'title'     => esc_html__('Display Cart Icon', 'socialv'),
					'options'   => array(
						'yes' => esc_html__('Yes', 'socialv'),
						'no' => esc_html__('No', 'socialv')
					),
					'default'   => esc_html__('yes', 'socialv')
				),

				// -------- header User ----------//

				array(
					'id' => 'site_login_link_section',
					'type' => 'section',
					'title' => esc_html__('Login', 'socialv'),
					'indent' => true,
				),

				array(
					'id' 		=> 'header_display_login',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Login Button', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					"class"	=> "socialv-sub-fields",
					'default'	=> esc_html__('yes', 'socialv'),
				),

				array(
					'id'        => 'site_login_title',
					'type'      => 'text',
					'title'     => esc_html__('Button Text', 'socialv'),
					'subtitle'     => esc_html__('Enter Button Text', 'socialv'),
					'default'     => esc_html__('Login', 'socialv'),
					"class"	=> "socialv-sub-fields",
					'required' 	=> array('header_display_login', '=', 'yes'),
				),

				array(
                    'id'        => 'is_socialv_site_login_icon_desktop',
                    'type'      => 'checkbox',
                    'desc'     => esc_html__('Showing Login Icon in desktop view.', 'socialv'),
                ),


				array(
					'id'       => 'socialv_site_login_logo',
					'type'     => 'media',
					'url'      => false,
					'read-only' => false,
					'required' 	=> array('header_display_login', '=', 'yes'),
					'default'  => array('url' => get_template_directory_uri() . '/assets/images/redux/login-icon.svg'),
				),

				array(
					'id'	 	=> 'registration_process',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Registration Process', 'socialv'),
					'subtitle' 	=> wp_kses(__('<br />Select method for registrtion.<br />Auto => user can direct access site after regitser.<br />Manually => user need to wait till admin permission.<br />Verification Key => user get activation key on email to activate acount.', 'socialv'), array('br' => array())),
					'options' 	=> array(
						'default' 		=> esc_html__('Auto', 'socialv'),
						'mannuly' 		=> esc_html__('Manually', 'socialv'),
						'verification_key' 		=> esc_html__('Verification Key', 'socialv')
					),
					'default' 	=> 'default'
				),
				array(
					'id'       => 'resend_email_verify',					
					'title'    => esc_html('Re-send Verification Email', 'socialv'), 
					'subtitle' => esc_html('Option to re-send the verfication email if user has not received it','socialv'),
					'type'     => 'switch',
					'default'  => '0',
					'off'      => esc_html('Off','socialv'),
					'on'       => esc_html('On','socialv'),
					"class"	   => "socialv-sub-fields",
					'required' => array('registration_process', '=', 'verification_key'),
				),
				//text which will be displayed on resending verification page
				array(
					'id'        => 'resend_email_verify_desc',
					'type'      => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Resend Email Verification Text', 'socialv'),
					'subtitle'     => esc_html__('Enter Description Text for Resending Verification Email', 'socialv'),
					'default'     => esc_html__('Welcome to socialV, a platform to connect with the social world.', 'socialv'),
					'required' 	=> array('resend_email_verify', '=', '1'),
				),
				//select page for resending verification page
				array(
					'id'    => 'resend_email_verify_link',
					'type' => 'select',
					'multi' => false,
					'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Resending Verification Email Page', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-resend-verification-email] Shortcode on a page which you selected to redirect.', 'socialv'),
					'required' 	=> array('resend_email_verify', '=', '1'),
				),
				array(
					'id'       	=> 'manually_proccess',
					'type'     	=> 'button_set',
					'title'     => esc_html__('After Registration Option', 'socialv'),
					'options' 	=> array(
						'text' 		=> esc_html__('Text', 'socialv'),
						'page' 		=> esc_html__('Page', 'socialv'),
					),
					'default'   => 'text',
					'subtitle'    	=> esc_html__('Select option for after registration', 'socialv'),
					'required' 	=> array('registration_process', '=', 'mannuly'),
				),

				array(
					'id'        => 'manually_proccess_text',
					'type'      => 'text',
					'subtitle'     => esc_html__('Enter Description Text for after Registration display.', 'socialv'),
					'default'     => esc_html__('Please wait until your account has been verified by the admin.', 'socialv'),
					'desc' => esc_html__('This text user can see after register on registration page', 'socialv'),
					'required' 	=> array('manually_proccess', '=', 'text'),
				),

				array(
					'id'        => 'manually_proccess_page',
					'type'      => 'select',
                    'multi'     => false,
                    'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
					'subtitle'     => esc_html__('Select page to display after registration.', 'socialv'),
					'desc' => esc_html__('You can redirect user on specific page to display some text/content such as "thank you..."', 'socialv'),
					'required' 	=> array('manually_proccess', '=', 'page'),
				),

				array(
					'id'       	=> 'site_login',
					'type'     	=> 'switch',
					'on'		=> esc_html__('Popup', 'socialv'),
					'off'		=> esc_html__('New Page', 'socialv'),
					'default'   => '0',
					"class"	=> "socialv-sub-fields",
					'title'    	=> esc_html__('Display Login Form', 'socialv'),
					'required' 	=> array('header_display_login', '=', 'yes'),
				),
				array(
					'id'        => 'site_login_link',
					'type'     => 'select',
					'multi'    => false,
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Select Page For Login', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-login] Shortcode on a page which you selected', 'socialv'),
					'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
					'required' 	=> array('site_login', '=', '0'),
				),


				array(
					'id'        => 'site_login_shortcode',
					'type'     => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Login Form', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-login] Shortcode to display form', 'socialv'),
					'required' 	=> array('site_login', '=', '1'),
					'default' => '[iqonic-login]',
				),

				array(
					'id'        => 'site_login_desc',
					'type'      => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Login Description', 'socialv'),
					'subtitle'     => esc_html__('Enter Description Text for Login', 'socialv'),
					'default'     => esc_html__('Welcome to socialV, a platform to connect with the social world', 'socialv'),
					'required' 	=> array('header_display_login', '=', 'yes'),
				),

				array(
					'id'        => 'site_forgetpwd_link',
					'type'     => 'select',
					'multi'    => false,
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Select Page For Forget Password', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-lost-pass] Shortcode on a page which you selected', 'socialv'),
					'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
					'required' 	=> array('site_login', '=', '0'),
				),


				array(
					'id'        => 'site_forgetpwd_shortcode',
					'type'     => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Forget Password Form', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-lost-pass] Shortcode to display form', 'socialv'),
					'required' 	=> array('site_login', '=', '1'),
					'default' => '[iqonic-lost-pass]',
				),

				array(
					'id'        => 'site_forgetpwd_desc',
					'type'      => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Forget Password Text', 'socialv'),
					'subtitle'     => esc_html__('Enter Description Text for Forget Password', 'socialv'),
					'default'     => esc_html__('Welcome to socialV, a platform to connect with the social world', 'socialv'),
					'required' 	=> array('header_display_login', '=', 'yes'),
				),
				array(
					'id'        => 'site_resend_verification_email_desc',
					'type'      => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Resend Email Verification Text', 'socialv'),
					'subtitle'     => esc_html__('Enter Description Text for Resending Verification Email', 'socialv'),
					'default'     => esc_html__('Welcome to socialV, a platform to connect with the social world', 'socialv'),
					'required' 	=> array('header_display_login', '=', 'yes'),
				),

				array(
					'id'        => 'site_register_link',
					'type'     => 'select',
					'multi'    => false,
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Select Page For Register', 'socialv'),
					'subtitle'      =>  esc_html__('Use [iqonic-register] Shortcode on a page which you selected', 'socialv'),
					'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
					'required' 	=> array('header_display_login', '=', 'yes'),
				),

				array(
					'id'        => 'site_register_desc',
					'type'      => 'text',
					"class"	=> "socialv-sub-fields",
					'title'     => esc_html__('Register Text', 'socialv'),
					'subtitle'     => esc_html__('Enter Description Text for Register', 'socialv'),
					'default'     => esc_html__('Welcome to socialV, a platform to connect with the social world', 'socialv'),
					'required' 	=> array('header_display_login', '=', 'yes'),
				),

			)
		));
		
		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Sticky Header', 'socialv'),
			'id' => 'sticky_header',
			'icon' => 'custom-header-main',
			'subsection' => true,
			'desc' => esc_html__('This section contains options for sticky header background.', 'socialv'),
			'fields' => array(

				array(
					'id'	 	=> 'socialv_sticky_header_background_type',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Background', 'socialv'),
					'subtitle' 	=> esc_html__('Select the variation for header background', 'socialv'),
					'options' 	=> array(
						'default' 		=> esc_html__('Default', 'socialv'),
						'color' 		=> esc_html__('Color', 'socialv'),
						'image' 		=> esc_html__('Image', 'socialv'),
						'transparent' 	=> esc_html__('Transparent', 'socialv')
					),
					'default' 	=> esc_html__('default', 'socialv')
				),

				array(
					'id' 		=> 'socialv_sticky_header_background_color',
					'type' 		=> 'color',
					'title' 		=> esc_html__('Background Color', 'socialv'),
					'subtitle' 		=> esc_html__('Choose background Color', 'socialv'),
					'required' 	=> array('socialv_sticky_header_background_type', '=', 'color'),
					"class"	=> "socialv-sub-fields",
					'mode' 		=> 'background',
					'transparent' => false
				),

				array(
					'id' 		=> 'socialv_sticky_header_background_image',
					'type' 		=> 'media',
					'url' 		=> false,
					'title' 		=> esc_html__('Background image', 'socialv'),
					'subtitle' 		=> esc_html__('Upload background image', 'socialv'),
					'required' 	=> array('socialv_sticky_header_background_type', '=', 'image'),
					"class"	=> "socialv-sub-fields",
					'read-only' => false,
					'subtitle' 	=> esc_html__('Upload background image for header.', 'socialv'),
				),
			)
		));
		Redux::set_section($this->opt_name, array(
			'title' => esc_html__('Search Bar', 'socialv'),
			'id' => 'search_bar_opt',
			'icon' => 'custom-header-main',
			'subsection' => true,
			'desc' => esc_html__('This section contains options for search bar.', 'socialv'),
			'fields' => array(
				array(
					'id' 		=> 'header_display_search',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Display Search', 'socialv'),
					'subtitle'  => esc_html__('Turn on to display the Search in header.', 'socialv'),
					'options' 	=> array(
						'yes' 		=> esc_html__('On', 'socialv'),
						'no' 		=> esc_html__('Off', 'socialv')
					),
					'default'	=> esc_html__('yes', 'socialv'),
				),

				array(
					'id' 		=> 'header_search_text',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Enter Placeholder Text', 'socialv'),
					'required' 	=> array('header_display_search', '=', 'yes'),
					'validate' 	=> 'text',
					"class"	=> "socialv-sub-fields",
					'default' 	=> esc_html__('Search here', 'socialv'),
				),
				array(
					'id' 		=> 'header_search_limit',
					'type' 		=> 'text',
					'title' 		=> esc_html__('Show List Limit', 'socialv'),
					'subtitle'  => esc_html__('Enter a value for the text range', 'socialv'),
					'required' 	=> array('header_display_search', '=', 'yes'),
					"class"	=> "socialv-sub-fields",
					'default' 	=> 5,
				),
				array(
                    'id' => 'socialv_search_content_list',
                    'type' => 'checkbox',
                    'title' => esc_html__('Search List Options', 'socialv'),
                    'subtitle' => esc_html__('Select Specific Search Item', 'socialv'),
                    'label' => true,
					"class"	=> "socialv-sub-fields",
                    'options' => array(
                        'group' => esc_html__('Group', 'socialv'),
                        'member' => esc_html__('Member', 'socialv'),
                        'activity' => esc_html__('Activity', 'socialv'),
                        'post' => esc_html__('Post', 'socialv'),
                        'product' => esc_html__('Product', 'socialv'),
                        'course' => esc_html__('Course', 'socialv'),
                        'page' => esc_html__('Page', 'socialv'),
                        'forum' => esc_html__('Forum', 'socialv'),
                        'topic' => esc_html__('Topic', 'socialv'),
                        'reply' => esc_html__('Reply', 'socialv')
                    ),
                    'required' => array('header_display_search', '=', 'yes'),
                    'default' => array(
                        'group' => true,
                        'member' => true, 
                        'activity' => true, 
                        'post' => true,
						'product' => true,
                        'course' => true, 
                        'page' => true, 
                        'forum' => true,
                        'topic' => true, 
                        'reply' => true, 
                    ),
                ),		
			)
		));
	}
}
