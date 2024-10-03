<?php
defined('ABSPATH') || exit;

use IR\Admin\Classes\IR_Database;

function get_comment_reaction($activity_id, $user_id, $comment_id) {
    $db_obj = new IR_Database();
    $commentReaction = $db_obj->getCommentReaction($activity_id, $user_id, $comment_id);
    ?>

    <div class="iqonic-reaction comment-reaction">
        <div class="user-comment-reaction"> <?php
            if($commentReaction) {
                foreach($commentReaction as $reaction) { ?>
                    <div class="reacted reaction-<?php echo esc_attr($reaction->name); ?>" data-table_id="<?php echo esc_attr($reaction->table_id ); ?>" data-activity_id="<?php echo esc_attr($activity_id); ?>" data-user_id="<?php echo esc_attr($user_id); ?>" data-comment_id="<?php echo esc_attr($comment_id); ?>">
                        <img class="reaction-image" src="<?php echo esc_url($reaction->image_url); ?>" alt="<?php echo esc_attr($reaction->name); ?>">
                        <span class="reaction-name"><?php echo esc_html($reaction->name); ?></span>
                    </div> <?php
                }
            } else {
                do_action("iqonic-reaction-button", $activity_id, $user_id, $comment_id  );
            } ?>
    
        </div> <?php

        do_action("iqonic-reactions_list", $activity_id, $user_id, $comment_id); ?>
    </div>
    <?php
}

add_action("iqonic-comment-reaction",'get_comment_reaction', 10, 4);

?>