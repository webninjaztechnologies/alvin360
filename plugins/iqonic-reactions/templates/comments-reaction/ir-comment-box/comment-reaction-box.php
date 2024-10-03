<?php

use IR\Admin\Classes\IR_Database;

use function SocialV\Utility\socialv;

defined('ABSPATH') || exit;
function comment_reaction_box($activity_id, $user_id, $comment_id)
{
    $db_obj = new IR_Database();
    $reaction_list = $db_obj->getGroupedCommentReaction($activity_id, $comment_id);
    $user_list = $db_obj->getCommentReactionByCommentId($activity_id, $comment_id); ?>

    <div class="ir-reaction-modal ir-comment-box">
        <div class="ir-modal-centered">
            <div class="ir-box">
                <div class="ir-modal-head">
                    <div class="ir-options" data-activity_id="<?php echo esc_attr($activity_id); ?>" data-comment_id="<?php echo esc_attr($comment_id); ?>">
                        <div class="ir-option active" data-reaction_id="all">
                            <span class="ir-option-text"><?php esc_html_e('All', IQONIC_REACTION_TEXT_DOMAIN); ?></span>
                        </div>

                        <div class="item-list-tabs no-ajax reaction-tab-lists">
                            <div class="left" onclick="slide('left',event)"><i class="iconly-Arrow-Left-2 icli"></i></div>
                            <div class="right" onclick="slide('right',event)"><i class="iconly-Arrow-Right-2 icli"></i></div>

                            <div class="reaction-tab-container custom-nav-slider">
                                <ul class="list-inline">
                                    <?php foreach ($reaction_list as $value) { ?>
                                        <div class="ir-option" data-reaction_id="<?php echo esc_attr($value->reaction_id); ?>">
                                            <img class="ir-option-image" src="<?php echo esc_url($value->image_url); ?>" alt="<?php echo esc_attr($value->name); ?>">
                                            <span class="ir-option-text"> <?php echo esc_html($value->reaction_count); ?> </span>
                                        </div>
                                    <?php
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php do_action("ir-box-close") ?>
                </div>

                <div class="ir-modal-body">
                    <div class="ir-reaction-card-item">
                        <div class="ir-reaction-card-wrapper">
                            <?php
                            foreach ($user_list as $value) {
                                if (isset($value->user_id)) { ?>
                                <div class="user-reaction-list">
                                    <div class="meta">
                                        <a class="user-avatar" href="<?php echo esc_url(bp_members_get_user_url($value->user_id)) ?>">
                                        <?php echo bp_core_fetch_avatar(array('item_id' => $value->user_id, 'type' => 'full', 'width' => '50', 'height'  => '50', 'class' => 'avatar rounded-circle photo')); ?>                                        
                                         </a>
                                        <a href="<?php echo esc_url(bp_members_get_user_url($value->user_id)); ?>">
                                            <h6 class="name"> <?php echo bp_core_get_user_displayname($value->user_id) ?>
                                            <?php if (class_exists("BP_Verified_Member"))
                                            echo socialv()->socialv_get_verified_badge($value->user_id);
                                            ?> </h6>
                                            <p class="m-0"><?php echo '@' . bp_members_get_user_slug($value->user_id) ?></p>
                                        </a>
                                    </div>

                                    <div class="user-reaction">
                                        <img src="<?php echo esc_url($value->image_url); ?>" alt="<?php echo esc_attr($value->name['0']); ?>">
                                    </div>
                                </div>
                            <?php
                            }
                         } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

add_action("iqonic-ir-comment-box", "comment_reaction_box", 10, 3);

?>