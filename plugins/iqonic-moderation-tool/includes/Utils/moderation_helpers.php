<?php

use IMT\Admin\Classes\IMT_Settings;

add_action('wp_ajax_imt_suspend_member', 'imt_admin_suspend_member');
add_action('wp_ajax_nopriv_imt_suspend_member', 'imt_admin_suspend_member');

function imt_admin_suspend_member()
{
    $member_id = sanitize_text_field($_GET['member_id']);
    $is_suspended = sanitize_text_field($_GET['is_suspended']);

    if ($is_suspended) {
        $suspended = imt_unsuspend_member($member_id);
        $status = ['status' => !in_array("suspended", $suspended), "type" => "unsuspend"];
    } else {
        $suspended = imt_suspend_member($member_id);
        $status = ['status' => in_array("suspended", $suspended), "type" => "suspend"];
    }

    wp_send_json($status);
}

function imt_suspend_member($member_id)
{
    if (empty($member_id)) return;
    $member_id = (int) $member_id;

    $user = new WP_User($member_id);
    update_user_meta($member_id, 'imt_suspend_member', true);
    $user->add_role('suspended');
    return $user->roles;
}

function imt_unsuspend_member($member_id)
{
    if (empty($member_id)) return;
    $member_id = (int) $member_id;
    $user = new WP_User($member_id);
    $user->remove_role('suspended');
    update_user_meta($member_id, 'imt_suspend_member', false);
    return $user->roles;
}

add_action('wp_ajax_imt_moderate_action', 'imt_remove_reported_activity');
add_action('wp_ajax_nopriv_imt_moderate_action', 'imt_remove_reported_activity');
function imt_remove_reported_activity()
{
    $id = (int) sanitize_text_field($_GET['id']);
    $is_moderated = sanitize_text_field($_GET['is_moderated']);
    $type = sanitize_text_field($_GET['type']);

    $option = 'imt_moderated_' . $type . '_list';
    $exists = get_option($option);
    if ($is_moderated) {

        $key = array_search($id, $exists);
        $if_zero = $key == 0 && !empty($exists[$key]);
        if ($key || $if_zero) {
            unset($exists[$key]);
        }

        update_option($option, $exists);

        if ($type == "activity")
            imt_unmoderate_activity($id);

        wp_send_json(["status" => true, "type" => "unmoderated"]);
    } else {
        if ($exists) {
            $exists = array_merge($exists, [$id]);
        } else {
            $exists = [$id];
        }
        update_option($option, $exists);

        if ($type == "activity")
            imt_moderate_activity($id);

        wp_send_json(["status" => true, "type" => "moderated"]);
    }
    wp_send_json(["status" => false]);
    die;
}
function imt_unmoderate_activity($id)
{
    $activity = new BP_Activity_Activity($id);
    if (empty($activity->id))
        return false;

    // Mark as spam.
    bp_activity_mark_as_ham($activity);

    $activity->save();
}
function imt_moderate_activity($id)
{
    $activity = new BP_Activity_Activity($id);
    if (empty($activity->id))
        return false;

    // Mark as spam.
    bp_activity_mark_as_spam($activity);

    $activity->save();
}

add_filter('authenticate', 'restrict_media_role_authenticate', 99);
function restrict_media_role_authenticate($user)
{
    if (isset($user->roles) && in_array('suspended', (array) $user->roles)) {
        $message = __("Access denied, You are currently suspended.", IQONIC_MODERATION_TEXT_DOMAIN);
        return new WP_Error('user_suspended', apply_filters("imt_suspended_user_authentication_message", $message));
    }
    return $user;
}

add_action("init", "imt_if_suspended_member");
function imt_if_suspended_member()
{
    global $current_user;
    $user_roles = $current_user->roles;

    if (is_admin()) return;

    if (in_array("suspended", $user_roles)) {
        wp_logout();
        exit();
    }
}

add_action("bp_before_group_home_content", "imt_group_single_if_moderated");
function imt_group_single_if_moderated()
{
    global $imt_settings;
    $group_id = bp_get_group_id();
    $moderated_groups = imt_get_moderated_list_by_type("group");

    if (!empty($moderated_groups) && in_array($group_id, $moderated_groups)) {
        if (isset($imt_settings['moderated_group_page'])) {
            wp_redirect(get_permalink($imt_settings['moderated_group_page']));
            exit;
        } else {
            status_header(404);
            get_template_part(404);
        }
    }
}

add_filter("bp_after_has_groups_parse_args", "imt_exclude_moderated_groups");
function imt_exclude_moderated_groups($args)
{
    $groups = imt_get_moderated_list_by_type("group");
    $exclude = ($groups) ? implode(",", $groups) : '';
    $exclude .=  (!empty($args['exclude']) && is_array($args['exclude'])) ? "," . implode(",", $args['exclude']) : $args['exclude'];

    $args['exclude'] = trim($exclude, ",");
    $args['exclude'] = trim($exclude, ",");
    return $args;
}

function imt_get_moderated_list_by_type($type)
{
    $key = "imt_moderated_" . $type . "_list";
    return get_option($key);
}

add_action("bp_group_header_actions", "imt_group_header_actions");
function imt_group_header_actions()
{
    global $imt_settings;

    if (!IMT_Settings::is_report_enable()) return false;

    if (isset($imt_settings['is_group_report_feature']) && ($imt_settings['is_group_report_feature'] === 'enable')) {
        $group_id = bp_get_group_id();

        if (!is_user_logged_in() || !imt_can_report($group_id, "group")) return;

        echo '<div class="dropdown">
        <a class="btn-dropdown  btn moderation-btns" href="javascript:void(0);" role="button" id="context-report-block" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="icon-toggle-dot"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="context-report-block">';
        if (shortcode_exists("imt_report_button")) :
            echo '<li>
                    ' . do_shortcode("[imt_report_button id=$group_id type=group classes='dropdown-item']") . '
                </li>';
        endif;
        echo '</ul></div>';
    }
}
