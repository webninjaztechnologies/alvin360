<?php
namespace IR\Admin\Classes;

use IR\Admin\Classes\Settings\General;

class IR_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu()
    {
        add_menu_page(
            esc_html__('Iqonic Reactions', IQONIC_REACTION_TEXT_DOMAIN),
            esc_html__('Iqonic Reactions', IQONIC_REACTION_TEXT_DOMAIN),
            'manage_options',
            '_ir_options',
            '',
            'dashicons-thumbs-up'
        );
    }
}
