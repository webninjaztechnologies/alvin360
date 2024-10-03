<?php

namespace Elementor;
if (!class_exists('PMPro_Membership_Level')) {
    return;
}
global $pmpro_currency_symbol, $pmpro_currencies, $pmpro_currency, $current_user;

$settings = $this->get_settings();
$repeater = $settings['tabs'];
$level = pmpro_getLevel($settings['pmp_pricing_plan_id']);
if (empty($level)) return;
$period = $level->cycle_period;
$period_number = $level->cycle_number;
if ($period_number == 0 && $period == "Day") {
    $period_text = esc_html__('Month', IQONIC_EXTENSION_TEXT_DOMAIN);
} else {
    $period_text = $period_number . ' ' . $period;
}

if (!empty($pmpro_currencies[$pmpro_currency]) && is_array($pmpro_currencies[$pmpro_currency])) {
    if (isset($pmpro_currencies[$pmpro_currency]['symbol'])) {
        $pmpro_currency_symbol = $pmpro_currencies[$pmpro_currency]['symbol'];
    } else {
        $pmpro_currency_symbol = '';
    }
}

$initial_payment = $level->initial_payment;
$billing_amount = $level->billing_amount;

$expiration_number = $level->expiration_number;
$expiration_period = $level->expiration_period;

$user_level = pmpro_getSpecificMembershipLevelForUser($current_user->ID, $level->id);
$has_level = !empty($user_level);
if ($has_level) {
    //if it's a one-time-payment level, offer a link to renew
    if (pmpro_isLevelExpiringSoon($user_level) && $level->allow_signups) {
        $pmpro_checkout_page_link = esc_url(pmpro_url("checkout", "?level=" . $level->id, "https"));
        if ($settings['discount_code'] && function_exists('pmpro_checkDiscountCode') && pmpro_checkDiscountCode($settings['discount_code'])) {
            $pmpro_checkout_page_link .= '&discount_code=' . $settings['discount_code'];
        }
    } else {
        $pmpro_checkout_page_link = esc_url(pmpro_url("account"));
        $account_page_text = esc_html($settings['account_exists']);
        $active_plan = esc_html__("active", IQONIC_EXTENSION_TEXT_DOMAIN);
    }
} else {
    $pmpro_checkout_page_link = esc_url(pmpro_url("checkout", "?level=" . $level->id, "https"));
    if ($settings['show_discount_code'] == "yes" && !empty($settings['discount_code']) && function_exists('pmpro_checkDiscountCode') && pmpro_checkDiscountCode($settings['discount_code'])) {
        $pmpro_checkout_page_link .= '&discount_code=' . $settings['discount_code'];
    }
} ?>

<div class="socialv-pmp-pricing-plans-wrapper" id="pmpro_levels_table">
    <?php if ($settings['show_discount_banner'] == "yes" && !empty($settings['discount_text'])) { ?>
        <div class="pricing-plan-discount">
            <span class="plan-offer"> <?php echo esc_html($settings['discount_text']); ?> </span>
        </div>
    <?php } ?>

    <div class="pricing-plan-header">
        <div class="plan-wrapper">
            <h4 class="plan-name"><?php echo esc_html($level->name) ?></h4>

            <?php if (!empty($settings['image']['url'])) {
                echo '<img class="img-fluid" src="' . esc_url($settings['image']['url']) . '" alt="' . esc_attr__('Image', IQONIC_EXTENSION_TEXT_DOMAIN) . '" loading="lazy">';
            } ?>
        </div>
        <div class="plan-meta-details">
            <div class="plan-period">
                <?php if ($settings['show_sale_price'] == "yes") { ?>
                    <span class="sale-price">
                        <?php echo esc_html($pmpro_currency_symbol);
                        echo esc_html($settings['sale_price']); ?>
                    </span>
                <?php } ?>

                <span class="main-price">
                    <?php echo esc_html($pmpro_currency_symbol);
                    echo esc_html($initial_payment); ?>
                </span>
                <?php

                if ($billing_amount !== $initial_payment) {
                    echo '<span class="socialv-billing_amount">';
                    esc_html_e('Now and ', IQONIC_EXTENSION_TEXT_DOMAIN);
                    echo esc_html($pmpro_currency_symbol);
                    echo esc_html($billing_amount);
                    echo '</span>';
                } ?>

                <span class="plan-date-pack">/ <?php echo esc_html($period_text); ?></span>

            </div>

            <?php
            if (function_exists('pmprosd_getDelay')) {
                $trial_period = (int)pmprosd_getDelay($settings['pmp_pricing_plan_id']);
                if (!empty($trial_period)) { ?>
                    <div class="trail-period">
                        <?php echo esc_html__(sprintf("%d Day(s) Trial Period.", $trial_period), IQONIC_EXTENSION_TEXT_DOMAIN); ?>
                    </div>
            <?php }
            }
            if ($settings['show_expiration'] == "yes" && $expiration_number > 0 && $expiration_period != '') {
                echo '<div class="plan_expiration">';
                echo esc_html__(sprintf("Membership expires after %d %s", $expiration_number, $expiration_period), IQONIC_EXTENSION_TEXT_DOMAIN);
                echo '</div>';
            } ?>

        </div>
        <?php if (isset($active_plan) && !empty($active_plan)) { ?>
            <div class="active-plan text-capitalize"><span><?php echo esc_html($active_plan) ?></span></div>
        <?php } ?>
    </div>
   
    <div class="wrap-details-pricing">
        <?php

        if ($settings['show_description'] == "yes") {
            if (!empty($level->description)) { ?>
                <div class="plan_description">
                    <?php echo $this->parse_text_editor($level->description); ?>
                </div>
            <?php }
        }

        if ($level->allow_signups !== '1') {
            echo esc_html__("Signup has been disabled for this plan", IQONIC_EXTENSION_TEXT_DOMAIN);
        } else { ?>
            <div class="pricing-plan-description">
                <ul>
                    <?php foreach ($repeater as $repeater_data) { ?>
                        <li>
                            <?php Icons_Manager::render_icon($repeater_data['tab_icon'], ['aria-hidden' => 'true']); ?>
                            <span class="plan-dec"> <?php echo esc_html($repeater_data['plan_description']); ?> </span>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="pricing-plan-footer">
                <?php
                if (isset($account_page_text) && !empty($account_page_text)) {
                    $value = $account_page_text;
                } else {
                    $value = "Select " . $level->name;
                } ?>

                <form action="<?php echo esc_url($pmpro_checkout_page_link); ?>" method="POST">
                    <?php
                    if (!empty($settings['button_text']) && $settings['show_custom_link'] == 'yes') {
                        require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Elements/Button/render.php';
                    } else { ?>
                        <button class="btn btn-hover socialv-button w-100 <?php echo esc_attr(!empty($active_plan) ? 'disabled' : '') ; ?>">
                            <?php echo esc_html($value); ?>
                        </button>
                    <?php }
                    ?>
                    <input type="hidden" name="original_price" value="<?php echo esc_attr($pmpro_currency_symbol . $initial_payment); ?>">
                </form>

            </div>
        <?php } ?>
    </div>
</div>