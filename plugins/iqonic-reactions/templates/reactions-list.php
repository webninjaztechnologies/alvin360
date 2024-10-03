<?php
/**
 * The template for displaying all the reactions list
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reactions-list.php
 * @version 1.0.0
 */

use IR\Admin\Classes\IR_Database;

defined('ABSPATH') || exit;

function get_reactions_list($activity_id, $user_id, $comment_id = NULL) {
    static $reaction_names;

    if (!isset($reaction_names)) {
        $db_obj = new IR_Database();
        $reaction_names = $db_obj->getAllReactionsList();
    }

    echo '<div class="reaction-wrapper" data-activity_id="' . esc_attr($activity_id) . '" data-user_id="' . esc_attr($user_id) . '" data-comment_id="' . esc_attr($comment_id) . '">'; 
    foreach ($reaction_names as $value) {
        if (isset($value->image_url) && !empty($value->image_url)) {
            echo '<div class="reaction-option reaction-' . esc_attr($value->name) . '" data-reaction_id="' . esc_attr($value->id) . '" data-title="' . esc_attr($value->name) . '">';                   
            echo '<img class="reaction-image" src="' . esc_url($value->image_url) . '" alt="' . esc_attr($value->name) . '">';
            echo '<span class="reaction-name">' . esc_html($value->name) . '</span></div>'; 
        }
    } 
    echo '</div>'; 
}

add_action("iqonic-reactions_list" , "get_reactions_list",10, 3);
?>