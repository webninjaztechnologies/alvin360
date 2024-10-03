<?php

namespace Iqonic\Elementor\Elements\Testimonial;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_testimonial';
    }

    public function get_title()
    {
        return esc_html__('Iqonic Testimonial', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return ' eicon-testimonial';
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section',
            [
                'label' => esc_html__('Iqonic Testimonial', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        //repeater for static slider
        $repeater = new Repeater();

        $repeater->add_control(
            'testi_title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Floyd Miles', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testi_designation',
            [
                'label' => esc_html__('Designation', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('UX Engineer', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testi_company',
            [
                'label' => esc_html__('Company name', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Iqonic', IQONIC_EXTENSION_TEXT_DOMAIN),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'placeholder' => esc_html__('Enter Title Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'default' => esc_html__('Egestas nunc, elementum ut consectetur faucibus vulputate. Massa purus feugiat massa vivamus viverra senectus.', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $repeater->add_control(
            'testi_image',
            [
                'label' => esc_html__('Choose Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'testi_list',
            [
                'label' => esc_html__('Testimonial List', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testi_title' => esc_html__('Floyd Miles', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'testi_designation' => esc_html__('Web Developer', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description' => esc_html__('Egestas nunc, elementum ut consectetur faucibus vulputate. Massa purus feugiat massa vivamus viverra senectus.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                    [
                        'testi_title' => esc_html__('Richard Villiom', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'testi_designation' => esc_html__('UX Engineer', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description' => esc_html__('Egestas nunc, elementum ut consectetur faucibus vulputate. Massa purus feugiat massa vivamus viverra senectus.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                    [
                        'testi_title' => esc_html__('Denver Mark', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'testi_designation' => esc_html__('Software Engineer', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'description' => esc_html__('Egestas nunc, elementum ut consectetur faucibus vulputate. Massa purus feugiat massa vivamus viverra senectus.', IQONIC_EXTENSION_TEXT_DOMAIN),
                    ],
                ],
                'title_field' => '{{{ testi_title }}}',
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

        $this->end_controls_section();

        $this->start_controls_section(
            'swiper_control_section',
            [
                'label' => esc_html__('Slider Controls', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

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
            'want_scrollbar',
            [
                'label' => esc_html__('Show Scrollbar ?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'false',
                'options' => [
                    'true'  => esc_html__('True', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'false' => esc_html__('False', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
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
                'selectors' => ['
                    {{WRAPPER}} .swiper-button-prev .text-btn-line-holder .text-btn-line-top, 
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
                'selectors' => ['
                    {{WRAPPER}} .iqonic-navigation .swiper-button-prev,
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
                    '{{WRAPPER}} .iqonic-navigation:hover .swiper-button-prev ,{{WRAPPER}} .iqonic-navigation:hover .swiper-button-next' => 'border-color:{{VALUE}};'
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonial_title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'testimonial_title_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-testi-title',
                //.socialv-testi-title ',
                
            ]
        );

        $this->start_controls_tabs('style_title_tabs');

        $this->start_controls_tab(
            'style_title_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testi-title' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_title_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testi-title:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonial_designation',
            [
                'label' => esc_html__('Designation', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'testimonial_designation_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-testi-designation ',
            ]
        );

        $this->start_controls_tabs('style_designation_tabs');

        $this->start_controls_tab(
            'style_designation_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'designation_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testi-designation' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_designation_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'designation_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testi-designation:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonial_description',
            [
                'label' => esc_html__('Description', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'testimonial_description_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .testimonial-message',
            ]
        );

        $this->start_controls_tabs('style_description_tabs');

        $this->start_controls_tab(
            'style_description_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testimonial-user' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_description_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'description_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-testimonial-user:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonial_options',
            [
                'label' => esc_html__('General Options', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('style_testimonial_bg_tabs');
        $this->start_controls_tab(
            'style_testimonial_bg_title_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'port_normal_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-testimonial',
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'style_testimonial_bg_title_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'port_hover_background',
                'label' => esc_html__('Background', IQONIC_EXTENSION_TEXT_DOMAIN),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .socialv-testimonial:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'testimonial_margin',
            [
                'label' => esc_html__('Margin', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-testimonial' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'testimonial_padding',
            [
                'label' => esc_html__('Padding', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .socialv-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    ThumbSwiper();
                })(jQuery);
            </script>
<?php
        }
    }
}
