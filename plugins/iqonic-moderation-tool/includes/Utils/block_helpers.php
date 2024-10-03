<?php

use IMT\Admin\Classes\IMT_Settings;

function get_imt_config()
{
    return $GLOBALS['imt_config']['Elements'];
}

/**
 * Block member.
 *
 * @param int $blocked_member     Id of the member whom the current member is blocking.
 * @param int $blocked_by       Id of the current member who's blocking.
 * @return true if user blocked, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_block_member($blocked_member, $blocked_by = null)
{
    if ($blocked_by == null) $blocked_by = get_current_user_id();

    if (user_can($blocked_member, 'manage_options')) return false;

    $is_blocked = imt_update_blocked_member_list($blocked_member, $blocked_by);

    if ($is_blocked) {

        $is_friends_remove_friend = apply_filters("imt_is_friends_remove_friend", true);
        if ($is_friends_remove_friend)
            friends_remove_friend($blocked_by, $blocked_member);
    }

    do_action('imt_after_block_member', $is_blocked, $blocked_member, $blocked_by);

    return $is_blocked;
}

/**
 * Update blocked member list.
 *
 * @param int $blocked_member     Id of the member whom the current member is blocking.
 * @param int $blocked_by       Id of the current member who's blocking.
 * @return true if user blocked, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_update_blocked_member_list($blocked_member, $blocked_by = null)
{
    // user block by
    $is_blocked = false;
    if (is_null($blocked_by)) {
        $blocked_by = get_current_user_id();
    }
    
    $block_list = get_user_meta($blocked_by, 'imt_blocked_members', true);
    
    if (!is_array($block_list)) {
        $block_list = [];
    }
    
    if (!in_array($blocked_member, $block_list)) {
        $block_list[] = $blocked_member;
        $is_blocked = update_user_meta($blocked_by, 'imt_blocked_members', $block_list);
    }
    
    do_action('imt_after_update_block_member_list', $block_list, $blocked_member, $blocked_by);
    
    return $is_blocked;
    
}

/**
 * Update member blocked by list.
 *
 * @param int $blocked_member     Id of the member whom the current member is blocking.
 * @param int $blocked_by       Id of the current member who's blocking.
 * @return true if user blocked, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_update_blocked_by_list($blocked_member, $blocked_by = null)
{
    // user who blocked
    if ($blocked_by === null) {
        $blocked_by = get_current_user_id();
    }

    $block_list_by = get_user_meta($blocked_member, "imt_member_blocked_by", true) ?? [];

    if (!in_array($blocked_by, $block_list_by)) {
        $block_list_by[] = $blocked_by;
        $is_updated = update_user_meta($blocked_member, 'imt_member_blocked_by', $block_list_by);
    } else {
        $is_updated = false;
    }

    do_action('imt_after_update_blocked_by_list', $block_list_by, $blocked_member, $blocked_by);

    return $is_updated;
}

/**
 * Unblock member.
 *
 * @param int $blocked_member     Id of the member whom the current member is unblocking.
 * @param int $blocked_by       Id of the current member who's unblocking.
 * @return true if user unblocked, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_unblock_member($blocked_member, $blocked_by = null)
{
    if ($blocked_by == null) $blocked_by = get_current_user_id();

    $is_unblocked = false;
    $block_list = get_user_meta($blocked_by, "imt_blocked_members", true);

    if ($block_list) {

        $blocked_member_index = array_search($blocked_member, $block_list);
        if ($blocked_member_index !== false) {
            unset($block_list[$blocked_member_index]);
            $is_unblocked = update_user_meta($blocked_by, "imt_blocked_members", $block_list);
        }
    }

    if ($is_unblocked) {
        $block_list_by = get_user_meta($blocked_member, "imt_member_blocked_by", true);
        if ($block_list_by) {

            $blocked_member_by = array_search($blocked_by, $block_list_by);
            if ($blocked_member_by !== false) {
                unset($block_list_by[$blocked_member_by]);
                $is_unblocked = update_user_meta($blocked_member, 'imt_member_blocked_by', $block_list_by);
            }
        }
    }

    do_action('imt_after_unblock_members', $block_list, $block_list_by, $blocked_member, $blocked_member);

    return $is_unblocked;
}

/**
 * Get blocked members.
 *
 * @param int $user_id     Id of the member.
 * 
 * @return array           Of blocked member, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_get_blocked_members_ids($user_id = null)
{
    if ($user_id == null) $user_id = get_current_user_id();

    $block_list = get_user_meta($user_id, "imt_blocked_members", true);

    if ($block_list)
        $block_list = apply_filters("imt_blocked_members_list", $block_list);
    else
        $block_list = false;

    return $block_list;
}

/**
 * Get member blocked by list.
 *
 * @param int $user_id     Id of the member.
 * 
 * @return array           Of member blocked by, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_get_members_blocked_by_ids($user_id = null)
{
    if ($user_id == null) $user_id = get_current_user_id();

    $block_list = get_user_meta($user_id, "imt_member_blocked_by", true);

    if ($block_list)
        $block_list = apply_filters("imt_member_blocked_by_list", $block_list);
    else
        $block_list = false;

    return $block_list;
}

function imt_is_blocked_by_me($user_id, $current_user_id = null)
{
    if ($current_user_id == null) $current_user_id = get_current_user_id();

    $blocked_list = imt_get_blocked_members_ids($current_user_id);

    if (!$blocked_list) return false;

    return in_array($user_id, $blocked_list) ? true : false;
}

function imt_is_other_member_blocked_me($user_id, $current_user_id = null)
{
    if ($current_user_id == null) $current_user_id = get_current_user_id();

    $blocked_list = imt_get_members_blocked_by_ids($user_id);

    if (!$blocked_list) return false;

    return in_array($current_user_id, $blocked_list) ? true : false;
}


function imt_get_block_button_template($args = [])
{
    imt_get_template_part("templates/block/block-unblock", "button", $args);
}

add_action("bp_before_member_home_content", "imt_user_profile_if_block");
function imt_user_profile_if_block()
{
    $displayed_user_id = bp_displayed_user_id();
    $current_user_id = get_current_user_id();

    if (!is_user_logged_in() || $displayed_user_id == $current_user_id) return;

    if (imt_is_blocked_by_me($displayed_user_id, $current_user_id)) {
        status_header(404);
        get_template_part(404);
        exit;
    }
    if (imt_is_blocked_by_me($current_user_id, $displayed_user_id)) {
        status_header(404);
        get_template_part(404);
        exit;
    }
}

add_action('wp_ajax_imt_block_unblock_member', 'imt_block_unblock_member');
add_action('wp_ajax_nopriv_imt_block_unblock_member', 'imt_block_unblock_member');

function imt_block_unblock_member()
{
    $member_id = sanitize_text_field($_GET['member_id']);
    $is_blocked = sanitize_text_field($_GET['is_blocked']);
    $current_user_id = get_current_user_id();

    if ($is_blocked) {
        $status = ['status' => imt_unblock_member($member_id, $current_user_id)];
    } else {
        $status = ['status' => imt_block_member($member_id, $current_user_id)];
    }

    wp_send_json($status);
}


add_filter("bp_after_has_members_parse_args", "imt_exclude_blocked_members");
function imt_exclude_blocked_members($args)
{

    if (!is_user_logged_in() || !IMT_Settings::is_block_unblock_enable()) return $args;

    $current_user_id = get_current_user_id();

    $blocked_by_me = imt_get_blocked_members_ids($current_user_id);
    $blocked_me = imt_get_members_blocked_by_ids($current_user_id);

    $exclude = ($blocked_by_me) ? implode(",", $blocked_by_me) : '';
    $exclude .=  ($blocked_me) ? "," . implode(",", $blocked_me) : '';
    $exclude .=  (!empty($args['exclude']) && is_array($args['exclude'])) ? "," . implode(",", $args['exclude']) : $args['exclude'];

    $args['exclude'] = trim($exclude, ",");

    return $args;


    $exclude = '';
    if (!empty($blocked_by_me)) {
        $exclude .= implode(",", $blocked_by_me);
    }
    if (!empty($blocked_me)) {
        $exclude .= ($exclude) ? "," . implode(",", $blocked_me) : implode(",", $blocked_me);
    }
    if (!empty($args['exclude'])) {
        $exclude .= ($exclude) ? "," . implode(",", $args['exclude']) : implode(",", $args['exclude']);
    }

    $args['exclude'] = trim($exclude, ",");

    return $args;
}

add_action("bp_member_header_actions", "imt_member_header_actions", 99);
function imt_member_header_actions()
{
    $user_id = bp_displayed_user_id();
    if (user_can($user_id, 'manage_options')) return;

    if (!is_user_logged_in() || $user_id == get_current_user_id()) return;
    $is_block = IMT_Settings::is_block_unblock_enable();
    $is_report = IMT_Settings::is_report_enable();
    $is_memeber_report = IMT_Settings::is_member_report_enable();

    
    if (!$is_block && !$is_report) return;
?>  
    <div class="dropdown">
        <a class="btn-dropdown  btn moderation-btns" href="javascript:void(0);" role="button" id="context-report-block" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="icon-toggle-dot"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="context-report-block">
            <?php if (shortcode_exists("imt_report_button") && $is_report && $is_memeber_report) : ?>
                <li>
                    <?php echo do_shortcode("[imt_report_button id=$user_id type=member classes='dropdown-item']"); ?>
                </li>
            <?php endif; ?>

            <?php if (shortcode_exists("imt_block_button") && $is_block) : ?>
                <li>
                    <?php echo do_shortcode("[imt_block_button member-id=$user_id classes='dropdown-item']"); ?>
                </li>
            <?php endif; ?>

        </ul>
    </div>
<?php
}

add_action('bp_settings_setup_nav', 'imt_user_block_list_nav');
function imt_user_block_list_nav()
{
    if (!bp_is_active('settings') || !IMT_Settings::is_block_unblock_enable()) {
        return;
    }

    // Determine user to use.
    if (bp_displayed_user_domain()) {
        $user_domain = bp_displayed_user_domain();
    } elseif (bp_loggedin_user_domain()) {
        $user_domain = bp_loggedin_user_domain();
    } else {
        return;
    }

    // Get the settings slug.
    $settings_slug = bp_get_settings_slug();

    bp_core_new_subnav_item(array(
        'name'            => _x('Block List', 'Profile settings sub nav', IQONIC_MODERATION_TEXT_DOMAIN),
        'slug'            => 'block-list',
        'parent_url'      => trailingslashit($user_domain . $settings_slug),
        'parent_slug'     => $settings_slug,
        'screen_function' => 'imt_blocked_list_screen',
        'position'        => 40,
        'user_has_access' => bp_core_can_edit_settings()
    ), 'members');
}
function imt_blocked_list_screen()
{
    add_action('bp_template_content', 'imt_get_blocked_list_template');

    bp_core_load_template(apply_filters('bp_settings_screen_xprofile', '/members/single/settings/profile'));
}
function imt_get_blocked_list_template()
{
    imt_get_template_part("templates/profile/user-block", "list", ["member-id" => bp_displayed_user_id()]);
}

// exclude blocked member notifications.
add_filter('bp_notifications_get_where_conditions', 'imt_notifications_get_where_condition_for_blocked', 10, 2);
function imt_notifications_get_where_condition_for_blocked($where, $args)
{
    $bp = buddypress();
    $user_id = $args['user_id'];

    $blocked_user = imt_get_blocked_members_ids($user_id);

    if (!$blocked_user) return $where;

    $blocked_user = implode(",", $blocked_user);

    $table_name = isset($args['table_name']) ? $args['table_name'] : $bp->notifications->table_name;

    $where['imt_blocked_item_id'] = "item_id NOT IN (select item_id from {$table_name} WHERE user_id={$user_id} AND component_action IN('friendship_accepted','friendship_request') AND item_id IN({$blocked_user}))";

    $where['imt_blocked_secondary_item_id'] = "secondary_item_id NOT IN (select secondary_item_id from {$table_name} WHERE user_id={$user_id} AND component_action IN('action_activity_liked','update_reply','comment_reply','new_at_mention','new_membership_request') AND secondary_item_id IN({$blocked_user}))";

    return $where;
}

add_filter('bp_activity_get_where_conditions', 'imt_exclude_blocked_user_activities');
function imt_exclude_blocked_user_activities($where_conditions)
{

    $user_id = get_current_user_id();
    $user_ids = [];
    $blocked_users = imt_get_blocked_members_ids($user_id);
    $user_blocked_by = imt_get_members_blocked_by_ids($user_id);

    if ($blocked_users) $user_ids = $blocked_users;
    if ($user_blocked_by) $user_ids = array_merge($user_ids, $user_blocked_by);

    if (count($user_ids) > 0) {
        $user_ids = implode(",", array_unique($user_ids));
        $where_conditions["user_not_in"] = "a.user_id NOT IN ($user_ids)";
    }

    return $where_conditions;
}
