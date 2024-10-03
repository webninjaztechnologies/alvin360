<?php

namespace Iqonic\Elementor\Elements\Counter;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Widget extends Widget_Base
{
    public function get_name()
    {
        return esc_html__('iqonic_counter', IQONIC_EXTENSION_TEXT_DOMAIN);
    }

    public function get_title()
    {
        return esc_html__('Iqonic Counter', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-counter';
    }
    protected function register_controls()
    {

		$this->start_controls_section(
			'section',
			[
				'label' => esc_html__( 'Counter', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
		);

       
        $this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Title', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'Add Your Title Text Here', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter Description', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'default' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', IQONIC_EXTENSION_TEXT_DOMAIN ),
			
			]
        );
        

		$this->add_control(
			'counter_style',
			[
				'label'      => esc_html__( 'Select Style', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'none',
				'options'    => [
					
					'icon'          => esc_html__( 'Icon', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'image'          => esc_html__( 'Image', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'none'          => esc_html__( 'none', IQONIC_EXTENSION_TEXT_DOMAIN ),
					
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition' => [
					'counter_style' => 'icon',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'counter_style' => 'image',
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Counter Content', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter Counter Figure Number', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'default' => esc_html__( '100', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
		);

		$this->add_control(
			'content_after_text',
			[
				'label' => esc_html__( 'Counter After Content', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter Counter After Text', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
		);
		$this->add_control(
			'content_symbol',
			[
				'label' => esc_html__( 'Counter Symbol', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter Counter Symbol', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'default' => esc_html__( '+', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'      => esc_html__( 'Title Tag', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'h4',
				'options'    => [
					
					'h1'          => esc_html__( 'h1', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'h2'          => esc_html__( 'h2', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'h3'          => esc_html__( 'h3', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'h4'          => esc_html__( 'h4', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'h5'          => esc_html__( 'h5', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'h6'          => esc_html__( 'h6', IQONIC_EXTENSION_TEXT_DOMAIN ),
					
					
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
                    '{{WRAPPER}} .iq-counter' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


         $this->start_controls_section(
			'section_C68vdQNDp9Ley31a3gsb',
			[
				'label' => esc_html__( 'Counter Content ', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


        $this->start_controls_tabs( 'counter_tabs' );
		$this->start_controls_tab(
            'tabs_P4JUaV0fNS5f6bWh1ZQ5',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'counter_background',
                'label' => esc_html__( 'Background', IQONIC_EXTENSION_TEXT_DOMAIN ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .iq-counter, {{WRAPPER}} .iq-counter.iq-counter-style-4 .counter-content',
            ]
        );

		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_xcaiH8N2LfaIj3b56Grq',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'counter_hover_background',
                'label' => esc_html__( 'Hover Background', IQONIC_EXTENSION_TEXT_DOMAIN ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .iq-counter:hover, {{WRAPPER}} .iq-counter.iq-counter-style-4:hover .counter-content ',
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => esc_html__( 'Padding', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'counter_margin',
			[
				'label' => esc_html__( 'Margin', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);


        $this->end_controls_section();

        $this->start_controls_section(
			'section_aaQPp5E70aeHxBbJiuFl',
			[
				'label' => esc_html__( 'Title', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'counter_title_typography',
				'label' => esc_html__( 'Typography', IQONIC_EXTENSION_TEXT_DOMAIN ),				
				'selector' => '{{WRAPPER}} .iq-counter .counter-title-text',
			]
		);

		$this->start_controls_tabs( 'counter_title_tabs' );
		$this->start_controls_tab(
            'tabs_0SW35aiga5bxLG8hF3Q8',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_title_color',
			[
				'label' => esc_html__( 'Text Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter .counter-title-text' => 'color: {{VALUE}};',					
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_VXL5C9YfZbxId6ie2eab',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_title_hover_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .counter-title-text' => 'color: {{VALUE}};',
		 		],
				
			]
			
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'counter_title_padding',
			[
				'label' => esc_html__( 'Padding', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter .counter-title-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'counter_title_margin',
			[
				'label' => esc_html__( 'Margin', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter .counter-title-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);


        $this->end_controls_section();


         $this->start_controls_section(
			'section_Rzcf1f33Atx14dLXZ6Tw',
			[
				'label' => esc_html__( 'Timer', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'counter_timer_typography',
				'label' => esc_html__( 'Typography', IQONIC_EXTENSION_TEXT_DOMAIN ),				
				'selector' => '{{WRAPPER}} .iq-counter .timer,{{WRAPPER}} .iq-counter .counter-symbol,{{WRAPPER}} .iq-counter .counter-after-content',
			]
		);

		$this->start_controls_tabs( 'counter_timer_tabs' );
		$this->start_controls_tab(
            'tabs_Q436ETVNbixGeLZgaa26',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_timer_color',
			[
				'label' => esc_html__( 'Text Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter .timer,{{WRAPPER}} .iq-counter .counter-symbol,{{WRAPPER}} .iq-counter .counter-after-content' => 'color: {{VALUE}};',
					
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_a595ji49bpGaDfuAQtYk',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_timer_hover_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .timer,{{WRAPPER}} .iq-counter:hover .counter-symbol,{{WRAPPER}} .iq-counter:hover .counter-after-content' => 'color: {{VALUE}};',
		 		],
				
			]
			
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'counter_timer_padding',
			[
				'label' => esc_html__( 'Padding', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter .iq-counter-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'counter_timer_margin',
			[
				'label' => esc_html__( 'Margin', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .iq-counter .iq-counter-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);



		
        $this->end_controls_section();

         $this->start_controls_section(
			'section_desc_style',
			[
				'label' => esc_html__( 'Description', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			
			]
		);
		


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'counter_desc_typography',
				'label' => esc_html__( 'Typography', IQONIC_EXTENSION_TEXT_DOMAIN ),				
				'selector' => ' {{WRAPPER}} .iq-counter .counter-content-text',
			]
		);

		$this->start_controls_tabs( 'counter_desc_tabs' );
		$this->start_controls_tab(
            'tabs_67Nb61a1by4wAauKI4Wa',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_desc_color',
			[
				'label' => esc_html__( 'Text Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter .counter-content-text' => 'color: {{VALUE}};',
					
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_ZncUVaJ0ybqcd55Kez8S',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_desc_hover_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .counter-content-text' => 'color: {{VALUE}};',
		 		],
				
			]
			
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'counter_desc_padding',
			[
				'label' => esc_html__( 'Padding', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .counter-content-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'counter_desc_margin',
			[
				'label' => esc_html__( 'Margin', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .counter-content-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);



        $this->end_controls_section();

        $this->start_controls_section(
			'section_t152L8Da7bf52Ya299fH',
			[
				'label' => esc_html__( 'Icon/Image', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],				
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
					'{{WRAPPER}} .iq-counter i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		

		$this->start_controls_tabs( 'counter_icon_tabs' );
		$this->start_controls_tab(
            'tabs_Iabfad8ObPL6iaD56AfC',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );

		$this->add_control(
			'counter_icon_color',
			[
				'label' => esc_html__( 'Icon Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .iq-counter i' => 'color: {{VALUE}};',
					
				],
			]
		);

		 $this->add_control(
			'counter_icon_has_border',
			[
				'label' => esc_html__( 'Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'yes' => esc_html__( 'yes', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'no' => esc_html__( 'no', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
        );

         $this->add_control(
			'counter_icon_border_style',
				[
					'label' => esc_html__( 'Border Style', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'condition' => [
					'counter_icon_has_border' => 'yes',
					],
					'type' => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'solid'  => esc_html__( 'Solid', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dashed' => esc_html__( 'Dashed', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dotted' => esc_html__( 'Dotted', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'double' => esc_html__( 'Double', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'outset' => esc_html__( 'outset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'groove' => esc_html__( 'groove', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'ridge' => esc_html__( 'ridge', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'inset' => esc_html__( 'inset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'hidden' => esc_html__( 'hidden', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'none' => esc_html__( 'none', IQONIC_EXTENSION_TEXT_DOMAIN ),
						
					],
					
					'selectors' => [
						'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'border-style: {{VALUE}};',
						
					],
				]
		);

		$this->add_control(
			'counter_icon_border_color',
			[
				'label' => esc_html__( 'Border Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_has_border' => 'yes',
					],
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'border-color: {{VALUE}};',
		 		],
				
				
			]
		);

		$this->add_control(
			'counter_icon_border_width',
			[
				'label' => esc_html__( 'Border Width', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_has_border' => 'yes',
					],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_control(
			'counter_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_has_border' => 'yes',
					],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);	
		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_AP0bbecfDbBeGaQaNWmI',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
		$this->add_control(
			'counter_icon_hover_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover i' => 'color: {{VALUE}};',
		 		],
				
			]
			
		);

		 $this->add_control(
			'counter_icon_hover_has_border',
			[
				'label' => esc_html__( 'Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'yes' => esc_html__( 'yes', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'no' => esc_html__( 'no', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
        );

         $this->add_control(
			'counter_icon_hover_border_style',
				[
					'label' => esc_html__( 'Border Style', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'condition' => [
					'counter_icon_hover_has_border' => 'yes',
					],
					'type' => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'solid'  => esc_html__( 'Solid', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dashed' => esc_html__( 'Dashed', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dotted' => esc_html__( 'Dotted', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'double' => esc_html__( 'Double', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'outset' => esc_html__( 'outset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'groove' => esc_html__( 'groove', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'ridge' => esc_html__( 'ridge', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'inset' => esc_html__( 'inset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'hidden' => esc_html__( 'hidden', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'none' => esc_html__( 'none', IQONIC_EXTENSION_TEXT_DOMAIN ),
						
					],
					
					'selectors' => [
						'{{WRAPPER}} .iq-counter:hover .iq-counter-icon' => 'border-style: {{VALUE}};',
						
					],
				]
		);

		$this->add_control(
			'counter_icon_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_hover_has_border' => 'yes',
					],
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .iq-counter-icon' => 'border-color: {{VALUE}};',
		 		],
				
				
			]
		);

		$this->add_control(
			'counter_icon_hover_border_width',
			[
				'label' => esc_html__( 'Border Width', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_hover_has_border' => 'yes',
					],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .iq-counter-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_control(
			'counter_icon_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'condition' => [
					'counter_icon_hover_has_border' => 'yes',
					],
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover .iq-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);	
		$this->end_controls_tab();
		$this->end_controls_tabs();


		 $this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'icon_height',
			[
				'label' => esc_html__( 'Height', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iq-counter .iq-counter-icon i' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'counter_icon_padding',
			[
				'label' => esc_html__( 'Padding', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'counter_icon_margin',
			[
				'label' => esc_html__( 'Margin', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter .iq-counter-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);






        $this->end_controls_section();

        $this->start_controls_section(
			'section_20bnaf9beBPYDN93bT0E',
			[
				'label' => esc_html__( 'Border', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
				
			]
		);

		$this->add_control(
			'has_border',
			[
				'label' => esc_html__( 'Set Custom Border?', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'label_off',
				'yes' => esc_html__( 'yes', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'no' => esc_html__( 'no', IQONIC_EXTENSION_TEXT_DOMAIN ),
			]
        );
        $this->start_controls_tabs( 'counter_border_tabs' );
		$this->start_controls_tab(
            'tabs_04kQ0L65UPMgbA74baDt',
            [
                'label' => esc_html__( 'Normal', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );
        $this->add_control(
			'border_style',
				[
					'label' => esc_html__( 'Border Style', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'type' => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'solid'  => esc_html__( 'Solid', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dashed' => esc_html__( 'Dashed', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dotted' => esc_html__( 'Dotted', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'double' => esc_html__( 'Double', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'outset' => esc_html__( 'outset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'groove' => esc_html__( 'groove', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'ridge' => esc_html__( 'ridge', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'inset' => esc_html__( 'inset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'hidden' => esc_html__( 'hidden', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'none' => esc_html__( 'none', IQONIC_EXTENSION_TEXT_DOMAIN ),						
					],
					'condition' => [
					'has_border' => 'yes',
					],
					'selectors' => [
						'{{WRAPPER}} .iq-counter, {{WRAPPER}} .iq-counter.iq-counter-style-4 .counter-content' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .iq-counter.iq-counter-style-4 ' => 'border:none;'
						
					],
				]
			);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter,{{WRAPPER}} .iq-counter.iq-counter-style-4 .counter-content' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4 ' => 'border:none;'
					
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter ,{{WRAPPER}} .iq-counter.iq-counter-style-4 .counter-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4 ' => 'border:none;'
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border radius', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter,{{WRAPPER}} .iq-counter.iq-counter-style-4 .counter-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4 ' => 'border:none;'
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
            'tabs_boFxnZ5gNIbPbBGkalbd',
            [
                'label' => esc_html__( 'Hover', IQONIC_EXTENSION_TEXT_DOMAIN ),
            ]
        );

           $this->add_control(
			'border_hover_style',
				[
					'label' => esc_html__( 'Border Style', IQONIC_EXTENSION_TEXT_DOMAIN ),
					'type' => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'solid'  => esc_html__( 'Solid', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dashed' => esc_html__( 'Dashed', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'dotted' => esc_html__( 'Dotted', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'double' => esc_html__( 'Double', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'outset' => esc_html__( 'outset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'groove' => esc_html__( 'groove', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'ridge' => esc_html__( 'ridge', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'inset' => esc_html__( 'inset', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'hidden' => esc_html__( 'hidden', IQONIC_EXTENSION_TEXT_DOMAIN ),
						'none' => esc_html__( 'none', IQONIC_EXTENSION_TEXT_DOMAIN ),						
					],
					'condition' => [
					'has_border' => 'yes',
					],
					'selectors' => [
						'{{WRAPPER}} .iq-counter:hover,{{WRAPPER}} .iq-counter.iq-counter-style-4:hover .counter-content' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .iq-counter.iq-counter-style-4:hover' => 'border:none;'
						
					],
				]
			);

		$this->add_control(
			'border_hover_color',
			[
				'label' => esc_html__( 'Color', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover,{{WRAPPER}} .iq-counter.iq-counter-style-4:hover .counter-content' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4:hover' => 'border:none;'
					
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_hover_width',
			[
				'label' => esc_html__( 'Border Width', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover,{{WRAPPER}} .iq-counter.iq-counter-style-4:hover .counter-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4:hover' => 'border:none;'
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_hover_radius',
			[
				'label' => esc_html__( 'Border radius', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iq-counter:hover,{{WRAPPER}} .iq-counter.iq-counter-style-4:hover .counter-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iq-counter.iq-counter-style-4:hover' => 'border:none;'
				],
				'condition' => [
					'has_border' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		
    }
    protected function render()
    {
        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Elements/Counter/render.php';
        if (Plugin::$instance->editor->is_edit_mode()) { 
            ?>
           <script>
               (function(jQuery) {
				    callCountTo();
               })(jQuery);
           </script> 
               <?php
       }
    }
}
