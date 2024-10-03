<?php

use IR\Admin\Classes\IR_Database;

defined("ABSPATH") || exit;

add_action("wp_ajax_iqonic_add_reaction_meta", "iqonic_add_reaction_meta");
add_action("wp_ajax_nopriv_iqonic_add_reaction_meta", "iqonic_add_reaction_meta");
function iqonic_add_reaction_meta()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $reaction_id = isset($_REQUEST['reaction_id']) ? $_REQUEST['reaction_id'] : '';
    $table_id = isset($_REQUEST['table_id']) ? $_REQUEST['table_id'] : '';

    $args = array(
        'reaction_id' => $reaction_id,
        'activity_id' => $activity_id,
        'user_id' => $user_id,
    );

    $where = array(
        'activity_id' => $activity_id,
        'user_id' => $user_id,
    );

    $db_obj = new IR_Database();

    if (!empty($table_id)) {
        $args['id'] = $table_id;
        $where['id'] = $table_id;
    }

    $db_obj->insertReactionActivity($args, $where);

    $result = $db_obj->getUserReaction($activity_id, $user_id);
    reaction_loop($result, $activity_id, $user_id, false);
    do_action("iqonic-user-reaction-list", $activity_id, $user_id, true);
    iqonic_set_user_reaction_notification($activity_id, $user_id);
    die;
}

add_action("wp_ajax_iqonic_delete_reaction_activity", "iqonic_delete_reaction_activity");
add_action("wp_ajax_nopriv_iqonic_delete_reaction_activity", "iqonic_delete_reaction_activity");
function iqonic_delete_reaction_activity()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

    $args = [
        'activity_id' => $activity_id,
        'user_id' => $user_id,
    ];

    $db_obj = new IR_Database();
    $result = $db_obj->deleteUserReactions($args);
    reaction_loop($result, $activity_id, $user_id, NULL, true);
    do_action("iqonic-user-reaction-list", $activity_id, $user_id);
    iqonic_remove_user_reaction_notification($activity_id, $user_id);
    die;
}

add_action("wp_ajax_iqonic_get_reaction_box_data", "iqonic_get_reaction_box_data");
add_action("wp_ajax_nopriv_iqonic_get_reaction_box_data", "iqonic_get_reaction_box_data");
function iqonic_get_reaction_box_data()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

    do_action('iqonic-reaction-box', $activity_id, $user_id);
    die;
}

add_action("wp_ajax_iqonic_get_grouped_reaction", "iqonic_get_grouped_reaction");
add_action("wp_ajax_nopriv_iqonic_get_grouped_reaction", "iqonic_get_grouped_reaction");
function iqonic_get_grouped_reaction()
{
    $activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : '';
    $reaction_id = isset($_REQUEST['reaction_id']) ? $_REQUEST['reaction_id'] : '';

    $db_obj = new IR_Database();
    if ($reaction_id != "all") {
        $result = $db_obj->getReactionByReactionId($activity_id, $reaction_id);
    } else {
        $result = $db_obj->getReactions($activity_id);
    }

    echo '<div class="ir-reaction-card-wrapper">';
    foreach ($result as $value) { ?>
        <div class="user-reaction-list">
            <div class="meta">
                <a class="user-avatar" href="<?php echo esc_url(bp_members_get_user_url($value->user_id)) ?>">
                    <?php bp_activity_avatar('user_id=' . $value->user_id) ?>
                </a>
                <a href="<?php echo esc_url(bp_members_get_user_url($value->user_id)); ?>">
                    <h6 class="name"> <?php echo bp_core_get_user_displayname($value->user_id) ?> </h6>
                    <p class="m-0"><?php echo '@' . bp_members_get_user_slug($value->user_id) ?></p>
                </a>
            </div>

            <div class="user-reaction">
                <img src="<?php echo esc_url($value->image_url); ?>" alt="<?php echo esc_attr($value->name['0']); ?>">
            </div>
        </div>
<?php
    }
    echo '</div>';
    die;
}

//delte all reaction id for activity and comment
add_action("wp_ajax_iqonic_delete_reaction_from_database", "iqonic_delete_reaction_from_database");
add_action("wp_ajax_nopriv_iqonic_delete_reaction_from_database", "iqonic_delete_reaction_from_database");

function iqonic_delete_reaction_from_database()
{
    $reaction_name = $_POST['reaction_name'];
    if (!isset($reaction_name) && empty($reaction_name)) return;

    $db_obj = new IR_Database();
    $db_obj->deleteActivityReactionByName($reaction_name);
    $db_obj->deleteCommentReactionByName($reaction_name);
    die;
}

//delte reaction from reaction list
add_action("wp_ajax_iqonic_delete_reaction_from_reaction_list", "iqonic_delete_reaction_from_reaction_list");
add_action("wp_ajax_nopriv_iqonic_delete_reaction_from_reaction_list", "iqonic_delete_reaction_from_reaction_list");

function iqonic_delete_reaction_from_reaction_list()
{
    $reaction_name = $_POST['reaction_name'];
    if (!isset($reaction_name) && empty($reaction_name)) return;

    $db_obj = new IR_Database();
    $db_obj->deleteReactionFromList($reaction_name);

    $ir_options = get_option('ir_options');
    $redux_reaction_name = $ir_options['reaction_name'];
    $redux_reaction_image = $ir_options['reaction_image'];

    $redux_reaction = array_map(
        function ($redux_reaction_name, $redux_reaction_image) {
            return array_combine(
                ['name', 'image_url'],
                [$redux_reaction_name, $redux_reaction_image]
            );
        },
        $redux_reaction_name,
        $redux_reaction_image
    );

    $options = array();

    foreach ($redux_reaction as $key => $value) {
        $redux_reaction_name = $value['name'];
        $redux_reaction_image = $value['image_url']['url'];

        if ($redux_reaction_name != $reaction_name) {
            $options['reactions_field']['redux_repeater_data'][$key]['title'] = " ";
            $options['reaction_name'][$key] = $value['name'];
            $options['reaction_image'][$key]['url'] = $value['image_url']['url'];
            $options['reaction_image'][$key]['thumbnail'] = $value['image_url']['thumbnail'];
        }
    }

    update_option('ir_options', $options);
    die;
}

//delte reaction from reaction list
add_action("wp_ajax_iqonic_update_reaction_list", "iqonic_update_reaction_list");
add_action("wp_ajax_nopriv_iqonic_update_reaction_list", "iqonic_update_reaction_list");

function iqonic_update_reaction_list()
{
    $reaction_name = $_POST['reaction_name'];
    $reaction_image = $_POST['reaction_image'];

    if (!isset($reaction_name) && empty($reaction_name)) return;
    if (!isset($reaction_image) && empty($reaction_image)) return;

    $db_obj = new IR_Database();
    $db_obj->update_reaction_list($reaction_name, $reaction_image);
    die;
}

//user posts notifications
function iqonic_set_user_reaction_notification($activity_id, $user_id = "")
{
    iqonic_add_user_reaction_notification($activity_id, $user_id);
}

function iqonic_add_user_reaction_notification($activity_id, $user_id = "")
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
            'item_id'           => $activity_id,
            'secondary_item_id' => $user_id,
            'component_name'    => 'iqonic_activity_reaction_notification',
            'component_action'  => 'action_activity_reacted',
            'is_new'            => 1
        ];

        $existing = BP_Notifications_Notification::get($notification_args);

        if (!empty($existing)) {
            BP_Notifications_Notification::delete(array('id' => $existing[0]->id));
        }
        bp_notifications_add_notification(array_merge($notification_args, ['date_notified' => bp_core_current_time()]));
    }
}

//user posts notifications
function iqonic_remove_user_reaction_notification($activity_id, $user_id = "")
{
    iqonic_delete_user_reaction_notification($activity_id, $user_id);
}

function iqonic_delete_user_reaction_notification($activity_id, $user_id = "")
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
            'item_id'           => $activity_id,
            'secondary_item_id' => $user_id,
            'component_name'    => 'iqonic_activity_reaction_notification',
            'component_action'  => 'action_activity_reacted',
            'is_new'            => 1
        ];

        $existing = BP_Notifications_Notification::get($notification_args);

        if (!empty($existing)) {
            BP_Notifications_Notification::delete(array('id' => $existing[0]->id));
        }
    }
}

//delte reaction notifications
add_action("wp_ajax_iqonic_delete_reaction_notification", "iqonic_delete_reaction_notification");
add_action("wp_ajax_nopriv_iqonic_delete_reaction_notification", "iqonic_delete_reaction_notification");

function iqonic_delete_reaction_notification()
{
    $reaction_name = $_POST['reaction_name'];

    if (!isset($reaction_name) && empty($reaction_name)) return;

    $db_obj = new IR_Database();
    $db_obj->delete_activity_reaction_notification($reaction_name);
    $db_obj->delete_comment_reaction_notification($reaction_name);
    die;
}
?>