<?php

/**
 * SocialV\Utility\Redux_Framework\Options\RestricatedMode class
 *
 * @package socialv
 */

namespace SocialV\Utility\Redux_Framework\Options;

use Redux;
use SocialV\Utility\Redux_Framework\Component;

class RestrictedMode extends Component
{

    public function __construct()
    {
        $this->set_widget_option();
    }

    protected function set_widget_option()
    {
        Redux::set_section($this->opt_name, array(
            'title'     => esc_html__('Restricted Mode', 'socialv'),
            'id'        => 'socialv_resticated_mode',
            'icon'      => 'custom-Restricated',
            'fields'    => array(

                array(
                    'id'        => 'display_resticated_page',
                    'type'      => 'button_set',
                    'title'     => esc_html__('Enable page restriction', 'socialv'),
                    'subtitle'  => esc_html__("Enable page restriction if you want to restrict your visitors to access specific pages.", "socialv"),
                    'options'   => array(
                        'yes'   => esc_html__('Yes', 'socialv'),
                        'no'    => esc_html__('No', 'socialv')
                    ),
                    'default'   => esc_html__('yes', 'socialv')
                ),

                array(
                    'id'        => 'default_page_link',
                    'type'      => 'select',
                    'multi'     => false,
                    'title'     => esc_html__('Select page for restriction', 'socialv'),
                    'subtitle'  => esc_html__("Select the specific page, that will appear if visitors try to access restricted pages.", "socialv"),
                    'desc'      =>  esc_html__('Visitors will redirect on this page if they try to access restricted pages.', 'socialv'),
                    'required'  => array('display_resticated_page', '=', 'yes'),
                    "class"        => "socialv-sub-fields",
                    'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
                ),

                array(
                    'id'        => 'nonrestricted_page',
                    'type'      => 'select',
                    'multi'     => true,
                    'title'     => esc_html__('Exclude pages from restriction', 'socialv'),
                    'subtitle'  => esc_html__("Select pages which you want to exclude from the restriction so that everyone can access them.", "socialv"),
                    'desc'      => esc_html__('Select pages that can accesible to all.', 'socialv'),
                    'required'  => array('display_resticated_page', '=', 'yes'),
                    'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
                    "class"        => "socialv-sub-fields",
                ),

                array(
                    'id'        => 'nonrestricted_url',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Exclude URL from restriction', 'socialv'),
                    'subtitle'  => esc_html__('Enter URLs that you want to exclude from the restriction, one URL per line.', 'socialv'),
                    'desc'      => esc_html__('These URLs will be accessible to everyone.', 'socialv'),
                    'required'  => array('display_resticated_page', '=', 'yes'),
                    'class'     => 'socialv-sub-fields',
                    'return value' => array(),
                ),

                array(
                    'id'        => 'nonrestricted_post_types',
                    'type'      => 'select',
                    'multi'     => true,
                    'title'     => esc_html__('Select post', 'socialv'),
                    'subtitle'  => esc_html__('Select the specific post type to exclude from the restriction.', 'socialv'),
                    'desc'      => esc_html__('These Post Types will be accessible to everyone.', 'socialv'),
                    'required'  => array('display_resticated_page', '=', 'yes'),
                    'class'     => 'socialv-sub-fields',
                    'data' => 'callback',
                    'args' => function_exists('iqonic_get_post_types') ? 'iqonic_get_post_types':[]
                ),  

                array(
                    'id'        => 'display_after_login_redirect',
                    'type'      => 'button_set',
                    'title'     => esc_html__('Redirect on referrer page after login?', 'socialv'),
                    'subtitle'  => esc_html__("Enable this option to redirect your users to the last visited page, after login.", "socialv"),
                    'options'   => array(
                        'true'  => esc_html__('Yes', 'socialv'),
                        'false' => esc_html__('No', 'socialv')
                    ),
                    'default'   => esc_html__('true', 'socialv')
                ),


                array(
                    'id'        => 'display_after_login_page',
                    'type'      => 'select',
                    'multi'     => false,
                    'title'     =>  esc_html__('Select page', 'socialv'),
                    'subtitle'  =>  esc_html__('Select the specific page to redirect your users after login.', 'socialv'),
                    'required'  => array('display_after_login_redirect', '=', 'false'),
                    'options'   => (function_exists('iqonic_get_posts')) ? iqonic_get_posts(array('post', 'page', 'lp_course', 'forum', 'topic'), false, true) : '',
                    "class"        => "socialv-sub-fields"
                ),

            )
        ));
    }
}
