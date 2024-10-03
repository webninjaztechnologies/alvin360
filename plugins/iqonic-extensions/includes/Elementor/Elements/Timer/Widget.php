<?php

namespace Iqonic\Elementor\Elements\Timer;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

use Elementor\Core\Schemes\Typography as Scheme_Typography;

if (!defined('ABSPATH')) exit;

class Widget extends Widget_Base
{
	public function get_name()
	{
		return 'iqonic_timer';
	}

	public function get_title()
	{
		return esc_html__('Iqonic Timer', IQONIC_EXTENSION_TEXT_DOMAIN);
	}
	public function get_categories()
	{
		return ['iqonic-extension'];
	}

	public function get_icon()
	{
		return 'eicon-countdown';
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'section',
			[
				'label' => esc_html__('Count Down Timer', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);

		$this->add_control(
			'timer_title',
			[
				'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('Default title', IQONIC_EXTENSION_TEXT_DOMAIN),
				'placeholder' => esc_html__('Type your title here', IQONIC_EXTENSION_TEXT_DOMAIN),
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

		$this->add_control(
			'future_date',
			[
				'label' => esc_html__('Select Date', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DATE_TIME,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'picker_options' => ['enableTime' => true]

			]
		);

		$this->add_control(
			'timer_format',
			[
				'label'      => esc_html__('Select Format', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'YODHMS',
				'options'    => [
					'YODHMS' => esc_html__('Year / Month / Day / Hour / Minute / Second', IQONIC_EXTENSION_TEXT_DOMAIN),
					'ODHMS'  => esc_html__('Month / Day/ Hour / Minute / Second', IQONIC_EXTENSION_TEXT_DOMAIN),
					'DHMS'   => esc_html__('Day / Hour / Minute / Second', IQONIC_EXTENSION_TEXT_DOMAIN),
					'HMS'    => esc_html__(' Hour / Minute / Second', IQONIC_EXTENSION_TEXT_DOMAIN),
					'MS'     => esc_html__('Minute / Second', IQONIC_EXTENSION_TEXT_DOMAIN),
					'S'      => esc_html__(' Second', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => esc_html__('Show Labels', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', IQONIC_EXTENSION_TEXT_DOMAIN),
				'label_off' => esc_html__('Hide', IQONIC_EXTENSION_TEXT_DOMAIN),
				'return_value' => 'true',
				'default' => 'true',
			]
		);


		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__('Alignment', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-start' => [
						'title' => esc_html__('Left', IQONIC_EXTENSION_TEXT_DOMAIN),
						'icon' => 'eicon-text-align-left',
					],
					'text-center' => [
						'title' => esc_html__('Center', IQONIC_EXTENSION_TEXT_DOMAIN),
						'icon' => 'eicon-text-align-center',
					],
					'text-end' => [
						'title' => esc_html__('Right', IQONIC_EXTENSION_TEXT_DOMAIN),
						'icon' => 'eicon-text-align-right',
					]
				]
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_count_down_title',
			[
				'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__(' Title Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .socialv-title.socialv-heading-title',
			]
		);

		$this->add_control(
			'timer_title_color',
			[
				'label' => esc_html__('Title Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .socialv-title.socialv-heading-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'timer_title_hover_color',
			[
				'label' => esc_html__('Title Hover Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .socialv-title.socialv-heading-title:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .socialv-title.socialv-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .socialv-title.socialv-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_down_style',
			[
				'label' => esc_html__('Timer Text', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbers_typography',
				'label' => esc_html__(' Number Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .numberDisplay',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'periods_typography',
				'label' => esc_html__(' Period Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .periodDisplay',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Timer Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-count-down ' => 'color: {{VALUE}};',
					'{{WRAPPER}} .numberDisplay' => 'color: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__('Text Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-count-down .iq-data-countdown-timer .periodDisplay' => 'color: {{VALUE}};',

				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'flip_back_back',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .iq-count-down .iq-data-countdown-timer .numberDisplay',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					]
				],
				'condition' => [
					'show_label' => 'true',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border_style',
			[
				'label' => esc_html__('Border', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'has_border',
			[
				'label' => esc_html__('Border?', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'label_off',
				'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
				'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);
		$this->add_control(
			'border_style',
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
				'condition' => [
					'has_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .iq-count-down .numberDisplay' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-count-down .numberDisplay' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'has_border' => 'yes',
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
					'{{WRAPPER}} .iq-count-down .numberDisplay' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'count_down_padding',
			[
				'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-count-down .numberDisplay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iq-count-down .numberDisplay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render()
	{
		require 'render.php';

		if (Plugin::$instance->editor->is_edit_mode()) { ?>
			<script>
				(function($) {
					callTimer();
				})(jQuery);
			</script>
<?php
		}
	}
}
