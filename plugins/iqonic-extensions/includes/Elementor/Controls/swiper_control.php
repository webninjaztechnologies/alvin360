<?php

namespace Elementor;

$this->add_control(
    'sw_loop',
    [
        'label' => esc_html__('Loop', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'true',
        'options' => [
            'true'  => esc_html__('True', IQONIC_EXTENSION_TEXT_DOMAIN),
            'false' => esc_html__('False', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'want_pagination',
    [
        'label' => esc_html__('Show Pagination ?', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'false',
        'options' => [
            'true'  => esc_html__('True', IQONIC_EXTENSION_TEXT_DOMAIN),
            'false' => esc_html__('False', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'want_nav',
    [
        'label' => esc_html__('Show Navigation ?', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'false',
        'options' => [
            'true'  => esc_html__('True', IQONIC_EXTENSION_TEXT_DOMAIN),
            'false' => esc_html__('False', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'sw_slide',
    [
        'label' => esc_html__('Slide Per Page', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 10,
        'step' => 1,
        'default' => 4,
    ]
);



$this->add_control(
    'sw_laptop_no',
    [
        'label' => esc_html__('Laptop View', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 0,
        'max' => 10,
        'step' => 1,
        'default' => 3,
    ]
);

$this->add_control(
    'sw_tab_no',
    [
        'label' => esc_html__('Tablet View', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 0,
        'max' => 10,
        'step' => 1,
        'default' => 2,
    ]
);

$this->add_control(
    'sw_mob_no',
    [
        'label' => esc_html__('Mobile View', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 0,
        'max' => 10,
        'step' => 1,
        'default' => 1,
    ]
);


$this->add_control(
    'sw_autoplay',
    [
        'label' => esc_html__('Auto Play Delay', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 10000,
        'step' => 5,
        'default' => 4000,
    ]
);

$this->add_control(
    'sw_speed',
    [
        'label' => esc_html__('Speed', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 0,
        'max' => 10000,
        'step' => 1,
        'default' => 1000,
    ]
);

$this->add_control(
    'sw_space_slide',
    [
        'label' => esc_html__('Space Between Slide', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::NUMBER,
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'default' => 40,
    ]
);

$this->start_controls_tabs(
    'style_swiper_tabs'
);

$this->start_controls_tab(
    'style_swiper_normal_tab',
    [
        'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
        'condition' => [
            'want_nav' => 'true',
        ],
    ]
);

$this->add_control(
    'navigation_normal_color',
    [
        'label' => esc_html__('Navigation Icon Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            ' {{WRAPPER}} .swiper-button-prev .text-btn-line-holder .text-btn-line-top, 
            {{WRAPPER}} .swiper-button-prev .text-btn-line-holder .text-btn-line,
            {{WRAPPER}} .swiper-button-prev .text-btn-line-holder .text-btn-line-bottom,
            {{WRAPPER}} .swiper-button-next .text-btn-line-holder .text-btn-line-top, 
            {{WRAPPER}} .swiper-button-next .text-btn-line-holder .text-btn-line,
            {{WRAPPER}} .swiper-button-next .text-btn-line-holder .text-btn-line-bottom' => 'background-color: {{VALUE}};'
        ],
    ]
);

$this->add_control(
    'navigation_normal_border_color',
    [
        'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .iqonic-navigation .swiper-button-prev,
            {{WRAPPER}} .iqonic-navigation .swiper-button-next' => 'border-color:{{VALUE}};'

        ],
    ]
);

$this->end_controls_tab();
$this->start_controls_tab(
    'style_swiper_hover_tab',
    [
        'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
        'condition' => [
            'want_nav' => 'true',
        ],
    ]
);

$this->add_control(
    'navigation_hover_color',
    [
        'label' => esc_html__('Navigation Icon Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [

            ' {{WRAPPER}} .swiper-button-prev:hover .text-btn-line-holder .text-btn-line-top, 
            {{WRAPPER}} .swiper-button-prev:hover .text-btn-line-holder .text-btn-line,
            {{WRAPPER}} .swiper-button-prev:hover .text-btn-line-holder .text-btn-line-bottom,
            {{WRAPPER}} .swiper-button-next:hover .text-btn-line-holder .text-btn-line-top, 
            {{WRAPPER}} .swiper-button-next:hover .text-btn-line-holder .text-btn-line,
            {{WRAPPER}} .swiper-button-next:hover .text-btn-line-holder .text-btn-line-bottom' => 'background-color: {{VALUE}};'
        ],
    ]
);

$this->add_control(
    'navigation_hover_border_color',
    [
        'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .iqonic-navigation .swiper-button-prev:hover,
            {{WRAPPER}} .iqonic-navigation .swiper-button-next:hover' => 'border-color:{{VALUE}};'
        ],
    ]
);

$this->end_controls_tab();
$this->end_controls_tabs();
