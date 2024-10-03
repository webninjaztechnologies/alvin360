<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

$this->add_control(
    'button_style',
    [
        'label' => esc_html__('Style', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'default',
        'options' => [
            'default'   => esc_html__('Default', IQONIC_EXTENSION_TEXT_DOMAIN),
            'style-one' => esc_html__('Link', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'button_types',
    [
        'label' => esc_html__('Types', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'socialv-btn-primary ',
        'condition' => ['button_style' => 'default'],
        'options' => [
            'socialv-btn-primary  '   => esc_html__('primary', IQONIC_EXTENSION_TEXT_DOMAIN),
            'socialv-btn-success ' => esc_html__('success', IQONIC_EXTENSION_TEXT_DOMAIN),
            'socialv-btn-danger ' => esc_html__('danger', IQONIC_EXTENSION_TEXT_DOMAIN),
            'socialv-btn-info ' => esc_html__('info', IQONIC_EXTENSION_TEXT_DOMAIN),
            'socialv-btn-warning ' => esc_html__('warning', IQONIC_EXTENSION_TEXT_DOMAIN),
            'socialv-btn-orange ' => esc_html__('orange', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'button_size',
    [
        'label' => esc_html__('Size', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'normal',
        'condition' => ['button_style' => 'default'],
        'options' => [
            'normal'   => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            'small' => esc_html__('Small', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
    ]
);

$this->add_control(
    'button_text',
    [
        'label' => esc_html__('Text', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::TEXT,
        'dynamic' => [
            'active' => true,
        ],
        'label_block' => true,
        'default' => esc_html__('Read More', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_control(
    'button_action',
    [
        'label' => esc_html__('Action', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SELECT,
        'default' => 'none',
        'options' => [
            'popup' => esc_html__('Open Popup', IQONIC_EXTENSION_TEXT_DOMAIN),
            'link'  => esc_html__('Open Link', IQONIC_EXTENSION_TEXT_DOMAIN),
            'none'  => esc_html__('none', IQONIC_EXTENSION_TEXT_DOMAIN),
        ],
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
        'condition' => ['button_action' => 'link']
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
            'button_action' => 'link',
        ],
        'options' => iqonic_get_posts("page"),
    ]
);

$this->add_control(
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
            'button_action' => 'link',
            'link_type' => 'custom',
        ]
    ]
);

$this->add_responsive_control(
    'align_button',
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
            '{{WRAPPER}} .socialv-button-container' => 'text-align: {{VALUE}};',
        ]
    ]
);

$this->end_controls_section();

$this->start_controls_section(
    'section_header',
    [
        'label' => esc_html__('Model Header', IQONIC_EXTENSION_TEXT_DOMAIN),
        'condition' => ['button_action' => 'popup']
    ]
);
$this->add_control(
    'model_title',
    [
        'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::TEXT,
        'dynamic' => [
            'active' => true,
        ],
        'label_block' => true,
        'default' => esc_html__('Model Title', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);


$this->add_control(
    'model_selected_icon',
    [
        'label' => esc_html__('Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'default' => [
            'value' => 'fas fa-star'
        ],
    ]
);

$this->end_controls_section();

$this->start_controls_section(
    'section_body',
    [
        'label' => esc_html__('Model Body', IQONIC_EXTENSION_TEXT_DOMAIN),
        'condition' => ['button_action' => 'popup']
    ]
);
$this->add_control(
    'model_body',
    [
        'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::WYSIWYG,
        'default' => esc_html__('Default description', IQONIC_EXTENSION_TEXT_DOMAIN),
        'placeholder' => esc_html__('Type your description here', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);


$this->end_controls_section();

// Button Text Style
$this->start_controls_section(
    'section_button_color',
    [
        'label' => esc_html__('Button Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'tab' => Controls_Manager::TAB_STYLE,

    ]
);

$this->start_controls_tabs('contact_tabs');


$this->start_controls_tab(
    'tabs_button_color_normal',
    [
        'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_control(
    'text_color',
    [
        'label' => esc_html__('Text Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .socialv-button.socialv-button-link ,
             {{WRAPPER}} a.socialv-btn-primary ' => 'color: {{VALUE}};',
           
            
        ],
    ]
);


$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name' => 'btn_text_typography',
        'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
        'selector' => '{{WRAPPER}} .socialv-button.socialv-button-link, {{WRAPPER}} a.socialv-btn-primary',
    ]
);
$this->end_controls_tab();

$this->start_controls_tab(
    'tabs_button_color_hover',
    [
        'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_control(
    'data_hover_text',
    [
        'label' => esc_html__('Text Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .socialv-button.socialv-button-link:hover,
            {{WRAPPER}} a.socialv-btn-primary:hover '  => 'color: {{VALUE}};',

        ],
    ]
);

$this->end_controls_tab();

$this->end_controls_tabs();



$this->end_controls_section();

$this->start_controls_section(
    'section_button_background',
    [
        'label' => esc_html__('Button Background', IQONIC_EXTENSION_TEXT_DOMAIN),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => ['button_style' => 'default']
    ]
);

$this->start_controls_tabs('button_background_tabs');
$this->start_controls_tab(
    'tabs_normal_background',
    [
        'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_group_control(
    Group_Control_Background::get_type(),
    [
        'name' => 'data_background',
        'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
        'types' => ['classic', 'gradient'],
        'selector' => '{{WRAPPER}} .socialv-btn-primary',
    ]
);

$this->end_controls_tab();

$this->start_controls_tab(
    'tabs_hover_background',
    [
        'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_group_control(
    Group_Control_Background::get_type(),
    [
        'name' => 'data_hover',
        'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
        'types' => ['classic', 'gradient', 'video'],
        'selector' => '{{WRAPPER}} .socialv-btn-primary:hover',
    ]
);

$this->end_controls_tab();
$this->end_controls_tabs();

$this->add_responsive_control(
    'socialv_button_box_padding',
    [
        'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}}  .socialv-btn-primary ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'socialv_button_box_margin',
    [
        'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}}  .socialv-btn-primary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

$this->end_controls_section();

// Box shadow Style Start
$this->start_controls_section(
    'section_button_shadow',
    [
        'label' => esc_html__('Button Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
            'button_style' => 'default'
        ],
    ]
);

$this->start_controls_tabs('button_shadow_tabs');
$this->start_controls_tab(
    'tabs_normal_btn_shadow',
    [
        'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_group_control(
    Group_Control_Box_Shadow::get_type(),
    [
        'name' => 'socialv_icon_box_shadow',
        'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
        'selector' => '{{WRAPPER}} .socialv-btn-primary',
    ]
);

$this->end_controls_tab();

$this->start_controls_tab(
    'tabs_hover_btn_shadow',
    [
        'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_group_control(
    Group_Control_Box_Shadow::get_type(),
    [
        'name' => 'socialv_iconhoverbox_box_shadow',
        'label' => esc_html__('Box Shadow', IQONIC_EXTENSION_TEXT_DOMAIN),
        'selector' => '{{WRAPPER}} .socialv-btn-primary:hover',
    ]
);

$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();

// Border Style Start
$this->start_controls_section(
    'section_button_border',
    [
        'label' => esc_html__('Button Border', IQONIC_EXTENSION_TEXT_DOMAIN),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => ['button_style' => 'default']
    ]
);

$this->add_control(
    'has_custom_border',
    [
        'label' => esc_html__('Use Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'no',
        'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
        'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
    ]
);

$this->add_control(
    'data_border',
    [
        'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .socialv-btn-primary' => 'border-color: {{VALUE}};',
        ],
        'condition' => ['has_custom_border' => 'yes'],
    ]
);

$this->add_control(
    'data_hover_border_outline',
    [
        'label' => esc_html__('Hover Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .socialv-btn-primary:hover' => 'border-color: {{VALUE}};',
        ],
        'condition' => ['has_custom_border' => 'yes'],
    ]
);

$this->add_control(
    'border_style',
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
        'condition' => ['has_custom_border' => 'yes'],
        'selectors' => [
            '{{WRAPPER}} .socialv-btn-primary' => 'border-style: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'border_width',
    [
        'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}} .socialv-btn-primary' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'condition' => ['has_custom_border' => 'yes'],
    ]
);

$this->add_control(
    'border_radius',
    [
        'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}} .socialv-btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'condition' => ['has_custom_border' => 'yes'],
    ]
);