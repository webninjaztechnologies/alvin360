<?php
// Register report custom post type
namespace IMT\Admin\Classes;


class IMT_Report
{
    public function init()
    {

        add_action('init', [$this, 'imt_cpt_report']);
        add_action("save_post_imt_reports", [$this, "imt_save_report"], 10, 3);
        add_filter('manage_imt_reports_posts_columns', array($this, 'imt_reports_posts_columns'), 10, 1);
        add_action('manage_imt_reports_posts_custom_column', array($this, 'imt_reports_posts_custom_column'), 10, 2);
    }

    public function imt_cpt_report()
    {
        $this->registerCustomPostType();
        $this->registerTaxonomy();
    }
    private function registerTaxonomy()
    {
        $tax_labels = array(
            'name'               => _x('Report types', 'taxonomy general name', IQONIC_MODERATION_TEXT_DOMAIN),
            'singular_name'      => _x('Report type', 'taxonomy singular name', IQONIC_MODERATION_TEXT_DOMAIN),
            'search_items'       => __('Search Report types', IQONIC_MODERATION_TEXT_DOMAIN),
            'all_items'          => __('All Report types', IQONIC_MODERATION_TEXT_DOMAIN),
            'edit_item'          => __('Edit Report type', IQONIC_MODERATION_TEXT_DOMAIN),
            'update_item'        => __('Update Report type', IQONIC_MODERATION_TEXT_DOMAIN),
            'add_new_item'       => __('Add New Report type', IQONIC_MODERATION_TEXT_DOMAIN),
            'new_item_name'      => __('New Report type Name', IQONIC_MODERATION_TEXT_DOMAIN),
            'not_found'          => __('No Report types found', IQONIC_MODERATION_TEXT_DOMAIN),
            'not_found_in_trash' => __('No Reports types found in Trash', IQONIC_MODERATION_TEXT_DOMAIN),
            'menu_name'          => __('Report types', IQONIC_MODERATION_TEXT_DOMAIN)
        );

        register_taxonomy('report-types', array('imt_reports'), array(
            'hierarchical'          => false,
            'labels'                => $tax_labels,
            'rewrite'               => array('slug' => 'report-types'),
            'show_ui'               => true,
            'publicly_queryable'    => false,
            'show_in_menu'          => 'imt-admin-menu',
        ));
    }
    private function registerCustomPostType()
    {

        $labels = array(
            'name'               => esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'singular_name'      => esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'menu_name'          => esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'name_admin_bar'     => esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'add_new'            => esc_html__('Add New', IQONIC_MODERATION_TEXT_DOMAIN),
            'add_new_item'       => esc_html__('Add New Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'new_item'           => esc_html__('New Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'edit_item'          => esc_html__('Edit Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'view_item'          => esc_html__('View Report', IQONIC_MODERATION_TEXT_DOMAIN),
            'all_items'          => esc_html__('Iqonic Moderation Tool', IQONIC_MODERATION_TEXT_DOMAIN),
            'search_items'       => esc_html__('Search Reports', IQONIC_MODERATION_TEXT_DOMAIN),
            'parent_item_colon'  => esc_html__('Parent Reports:', IQONIC_MODERATION_TEXT_DOMAIN),
            'not_found'          => esc_html__('No Reports found.', IQONIC_MODERATION_TEXT_DOMAIN),
            'not_found_in_trash' => esc_html__('No Reports found in Trash.', IQONIC_MODERATION_TEXT_DOMAIN)
        );

        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'taxonomies'            => array('report-types'),
            'show_in_menu'          => 'imt-admin-menu',
            'rewrite'               => array('slug' => 'imt-reports'),
            'capability_type'       => 'post',
            'has_archive'           => "reports",
            "exclude_from_search"   => false,
            'hierarchical'          => false,
            'menu_position'         => 1,
            'menu_icon'             => '',
            'supports'              => array('title'),
            'map_meta_cap'          => true,
            'register_meta_box_cb'  => [$this, 'imt_report_meta_box_init']
        );

        register_post_type('imt_reports', $args);
    }
    public function imt_report_meta_box_init()
    {
        $get_cur_screen = get_current_screen();
        if ($get_cur_screen->action !== "add") {
            add_meta_box(
                'imt-report-moderation',
                __('Report Moderation', IQONIC_MODERATION_TEXT_DOMAIN),
                [$this, 'imt_report_cpt_moderation_meta_box'],
                'imt_reports',
                'normal'
            );
        }
        add_meta_box(
            'imt-report-details',
            __('Report Details', IQONIC_MODERATION_TEXT_DOMAIN),
            [$this, 'imt_report_cpt_details_meta_box'],
            'imt_reports',
            'normal'
        );
    }
    public function imt_report_cpt_details_meta_box($post)
    {
        wp_nonce_field('report_cpt-details_meta_box', 'report_cpt-details_meta_box_nonce');
        require_once IQONIC_MODERATION_TOOL_PATH . 'includes/Admin/Metabox/report-details-metabox.php';
    }
    public function imt_report_cpt_moderation_meta_box($post) {
        if ($post->imt_admin_created == 1) {
            echo __('Reports created by administrators relate to members, rather than individual items. Therefore moderation is not available.', IQONIC_MODERATION_TEXT_DOMAIN);
            return;
        } elseif (!metadata_exists('post', $post->ID, 'imt_item_id')) {
            echo __('Moderation is not available for reports made prior to Version 3', IQONIC_MODERATION_TEXT_DOMAIN);
            return;
        }
    
        // See if the user is suspended, to set our Suspend User ajax button
        $status = get_user_meta($post->imt_member_reported, 'imt_suspend_member', true);
        $is_suspended = ($status == 0 || empty($status)) ? false : true;
    
        // See if the item this report refers to is moderated, to set our Moderate ajax button
        $option = 'imt_moderated_' . $post->imt_activity_type . '_list';
        $exists = get_option($option);
        $check = ($post->imt_activity_type == "activity") ? $post->imt_item_id : $post->imt_member_reported;
        $moderated = ($exists && in_array($check, $exists)) ? true : false;
    
        echo '<div class="report-moderation-metabox">';
        $count = imt_item_reports_count(get_post_meta($post->ID, 'imt_item_id', true));
        $count_ord = imt_ordinal($count);
    
        // Create Nonce
        $moderation_metabox_nonce = wp_create_nonce('moderation_metabox_nonce');
    
        echo '<p>' . sprintf(__('This is the %s time this item has been reported.', IQONIC_MODERATION_TEXT_DOMAIN), $count_ord) . '</p>';
        echo '<fieldset class="field-wrap">';
        echo '<input id="moderation-metabox-nonce" type="hidden" name="moderation_metabox_nonce" value="' . $moderation_metabox_nonce . '">';
    
        if (in_array($post->imt_activity_type, ['activity', 'member'])) {
            echo '<div class="button ' . ($is_suspended ? 'imt-member-suspended' : '') . '" id="admin-suspend-member" type="submit" data-suspended="' . $is_suspended . '" data-id="' . $post->imt_member_reported . '">'
                . ($is_suspended ? __("Unsuspend Member", IQONIC_MODERATION_TEXT_DOMAIN) : __("Suspend Member", IQONIC_MODERATION_TEXT_DOMAIN)) . '</div> &nbsp';
        }
    
        if ($post->imt_activity_type == 'activity') {
            echo '<div class="button ' . ($moderated ? 'item-moderated' : '') . '" id="imt-moderate" type="submit" data-id="' . $post->imt_item_id . '" data-activity="' . $post->imt_activity_type . '" data-post="' . $post->imt_item_id . '" data-moderated="' . $moderated . '">'
                . ($moderated ? __("Unmoderate Activity", IQONIC_MODERATION_TEXT_DOMAIN) : __("Moderate Activity", IQONIC_MODERATION_TEXT_DOMAIN)) . '</div>';
        }
    
        if ($post->imt_activity_type == 'group') {
            echo '<div class="button ' . ($moderated ? 'item-moderated' : '') . '" id="imt-moderate" type="submit" data-id="' . $post->imt_member_reported . '" data-activity="' . $post->imt_activity_type . '" data-post="' . $post->imt_item_id . '" data-moderated="' . $moderated . '">'
                . ($moderated ? __("Unmoderate Group", IQONIC_MODERATION_TEXT_DOMAIN) : __("Moderate Group", IQONIC_MODERATION_TEXT_DOMAIN)) . '</div>';
        }
    
        echo '</fieldset></div>';
    }
    
    // report admin Column Header
    public function imt_reports_posts_columns($columns)
    {

        $columns = array(
            'cb'                    => $columns['cb'],
            'title'                 => 'Title',
            'taxonomy-report-types' => "Report Types",
            'content-type'          => "Content Type",
            'total-reports'         => 'Total Reports',
            'date'                  => 'Date'
        );
        return $columns;
    }

    //report admin Column Content
    function imt_reports_posts_custom_column($column, $post_id)
    {

        if (sanitize_title('total-reports') === $column) {
            echo imt_item_reports_count(get_post_meta($post_id, 'imt_item_id', true));
        }
        if (sanitize_title('content-type') === $column) {
            echo ucfirst(get_post_meta($post_id, 'imt_activity_type', true));
        }
    }

    public function imt_save_report($post_id, $post, $update)
    {
        // Checks save status
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        // Verify nonce
        $nonce = 'report_cpt-details_meta_box_nonce';
        if (!isset($_POST[$nonce]) || !wp_verify_nonce($_POST[$nonce], 'report_cpt-details_meta_box')) {
            return;
        }
        /* OK, it's safe for us to save the data now. */
        // Sanitize the user input.
        // Update 'is_upheld' and 'imt_admin_created'
        update_post_meta($post_id, 'is_upheld', 1);
        $meta_fields = array(
            'imt_member_reported',
            'imt_reported_by',
            'imt_activity_type',
            'imt_report_substantiated',
        );
        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $field, $value);
            }
        }


        if (isset($_POST['imt_admin_created'])) {
            update_post_meta($post_id, 'imt_admin_created', 1);
        }
    }
}
