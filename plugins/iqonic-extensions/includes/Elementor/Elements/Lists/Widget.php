<?php

namespace Iqonic\Elementor\Elements\Lists;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return esc_html__('iqonic_lists', IQONIC_EXTENSION_TEXT_DOMAIN);
    }

    public function get_title()
    {
        return esc_html__('Iqonic Lists', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-bullet-list';
    }
    protected function register_controls()
    {

        $this->start_controls_section(
			'section',
			[
				'label' => esc_html__('Lists', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);

		$this->add_control(
			'list_style',
			[
				'label'      => esc_html__('List Style', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'unorder',
				'options'    => [

					'order'          => esc_html__('Order List', IQONIC_EXTENSION_TEXT_DOMAIN),
					'unorder'          => esc_html__('Unorder List', IQONIC_EXTENSION_TEXT_DOMAIN),
					'icon'          => esc_html__('icon', IQONIC_EXTENSION_TEXT_DOMAIN),
					'image'          => esc_html__('Image', IQONIC_EXTENSION_TEXT_DOMAIN),


				],
			]
		);

		$this->add_control(
			'list_style_type_ol',
			[
				'label'      => esc_html__('List Style', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'decimal',
				'options'    => [
					'decimal'          => esc_html__('Decimal', IQONIC_EXTENSION_TEXT_DOMAIN),
					'decimal-leading-zero' => esc_html__('Decimal Leading Zero', IQONIC_EXTENSION_TEXT_DOMAIN),
					'lower-alpha'          => esc_html__('Lower Alpha', IQONIC_EXTENSION_TEXT_DOMAIN),
					'lower-greek'          => esc_html__('Lower Greek', IQONIC_EXTENSION_TEXT_DOMAIN),
					'lower-latin'          => esc_html__('Lower Latin', IQONIC_EXTENSION_TEXT_DOMAIN),
					'lower-roman'          => esc_html__('Lower Roman', IQONIC_EXTENSION_TEXT_DOMAIN),
					'upper-alpha'          => esc_html__('Upper Alpha', IQONIC_EXTENSION_TEXT_DOMAIN),
					'upper-roman'          => esc_html__('Upper Roman', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
				'condition' => [
					'list_style' => 'order',
				],
				'selectors' => [
					'{{WRAPPER}} .iq-list .iq-order-list li' => 'list-style-type: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_style_type_ul',
			[
				'label'      => esc_html__('List Style', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'circle',
				'options'    => [
					'circle' => esc_html__('Circle', IQONIC_EXTENSION_TEXT_DOMAIN),
					'disc'   => esc_html__('Disc', IQONIC_EXTENSION_TEXT_DOMAIN),
					'square' => esc_html__('Square', IQONIC_EXTENSION_TEXT_DOMAIN),

				],
				'condition' => [
					'list_style' => 'unorder',
				],
				'selectors' => [
					'{{WRAPPER}} .iq-list .iq-unoreder-list li' => 'list-style-type: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_column',
			[
				'label'      => esc_html__('No. Of column', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'one',
				'options'    => [

					'one'          => esc_html__('1 column', IQONIC_EXTENSION_TEXT_DOMAIN),
					'two'          => esc_html__('2 column', IQONIC_EXTENSION_TEXT_DOMAIN),
					'three'        => esc_html__('3 column', IQONIC_EXTENSION_TEXT_DOMAIN),
					'four'         => esc_html__('4 column', IQONIC_EXTENSION_TEXT_DOMAIN),
					'five' 		   => esc_html__('5 column', IQONIC_EXTENSION_TEXT_DOMAIN),
					'six'		   => esc_html__('6 column', IQONIC_EXTENSION_TEXT_DOMAIN),

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
					'list_style' => 'icon',
				],
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
            ]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__('Image', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'list_style' => 'image',
				],
			]
		);


		$repeater = new Repeater();
		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__('Title & Description', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Tab Title', IQONIC_EXTENSION_TEXT_DOMAIN),
				'placeholder' => esc_html__('Tab Title', IQONIC_EXTENSION_TEXT_DOMAIN),
				'label_block' => true,
			]
		);


		$this->add_control(
			'tabs',
			[
				'label' => esc_html__('Lists Items', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__('List Items', IQONIC_EXTENSION_TEXT_DOMAIN),

					]

				],
				'title_field' => '{{{ tab_title }}}',
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
                    '{{WRAPPER}} .iq-list' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_6etDdWFTgLOef0R9zMse',
			[
				'label' => esc_html__('List', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'iq_lists_text_typography',
				'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
				'selector' => '{{WRAPPER}}  .iq-list li',
			]
		);

		$this->start_controls_tabs('iq_lists_tabs');

		$this->start_controls_tab(
			'tabs_rpv2n9KXlxDj14M0La0F',
			[
				'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);
		$this->add_control(
			'iq_list_color',
			[
				'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list li' => 'color: {{VALUE}};',
				],

			]

		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'iq_lists_background',
				'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .iq-list',
			]
		);


		$this->add_control(
			'iq_lists_has_border',
			[
				'label' => esc_html__('Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
				'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);
		$this->add_control(
			'iq_lists_border_style',
			[
				'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'condition' => [
					'iq_lists_has_border' => 'yes',
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
					'{{WRAPPER}} .iq-list ' => 'border-style: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'iq_lists_border_color',
			[
				'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_has_border' => 'yes',
				],
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list' => 'border-color: {{VALUE}};',
				],


			]
		);

		$this->add_control(
			'iq_lists_border_width',
			[
				'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_has_border' => 'yes',
				],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'iq_lists_border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_has_border' => 'yes',
				],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_xa5Ad6Ea1dZjeSNHgYzh',
			[
				'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);
		$this->add_control(
			'iq_list_hover_color',
			[
				'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list li:hover' => 'color: {{VALUE}};',
				],

			]

		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'iq_lists_hover_background',
				'label' => esc_html__('Hover Background', IQONIC_EXTENSION_TEXT_DOMAIN),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .iq-list:hover ',
			]
		);


		$this->add_control(
			'iq_lists_hover_has_border',
			[
				'label' => esc_html__('Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
				'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);
		$this->add_control(
			'iq_lists_hover_border_style',
			[
				'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_hover_has_border' => 'yes',
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
					'{{WRAPPER}} .iq-list:hover ' => 'border-style: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'iq_lists_hover_border_color',
			[
				'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_hover_has_border' => 'yes',
				],
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list:hover' => 'border-color: {{VALUE}};',
				],


			]
		);

		$this->add_control(
			'iq_lists_hover_border_width',
			[
				'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_hover_has_border' => 'yes',
				],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'iq_lists_hover_border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'condition' => [
					'iq_lists_hover_has_border' => 'yes',
				],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();



		$this->add_responsive_control(
			'iq_lists_padding',
			[
				'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}}  .iq-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'iq_lists_margin',
			[
				'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}}  .iq-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);


		$this->end_controls_section();


		/*Fancy Icon start*/

		$this->start_controls_section(
			'section_UxR43bcXm9c7bcjeey0V',
			[
				'label' => esc_html__('List Icon/Image', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __('Icon Size <br> <span style="color: #5bc0de"> (Note : working only for icon) </span>', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs('Fancybox_icon_tabs');
		$this->start_controls_tab(
			'tabs_8O8eC1f3ecBWhTpj9AlJ',
			[
				'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);


		$this->add_control(
			'icon_color',
			[
				'label' => __('Choose Color <br> <span style="color: #5bc0de"> (Note : working only for icon) </span>', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i' => 'color: {{VALUE}};',
				],

			]

		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'iq_list_icon_background',
				'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img',
			]
		);

		$this->add_control(
			'iq_list_icon_border_style',
			[
				'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
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
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'border-style: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'iq_list_icon_border_color',
			[
				'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'border-color: {{VALUE}};',
				],


			]
		);

		$this->add_control(
			'iq_list_icon_border_width',
			[
				'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'iq_list_icon_border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_oQUHWq6F3sZfKh75bNzd',
			[
				'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => __('Choose Color <br> <span style="color: #5bc0de"> (Note : working only for icon) </span>', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,

				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li:hover i' => 'color: {{VALUE}};',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'iq_list_icon_hover_background',
				'label' => esc_html__('Hover Background', IQONIC_EXTENSION_TEXT_DOMAIN),
				'types' => ['classic', 'gradient'],
				'selector' => ' {{WRAPPER}} .iq-list-with-icon li:hover i,{{WRAPPER}} .iq-list-with-img li:hover img',
			]
		);


		$this->add_control(
			'iq_list_icon_hover_border_style',
			[
				'label' => esc_html__('Border Style', IQONIC_EXTENSION_TEXT_DOMAIN),
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
					'{{WRAPPER}} .iq-list-with-icon li:hover i,{{WRAPPER}} .iq-list-with-img li:hover img' => 'border-style: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'iq_list_icon_hover_border_color',
			[
				'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li:hover i,{{WRAPPER}} .iq-list-with-img li:hover img' => 'border-color: {{VALUE}};',
				],


			]
		);

		$this->add_control(
			'iq_list_icon_hover_border_width',
			[
				'label' => esc_html__('Border Width', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li:hover i,{{WRAPPER}} .iq-list-with-img li:hover img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'iq_list_icon_hover_border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li:hover i,{{WRAPPER}} .iq-list-with-img li:hover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__('Width', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
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
				],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_height',
			[
				'label' => esc_html__('Height', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
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
				],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iq-list-with-icon li i' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'iq_list_icon_padding',
			[
				'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'iq_list_icon_margin',
			[
				'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-list-with-icon li i,{{WRAPPER}} .iq-list-with-img li img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);


		$this->end_controls_section();

    }
    protected function render()
    {
		require 'render.php';
    }
}
