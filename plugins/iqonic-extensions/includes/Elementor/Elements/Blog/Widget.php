<?php

namespace Iqonic\Elementor\Elements\Blog;

use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;

class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_blog';
    }
    public function get_title()
    {
        return esc_html__('Iqonic Blog', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }
    public function get_icon()
    {
        return 'eicon-info-box';
    }
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_test_list',
            [
                'label' => esc_html__('Blogs', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'blog_style',
            [
                'label'      => esc_html__('Grid Columns', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => '3',
                'options'    => [
                    '1'    => esc_html__('Blog 1 Column', IQONIC_EXTENSION_TEXT_DOMAIN),
                    '2'    => esc_html__('Blog 2 Columns', IQONIC_EXTENSION_TEXT_DOMAIN),
                    '3'    => esc_html__('Blog 3 Columns', IQONIC_EXTENSION_TEXT_DOMAIN),
                    '4'    => esc_html__('Blog 4 Columns', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'      => esc_html__('Title Tag', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'default'    => 'h5',
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
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'label_off' => esc_html__( 'Hide', IQONIC_EXTENSION_TEXT_DOMAIN ),
				'return_value' => 'yes',
				'default' => 'yes',
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
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', IQONIC_EXTENSION_TEXT_DOMAIN),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_control',
            [
                'label' => esc_html__('Post Control', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'blog_cat',
            [
                'label' => esc_html__('Category', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT2,
                'return_value' => 'true',
                'multiple' => true,
                'options' => isset($_REQUEST['editor_post_id']) ? iqonic_get_taxonomies('category') : [],
            ]
        );

        $this->add_control(
            'display_thumbnail',
            [
                'label' => esc_html__('Display Thumbnail ?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image',
                'default'   => 'large',
                'separator' => 'none',
                'condition' => ["display_thumbnail", "yes"]
            ]
        );
        $this->add_control(
            'display_excerpt',
            [
                'label' => esc_html__('Display Excerpt ?', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'yes' => esc_html__('yes', IQONIC_EXTENSION_TEXT_DOMAIN),
                'no' => esc_html__('no', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'     => esc_html__('Posts Per Page', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'         => Controls_Manager::NUMBER,
                'label_block'    => false,
                'min'         => -1,
                'max'         => 100,
                'step'         => 1,
                'default'     => 3,
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order By', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'DESC' => esc_html__('Descending', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'ASC' => esc_html__('Ascending', IQONIC_EXTENSION_TEXT_DOMAIN)
                ],
            ]
        );

        $this->end_controls_section();
    
        $this->start_controls_section(
            'section_blog_title',
            [
                'label' => esc_html__('Title', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'blog_title_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .socialv-heading-title',
            ]
        );

        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_Blog_title_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'blog_title_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-heading-title' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_Blog_title_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'blog_title_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .socialv-heading-title:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_category',
            [
                'label' => esc_html__('Category', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_title_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .widget_categories a',
            ]
        );

        $this->start_controls_tabs(
            'style_category_tabs'
        );

        $this->start_controls_tab(
            'style_category_title_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'category_title_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .widget_categories a' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_category_title_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'category_title_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .blog-category a:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_date',
            [
                'label' => esc_html__('Date', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__('Typography', IQONIC_EXTENSION_TEXT_DOMAIN),
                'selector' => '{{WRAPPER}} .entry-date',
            ]
        );

        $this->start_controls_tabs(
            'style_date_tabs'
        );

        $this->start_controls_tab(
            'style_date_normal_tab',
            [
                'label' => esc_html__('Normal', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'date_normal_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .entry-date' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_date_hover_tab',
            [
                'label' => esc_html__('Hover', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );

        $this->add_control(
            'date_hover_color',
            [
                'label' => esc_html__('Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .entry-date:hover' => 'color:{{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function render()
    {
        require 'render.php';
    }
}
