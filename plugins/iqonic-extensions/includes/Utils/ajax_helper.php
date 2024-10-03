<?php

if (!function_exists('socialv_enqueue_ajax_login_scripts')) {
    function socialv_enqueue_ajax_login_scripts()
    {
        wp_enqueue_script('socialv-ajax-login', IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/js/ajax-custom.js', array('jquery'), IQONIC_EXTENSION_VERSION, true);
        wp_localize_script('socialv-ajax-login', 'socialv_ajax_login_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'loading_message' => esc_html__('Verifying...', IQONIC_EXTENSION_TEXT_DOMAIN),
        ));
    }
}
add_action('wp_enqueue_scripts', 'socialv_enqueue_ajax_login_scripts');

add_action('wp_ajax_socialv_ajax_login', 'socialv_ajax_login');
add_action('wp_ajax_nopriv_socialv_ajax_login', 'socialv_ajax_login');
function socialv_ajax_login()
{
    $response = [];
    // if ajax is not loaded, exit
    if (!wp_doing_ajax()) {
        exit;
    }

    $after_login_page = home_url();

    $socialv_options = get_option('socialv-options');


    $after_login_option = isset($socialv_options['display_after_login_redirect']) ? $socialv_options['display_after_login_redirect'] : '';
    if (!empty($after_login_option) && $after_login_option == 'false') {
        $after_login_page = !empty($socialv_options['display_after_login_page']) ? (get_permalink($socialv_options['display_after_login_page'])) : home_url();
    }
    /*** Login ajax **/
    if (isset($_POST['formType']) && 'login' === $_POST['formType']) {

        // Verify nonce, else exit		
        if (isset($_REQUEST['socialv_ajax_login_page_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_login_page_nonce'], 'socialv_ajax_login_action')) {
            exit;
        } elseif (isset($_REQUEST['socialv_ajax_login_popup_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_login_popup_nonce'], 'socialv_ajax_login_action')) {
            exit;
        }

        // Clean up username and password
        $username = esc_sql($_POST['loginUsername']);
        $password = $_POST['loginPassword'];
        $remember = isset($_POST['loginRemember']) ? true : false;

        // Get user data from username
        $user_data = !empty($user_data) ? get_user_by('login', $username) : get_user_by('email', $username);

        // Attempt login
        $login_data = [
            'user_login' => $username,
            'user_password' => $password,
            'remember' => $remember,
        ];

        if (!empty($user_data) && function_exists('bp_is_active') && BP_Signup::check_user_status($user_data->ID)) {
            $login_data['user_login'] = '';
            $login_data['user_password'] = '';
        }

        $secure_cookie = is_ssl() ? true : false;
        $user_verify = wp_signon($login_data, $secure_cookie);

        // Error checking	
        if (is_wp_error($user_verify)) {
            $error_code = $user_verify->get_error_code();
            $error_messages = [
                'invalid_username' => esc_html__('Invalid username.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'invalid_email' => esc_html__('Invalid email.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'incorrect_password' => esc_html__('Invalid password.', IQONIC_EXTENSION_TEXT_DOMAIN),
                'user_suspended' => $user_verify->get_error_message(),
                'wfls_captcha_verify' => esc_html__('Please check your email.', IQONIC_EXTENSION_TEXT_DOMAIN),
            ];
            $message = isset($error_messages[$error_code]) ? $error_messages[$error_code] : esc_html__('This account has not yet been verified.', IQONIC_EXTENSION_TEXT_DOMAIN);
            $response = ['message' => $message];
        } else {
            $url_link = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : $after_login_page;
            $redirect_url = apply_filters('socialv_page_link_redirect', $url_link);
            $login_redirect_page = $url_link;

            if (!$redirect_url && $login_redirect_page) {
                $redirect_url_parameter = (false !== strpos($redirect_url, '&')) ? '&loggedin=true' : '?loggedin=true';
                $redirect_url = $redirect_url . $redirect_url_parameter;
            }

            $response = ['status' => 'login-success', 'message' => esc_html__('Login successful.', IQONIC_EXTENSION_TEXT_DOMAIN), 'redirect' => esc_url($redirect_url)];
        }
        wp_send_json($response);
        die();

        /*** forget pwd ajax **/
    } elseif (isset($_POST['formType']) && 'lost_password' === $_POST['formType']) {
        // Verify nonce, else exit		
        if (isset($_REQUEST['socialv_ajax_lost_password_page_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_lost_password_page_nonce'], 'socialv_ajax_login_action')) {
            exit;
        }

        $user_input = esc_sql(trim($_POST['registrationUsername']));
        $message = '';

        if (strpos($user_input, '@')) {
            $user_data = get_user_by('email', $user_input);
            $message = empty($user_data) ? esc_html__('Invalid email address.', IQONIC_EXTENSION_TEXT_DOMAIN) : $message;
        } else {
            $user_data = get_user_by('login', $user_input);
            $message = empty($user_data) ? esc_html__('Invalid username.', IQONIC_EXTENSION_TEXT_DOMAIN) : $message;
        }
        $response = ['message' => $message];
        if (!empty($user_data)) {
            $user_email = $user_data->user_email;
            // Email sent or not sent notice	
            ob_start();
            do_action("before_lost_password_success", $user_email, $user_data);
            $output = ob_get_clean();

            $response = ['status' => 'lost-password-success', 'message' => esc_html__('We have just sent you an email with instructions to reset your password.', IQONIC_EXTENSION_TEXT_DOMAIN), 'email' => $output];
        }

        wp_send_json($response);
        die();
        /**
         * Reset password ajax
         *
         */
    } elseif (isset($_POST['formType']) && $_POST['formType'] === 'reset_password') {

        // Verify nonce, else exit		
        if (isset($_REQUEST['socialv_ajax_login_page_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_login_page_nonce'], 'socialv_ajax_login_action')) {
            exit;
        } elseif (isset($_REQUEST['socialv_ajax_login_popup_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_login_popup_nonce'], 'socialv_ajax_login_action')) {
            exit;
        }

        $user = check_password_reset_key($_POST['resetKey'], $_POST['resetLogin']);
        // Check if key is valid
        if (is_wp_error($user)) {
            if ($user->get_error_code() === 'expired_key') {
                $response = ['message' => esc_html__('This key has expired.', IQONIC_EXTENSION_TEXT_DOMAIN)];
            } else {
                $response = ['message' => esc_html__('This key is invalid.', IQONIC_EXTENSION_TEXT_DOMAIN)];
            }
        }

        if (isset($_POST['resetPass1']) && $_POST['resetPass1'] !== $_POST['resetPass2']) {
            $response = ['message' => esc_html__('Your passwords do not match.', IQONIC_EXTENSION_TEXT_DOMAIN)];
        } else {
            reset_password($user, $_POST['resetPass1']);
            $response = ['status' => 'reset-password-success', 'message' => esc_html__('Your password has been reset.', IQONIC_EXTENSION_TEXT_DOMAIN)];
        }
        wp_send_json($response);
        die();
    } elseif (isset($_POST['formType']) && 'resend_email' === $_POST['formType']) {
        //Verify nonce, else exit
        if (isset($_REQUEST['socialv_ajax_resend_verify_email_page_nonce']) && !wp_verify_nonce($_REQUEST['socialv_ajax_resend_verify_email_page_nonce'], 'socialv_ajax_login_action')) {
            exit;
        }

        $resend_email_input = esc_sql($_POST['resendEmail']);

        $user_email = sanitize_email($resend_email_input);

        if (email_exists($resend_email_input)) {
            $user_id = get_user_by('email', $resend_email_input)->ID;
            $user_status = get_user_option('user_status', $user_id);

            if (!$user_status) {
                $response = array('message' => esc_html__('Account is already activated.', IQONIC_EXTENSION_TEXT_DOMAIN));
            } else {

                global $wpdb;
                $table_name = $wpdb->prefix . 'signups';
                $activation_key = $wpdb->get_var(
                    $wpdb->prepare("SELECT activation_key FROM $table_name WHERE user_email = %s", $user_email)
                );
                if (empty($activation_key)) {
                    $activation_key = socialv_generate_new_activation_key($user_id, $user_email);
                }

                $response = ['status' => 'resend-verification-email-success', 'message' => esc_html__('Check Email To Verify Your Account.', IQONIC_EXTENSION_TEXT_DOMAIN)];
                if (bp_is_active('xprofile') && function_exists('bp_xprofile_fullname_field_id')) {
                    $fullname_field_id = bp_xprofile_fullname_field_id();

                    if (isset($user_id) && bp_has_profile(array('user_id' => $user_id, 'field' => $fullname_field_id))) {
                        // Get the value of the xProfile full name field
                        $profile_data = bp_get_profile_field_data(['field' => $fullname_field_id]);
                        if (!empty($profile_data)) {
                            $salutation = $profile_data;
                        }
                    }
                }
                bp_core_signup_send_validation_email($user_id, $user_email, $activation_key, $salutation);
                
            }
        } else {
            $response = array('message' => esc_html__('User not registered yet.', IQONIC_EXTENSION_TEXT_DOMAIN));
        }
        wp_send_json($response);
        die();
    }
    exit();
}

function iqonic_scripts()
{
    wp_register_script('iqonic_ajax', false);
    wp_localize_script('iqonic_ajax', 'iq_like_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('iqonic_ajax');
}
add_action('wp_enqueue_scripts', 'iqonic_scripts', 15);

/* actions call */
add_action('init', 'socialv_post_like_create');
function socialv_post_like_create()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'post_like_table';
    global $charset_collate;
    $charset_collate = $wpdb->get_charset_collate();
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        $create_sql = "CREATE TABLE {$table_name} (
            id INT(11) NOT NULL AUTO_INCREMENT,
            postid INT(11) NOT NULL,
            clientip VARCHAR(40) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate}";
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($create_sql);
    }
    if (!isset($wpdb->post_like_table)) {
        $wpdb->post_like_table = $table_name;
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $table_name);
    }
}

function get_client_ip()
{
    $ip = bbp_get_user_id();
    return $ip;
}
