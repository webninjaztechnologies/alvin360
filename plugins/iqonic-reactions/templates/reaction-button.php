<?php

/**
 * The template for displaying the reaction button when no reaction is done
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reaction-button.php
 * @version 1.0.0
 */

use IR\Admin\Classes\IR_Database;

defined('ABSPATH') || exit;

function reaction_button($activity_id, $user_id, $comment_id = NULL)
{
    global $ir_options;
    global $default_reaction;

    if(isset($ir_options['default_reaction']) && !empty($ir_options['default_reaction'])) {
        $reaction_name = $ir_options['default_reaction'];
        
        if (!$default_reaction) {
            $db_obj = new IR_Database();
            $default_reaction = $db_obj->get_default_reaction($reaction_name);
        }
    } 

    echo '<div class="reaction-button" data-activity_id="'. esc_attr($activity_id).'" data-user_id="'. esc_attr($user_id).'" data-reaction_id="'. (isset($default_reaction['0']) ? esc_attr($default_reaction['0']->id) : '') .'" data-comment_id="'. esc_attr($comment_id) .'">';
        
        if (empty($ir_options['default_reaction_image']['url'])) { 
            echo '<i class="icon-thumb"></i>';
    
        } else { 
           echo '<img src="'. esc_url($ir_options['default_reaction_image']['url']).'" alt="'. esc_attr('default-img', IQONIC_REACTION_TEXT_DOMAIN) .'" class="iqonic-reaction-default">';
        
        } 
        echo '<span class="reaction-text">';
          
            if (isset($default_reaction[0]->name)) {
                echo esc_html($default_reaction[0]->name);
            } else {
                echo esc_html__("Like", IQONIC_REACTION_TEXT_DOMAIN);
            }
        echo '</span></div>';

}

add_action("iqonic-reaction-button", "reaction_button", 10, 3); ?>