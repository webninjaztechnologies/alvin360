<?php

add_action('elementor/frontend/section/before_render', 'iqonic_animation_before_render', 1);
add_action('elementor/element/section/section_layout/after_section_end', 'iqonic_animation_register_controls', 1);

function iqonic_animation_register_controls($element)
{
    $element->start_controls_section(
        'marvy_ripples_animation_section',
        [
            'label' => esc_html__('Ripples Animation', IQONIC_EXTENSION_TEXT_DOMAIN),
            'tab' => \Elementor\Controls_Manager::TAB_LAYOUT
        ]
    );

    $element->add_control(
        'marvy_enable_ripples_animation',
        [
            'label' => esc_html__('Enable Ripples Animation', IQONIC_EXTENSION_TEXT_DOMAIN),
            'type' => \Elementor\Controls_Manager::SWITCHER,
        ]
    );

    $element->add_control(
        'marvy_ripples_animation_circle_color',
        [
            'label' => esc_html__('Circle Color', IQONIC_EXTENSION_TEXT_DOMAIN),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2F74C5',
            'condition' => [
                'marvy_enable_ripples_animation' => 'yes',
            ]
        ]
    );

    $element->add_control(
        'marvy_ripples_animation_circle_size',
        [
            'label' => esc_html__('Size', IQONIC_EXTENSION_TEXT_DOMAIN),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 100,
            'min' => 50,
            'max' => 1000,
            'step' => 5,
            'condition' => [
                'marvy_enable_ripples_animation' => 'yes',
            ]
        ]
    );

    $element->add_control(
        'marvy_ripples_animation_circle_position',
        [
            'label' => esc_html__('Position', IQONIC_EXTENSION_TEXT_DOMAIN),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' => esc_html__('Left', IQONIC_EXTENSION_TEXT_DOMAIN),
                'top' => esc_html__('Top', IQONIC_EXTENSION_TEXT_DOMAIN),
                'right' => esc_html__('Right', IQONIC_EXTENSION_TEXT_DOMAIN),
                'bottom' => esc_html__('Bottom', IQONIC_EXTENSION_TEXT_DOMAIN),
                'topLeft' => esc_html__('Top Left', IQONIC_EXTENSION_TEXT_DOMAIN),
                'topRight' => esc_html__('Top Right', IQONIC_EXTENSION_TEXT_DOMAIN),
                'bottomRight' => esc_html__('Bottom Right', IQONIC_EXTENSION_TEXT_DOMAIN),
                'bottomLeft' => esc_html__('Bottom Left', IQONIC_EXTENSION_TEXT_DOMAIN),
                'center' => esc_html__('Center', IQONIC_EXTENSION_TEXT_DOMAIN)
            ],
            'condition' => [
                'marvy_enable_ripples_animation' => 'yes'
            ]
        ]
    );

    $element->end_controls_section();
}

function iqonic_animation_before_render($element)
{
    $settings = $element->get_settings();

    if ($settings['marvy_enable_ripples_animation'] === 'yes') {
        $element->add_render_attribute(
            '_wrapper',
            [
                'data-marvy_enable_ripples_animation' => 'true',
                'data-marvy_ripples_animation_circle_color' => $settings['marvy_ripples_animation_circle_color'],
                'data-marvy_ripples_animation_circle_position' => $settings['marvy_ripples_animation_circle_position'],
                'data-marvy_ripples_animation_circle_size' => $settings['marvy_ripples_animation_circle_size']
            ]
        );
    } else {
        $element->add_render_attribute('_wrapper', 'data-marvy_enable_ripples_animation', 'false');
    }
}

add_action('wp_enqueue_scripts',  function () {
    wp_enqueue_script('iqonic-ripples-animation', IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/js/ripples_animation.js', array('jquery'), IQONIC_EXTENSION_VERSION, true);
    wp_enqueue_style('iqonic-ripple-animation', IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/css/ripples_animation.css', array(), IQONIC_EXTENSION_VERSION, 'all');
}, 10, 5);
