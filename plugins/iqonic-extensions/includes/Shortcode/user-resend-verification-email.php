<?php

use function SocialV\Utility\socialv;

add_action('before_resend_email_success', 'socialv_resend_verify_email', 10, 2);
add_shortcode('iqonic-resend-verification-email', 'socialv_resend_verification_email');

if (!function_exists('socialv_resend_verification_email')) {
    function socialv_resend_verification_email()
    {
        //get registration form if user not logged in
        if (!is_user_logged_in()) {
            return socialv_resend_verification_email_form();
        }
    }
}

if (!function_exists('socialv_resend_verification_email_form')) {
    function socialv_resend_verification_email_form()
    {
        ob_start();
?>
        <div id="user_forgetpwd" class="card-main socialv-bp-login">
            <div class="card-inner">
                <div class="socialv-login-form">
                    <?php socialv()->get_shortcode_content("resend_verify_email"); ?>
                    <form class="iqonic-resend-verification-email-form" action="#" method="post" name="resend_verifiy_email_form" id="resend_verifiy_email_form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="iqonic-result-msg"></div>
                                <div class="login-username iqonic-data-input">
                                    <label for="user_email"><?php esc_html_e('Email Address', 'iqonic-extension'); ?></label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="iconly-Message icli"></i></span>
                                        <input id="user_email_resend_email_id" type="email" autocomplete="off" name="user_resend_email" class="form-control" value="" placeholder="<?php esc_html_e('User Email Address', 'iqonic-extension'); ?>" required /></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="socialv-auth-button">
                            <button id="wp-submit_resend_mail" class="w-100 socialv-button iqonic-data-input" type="submit" value="<?php esc_html_e('Resend Verification Email', 'iqonic-extension'); ?>"><?php esc_html_e('Resend Email', 'iqonic-extension'); ?></button>
                        </div>
                        <input type="hidden" name="iq_form_type" value="resend_email" />

                        <?php if (isset($element_nonce) && true == $element_nonce) { ?>
                            <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_login_page_nonce'); ?>
                        <?php } else { ?>
                            <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_login_popup_nonce'); ?>
                        <?php } ?>

                    </form>
                    <?php socialv()->get_shortcode_links('resend_verify_email'); ?>
                </div>
            </div>
        </div>
<?php

        return ob_get_clean();
    }
}

?>