<?php

namespace Iqonic\Acf;

class General
{
    public function __construct()
    {
        if (defined('IQONIC_EXTENSION_VERSION')) {
            $this->version = IQONIC_EXTENSION_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'iqonic-extension';

        $this->set_general_options();
        
    }

    public function set_general_options()
    {
        if (function_exists('acf_add_local_field_group')) :

            // Page Options
            acf_add_local_field_group(array(
                'key' => 'group_46Cg7N74r8t811VLFfR6',
                'title' => 'Page Options',
                'fields' => array(

                    //general
                    array(
                        'key' => 'field_general',
                        'label' => 'General',
                        'name' => 'general_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'is_page_spacing_field',
                        'label' => 'Page Spacing',
                        'name' => '_is_page_spacing',
                        'type' => 'button_group',
                        'instructions' => 'Adjust your site page top / bottom spacing.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'custom' => 'Custom',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'wrapper' => array(
                            'width' => '100%',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'page_spacing_field',
                        'label' => '',
                        'name' => '_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Top General',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),                        
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'bottom_page_spacing_field',
                        'label' => '',
                        'name' => '_bottom_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Bottom General',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),                        
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'tablet_page_spacing_field',
                        'label' => '',
                        'name' => '_tablet_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Top Tablet',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => '_bottom_tablet_page_spacing_field',
                        'label' => '',
                        'name' => '_bottom_tablet_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Bottom Tablet',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'mobile_page_spacing_field',
                        'label' => '',
                        'name' => '_mobile_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Top Mobile',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => '_bottom_mobile_page_spacing_field',
                        'label' => '',
                        'name' => '_bottom_mobile_page_spacing',
                        'type' => 'text',
                        'instructions' => 'Bottom Mobile',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'is_page_spacing_field',
                                    'operator' => '!=',
                                    'value' => 'default',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15%',
                            'class' => '',
                            'id' => '',
                        ),
                        'prepend' => '',
                        'append' => '',
                        'placeholder' => 'ex. 25(px, em, %)',
                        'message' => '',
                        'default_value' => '',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    //Logo Option

                    array(
                        'key' => 'field_logo_settings',
                        'label' => 'Logo Settings',
                        'name' => 'header',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),

                    array(
                        'key' => 'acf_key_logo_switch',
                        'label' => 'Display Logo',
                        'name' => 'display_logo',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'yes',
                            'no' => 'no',
                        ),
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),


                    array(
                        'key' => 'acf_key_logo_switch_option',
                        'label' => 'Upload Logo',
                        'name' => 'display_logo_options',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'light' => 'Light',
                            'dark' => 'Dark',
                        ),
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'field_logo',
                        'label' => 'Logo',
                        'name' => 'header_logo',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_logo_switch_option',
                                    'operator' => '==',
                                    'value' => 'light',
                                ),
                            ),
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),



                    array(
                        'key' => 'field_dark_logo',
                        'label' => 'Dark Logo',
                        'name' => 'header_dark_logo',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_logo_switch_option',
                                    'operator' => '==',
                                    'value' => 'dark',
                                ),
                            ),
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),


                    array(
                        'key' => 'field_verticle_header_text',
                        'label' => 'Logo Text',
                        'name' => 'verticle_header_text',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_logo_switch',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'prepend' => '',
                        'append' => '',
                        'default_value' => esc_html__('SocialV', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ),
                    array(
                        'key' => 'field_verticle_header_color',
                        'label' => 'Choose Text Color',
                        'name' => 'verticle_header_color',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_logo_switch',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '33%',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'enable_opacity' => 0,
                    ),




                    // Header Option
                    array(
                        'key' => 'field_TfCbZ17c4cciu',
                        'label' => 'Header Settings',
                        'name' => 'header',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),

                    array(
                        'key' => 'acf_key_header_switch',
                        'label' => 'Display Header',
                        'name' => 'display_header',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'yes',
                            'no' => 'no',
                        ),
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),


                    array(
                        'key' => 'key_header',
                        'label' => 'Header Layout',
                        'name' => 'header_layout_name',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_header_switch',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'choices' => array(
                            'default' => 'Default',
                            '1' => 'Style 1',
                            '2' => 'Style 2',
                        ),
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),


                    // Banner Settings
                    array(
                        'key' => 'field_7a2p3jBTfCbZ17c4cciu',
                        'label' => 'Banner Settings',
                        'name' => 'banner',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),

                    array(
                        'key' => 'field_display_banner',
                        'label' => 'Display Banner',
                        'name' => 'display_banner',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'field_WGCt5cd3bf759qMh8gRk',
                        'label' => 'Display Title',
                        'name' => 'display_breadcrumb_title',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_display_banner',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ),
                            ),
                        ),
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'field_3PnJp21d93eM5Nrs8422',
                        'label' => 'Display Breadcrumbs',
                        'name' => 'display_breadcumb_nav',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_display_banner',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),


                    // Footer Options
                    array(
                        'key' => 'field_1gY7e',
                        'label' => 'Footer Settings',
                        'name' => 'footer',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'left',
                        'endpoint' => 0,
                    ),

                    array(
                        'key' => 'acf_key_footer_switch',
                        'label' => 'Display Footer',
                        'name' => 'display_footer',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            'yes' => 'yes',
                            'no' => 'no',
                        ),
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                            'id' => '',
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'acf_key_footer',
                        'label' => 'Customize Footer',
                        'name' => 'acf_footer_options',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'choices' => array(
                            'default' => 'Default',
                            '1' => 'One Column',
                            '2' => 'Two Column',
                            '3' => 'Three Column',
                            '4' => 'Four Column'
                        ),
                        'wrapper' => array(
                            'width' => '55%',
                            'class' => '',
                            'id' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_footer_switch',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ),
                            ),
                        ),
                        'allow_null' => 0, 
                        'layout' => 'horizontal', 
                        'message' => '',
                        'default_value' => 'default',
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),

                    array(
                        'key' => 'field_footer_bg_color',
                        'label' => 'Background color',
                        'name' => 'footer_background_color',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'acf_key_footer_switch',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '20%',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'enable_opacity' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
                        ),
                    ),
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'buddypress',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));

        endif;
    }
}
