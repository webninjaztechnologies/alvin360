<?php

namespace IR\Shortcodes;

class Shortcodes
{
    protected $dependent_scripts, $types;
    public function init()
    {
        $this->dependent_scripts = self::get_dependancy();
    }

    public function ir_dependent_style()
    {
        foreach ($this->dependent_scripts as $key => $script) {
            if (isset($this->dependent_scripts[$key])) {
                if (isset($script["css"]) && !empty($script["css"]))
                    wp_enqueue_style($script["name"], IQONIC_REACTION_URL . 'includes/assets/css/' . $script["css"], array(),  IQONIC_REACTION_VERSION);
            }
        }

        
    }
    public function ir_dependent_scripts(){
        foreach ($this->dependent_scripts as $key => $script) {
            if (isset($this->dependent_scripts[$key])) {
                if (isset($script["js"]) && !empty($script["js"]))
                    wp_enqueue_script($script["name"], IQONIC_REACTION_URL . 'includes/assets/js/' . $script["js"], array(),  IQONIC_REACTION_VERSION);
            }
        }
        
        wp_register_script('ir_reaction_ajax_url', false);
        wp_localize_script('ir_reaction_ajax_url', 'ir_reaction_ajax_params', array(
            'ajaxUrl'           => admin_url('admin-ajax.php'), // WordPress AJAX
        ));
        wp_enqueue_script('ir_reaction_ajax_url');
    }

    public function register_admin_repeater_scripts()
    {
        wp_register_script('iqonic-redux-repeater', IQONIC_REACTION_URL . 'includes/Admin/assets/js/redux-repeater.js', array('jquery'),  IQONIC_REACTION_VERSION, true);
        wp_enqueue_script('iqonic-redux-repeater');
    }

    public function get_dependancy()
    {
        return [
            "reaction-dependent-script" => [
                "name"      => "iqonic-user-reaction",
                "css"       => "reaction.css",
                "js"        => "user-reaction.js",
            ],
            "comment-reaction-script" => [
                "name" => "iqonic-comment-reaction",
                "js"   => "comment-reaction.js",
            ]
        ];
    }
}
