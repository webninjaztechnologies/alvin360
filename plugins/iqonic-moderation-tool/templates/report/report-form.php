<?php

/**
 * Block button
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag\Em;

defined('ABSPATH') || exit;
$report_types = imt_get_report_types();
?>
<div class="report-form-wrap">
    <form class="report-form" id="imt-report-from" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h5 class="report-types"><?php _e("Report Types", IQONIC_MODERATION_TEXT_DOMAIN); ?></h5>
        <ul class="select-report-types list-inline">
            <?php if ($report_types) : ?>
                <?php $selected = "selected"; ?>
                <?php foreach ($report_types as $key => $value) : ?>
                    <li>
                        <input type="radio" class="report-values" name="report_type" id="<?php echo esc_html($key); ?>" value="<?php echo esc_html($key); ?>" <?php echo $selected; ?>>
                        <label for="<?php echo esc_html($key); ?>" class="report-type-lable"> <?php echo esc_html($value); ?></label>
                    </li>
                    <?php if (!empty($selected)) $selected = ""; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <textarea class="report-details" name="report_details" cols="30" rows="10" placeholder="<?php esc_attr_e("Report details(Optional)", IQONIC_MODERATION_TEXT_DOMAIN); ?>"></textarea>
        <input type="hidden" value="" name="id" id="type-id" />
        <input type="hidden" value="" id="activity-type" name="activity_type" />
        <?php wp_nonce_field('verify_imt_submit_report_nonce', 'imt_submit_report_nonce'); ?>

        <button type="submit" id="imt-submit-report" class="imt-submit-report btn imt-btn-success socialv-btn-success" data-message="<?php esc_attr_e('Reported Successfully.',IQONIC_MODERATION_TEXT_DOMAIN); ?>">
            <?php _e("Submit", IQONIC_MODERATION_TEXT_DOMAIN); ?>
        </button>
    </form>
</div>