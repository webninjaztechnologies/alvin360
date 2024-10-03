<?php

/**
 * The template for displaying the reaction icons and total user reacted for a particular post
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reaction-box/reaction-list.php
 * @version 1.0.0
 */

use IR\Admin\Classes\IR_Database;

defined('ABSPATH') || exit;

function reaction_list($activity_id, $user_id, $is_reacted = false)
{
    $db_obj = new IR_Database();
    $reaction_list = $db_obj->getReactions($activity_id);
    $count = 1;
    $total_count = count($reaction_list);
    if ($reaction_list) {
?>

        <div class="iqonic-meta-details">
            <div class="user-reaction-details">
                <div class="emoji-reaction custom-nav-slider" data-activity_id="<?php echo esc_attr($activity_id) ?>" data-reaction_id="<?php echo esc_attr($user_id); ?>">
                    <div class="liked-member">
                        <ul class="member-thumb-group list-img-group"> <?php
                                                                        foreach ($reaction_list as $key => $value) {
                                                                            if (!isset($value->image_url) ||  empty($value->image_url)) continue;
                                                                            if (isset($reaction_list[$key + 1]->id)) {
                                                                                if ($reaction_list[$key + 1]->id == $value->id) {
                                                                                    continue;
                                                                                } else {
                                                                                    if (isset($reaction_list[$key + 2]->id)) {
                                                                                        if ($reaction_list[$key + 2]->id == $value->id) {
                                                                                            continue;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
                                <li>
                                    <img src="<?php echo esc_url($value->image_url); ?>" alt="<?php echo esc_attr($value->name); ?>">
                                </li> <?php
                                                                            if ($count >= 3) break;
                                                                            else $count++;
                                                                        } ?>
                        </ul>
                    </div>
                </div>

                <span class="total-member">
                    <?php if ($total_count >= 1) {
                        echo esc_html__("Reacted by ", IQONIC_REACTION_TEXT_DOMAIN);
                        foreach ($reaction_list as $value) {
                            $last_user_id = $is_reacted ? $user_id : $value->user_id;
                            if (empty($value->image_url)) continue;

                    ?>
                            <a href="<?php echo esc_url(bp_members_get_user_url($last_user_id)); ?>">
                                <?php echo bp_core_get_user_displayname($last_user_id); ?>
                            </a> <?php
                                    break;
                                }
                            }

                            if ($total_count > 1) {
                                $total_count = $total_count - 1;
                                esc_html_e("And ", IQONIC_REACTION_TEXT_DOMAIN);
                                echo '<span class="other-content"' . 'data-activity_id="' . esc_attr($activity_id) . '">';
                                echo esc_html($total_count);
                                if ($total_count == 1)
                                    esc_html_e(" Other", IQONIC_REACTION_TEXT_DOMAIN);
                                else
                                    esc_html_e(" Others ", IQONIC_REACTION_TEXT_DOMAIN);
                                echo '</span';
                            } ?>
                </span>
            </div>
        </div>
<?php
    }
}

add_action("iqonic-user-reaction-list", "reaction_list", 10, 3);

?>