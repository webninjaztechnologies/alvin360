<?php

/**
 * Block button
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */
defined('ABSPATH') || exit;
$report_types = imt_get_report_types();
?>
<div class="report-form-wrap">
    <form class="report-form" id="imt-report-from" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label class="report-types"><?php _e("Report Types", IQONIC_MODERATION_TEXT_DOMAIN); ?></label>
        <select class="select-report-types" name="report_type">
            <?php if ($report_types) : ?>
                <?php foreach ($report_types as $key => $value) : ?>
                    <option value="<?php echo esc_html($key); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <textarea class="report-details" name="report_details" cols="30" rows="10"></textarea>
        <input type="hidden" value="" name="id" id="type-id" />
        <input type="hidden" value="" id="activity-type" name="activity_type" />
        <?php wp_nonce_field('verify_imt_submit_report_nonce', 'imt_submit_report_nonce'); ?>

        <button type="submit" id="imt-submit-report" class="imt-submit-report">
            <?php _e("Submit", IQONIC_MODERATION_TEXT_DOMAIN); ?>
        </button>
    </form>
</div>