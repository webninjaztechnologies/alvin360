<?php

namespace Iqonic\Elementor\Elements\Before_After_Image;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return esc_html__('iqonic_before_after_image', IQONIC_EXTENSION_TEXT_DOMAIN);
    }

    public function get_title()
    {
        return esc_html__('Iqonic Before/After image', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-image-before-after';
    }
    protected function register_controls()
    {
        $this->start_controls_section(
            'before_after_img_sec',
            [
                'label' => esc_html__('Image', IQONIC_EXTENSION_TEXT_DOMAIN),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image_before',
            [
                'label' => esc_html__('Choose Image Before', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'image_after',
            [
                'label' => esc_html__('Choose Image After', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'orientation',
            [
                'label'      => esc_html__('Orientation', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type'       => Controls_Manager::SELECT,
                'options'    => [
                    'horizontal'    => esc_html__('Horizontal', IQONIC_EXTENSION_TEXT_DOMAIN),
                    'vertical'      => esc_html__('Vertical', IQONIC_EXTENSION_TEXT_DOMAIN),
                ],
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'handle_color',
            [
                'label' => esc_html__('Handle Color', IQONIC_EXTENSION_TEXT_DOMAIN),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-handle:before,{{WRAPPER}} .twentytwenty-handle:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .twentytwenty-handle:before' => 'box-shadow:0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5)',
                    '{{WRAPPER}} .twentytwenty-handle:after' => 'box-shadow:0 -3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5)',
                    '{{WRAPPER}} .twentytwenty-handle' => 'border:3px solid {{VALUE}};',
                    '{{WRAPPER}} .twentytwenty-handle .twentytwenty-left-arrow' => 'border-right:6px solid {{VALUE}};',
                    '{{WRAPPER}} .twentytwenty-handle .twentytwenty-right-arrow' => 'border-left:6px solid {{VALUE}};',
                    '{{WRAPPER}} .twentytwenty-handle .twentytwenty-up-arrow' => 'border-bottom:6px solid {{VALUE}};',
                    '{{WRAPPER}} .twentytwenty-handle .twentytwenty-down-arrow' => 'border-top:6px solid {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        require 'render.php';
    }
}
