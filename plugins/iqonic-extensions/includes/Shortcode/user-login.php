<?php

use function SocialV\Utility\socialv;

add_shortcode('iqonic-login', 'socialv_login_form');

if (!function_exists('socialv_login_form')) {
    function socialv_login_form()
    {
        // get registration form if user not logged in
        if (!is_user_logged_in() || is_user_logged_in()) {
            global $wp;
            global $current_url;

            $current_url = home_url(add_query_arg(array($_REQUEST), $wp->request));
            if (!defined('CURL')) {
                define('CURL', $current_url);
            }
            $page = explode('?', $current_url);
            $get_page = end($page);
            if ($get_page === 'forget-password' || (isset($_POST['action']) && $_POST['action'] == 'forget-password'))
                return socialv_edit_pass_form();
            else
                return socialv_user_login();
        } else {
            if (!is_super_admin(get_current_user_id())) {
                wp_redirect(home_url());
                exit;
            }
        }
    }
}
if (!function_exists('socialv_user_login')) {
    function socialv_user_login()
    {
        ob_start();
        $socialv_options = get_option('socialv-options');
        if (!is_user_logged_in()) {
            do_action('login_enqueue_scripts');
        }


?>
        <div id="user_loginform" class="card-main socialv-bp-login ">
            <div class="card-inner">
                <div class="socialv-login-form">
                    <?php socialv()->get_shortcode_content("login"); ?>
                    <form name="loginform" id="loginform" class="iqonic-login-form" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
                        <div class="iqonic-result-msg"></div>

                        <p class="login-username iqonic-data-input">
                            <label for="user_login"><?php esc_html_e('Username or Email Address', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                        <div class="input-group mb-3 "><span class="input-group-text"><i class="iconly-Add-User icli"></i></span>
                            <input type="text" required="" name="log" class="userform form-control" id="user_login" autocomplete="username" value="<?php socialv()->get_default_login_user('marvin'); ?>" placeholder="<?php esc_html_e('Username', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" size="20">
                        </div>
                        </p>
                        <p class="login-password">
                            <label for="user_pass"><?php esc_html_e('Your Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                        <div class="input-group mb-3 position-relative">
                            <span class="input-group-text"><i class="iconly-Lock icli"></i></span>
                            <input type="password" required="" name="pwd" class="password form-control socialv-password-field" placeholder="<?php esc_html_e('Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" id="user_pass" autocomplete="current-password" value="<?php socialv()->get_default_login_user('marvin'); ?>" size="20">
                            <span>
                                <span class="icon-eye-close toggle-password show-password"></span>
                            </span>
                        </div>
                        <input class="g-recaptcha-response" type="hidden" name="g-recaptcha-response">
                        </p>

                        <div class="d-flex flex-sm-row justify-content-between align-items-center mb-4">
                            <p class="login-remember m-0"><label class="m-0"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> <?php esc_html_e('Remember Me', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label></p>
                            <?php
                            $forget_link = isset($socialv_options['site_forgetpwd_link']) ? get_page_link($socialv_options['site_forgetpwd_link']) : '#';
                            $redirect_page_id = !empty($socialv_options['default_page_link']) ? $socialv_options['default_page_link'] : "#";
                            ?>
                            <a id="user_changepass" href="<?php echo esc_url(($socialv_options['site_login'] == 0 || is_page($redirect_page_id)) ? $forget_link : '#changepass') ?>" class="forgot-pwd"><?php esc_html_e('Forgot Password?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></a>
                        </div>
                        <p class="login-submit">
                            <button id="wp-submit" class="w-100 socialv-button iqonic-data-input" type="submit" value="<?php esc_html_e('Sign In', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"><?php esc_html_e('Sign In', IQONIC_EXTENSION_TEXT_DOMAIN); ?></button>
                        </p>
                        <input type="hidden" name="iq_form_type" value="login" />
                        <?php if (isset($element_nonce) && true == $element_nonce) { ?>
                            <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_login_page_nonce'); ?>
                        <?php } else { ?>
                            <?php wp_nonce_field('socialv_ajax_login_action', 'socialv_ajax_login_popup_nonce'); ?>
                        <?php } ?>

                    </form>
                    <?php do_action('get_socialv_social_after'); ?>
                    <?php socialv()->get_shortcode_links('register'); ?>

                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
?>