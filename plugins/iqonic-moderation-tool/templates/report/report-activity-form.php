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
<div class="report-activity-wrap">
    <div class="report-head">
        <h4><?php _e("Report Activity", IQONIC_MODERATION_TEXT_DOMAIN); ?></h4>
    </div>
    <div class="report-body">
        <form class="report-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <label class="report-types"><?php _e("Report Types", IQONIC_MODERATION_TEXT_DOMAIN); ?></label>
            <select class="select-report-types" name="report_type">
                <?php if ($report_types) : ?>
                    <?php foreach ($report_types as $key => $value) : ?>
                        <option value="<?php echo esc_html($key); ?>"><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <textarea class="report-details" name="report_details" cols="30" rows="10"></textarea>
            <input type="hidden" value="activity" name="activity-type" />
            <button type="submit" class="submit-report"><?php _e("Submit", "iqonic_moderation-tool"); ?></button>
        </form>
    </div>
</div>