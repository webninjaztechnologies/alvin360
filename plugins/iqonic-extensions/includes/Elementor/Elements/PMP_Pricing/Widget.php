<?php

namespace Iqonic\Elementor\Elements\PMP_Pricing;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Widget extends Widget_Base {

    public function get_name() {
        return 'iqonic_pmpro_pricing';
    }

    public function get_title() {
        return esc_html__('PMP Pricing Plan', IQONIC_EXTENSION_TEXT_DOMAIN);
    }

    public function get_categories() {
        return ['iqonic-extension'];
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    protected function register_controls() {
        $this->start_controls_section(
                'section_pmpro_plan',
                [
                    'label' => esc_html__('PMP Pricing Plan', IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'pmp_pricing_plan_id',
                [
                    'label' => esc_html__('Select PMP Pricing Plan To Display', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT,
                    'options' => (class_exists('PMPro_Membership_Level') ? socialv_pmpro_subscription_plan_list() : ''),
                ]
        );

        $this->add_control(
                'show_discount_code',
                [
                    'label' => esc_html__('Apply Discount Code Directly At Checkout', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'discount_code',
                [
                    'label' => esc_html__('Select Discount Code', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT,
                    'options' => (class_exists('PMPro_Membership_Level') ? socialv_pmpro_discount_code_list() : ''),
                    'condition' => [
                        'show_discount_code' => 'yes'
                    ]
                ]
        );

        $this->add_control(
                'show_sale_price',
                [
                    'label' => esc_html__('Show Sale Price', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'sale_price',
                [
                    'label' => esc_html__('Enter Sale Price', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        'show_sale_price' => 'yes'
                    ]
                ]
        );

        $this->add_control(
                'show_discount_banner',
                [
                    'label' => esc_html__('Show Discount Banner', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'discount_text',
                [
                    'label' => esc_html__('Enter Discount Text', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__("Save 20%", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'condition' => [
                        'show_discount_banner' => 'yes'
                    ]
                ]
        );

        $this->add_control(
                'show_description',
                [
                    'label' => esc_html__('Show Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'show_expiration',
                [
                    'label' => esc_html__('Show Expiration Information (if any)', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->add_control(
                'account_exists',
                [
                    'label' => esc_html__('Text to display when account already exists with the selected plan', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => esc_html__("My Account", IQONIC_EXTENSION_TEXT_DOMAIN),
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
                ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
                'tab_icon',
                [
                    'label' => esc_html__('Select Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'default' => [
                        'value' => 'fas fa-check'
                    ],
                ]
        );

        $repeater->add_control(
                'plan_description',
                [
                    'default' => esc_html__('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'placeholder' => esc_html__('Tab Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::TEXTAREA,
                    'show_label' => false,
                ]
        );

        $this->add_control(
                'tabs',
                [
                    'label' => esc_html__('Tabs Items', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'plan_description' => esc_html__('Lorem ipsum', IQONIC_EXTENSION_TEXT_DOMAIN),
                        ]
                    ],
                    'title_field' => '{{{ plan_description }}}',
                ]
        );

        $this->add_control(
                'show_custom_link',
                [
                    'label' => esc_html__('Use Custom Button ? ', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__("Yes", IQONIC_EXTENSION_TEXT_DOMAIN),
                    'label_off' => esc_html__("No", IQONIC_EXTENSION_TEXT_DOMAIN),
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_plan_body',
                [
                    'label' => esc_html__('Pricing plan Main Box', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'mainbox_background',
                    'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper',
                ]
        );

        $this->add_responsive_control(
                'mainbox_border_radius',
                [
                    'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'main_box_shadow',
                    'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper',
                ]
        );

        $this->add_responsive_control(
                'box_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'box_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_plan_header',
                [
                    'label' => esc_html__('Pricing Plan Header', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

          $this->add_control(
                'heading_box',
                [
                    'label' => esc_html__('BOX', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

                $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'headerbox_background',
                    'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header',
                ]
        );

        $this->add_responsive_control(
                'headerbox_border_radius',
                [
                    'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'header_box_shadow',
                    'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header',
                ]
        );

        $this->add_responsive_control(
                'header_box_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'header_box_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_control(
                'title_color',
                [
                    'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-name' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-name',
                ]
        );
        $this->add_responsive_control(
                'title_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'title_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'price_title',
                [
                    'label' => esc_html__('Price', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'price_color',
                [
                    'label' => esc_html__('Price', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper span.main-price' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'price_discount_color',
                [
                    'label' => esc_html__('Discount Price Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper span.plan-date-pack' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper span.main-price, .plan-meta-details span.sale-price',
                ]
        );

        $this->add_responsive_control(
                'price_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .plan-meta-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'price_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .plan-meta-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'expire_title',
                [
                    'label' => esc_html__('Expire Plan', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'expire_color',
                [
                    'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .plan-meta-details .plan_expiration' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'expire_typography',
                    'selector' => '{{WRAPPER}} .plan-meta-details .plan_expiration',
                ]
        );

        $this->add_responsive_control(
                'expire_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .plan-meta-details .plan_expiration' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'expire_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .plan-meta-details .plan_expiration' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'pmp_img_section',
                [
                    'label' => __('Image', 'iqonic'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
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
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg' => 'width: {{SIZE}}{{UNIT}};',
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
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'imagebox_background',
                    'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg',
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
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg',
                ]
        );
        $this->add_responsive_control(
                'image_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper img, {{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-header .plan-wrapper svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_plan_content',
                [
                    'label' => esc_html__('Pricing Plan Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

          $this->add_control(
                'content_box',
                [
                    'label' => esc_html__('BOX', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

                $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'contentbox_background',
                    'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .wrap-details-pricing',
                ]
        );

        $this->add_responsive_control(
                'contentbox_border_radius',
                [
                    'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wrap-details-pricing' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_box_shadow',
                    'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'selector' => '{{WRAPPER}} .wrap-details-pricing',
                ]
        );

        $this->add_responsive_control(
                'content_box_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wrap-details-pricing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'content_box_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wrap-details-pricing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
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
                'icon_color',
                [
                    'label' => esc_html__('Icon/Image Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-description ul li i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-description ul li svg' => 'color : {{VALUE}} ,fill: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'description_color',
                [
                    'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .plan-dec' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'selector' => '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .plan-dec',
                ]
        );

        $this->add_responsive_control(
                'desc_padding',
                [
                    'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-description ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'desc_margin',
                [
                    'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .socialv-pmp-pricing-plans-wrapper .pricing-plan-description ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
                'section_button',
                [
                    'label' => esc_html__('Button', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'condition' => [
                        'show_custom_link' => 'yes',
                    ]
                ]
        );

        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/button_controls.php';

        $this->end_controls_section();
    }

    protected function render() {
        require 'render.php';
    }
}
