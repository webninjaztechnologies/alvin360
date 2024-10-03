<?php

namespace Iqonic\Elementor\Elements\Button;

use Elementor\Widget_Base;
use Elementor\Plugin;

if (!defined('ABSPATH')) exit;


class Widget extends Widget_Base
{
    public function get_name()
    {
        return 'iqonic_button';
    }

    public function get_title()
    {
        return esc_html__('Iqonic Button', IQONIC_EXTENSION_TEXT_DOMAIN);
    }
    public function get_categories()
    {
        return ['iqonic-extension'];
    }

    public function get_icon()
    {
        return 'eicon-button';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__('Button', IQONIC_EXTENSION_TEXT_DOMAIN),
            ]
        );
        
        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/button_controls.php';

        $this->end_controls_section();
    }

    protected function render()
    {
        require 'render.php';
    }
}
