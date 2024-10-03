<?php

namespace Iqonic\Elementor\Elements\IconBox;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_iconbox';
    }

    public function get_title()
    {
        return esc_html__('Iqonic IconBox', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-icon-box';
    }

    protected function register_controls()
    {

        /* Start design_style */
        $this->start_controls_section(
            'section_design_style',
            [
                'label' => __('Icon Box Style', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'design_style',
            [
                'label'      => __('Icon Box Styles', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'icon-box-1',
                'options'    => [
                    'icon-box-1' => __('Style 1', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'icon-box-2' => __('Style 2', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'icon-box-3' => __('Style 3', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
            ]
        );

        $this->end_controls_section();
        /* End design_style */

        $this->start_controls_section(
            'section_iconbox',
            [
                'label' => esc_html__('IconBox', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Design', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'media_style',
            [
                'label'      => esc_html__('Icon / Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'image',
                'options'    => [
                    'icon'          => esc_html__('Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'image'          => esc_html__('Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => [
                    'media_style' => 'icon',
                ],
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'media_style' => 'image',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'      => esc_html__('Title Tag', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'h5',
                'options'    => [
                    'h1'          => esc_html__('h1', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'h2'          => esc_html__('h2', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'h3'          => esc_html__('h3', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'h4'          => esc_html__('h4', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'h5'          => esc_html__('h5', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'h6'          => esc_html__('h6', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Vitae, amet gravida lacinia purus lectus. Enim elit commodo magna leo vel lacus. ', IQONIC_EXTENSION_TEXT_DOMAIN),
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('Enter Description', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );


        $this->add_control(
            'icon_button_action',
            [
                'label' => esc_html__('Use Link', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => esc_html__('Yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('No', IQONIC_EXTENSION_TEXT_DOMAIN),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'icon_link_type',
            [
                'label' => esc_html__('Link Type', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'custom' => esc_html__('Custom', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'condition' => ['icon_button_action' => 'yes']
            ]
        );

        $this->add_control(
            'icon_dynamic_link',
            [
                'label' => esc_html__('Select Page', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'return_value' => 'true',
                'multiple' => true,
                'condition' => [
                    'icon_link_type' => 'dynamic',
                    'icon_button_action' => 'yes',
                ],
                'options' => isset($_REQUEST['editor_post_id']) ? iqonic_get_posts("page") : [],
            ]
        );
        $this->add_control(
            'icon_use_new_window',
            [
                'label' => esc_html__('Open in new window', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => esc_html__('Yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('No', IQONIC_EXTENSION_TEXT_DOMAIN),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'icon_button_action' => 'yes',
                    'icon_link_type' => 'dynamic',
                ],
            ]
        );
        $this->add_control(
            'icon_link',
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
                    'icon_button_action' => 'yes',
                    'icon_link_type' => 'custom',
                ]
            ]
        );
        $this->add_control(
            'show_button',
            [
                'label' => esc_html__('Show Button', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_off' => esc_html__('Hide', IQONIC_EXTENSION_TEXT_DOMAIN),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_iconbox_box',
            [
                'label' => esc_html__('Icon Box', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('style_iconbox_box_tabs');

        $this->start_controls_tab(
            'style_iconbox_box_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'iconbox_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-icon-box',
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image i' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .socialv-icon-image svg, {{WRAPPER}} .socialv-icon-image svg path' => 'fill:{{VALUE}};'
                ],
                'condition' => [
                    'media_style' => 'icon',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_iconbox_box_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'iconbox_hover_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-icon-box:hover',
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__('Icon Hover Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-box:hover .socialv-icon-image i' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .socialv-icon-box:hover .socialv-icon-image svg, {{WRAPPER}} .socialv-icon-box:hover .socialv-icon-image svg path' => 'fill:{{VALUE}};'
                ],
                'condition' => [
                    'media_style' => 'icon',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'iconbox_shadow',
                'label' => __('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-icon-box',
            ]
        );

        $this->add_responsive_control(
            'align',
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-box-1' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'iconbox_box_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'iconbox_box_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'mainbox_height',
            [
                'label' => esc_html__('Height', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-box' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'mainbox_hover',
            [
                'label' => esc_html__('Hover Animation', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_off' => esc_html__('Hide', IQONIC_EXTENSION_TEXT_DOMAIN),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'use_dot_style',
            [
                'label' => esc_html__('Use Dot', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'block' => esc_html__('Yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'none' => esc_html__('No', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'condition' => ['design_style' => 'icon-box-3'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-box.socialv-icon-box-3::before' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__('Dot Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'condition' => ['use_dot_style' => 'block', 'design_style' => 'icon-box-3'],
                'selectors' => ['{{WRAPPER}} .socialv-icon-box.socialv-icon-box-3::before' => 'color:{{VALUE}};'],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_iconbox_title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-heading-title',
            ]
        );

        $this->start_controls_tabs(
            'style_iconbox_tabs'
        );

        $this->start_controls_tab(
            'style_iconbox_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-heading-title' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_iconbox_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Title Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-heading-title:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'iconbox_box_title_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box .socialv-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'iconbox_box_title_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box .socialv-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_iconbox_description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-description',
            ]
        );

        $this->start_controls_tabs(
            'style_description_tabs'
        );

        $this->start_controls_tab(
            'style_description_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_normal_color',
            [
                'label' => esc_html__('Description Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-description' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_description_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_hover_color',
            [
                'label' => esc_html__('Description Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-description:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_box_iicon',
            [
                'label' => esc_html__('Image / Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'style_icon_box_icon_tabs'
        );

        $this->start_controls_tab(
            'style_icon_box-icon_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'iconbox_icon_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-icon-box .socialv-icon-image',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_icon_box-icon_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'iconbox_icon_hover_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-icon-box:hover .socialv-icon-image',
            ]
        );;

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'icon_box_container',
            [
                'label' => esc_html__('Icon Background Size', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_background_width',
            [
                'label' => esc_html__('Icon background size', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-box .socialv-icon-image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .socialv-icon-image' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'icon_box_icon_size',
            [
                'label' => esc_html__('Icon Size', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => esc_html__('Width', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .socialv-icon-image i, {{WRAPPER}} .socialv-icon-image svg' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__('Height', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .socialv-icon-image i,{{WRAPPER}} .socialv-icon-image svg' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );



        $this->add_control(
            'iconbox_has_custom_border',
            [
                'label' => esc_html__('Use Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'iconbox_data_border',
            [
                'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img,{{WRAPPER}} .socialv-icon-image i, {{WRAPPER}} .socialv-icon-image svg' => 'border-color: {{VALUE}};',
                ],
                'condition' => ['iconbox_has_custom_border' => 'yes'],
            ]
        );

        $this->add_control(
            'iconbox_border_style',
            [
                'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'solid' => esc_html__('Solid', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'dashed' => esc_html__('Dashed', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'dotted' => esc_html__('Dotted', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'double' => esc_html__('Double', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'outset' => esc_html__('outset', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'groove' => esc_html__('groove', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'ridge' => esc_html__('ridge', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'inset' => esc_html__('inset', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'hidden' => esc_html__('hidden', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'none' => esc_html__('none', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'condition' => ['iconbox_has_custom_border' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img,{{WRAPPER}} .socialv-icon-image i,{{WRAPPER}} .socialv-icon-image svg' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'iconbox_border_width',
            [
                'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img , {{WRAPPER}} .socialv-icon-image i,{{WRAPPER}} .socialv-icon-image svg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['iconbox_has_custom_border' => 'yes'],
            ]
        );

        $this->add_control(
            'iconbox_border_radius',
            [
                'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-icon-image img, {{WRAPPER}} .socialv-icon-image i, {{WRAPPER}} .socialv-icon-image svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['iconbox_has_custom_border' => 'yes'],
            ]
        );


        $this->add_responsive_control(
            'iconbox_box_icon_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box .socialv-icon-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'iconbox_box_icon_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-icon-box .socialv-icon-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__('Button', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'show_button' => 'yes',
                ]
            ]
        );

        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/button_controls.php';

        $this->end_controls_section();
    }

    protected function render()
    {
        require 'render.php';
    }
}

