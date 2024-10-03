<?php

namespace Iqonic\Elementor\Elements\Social_Icons;

use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
	public function get_name()
	{
		return 'iqonic_social_icons';
	}

	public function get_title()
	{
		return esc_html__('Iqonic Social Icons', IQONIC_EXTENSION_TEXT_DOMAIN);
	}
	public function get_categories()
	{
		return ['iqonic-extension'];
	}

	public function get_icon()
	{
		return 'eicon-social-icons';
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'section_social_icon',
			[
				'label' => esc_html__('Social Icons', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__('Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social',
				'default' => [
					'value' => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'iqonic-extension',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
					'fa-solid' => [
						'envelope',
						'link',
						'rss',
					],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__('Link', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::URL,
				'default' => [
					'is_external' => 'true',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__('https://your-link.com', IQONIC_EXTENSION_TEXT_DOMAIN),
			]
		);

		$repeater->add_control(
			'social_text',
			[
				'label' => esc_html__('Text', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);


		$this->add_control(
			'social_icon_list',
			[
				'label' => esc_html__('Social Icons', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'social_icon' => [
							'value' => 'fab fa-facebook',
							'library' => 'fa-brands',
						], 
						'social_text' => esc_html__('Facebook', IQONIC_EXTENSION_TEXT_DOMAIN),
					],
					[
						'social_icon' => [
							'value' => 'fab fa-twitter',
							'library' => 'fa-brands',
						],
						'social_text' => esc_html__('Twitter', IQONIC_EXTENSION_TEXT_DOMAIN),
					],
					[
						'social_icon' => [
							'value' => 'fab fa-youtube',
							'library' => 'fa-brands',
						],
						'social_text' => esc_html__('Youtube', IQONIC_EXTENSION_TEXT_DOMAIN),
					],
				],
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__('Layout', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__('Only Icons', IQONIC_EXTENSION_TEXT_DOMAIN),
					'names' => esc_html__('Only Names', IQONIC_EXTENSION_TEXT_DOMAIN),
					'icons_names' => esc_html__('Icon + name', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
			]
		);
		
		$this->add_control(
			'position',
			[
				'label' => esc_html__('Position', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__('Horizontal', IQONIC_EXTENSION_TEXT_DOMAIN),
					'verticle' => esc_html__('Verticle', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
				'prefix_class' => 'iqonic-extension-social-position-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__('Shape', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SELECT,
				'default' => 'rounded',
				'options' => [
					'rounded' => esc_html__('Rounded', IQONIC_EXTENSION_TEXT_DOMAIN),
					'square' => esc_html__('Square', IQONIC_EXTENSION_TEXT_DOMAIN),
					'circle' => esc_html__('Circle', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
				'prefix_class' => 'iqonic-extension-shape-',
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__('Alignment', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				],
				'prefix_class' => 'e-grid-align-',
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .socialv-social-share' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__('View', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_style',
			[
				'label' => esc_html__('Social Icons', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__('Official Color', IQONIC_EXTENSION_TEXT_DOMAIN),
					'custom' => esc_html__('Custom', IQONIC_EXTENSION_TEXT_DOMAIN),
				],
				'selectors' => [
					
					'{{WRAPPER}} .fab fa-facebook' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__('Primary Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-share' => 'background-color: {{VALUE}};',
				
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__('Secondary Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-share i, {{WRAPPER}} .socialv-share' => 'color: {{VALUE}};',
					
					'{{WRAPPER}} .socialv-share svg,{{WRAPPER}} .socialv-share svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon size', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'condition' => [
					'layout' => [ 'default', 'icons_names' ],
				],
				'selectors' => [
					
					'{{WRAPPER}} .socialv-social-icons .socialv-social-item .socialv-share, {{WRAPPER}} .socialv-social-item .socialv-share i' => 'font-size: {{SIZE}}{{UNIT}}' ,
					
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__('Spacing', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-social-share' => 'word-spacing: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__('Rows Gap', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-social-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border', // We know this mistake - TODO: 'icon_border' (for hover control condition also)
				'selector' => '{{WRAPPER}} .socialv-share',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .socialv-share' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_hover',
			[
				'label' => esc_html__('Icon Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => esc_html__('Primary Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-share:hover' => 'background-color: {{VALUE}};',
					
				],
			]
		);

		$this->add_control(
			'hover_secondary_color',
			[
				'label' => esc_html__('Secondary Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					
					'{{WRAPPER}} .socialv-share:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .socialv-share:hover' => 'color: {{VALUE}};',
					
					'{{WRAPPER}} .socialv-share:hover svg,{{WRAPPER}} .socialv-share:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_border_color',
			[
				'label' => esc_html__('Border Color', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'image_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .socialv-share:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__('Hover Animation', IQONIC_EXTENSION_TEXT_DOMAIN),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_section();

		

	}

	protected function render()
	{
		require 'render.php';
	}
}
