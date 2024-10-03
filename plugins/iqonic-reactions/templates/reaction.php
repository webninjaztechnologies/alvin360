<?php
/**
 * The template for displaying the current user reaction
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reaction.php
 * @version 1.0.0
 */

use IR\Admin\Classes\IR_Database;

defined('ABSPATH') || exit;
function get_reaction($activity_id, $user_id) {

    $db_obj = new IR_Database();
    $userReaction = $db_obj->getUserReaction($activity_id, $user_id); 

    echo '<div class="iqonic-reaction">
        <div class="user-reaction">'; 
            if($userReaction) { 
                echo '<div class="reacted reaction-'. esc_attr($userReaction['0']->name).'" data-table_id="'. esc_attr($userReaction['0']->table_id ).'" data-activity_id="'. esc_attr($activity_id).'" data-user_id="'. esc_attr($user_id ) .'">';
                 echo  '<img class="reaction-image" src="'. esc_url($userReaction['0']->image_url).'" alt="'. esc_attr($userReaction['0']->name ).'">';
                   echo '<span class="reaction-name">'. esc_html__($userReaction['0']->name) .'</span>';
                echo '</div>'; 
            } else {
                do_action("iqonic-reaction-button", $activity_id, $user_id);
            } 
        echo '</div>';
         do_action("iqonic-reactions_list", $activity_id, $user_id); 
    echo '</div>'; 
}

add_action("iqonic_reaction","get_reaction", 10, 2);

?>