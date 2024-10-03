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

$member_id = $args["member-id"];
$current_user_id = get_current_user_id();
if ($current_user_id == $member_id) return;

$is_blocked = imt_is_blocked_by_me($member_id, $current_user_id);
$block_label =  $is_blocked ? __("Unblock", IQONIC_MODERATION_TEXT_DOMAIN) : __("Block", IQONIC_MODERATION_TEXT_DOMAIN);

$classes = isset($args["classes"]) ? $args["classes"] : '';

wp_cache_set("block-dependent-script", true, "imt-depedent-scripts");
?>
<a href="javascript:void(0);" type="submit" name="block_member" class="imt-block-button <?php echo esc_attr($classes); ?> text-capitalize" data-id="<?php echo esc_attr($member_id); ?>" data-blocked="<?php echo $is_blocked; ?>">
    <?php echo esc_html($block_label); ?>
</a>