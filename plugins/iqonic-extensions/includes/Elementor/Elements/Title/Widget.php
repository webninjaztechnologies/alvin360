<?php

namespace Iqonic\Elementor\Elements\Title;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_title';
    }

    public function get_title()
    {
        return esc_html__('Iqonic Section Title', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-site-title';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section',
            [
                'label' => esc_html__('Section Title', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => esc_html__('Section Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__('Section Title', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );


        $this->add_control(
            'sub_title',
            [
                'label' => esc_html__('Section Sub Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__('Section Sub Title', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'sub_title_position',
            [
                'label'      => esc_html__('Sub Title Position', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'before',
                'options'    => [
                    'before' => esc_html__('Before Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'after'  => esc_html__('After Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
            ]
        );

        $this->add_control(
            'has_description',
            [
                'label' => esc_html__('Has Description?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('Enter Title Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'default' => esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => ['has_description' => 'yes']
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'      => esc_html__('Title Tag', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'h2',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_action',
            [
                'label' => esc_html__('Use Link ?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN)
            ]
        );


        $this->add_control(
            'link_type',
            [
                'label' => esc_html__('Link Type', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__('Dynamic', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'custom' => esc_html__('Custom', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'condition' => ['title_action' => 'yes']
            ]
        );

        $this->add_control(
            'dynamic_link',
            [
                'label' => esc_html__('Select Page', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'return_value' => 'true',
                'multiple' => true,
                'condition' => [
                    'link_type' => 'dynamic',
                    'title_action' => 'yes',
                ],
                'options' => iqonic_get_posts("page"),
            ]
        );


        $this->add_control(
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
                    'title_action' => 'yes',
                    'link_type' => 'custom',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_box_stye',
            [
                'label' => esc_html__('Title Box', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('titlebox_tabs');
        $this->start_controls_tab(
            'tabs_title_box_normal',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'titlebox_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box ',
            ]
        );

        $this->add_control(
            'titlebox_has_border',
            [
                'label' => esc_html__('Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'titlebox_border_style',
            [
                'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'condition' => [
                    'titlebox_has_border' => 'yes',
                ],
                'options' => [
                    'solid'  => esc_html__('Solid', IQONIC_EXTENSION_TEXT_DOMAIN),
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

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box ' => 'border-style: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'titlebox_border_color',
            [
                'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_has_border' => 'yes',
                ],
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'titlebox_border_width',
            [
                'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_has_border' => 'yes',
                ],
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'titlebox_border_radius',
            [
                'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_has_border' => 'yes',
                ],
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_title_box_hover',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'titlebox_hover_background',
                'label' => esc_html__('Hover Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box:hover ',
            ]
        );


        $this->add_control(
            'titlebox_hover_has_border',
            [
                'label' => esc_html__('Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );
        $this->add_control(
            'titlebox_hover_border_style',
            [
                'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_hover_has_border' => 'yes',
                ],
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'solid'  => esc_html__('Solid', IQONIC_EXTENSION_TEXT_DOMAIN),
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

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box:hover' => 'border-style: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'titlebox_hover_border_color',
            [
                'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_hover_has_border' => 'yes',
                ],
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box:hover' => 'border-color: {{VALUE}};',
                ],


            ]
        );

        $this->add_control(
            'titlebox_hover_border_width',
            [
                'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_hover_has_border' => 'yes',
                ],
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'titlebox_hover_border_radius',
            [
                'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                'condition' => [
                    'titlebox_hover_has_border' => 'yes',
                ],
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();



        $this->add_responsive_control(
            'titlebox_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-title-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'titlebox_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}  .socialv-title-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'mobile_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-title-box  .socialv-heading-title,{{WRAPPER}} .socialv-title-box  .socialv-heading-title .left-text',
            ]
        );

        $this->start_controls_tabs('title_tabs');

        $this->start_controls_tab(
            'title_color_tab_normal',
            [
                'label' => esc_html__('normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-heading-title' => 'color: {{VALUE}};',
                ],

            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_back_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box  .socialv-heading-title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_tab_hover',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-heading-title:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_hover_back_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box:hover  .socialv-heading-title',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => esc_html__('Sub Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('sub_title_tabs');

        $this->start_controls_tab(
            'sub_title_color_tab_normal',
            [
                'label' => esc_html__('normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'sub_title_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box .socialv-subtitle' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sub_title_back_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box .socialv-subtitle',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'sub_title_color_tab_hover',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'sub_title_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box .socialv-subtitle:hover' => 'color: {{VALUE}};',
                ],

            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sub_title_hover_back_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-title-box:hover .socialv-subtitle',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-title-box .socialv-subtitle',
            ]
        );


        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box .socialv-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_title_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box .socialv-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();
        // Sub Title Style Section End

        // Description Style Section
        $this->start_controls_section(
            'section_description_style',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['has_description' => 'yes']
            ]
        );

        $this->add_control(
            'description_heading_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('description_tabs');

        $this->start_controls_tab(
            'description_color_tab_normal',
            [
                'label' => esc_html__('normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-title-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'description_color_tab_hover',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-title-desc:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-title-box  .socialv-title-desc',
            ]
        );

        $this->add_responsive_control(
            'desciption_marging',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-title-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'desciption_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-title-box  .socialv-title-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_section();
        // Descrition Style Section End
    }

    protected function render()
    {
        require 'render.php';
    }
}
