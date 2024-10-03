<?php

namespace Iqonic\Elementor\Elements\Accordion;

use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return esc_html__('iqonic_accordion', IQONIC_EXTENSION_TEXT_DOMAIN);
    }

    public function get_title()
    {
        return esc_html__('Iqonic Accordion', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section',
            [
                'label' => esc_html__('Accordion', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__('Question', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('What is Lorem Ipsum?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'placeholder' => esc_html__('Tab Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => esc_html__('Answer', IQONIC_EXTENSION_TEXT_DOMAIN),
                'default' => esc_html__('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'placeholder' => esc_html__('Tab Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXTAREA,
                'show_label' => false,
            ]
        );


        $repeater->add_control(
            'has_active',
            [
                'label' => esc_html__('Use Active?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
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
                        'tab_title' => esc_html__('Tab #1', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'tab_content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ]
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );
        $this->add_control(
            'has_icon',
            [
                'label' => esc_html__('Use Icon?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'iqonic_has_box_shadow',
            [
                'label' => esc_html__('Box Shadow?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'active_icon',
            [
                'label' => esc_html__('Active Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star'

                ],
                'condition' => [
                    'has_icon' => 'yes',
                ],
                'label_block' => false,
                'skin' => 'inline',


            ]
        );
        $this->add_control(
            'inactive_icon',
            [
                'label' => esc_html__('Inactive Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star'

                ],
                'condition' => [
                    'has_icon' => 'yes',
                ],
                'label_block' => false,
                'skin' => 'inline',
            ]
        );


        $this->add_control(
            'title_tag',
            [
                'label'      => esc_html__('Title Tag', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'h4',
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

        $this->end_controls_section();

        $this->start_controls_section(
            'section_serial_no_style',
            [
                'label' => esc_html__('Sr. Number', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'serial_no_title_typography',
				'label' => esc_html__( 'Sr No Typography', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .socialv-accordion-block .socialv-accordion-title .socialv-accordion-title-info .socialv-serial-number',
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

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-title .accordion-title' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'title_active_color',
            [
                'label' => esc_html__('Text Active Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-active .socialv-accordion-title .accordion-title' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'title_back_color',
            [
                'label' => esc_html__('Background Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-title' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'title_back_active_color',
            [
                'label' => esc_html__('Active Background Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-active .socialv-accordion-title' => 'background: {{VALUE}};',

                ],
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Content Text Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-details' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'content_back_color',
            [
                'label' => esc_html__('Background Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-details' => 'background: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_active_color',
            [
                'label' => esc_html__('Active Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block.socialv-active .socialv-accordion-title .active svg,{{WRAPPER}} .socialv-accordion .socialv-accordion-block.socialv-active .socialv-accordion-title  .socialv-icon-style' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block .socialv-accordion-title .active svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_inactive_color',
            [
                'label' => esc_html__('Inactive Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.socialv-accordion .socialv-accordion-block.socialv-active .socialv-accordion-title .active svg,{{WRAPPER}} .socialv-accordion .socialv-accordion-block .socialv-accordion-title .socialv-icon-style' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block .socialv-accordion-title .inactive svg path' => 'fill: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'icon_back_color',
            [
                'label' => esc_html__('Icon Background Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .socialv-accordion .socialv-accordion-block .socialv-accordion-title .socialv-icon-style' => 'background: {{VALUE}};',
                  

                ],
            ]
        );

        $this->add_control(
            'icon_back_active_color',
            [
                'label' => esc_html__('Icon Active Background Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block.socialv-active .socialv-accordion-title .socialv-icon-style' => 'background: {{VALUE}};',
                    

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
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block' => 'border-style: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'border_active_color',
            [
                'label' => esc_html__('Active Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block.socialv-active' => 'border-color: {{VALUE}};',

                ],
                'condition' => [
                    'has_border' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'border_inactive_color',
            [
                'label' => esc_html__('Inactive Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block' => 'border-color: {{VALUE}};',

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
                    '{{WRAPPER}} .socialv-accordion .socialv-accordion-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        if (Plugin::$instance->editor->is_edit_mode()) {
?>
            <script>
                (function(jQuery) {
                    CallAccordion();   
                })(jQuery);
            </script>
<?php
        }
    }
}
