<?php

/**
 * Iqonic Reactions function
 * 
 * @since 1.0.0
 */

use IR\Admin\Classes\IR_Database;

function default_reactions()
{
    $reactions = [
        'like' => [
            'id' => '1',
            'name' => 'like',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/like.png'
        ],
        'dislike' => [
            'id' => '2',
            'name' => 'dislike',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/dislike.png'
        ],
        'angry' => [
            'id' => '3',
            'name' => 'angry',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/angry.png'
        ],
        'funny' => [
            'id' => '4',
            'name' => 'funny',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/funny.png'
        ],
        'happy' => [
            'id' => '5',
            'name' => 'happy',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/happy.png'
        ],
        'love' => [
            'id' => '6',
            'name' => 'love',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/love.png'
        ],
        'wow' => [
            'id' => '7',
            'name' => 'wow',
            'image_url' => IQONIC_REACTION_URL . 'includes/assets/images/wow.png'
        ],
    ];
    return $reactions;
}

function reaction_loop($result, $activity_id = NULL, $user_id = NULL, $comment_id = NULL, $delete_query = false)
{
    if ($delete_query == false) {
        foreach ($result as $reaction) { 
           echo '<div class="reacted reaction-'. esc_attr($reaction->name).'" data-table_id="'. esc_attr($reaction->table_id) .'" data-activity_id="'. esc_attr($activity_id) .'" data-user_id="'. esc_attr($user_id) .'" data-comment_id="'. esc_attr($comment_id) .'">';
            echo '<img class="reaction-image" src="'. esc_url($reaction->image_url) .'" alt="'. esc_attr($reaction->name) .'">';
            echo  '<span class="reaction-name">'. esc_html($reaction->name) .'</span></div>';

        }
    } else {
        do_action("iqonic-reaction-button", $activity_id, $user_id, $comment_id);
    }
}

function iqonic_is_reaction_plugin_active()
{
    if ((in_array('iqonic-reactions/iqonic-reactions.php', apply_filters('active_plugins', get_option('active_plugins')))))
        return true;

    return false;
}

function set_default_reaction_redux()
{
    $option = get_option("ir_options");
    $default_reaction = 'like';
    $option['default_reaction'] = $default_reaction;
    update_option("ir_options", $option);
}

function active_reaction_list()
{
    $db_obj = new IR_Database();
    $result = $db_obj->getAllReactionsList();

    $reaction = [];
    foreach ($result as $value) {
        $reaction[$value->name] = $value->name;
    }
    return $reaction;
}

add_filter('bp_notifications_get_registered_components', 'iqonic_reaction_notification_component');

function iqonic_reaction_notification_component($component_names = array())
{
    if (!is_array($component_names)) {
        $component_names = array();
    }
    array_push($component_names, 'iqonic_activity_reaction_notification', 'iqonic_comment_activity_reaction_notification');
    return $component_names;
}

//action module for notifications
add_filter('bp_notifications_get_notifications_for_user', 'iqonic_reaction_notifications', 9, 5);
function iqonic_reaction_notifications($action, $item_id, $secondary_item_id, $total_items, $format = 'string', $id = 0)
{
    //user post
    if ('action_activity_reacted' === $action) {
        if (!bp_is_active('activity')) {
            return $action;
        }
        
        $db_obj = new IR_Database();
        $userReaction = $db_obj->getUserReaction($item_id, $secondary_item_id);

        $activity_link  = esc_url(bp_get_activity_directory_permalink() . "p/" . $item_id);
        $user_who_liked  = bp_core_get_user_displayname($secondary_item_id);
        if ($total_items > 1) {
            $user_who_liked .= sprintf(__('And %d more users', IQONIC_REACTION_TEXT_DOMAIN), $total_items);
        }
        $img = isset($userReaction[0])?$userReaction[0]->image_url:"";
        $text = $user_who_liked;
        $text .= esc_html__(' reacted', 'c');
        $text .= "<img src=" . esc_url($img) . " class='iqonic-reactions-notification-img'>";
        $text .= esc_html__('on your post', IQONIC_REACTION_TEXT_DOMAIN);
        $text = "<a href=" . esc_url($activity_link) . ">" . $text . "</a>";
        if (!empty($img)) {
            // WordPress Toolbar.
            if ('string' === $format) {
                return apply_filters('iqonic_reaction_notification_string', '' . $text . '', $text, $activity_link);
            } else {
                return apply_filters('iqonic_reaction_notification_array', array(
                    'text' => $text,
                    'link' => $activity_link
                ), $activity_link, (int) $total_items, $text);
            }
        }
    }

    //comment
    if ('action_comment_activity_reacted' === $action) {
        if (!bp_is_active('activity')) {
            return $action;
        }

        $activity_link = bp_activity_get_permalink($item_id);
        if ((int) $total_items > 1) {
            $activity_link = add_query_arg('type', $action, $activity_link);
        }

        $user_who_liked  = bp_core_get_user_displayname($secondary_item_id);
        if ($total_items > 1) {
            $user_who_liked .= sprintf(__('And %d more users', IQONIC_REACTION_TEXT_DOMAIN), $total_items);
        }

        $text = $user_who_liked;
        $text .= esc_html__(' reacted', IQONIC_REACTION_TEXT_DOMAIN);
        $text .= esc_html__(' on your comment', IQONIC_REACTION_TEXT_DOMAIN);
        $text = "<a href=" . esc_url($activity_link) . ">" . $text . "</a>";

        // WordPress Toolbar.
        if ('string' === $format) {
            return apply_filters('iqonic_reaction_comment_notification_string', '' . $text . '', $text, $activity_link);
        } else {
            return apply_filters('iqonic_reaction_comment_notification_array', array(
                'text' => $text,
                'link' => $activity_link
            ), $activity_link, 1, $text);
        }
    }
    return $action;
}
