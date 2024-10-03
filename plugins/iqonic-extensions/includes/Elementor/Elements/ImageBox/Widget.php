<?php

namespace Iqonic\Elementor\Elements\ImageBox;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_imageBox';
    }

    public function get_title()
    {
        return __('Iqonic Image Box', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-image-box';
    }
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image Box', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );
        $this->add_control(
			'select_style',
			[
				'label'      => __('Styles', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'style1',
				'options'    => [
					'style1' => __('Style 1', IQONIC_EXTENSION_TEXT_DOMAIN),
					'style2' => __('Style 2', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
			]
		);	

        $this->add_control(
            'center_image',
            [
                'label' => __('Choose Center Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => ['select_style' => 'style2']
            ]
        );
        //repeater for static slider
        $repeater = new Repeater();
        $repeater->add_control(
            'image',
            [
                'label' => __('Choose  Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $repeater->add_control(
            'button_action',
            [
                'label' => esc_html__('Action', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'popup' => esc_html__('Open Popup', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'link'  => esc_html__('Open Link', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'none'  => esc_html__('none', IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
            ]
        );
        
        $repeater->add_control(
            'link_type',
            [
                'label' => esc_html__('Link Type', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'custom' => esc_html__('Custom', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'condition' => ['button_action' => 'link']
            ]
        );
        
        $repeater->add_control(
            'dynamic_link',
            [
                'label' => esc_html__('Select Page', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'return_value' => 'true',
                'multiple' => true,
                'condition' => [
                    'link_type' => 'dynamic',
                    'button_action' => 'link',
                ],
                'options' => iqonic_get_posts("page"),
            ]
        );
        $repeater->add_control(
            'use_new_window',
            [
                'label' => esc_html__( 'Open in new window', IQONIC_EXTENSION_TEXT_DOMAIN ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => esc_html__( 'Yes', IQONIC_EXTENSION_TEXT_DOMAIN ),
                'no' => esc_html__( 'No', IQONIC_EXTENSION_TEXT_DOMAIN ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'link_type' => 'dynamic',
                ],
            ]
        );
        
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', IQONIC_EXTENSION_TEXT_DOMAIN),
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'button_action' => 'link',
                    'link_type' => 'custom',
                ]
            ]
        );
        $repeater->add_control(
            'title_text',
            [
                'label' => esc_html__('Title & Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('This is the heading', IQONIC_EXTENSION_TEXT_DOMAIN),
                'placeholder' => esc_html__('Enter your title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'description_text',
            [
                'label' => esc_html__('Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('It is a long established fact that a reader will be distracted by the readable content.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'placeholder' => esc_html__('Enter your description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'img_list',
            [
                'label' => esc_html__('ImageBox List', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title_text' => esc_html__('Connect with the world', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description_text' => esc_html__('It is a long established fact that a reader will be distracted by the readable content.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                    [
                        'title_text' => esc_html__('Connect with the world', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description_text' => esc_html__('It is a long established fact that a reader will be distracted by the readable content.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                    [
                        'title_text' => esc_html__('Connect with the world', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description_text' => esc_html__('It is a long established fact that a reader will be distracted by the readable content.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                ],
                'title_field' => '{{{ title_text }}}',
            ]
        );
        $this->add_control(
            'title_size',
            [
                'label' => esc_html__('Title HTML Tag', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Width', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size_height',
            [
                'label' => esc_html__('Height', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'imagebox_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .image-box',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data img, {{WRAPPER}} .image-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-image-box-data img',
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_responsive_control(
            'title_bottom_space',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .socialv-image-box-data .title',
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .socialv-image-box-data .desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .socialv-image-box-data .desc',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_slider_control',
            [
                'label'     => esc_html__('Slider Controls', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/swiper_control.php';

        $this->end_controls_section();

    }

    protected function render()
    {
        require 'render.php';
        if (Plugin::$instance->editor->is_edit_mode()) { ?>
            <script>
                (function($) {
                    "use strict";
                    $(window).ready(function() {
                        Widget_swiperSlider();
                    });
                })(jQuery);
            </script>
<?php
        }
    }
}
