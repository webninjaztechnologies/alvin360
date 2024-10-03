<?php

use IR\Admin\Classes\IR_Database;

defined("ABSPATH") || exit;

add_action("wp_ajax_iqonic_add_comment_reaction", "iqonic_add_comment_reaction");
add_action("wp_ajax_nopriv_iqonic_add_comment_reaction", "iqonic_add_comment_reaction");
function iqonic_add_comment_reaction()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $comment_user_id = isset($_REQUEST['comment_user_id']) ? $_REQUEST['comment_user_id'] : '';
    $reaction_id = isset($_REQUEST['reaction_id']) ? $_REQUEST['reaction_id'] : '';
    $comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';
    $table_id = isset($_REQUEST['table_id']) ? $_REQUEST['table_id'] : '';

    $args = array(
        'comment_id' => $comment_id,
        'activity_id' => $activity_id,
        'user_id' => $comment_user_id,
        'reaction_id' => $reaction_id,
    );

    $where = array(
        'comment_id' => $comment_id,
        'activity_id' => $activity_id,
        'user_id' => $comment_user_id,
    );

    if (isset($table_id) && !empty($table_id)) {
        $args['id'] = $table_id;
        $where['id'] = $table_id;
    }

    $db_obj = new IR_Database();
    $db_obj->insertCommentReactionActivity($args, $where);

    $result = $db_obj->getCommentReaction($activity_id, $comment_user_id, $comment_id);
    reaction_loop($result, $activity_id, $comment_user_id, $comment_id, false);
    do_action("iqonic-comment-reaction-list", $activity_id, $comment_user_id, $comment_id, true);
    iqonic_set_comment_reaction_notification($activity_id, $comment_id, $comment_user_id);
    die;
}

add_action("wp_ajax_iqonic_delete_comment_reaction_activity", "iqonic_delete_comment_reaction_activity");
add_action("wp_ajax_nopriv_iqonic_delete_comment_reaction_activity", "iqonic_delete_comment_reaction_activity");
function iqonic_delete_comment_reaction_activity()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $comment_user_id = isset($_REQUEST['comment_user_id']) ? $_REQUEST['comment_user_id'] : '';
    $comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';

    $args = array(
        'comment_id'    => $comment_id,
        'activity_id'   => $activity_id,
        'user_id'       => $comment_user_id,
    );

    $db_obj = new IR_Database();
    $delete = $db_obj->deleteCommentReactionActivity($args);

    if ($delete) {
        $result = $db_obj->getCommentReaction($activity_id, $comment_user_id, $comment_id);
        reaction_loop($result, $activity_id, $comment_user_id, $comment_id, true);
        do_action("iqonic-comment-reaction-list", $activity_id, $comment_user_id, $comment_id);
        iqonic_remove_comment_reaction_notification($activity_id, $comment_user_id, $comment_id);
    }
    die;
}

add_action("wp_ajax_iqonic_comment_reaction_box", "iqonic_comment_reaction_box");
add_action("wp_ajax_nopriv_iqonic_comment_reaction_box", "iqonic_comment_reaction_box");
function iqonic_comment_reaction_box()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';
    do_action('iqonic-ir-comment-box', $activity_id, $user_id, $comment_id);
    die;
}

add_action("wp_ajax_iqonic_comment_grouped_reaction", "iqonic_comment_grouped_reaction");
add_action("wp_ajax_nopriv_iqonic_comment_grouped_reaction", "iqonic_comment_grouped_reaction");
function iqonic_comment_grouped_reaction()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $reaction_id = isset($_REQUEST['reaction_id']) ? $_REQUEST['reaction_id'] : '';
    $comment_id = isset($_REQUEST['comment_id']) ? $_REQUEST['comment_id'] : '';

    $db_obj = new IR_Database();
    if ($reaction_id != "all") {
        $result = $db_obj->getCommentReactionByReactionId($activity_id, $reaction_id, $comment_id);
    } else {
        $result = $db_obj->getCommentReactionByCommentId($activity_id, $comment_id);
    }

    echo '<div class="ir-reaction-card-wrapper">';
    foreach ($result as $value) { 
        echo '<div class="user-reaction-list">
                     <div class="meta">
                        <a class="user-avatar" href="'. esc_url(bp_members_get_user_url($value->user_id)).'">';
                             bp_activity_avatar('user_id=' . $value->user_id);
                echo '</a>
                <a href="'. esc_url(bp_members_get_user_url($value->user_id)) .'">
                    <h6 class="name"> '. bp_core_get_user_displayname($value->user_id) .' </h6>
                    <p class="m-0">@' . bp_members_get_user_slug($value->user_id) .'</p>
                </a>
                </div>
                <div class="user-reaction">
                    <img src="'. esc_url($value->image_url) .'" alt="'. esc_attr($value->name['0']) .'">
                </div>
             </div>';

    }
    echo '</div>';
    die;
}

//add comment notifications
function iqonic_set_comment_reaction_notification($activity_id, $comment_id, $user_id = "")
{
    iqonic_add_comment_reaction_notification($activity_id, $comment_id, $user_id);
}

function iqonic_add_comment_reaction_notification($activity_id, $comment_id, $user_id = "")
{
    $comment = new BP_Activity_Activity($comment_id);

    if ($comment) {
        $user_id = !empty($user_id) ? $user_id : get_current_user_id();
        $comment_user_id = $comment->user_id;

        $notify_user = get_user_meta($comment_user_id, "notification_activity_new_like", true);
        if ($notify_user == "no")
            return;

        if ($comment_user_id == $user_id)
            return;

        $notification_args = [
            'user_id'           => $comment_user_id,
            'item_id'           => $comment_id,
            'secondary_item_id' => $user_id,
            'component_name'    => 'iqonic_comment_activity_reaction_notification',
            'component_action'  => 'action_comment_activity_reacted',
            'is_new'            => 1
        ];

        $existing = BP_Notifications_Notification::get($notification_args);

        if (!empty($existing)) {
            BP_Notifications_Notification::delete(array('id' => $existing[0]->id));
        }
        bp_notifications_add_notification(array_merge($notification_args, ['date_notified' => bp_core_current_time()]));
    }
}


//remove comment notifications
function iqonic_remove_comment_reaction_notification($activity_id, $user_id = "", $comment_id = "")
{
    iqonic_delete_comment_reaction_notification($activity_id, $user_id, $comment_id);
}

function iqonic_delete_comment_reaction_notification($activity_id, $comment_id, $user_id = "")
{
    $activity = new BP_Activity_Activity($activity_id);
    if ($activity) {
        $user_id = !empty($user_id) ? $user_id : get_current_user_id();
        $activity_user_id = $activity->user_id;

        $notify_user = get_user_meta($activity_user_id, "notification_activity_new_like", true);
        if ($notify_user == "no")
            return;

        if ($activity_user_id == $user_id)
            return;

        $notification_args = [
            'user_id'           => $activity_user_id,
            'item_id'           => $comment_id,
            'secondary_item_id' => $user_id,
            'component_name'    => 'iqonic_comment_activity_reaction_notification',
            'component_action'  => 'action_comment_activity_reacted',
            'is_new'            => 0
        ];
        $notification_args_new = [
            'user_id'           => $activity_user_id,
            'item_id'           => $comment_id,
            'secondary_item_id' => $user_id,
            'component_name'    => 'iqonic_comment_activity_reaction_notification',
            'component_action'  => 'action_comment_activity_reacted',
            'is_new'            => 1
        ];

        $existing_new = BP_Notifications_Notification::get($notification_args_new);
        $existing_old = BP_Notifications_Notification::get($notification_args);
        
        if (!empty($existing_new))
            BP_Notifications_Notification::delete(array('id' => $existing_new[0]->id));

        if (!empty($existing_old))
            BP_Notifications_Notification::delete(array('id' => $existing_old[0]->id));
    }
}
