<?php

use function SocialV\Utility\socialv;

add_action('before_lost_password_success', 'socialv_send_pass', 10, 2);
add_shortcode('iqonic-lost-pass', 'socialv_forgot_form');

if (!function_exists('socialv_forgot_form')) {
    function socialv_forgot_form()
    {
        // get registration form if user not logged in
        if (!is_user_logged_in()) {
            return socialv_edit_pass_form();
        }
    }
}

if (!function_exists('socialv_edit_pass_form')) {
    function socialv_edit_pass_form()
    {
        ob_start();
?>
        <div id="user_forgetpwd" class="card-main socialv-bp-login">
            <div class="card-inner">
                <div class="socialv-login-form">
                    <?php socialv()->get_shortcode_content("forgetpwd"); ?>

                    <form name="lostpasswordform" id="changepass" class="iqonic-lost-password-form" action="#" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($_GET['reset_pwd']) && true == $_GET['reset_pwd']) { ?>
                                    <input type="hidden" name="login" value="<?php echo $_GET['login'] ?>" autocomplete="off">
                                    <input type="hidden" name="key" value="<?php echo strip_tags($_GET['key']); ?>" />

                                    <div class="iqonic-result-msg"></div>
                                    <div class="forgetpwd-email iqonic-data-input">
                                        <label for="user_email"><?php esc_html_e('New Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Message icli"></i></span>
                                            <input type="password" name="pass1" class="form-control" size="20" placeholder="<?php esc_html_e('New Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>

                                    <div class="forgetpwd-email iqonic-data-input">
                                        <label for="user_email"><?php esc_html_e('Confirm New Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Message icli"></i></span>
                                            <input type="password" name="pass2" class="pass2" size="20" placeholder="<?php esc_html_e('Confirm New Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>
                                    <input class="g-recaptcha-response" type="hidden" name="g-recaptcha-response">
                                    <?php do_action('lostpassword_form'); ?>
                                    <div class="socialv-auth-button">
                                        <button id="wp-submit" class="w-100 socialv-button iqonic-data-input" type="submit" value="<?php esc_html_e('Reset Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"><?php esc_html_e('Reset Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></button>
                                    </div>

                                    <input type="hidden" name="iq_form_type" value="reset_password" />

                                    <?php if (isset($element_nonce) && true == $element_nonce) { ?>
                                        <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_reset_password_page_nonce'); ?>
                                    <?php } else { ?>
                                        <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_reset_password_popup_nonce'); ?>
                                    <?php } ?>

                                <?php } else { ?>
                                    <div class="iqonic-result-msg"></div>
                                    <div class="forgetpwd-email iqonic-data-input">
                                        <label for="user_email"><?php esc_html_e('Username or Email', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Message icli"></i></span>
                                            <input type="text" autocomplete="off" name="user_login" class="form-control" value="" size="20" placeholder="<?php esc_html_e('Username or Email', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required /></p>
                                        </div>
                                    </div>
                                    <input class="g-recaptcha-response" type="hidden" name="g-recaptcha-response">
                                    <?php do_action('lostpassword_form'); ?>
                                    <div class="socialv-auth-button">
                                        <button id="wp-submit" class="w-100 socialv-button iqonic-data-input" type="submit" value="<?php esc_html_e('Reset Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"><?php esc_html_e('Reset Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></button>
                                    </div>
                                    <input type="hidden" name="iq_form_type" value="lost_password" />

                                    <?php if (isset($element_nonce) && true == $element_nonce) { ?>
                                        <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_lost_password_page_nonce'); ?>
                                    <?php } else { ?>
                                        <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_lost_password_popup_nonce'); ?>
                                    <?php } ?>

                                <?php } ?>

                            </div>
                        </div>
                    </form>
                    <?php socialv()->get_shortcode_links('forgetpwd'); ?>

                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}


if (!function_exists('socialv_send_pass')) {

    function socialv_send_pass($email, $user)
    {

        if (isset($_REQUEST['socialv_ajax_lost_password_page_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_lost_password_page_nonce'], 'socialv_ajax_login_action')) {
            exit;
        }

        if (!$user)
            $user = get_user_by('email', $email);

        $message = null;
        if ($user) {

            $title = esc_html__('New Password', IQONIC_EXTENSION_TEXT_DOMAIN);
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $password = substr(str_shuffle($str_result), 0, 10);
            $message = '<label><b>' . esc_html__('Hello', IQONIC_EXTENSION_TEXT_DOMAIN) . ',</b></label>';
            $message .= '<p>' . esc_html__('Your recently requested to reset your password. Here is the new password for Log In', IQONIC_EXTENSION_TEXT_DOMAIN) . '</p>';
            $message .= '<p><b>' . esc_html__('New Password', IQONIC_EXTENSION_TEXT_DOMAIN) . ' </b> : ' . $password . '</p>';
            $message .= '<p>' . esc_html__('Thanks', IQONIC_EXTENSION_TEXT_DOMAIN) . ',</p>';

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $is_sent_wp_mail = wp_mail($email, $title, $message, $headers);

            if ($is_sent_wp_mail) {
                wp_set_password($password, $user->ID);
                $message = true;
            } elseif (mail($email, $title, $message, $headers)) {
                wp_set_password($password, $user->ID);
                $message = true;
            } else {
                $message = false;
            }
        } else {
            $message = esc_html__('User not found with this email address', IQONIC_EXTENSION_TEXT_DOMAIN);
        }
        echo $message;
    }
}
