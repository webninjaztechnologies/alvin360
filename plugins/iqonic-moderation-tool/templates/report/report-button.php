<?php

/**
 * Report button
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */


defined('ABSPATH') || exit;

if (!is_user_logged_in()) return;

$classes = $args["classes"];
$id = $args["id"];
$type = $args["type"];
$name = $args["name"];
$href = !empty($args["page_id"]) ? get_permalink($args['page_id']) . "?id=$id&type=$type" : "javascript:void(0);";
$is_reported = false;
$button_value = $is_reported ? __("Report limit reached", IQONIC_MODERATION_TEXT_DOMAIN) :  __($name, IQONIC_MODERATION_TEXT_DOMAIN);
wp_cache_set("report-dependent-script", true, "imt-depedent-scripts");

$report_count = imt_item_reports_count($id, get_current_user_id());
if ($report_count == 5) {
    echo '<a href="javascript:void(0);" class="disabled ' . esc_attr($classes) . '">' . $button_value . '</a>';
    return;
}
?>

<div class="imt-report-button-wrap">
    <a href="<?php echo $href; ?>" data-target="imt-report-modal" name="imt_report_button" class="imt-report-button <?php echo esc_attr($classes); ?>" data-toggle="imt-modal" data-id="<?php echo esc_attr($id); ?>" data-type="<?php echo esc_attr($type); ?>">
        <?php echo wp_kses_post($button_value); ?>
        <?php echo (!empty($args['content']) ? wp_kses_post($args['content']) : ''); ?>
    </a>
</div>