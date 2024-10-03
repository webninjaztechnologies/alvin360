<?php

use PHPUnit\Util\Printer;

use function SocialV\Utility\socialv;

add_shortcode('iqonic-register', 'socialv_registration_form');

function socialv_registration_form($atts)
{
    extract(shortcode_atts(array(
        'redirect' => home_url(),
    ), $atts));

    // get registration form if user not logged in
    if (!is_user_logged_in() || is_user_logged_in()) {
        return socialv_registration_form_fields($atts);
    } else {
        if (!is_super_admin(get_current_user_id())) {
            wp_redirect($redirect);
        }
    }
}

if (!function_exists('socialv_registration_form_fields')) {
    // registration form fields
    function socialv_registration_form_fields($atts)
    {
        $socialv_options = get_option('socialv-options');

        ob_start();
        extract(shortcode_atts(array(
            'redirect' => home_url(),
        ), $atts)); ?>
        <div id="user_registerform" class="card-main socialv-bp-login">
            <div class="card-inner">
                <div class="socialv-login-form">
                    <?php socialv()->get_shortcode_content("register"); ?>
                    <?php $registration_enabled = get_option('users_can_register');
                    if ($registration_enabled) {
                    ?>
                        <?php
                        if (isset($_POST["socialv_user_login"]) && wp_verify_nonce($_POST['socialv_register_nonce'], 'iqonic-register-nonce')) {
                            $user_login       = $_POST["socialv_user_login"];
                            $user_email       = $_POST["socialv_user_email"];
                            $user_first       = $_POST["socialv_user_first"];
                            $user_last        = $_POST["socialv_user_last"];
                            $user_pass        = $_POST["socialv_user_pass"];
                            $pass_confirm     = $_POST["socialv_user_pass_confirm"];

                            $aValid = array('-', '_');
                            $errors = array();

                            if (email_exists($user_email)) {
                                $errors['email_error'] = __('Email already exists.', IQONIC_EXTENSION_TEXT_DOMAIN);
                            }
                            if (username_exists($user_login)) {
                                $errors['uname_error'] = __('Username already exists.', IQONIC_EXTENSION_TEXT_DOMAIN);
                            } elseif (!ctype_alnum(str_replace($aValid, '', $user_login))) {
                                $errors['uname_error'] = __('Your username is not properly formatted.', IQONIC_EXTENSION_TEXT_DOMAIN);
                            }

                            if (empty($user_pass) || empty($pass_confirm)) {
                                $errors['pass_error'] = __('Password fields should not be empty', IQONIC_EXTENSION_TEXT_DOMAIN);
                            } elseif ($user_pass !== $pass_confirm) {
                                $errors['pass_error'] = __('Password and confirm password did not match', IQONIC_EXTENSION_TEXT_DOMAIN);
                            }

                            // only create the user in if there are no errors
                            if (empty($errors)) {
                                if (isset($socialv_options['registration_process'])) {
                                    $registration_method = $socialv_options['registration_process'];
                                    if ($registration_method == 'default') {
                                        $new_user_id = wp_insert_user(
                                            array(
                                                'user_login'    => $user_login,
                                                'user_pass'     => $user_pass,
                                                'user_email'    => $user_email,
                                                'first_name'    => $user_first,
                                                'last_name'     => $user_last,
                                                'user_registered' => date('Y-m-d H:i:s')
                                            )
                                        );
                                        if ($new_user_id) {
                                            wp_new_user_notification($new_user_id);
                                            wp_set_current_user($new_user_id);
                                            wp_set_auth_cookie($new_user_id);
                                            do_action('wp_login', $user_login, get_user_by('ID', $new_user_id));

                                            // send the newly created user to the home page after logging them in
                                            if (isset($socialv_options['display_after_login_redirect']) && $socialv_options['display_after_login_redirect'] == 'false') {
                                                $redirect = (isset($socialv_options['display_after_login_page']) && !empty($socialv_options['display_after_login_page'])) ? (get_permalink($socialv_options['display_after_login_page'])) : home_url();
                                            }
                                            wp_redirect($redirect);
                                            exit;
                                        } else {
                                            echo '<div class="iqonic-result-msg socialv-alert  socialv-alert-danger">';
                                            echo '<div>' . esc_html__("Somthing went wrong please try again.", IQONIC_EXTENSION_TEXT_DOMAIN) . '</div>';
                                            echo  '</div>';
                                        }
                                    } elseif ($registration_method == 'mannuly') {
                                        $usermeta['password'] = wp_hash_password($user_pass);
                                        $usermeta = apply_filters('bp_signup_usermeta', $usermeta);
                                        // Set the filter to false to disable activation email
                                        add_filter('bp_core_signup_send_activation_key', '__return_false');

                                        // Call bp_core_signup_user function
                                        bp_core_signup_user($user_login, $user_pass, $user_email, $usermeta);

                                        // Remove the filter to prevent affecting other parts of your application
                                        remove_filter('bp_core_signup_send_activation_key', '__return_false');
                                        if (isset($socialv_options['manually_proccess']) && $socialv_options['manually_proccess'] == 'page') {
                                            $redirect = (isset($socialv_options['manually_proccess_page']) && !empty($socialv_options['manually_proccess_page'])) ? (get_permalink($socialv_options['manually_proccess_page'])) : '#';
                                            wp_redirect($redirect);
                                            exit;
                                        } else {
                                            $text = (isset($socialv_options['manually_proccess_text']) && !empty($socialv_options['manually_proccess_text'])) ? $socialv_options['manually_proccess_text'] : 'Please wait until your account has been verified by the admin.';
                                            echo '<div class="iqonic-result-msg socialv-alert  socialv-alert-success">';
                                            echo '<div>' . $text . '</div>';
                                            echo  '</div>';
                                        }
                                    } elseif ($registration_method == 'verification_key') {
                                        $bp = buddypress();
                                        $usermeta['password'] = wp_hash_password($user_pass);

                                        $usermeta = apply_filters('bp_signup_usermeta', $usermeta);
                                        $wp_user_id = bp_core_signup_user($user_login, $user_pass, $user_email, $usermeta);
                                        echo '<div class="iqonic-result-msg socialv-alert  socialv-alert-success">';
                                        echo '<div>' . esc_html__("Verify your account from your email.", IQONIC_EXTENSION_TEXT_DOMAIN)  . '</div>';
                                        echo  '</div>';
                                    }
                                } else {
                                    echo '<div class="iqonic-result-msg socialv-alert  socialv-alert-danger">';
                                    echo '<div>' . esc_html__("Somthing went wrong please try again.", IQONIC_EXTENSION_TEXT_DOMAIN) . '</div>';
                                    echo  '</div>';
                                }
                            } else {
                        ?>
                                <div class="iqonic-result-msg socialv-alert  socialv-alert-danger">
                                    <?php
                                    if (isset($errors['email_error']))
                                        echo '<div>' . esc_html($errors['email_error']) . '</div>';
                                    if (isset($errors['uname_error']))
                                        echo '<div>' . esc_html($errors['uname_error']) . '</div>';
                                    if (isset($errors['pass_error']))
                                        echo '<div>' . esc_html($errors['pass_error']) . '</div>';
                                    ?>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <form id="registerform" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="register-firstname">
                                        <label><?php esc_html_e("First Name", IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Add-User icli"></i></span>
                                            <input class="form-control" name="socialv_user_first" id="socialv_user_first" type="text" placeholder="<?php esc_html_e("First Name", IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="register-lastname">
                                        <label><?php esc_html_e("Last Name", IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Add-User icli"></i></span>
                                            <input class="form-control" name="socialv_user_last" id="socialv_user_last" type="text" placeholder="<?php esc_html_e('Last Name', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="register-username">
                                        <label><?php esc_html_e("Username", IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Add-User icli"></i></span>
                                            <input class="form-control" name="socialv_user_login" id="socialv_user_login" class="required" type="text" placeholder="<?php esc_html_e('Username', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="register-emailname">
                                        <label><?php esc_html_e('Email*', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Message icli"></i></span>
                                            <input class="form-control" name="socialv_user_email" id="socialv_user_email" class="required" type="email" placeholder="<?php esc_html_e('Email', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="register-password">
                                        <label><?php esc_html_e('Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Lock icli"></i></span>
                                            <input class="form-control socialv-password-field" name="socialv_user_pass" id="password" class="required" type="password" placeholder="<?php esc_html_e('Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                            <span class="icon-eye-close toggle-password show-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="register-username">
                                        <label><?php esc_html_e('Confirm Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="iconly-Lock icli"></i></span>
                                            <input class="form-control socialv-password-field" name="socialv_user_pass_confirm" id="password_again" class="required" type="password" placeholder="<?php esc_html_e('Confirm Password', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" required />
                                            <span class="icon-eye-close toggle-password show-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <input class="g-recaptcha-response" type="hidden" name="g-recaptcha-response">
                                <div class="col-md-12 socialv-auth-button">
                                    <input type="hidden" name="socialv_register_nonce" class="socialv-button w-100" value="<?php echo wp_create_nonce('iqonic-register-nonce'); ?>" />
                                    <button type="submit" class="socialv-button w-100" value="<?php esc_html_e('Register', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"><?php esc_html_e('Register', IQONIC_EXTENSION_TEXT_DOMAIN); ?></button>
                                </div>
                            </div>
                        </form>
                    <?php } else { ?>
                        <div id="registerform">
                            <p class="register-message"><?php esc_html_e('User registration is currently not allowed.', IQONIC_EXTENSION_TEXT_DOMAIN); ?></p>
                        </div>
                    <?php }
                    do_action('get_socialv_social_after');
                    socialv()->get_shortcode_links('login');
                    ?>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
