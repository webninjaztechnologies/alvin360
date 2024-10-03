<?php

/**
 * Report button
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag\Em;

defined('ABSPATH') || exit;

if (!is_user_logged_in()) return;

$classes = $args["classes"];
$id = $args["id"];
$type = $args["type"];
$href = !empty($args["page_id"]) ? get_permalink($args['page_id']) . "?id=$id&type=$type" : "javascript:void(0)";


$is_reported = false;
$button_value = $is_reported ? __("Report limit reached", IQONIC_MODERATION_TEXT_DOMAIN) :  __("Report", IQONIC_MODERATION_TEXT_DOMAIN);

wp_cache_set("report-dependent-script", true, "imt-depedent-scripts");

?>

<div class="imt-report-button-wrap">
    <a href="<?php echo $href; ?>" data-target="imt-report-modal" name="imt_report_button" class="imt-report-button <?php echo esc_attr($classes); ?>" data-toggle="imt-modal" data-id="<?php echo esc_attr($id); ?>" data-type="<?php echo esc_attr($type); ?>">
        <?php echo $button_value; ?>
    </a>
</div>