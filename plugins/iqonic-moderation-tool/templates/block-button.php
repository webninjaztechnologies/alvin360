<?php

/**
 * Block button
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) return;


$classes = $args["classes"];
$member_id = $args["member-id"];

if (get_current_user_id() == $member_id) return;

$is_blocked = imt_is_blocked_by_me($member_id);
$button_value = $is_blocked ? __("Unblock", IQONIC_MODERATION_TEXT_DOMAIN) :  __("Block", IQONIC_MODERATION_TEXT_DOMAIN);

wp_cache_set("block-dependent-script", true, "imt-depedent-scripts");
?>

<div class="imt-block-button-wrap">
    <a href="javascript:void(0)" name="imt_block_button" class="imt-block-button <?php echo esc_attr($classes); ?>" data-id="<?php echo esc_attr($member_id); ?>" data-blocked="<?php echo $is_blocked; ?>">
        <?php echo $button_value; ?>
    </a>
</div>