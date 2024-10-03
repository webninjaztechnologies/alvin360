<?php

namespace IMT\Admin\Classes;



class IMT_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'imt_enqueue_admin_script']);
        add_action('edit_user_profile', [$this, 'add_custom_user_profile_fields'], 1001);
        add_action('edit_user_profile_update', [$this, 'save_custom_user_profile_fields']);
    }

    public function add_admin_menu()
    {
        add_menu_page(
            __('Iqonic Moderation', IQONIC_MODERATION_TEXT_DOMAIN),
            "Iqonic Moderation Tool",
            'manage_options',
            'imt-admin-menu',
            [$this, 'imt_admin_menu_render'],
            'dashicons-admin-users'
        );

        $hook = add_submenu_page(
            'imt-admin-menu',
            __('Suspend', IQONIC_MODERATION_TEXT_DOMAIN),
            __('Suspend', IQONIC_MODERATION_TEXT_DOMAIN),
            'manage_options',
            'imt-suspend',
            [$this, "imt_suspended_user_menu"]
        );

        add_submenu_page(
            'imt-admin-menu',
            __('Report Types', IQONIC_MODERATION_TEXT_DOMAIN),
            __('Report Types', IQONIC_MODERATION_TEXT_DOMAIN),
            'manage_options',
            'report-types',
            [$this, "render_report_types_page"]
        );

        add_action("load-" . $hook, [$this, 'add_options']);
    }
    public function render_report_types_page()
    {
        wp_redirect(admin_url("edit-tags.php?taxonomy=report-types&post_type=imt_reports"));
        
    }

    public function imt_admin_menu_render()
    {
        wp_redirect(admin_url("edit.php?post_type=imt_reports"));
    }
    public function imt_suspended_user_menu() {
        if (!class_exists('Link_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    
            $wp_list_table = new IMT_Suspend();
            $search = isset($_GET['s']) ? $_GET['s'] : '';
    
            $wp_list_table->prepare_items($search);
            $wp_list_table->display();
        }
    }    

    function add_options()
    {
        $option = 'per_page';
        $args = array(
            'label' => 'Results',
            'default' => 10,
            'option' => 'users_per_page'
        );
        add_screen_option($option, $args);
    }
    public function imt_enqueue_admin_script($hook)
    {
        $version = IQONIC_MODERATION_TOOL_VERSION;
        $is_post_new_page = $hook == "post-new.php" && isset($_GET['post_type']) && $_GET['post_type'] == "imt_reports";
        $post_id = $hook == isset($_GET['post']) ? $_GET['post'] : "";
        $post_type =  !empty($post_id) ? get_post_type($post_id) : '';
        $is_suspend_user_page = isset($_GET['page']) && $_GET['page'] == "imt-suspend";

        if ('imt_reports' == $post_type || $is_post_new_page || $is_suspend_user_page) {
            wp_enqueue_script('imt-menu', plugin_dir_url(__DIR__) . 'assets/js/admin-moderation.js', array(), $version);
            wp_enqueue_style('imt-report', plugin_dir_url(__DIR__) . 'assets/css/report.css', array(), $version);
        }

        wp_register_script('imt_admin_ajax_url', false);
        wp_localize_script('imt_admin_ajax_url', 'imt_admin_ajax_url_params', array(
            'ajaxUrl'           => admin_url('admin-ajax.php'), // WordPress AJAX
            'suspendAction'     => [
                "action"                => "imt_suspend_member",
                "suspendButtonLabel"    => __("Suspend Member", "iqonic-moderation-toll"),
                "unsuspendButtonLabel"  => __("Unsuspend Member", "iqonic-moderation-toll")
            ],
            'moderateAction'  => [
                "action"                    => "imt_moderate_action",
                "moderateActivityLabel"      => "Moderate Activity",
                "ummoderateActivityLabel"    => "Unmoderate Activity",
                "moderateGroupLabel"        => "Moderate Group",
                "unmoderateGroupLabel"      => "Unmoderate Group"
            ],
        ));
        wp_enqueue_script('imt_admin_ajax_url');
    }

    public function add_custom_user_profile_fields($user)
    {
        if (!is_admin()) return;

        $is_suspended = get_user_meta($user->ID, "imt_suspend_member", true);

        if (!$is_suspended) return;
?>
        <hr style="margin-top:30px;" />
        <h2><?php _e("Moderation Tool", IQONIC_MODERATION_TEXT_DOMAIN); ?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="suspend-reason"><?php _e("Reason for suspending", IQONIC_MODERATION_TEXT_DOMAIN); ?></label></th>
                    <td>
                        <textarea name="suspend-reason" id="suspend-reason" rows="5" cols="30"><?php echo get_user_meta($user->ID, "imt_user_suspending_reason", true); ?></textarea>
                        <p class="suspend-reason-description">
                            <?php _e("You can edit reason of why you are suspending this user", IQONIC_MODERATION_TEXT_DOMAIN); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
    public function save_custom_user_profile_fields($user_id)
    {
        if (!is_admin()) return;

        if (isset($_POST['suspend-reason']))
            update_user_meta($user_id, "imt_user_suspending_reason", trim($_POST['suspend-reason']));
    }
}
