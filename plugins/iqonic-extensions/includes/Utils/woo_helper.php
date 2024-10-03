<?php
if (class_exists('WooCommerce')) {
    return;
}

// Wooocmerce popup Jquery
function socialv_load_js_css() {
    wp_enqueue_script('sweetalert2', IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/js/sweetalert2.js', array('jquery'), true);
}
add_action('wp_enqueue_scripts', 'socialv_load_js_css');

// Wooocmerce add to cart popup
add_action('wp_ajax_socialv_ajax_add_to_cart', 'socialv_ajax_add_to_cart');
add_action('wp_ajax_nopriv_socialv_ajax_add_to_cart', 'socialv_ajax_add_to_cart');
function socialv_ajax_add_to_cart()
{
    $product_id  = $_POST['product_id'];
    global $woocommerce;
    $woocommerce->cart->add_to_cart($product_id);
    die();
}

//you have to change according to above mini cart shortcode function.
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
?>
    <a href="#" class="dropdown-back" data-toggle="dropdown">
        <i class="fas fa-shopping-cart"></i>
        <div class="basket-item-count" style="display: inline;">
            <span class="cart-items-count count">
                <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
        </div>
    </a>
<?php $fragments['a.dropdown-back'] = ob_get_clean();
    return $fragments;
});

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
?>
    <div class="dropdown-menu dropdown-menu-mini-cart">
        <div class="widget_shopping_cart_content">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
<?php $fragments['ul.dropdown-menu'] = ob_get_clean();
    return $fragments;
});


add_action('wp_footer', 'ajax_added_to_cart_popup_script');
function ajax_added_to_cart_popup_script()
{
    $added_to_cart_text = esc_html__("Added to cart!", IQONIC_EXTENSION_TEXT_DOMAIN);
    $checkout_text = esc_html__("Checkout", IQONIC_EXTENSION_TEXT_DOMAIN);
    $continue_text = esc_html__("Continue shopping", IQONIC_EXTENSION_TEXT_DOMAIN);
    ?>
     <script type="text/javascript">
        jQuery(function($) {

            // On "added_to_cart" live event
            $(document.body).on('added_to_cart', function(a, b, c, d) {

                var prod_id = d.data('product_id'), // Get the product name
                    prod_qty = d.data('quantity'), // Get the quantity
                    prod_name = d.data('product_name'); // Get the product name

                Swal.fire({
                    title: '<?php echo $added_to_cart_text; ?>',
                    text: prod_name,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--color-theme-primary)',
                    cancelButtonColor: 'var(--color-theme-secondary)',
                    confirmButtonText: '<span class="socialv-btn-line-holder"><span class="socialv-btn-line-hidden"></span>       <span class="socialv-btn-text"><?php echo $checkout_text; ?></span><span class="socialv-btn-line"></span><i class="fas fa-chevron-right"></i></span>',
                    cancelButtonText: '<span class="socialv-btn-line-holder"><span class="socialv-btn-line-hidden"></span>       <span class="socialv-btn-text"><?php echo $continue_text; ?></span><span class="socialv-btn-line"></span><i class="fas fa-chevron-right"></i></span>',
                    customClass: {
                        confirmButton: 'popup-btn-checkout socialv-btn',
                        cancelButton: 'popup-btn-continue socialv-btn',
                    },
                    showClass: {
                        popup: 'animated fadeIn',
                    },
                    hideClass: {
                        popup: 'animated fadeOut',
                    }
                }).then((result) => {

                    if (result.value) {
                        window.location.href = '<?php echo wc_get_checkout_url(); ?>';
                    }
                });
            });
        });
    </script>

    <?php
}

/* woocommerce register shortcode */
add_shortcode('iqonic-signup-form', 'iqonic_woocommerce_registration');
function iqonic_woocommerce_registration()
{
    if (is_admin() || is_user_logged_in()) {
        return;
    }
    ob_start();
    do_action('woocommerce_before_customer_login_form'); ?>
    <form id="register-form" method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

        <?php do_action('woocommerce_register_form_start'); 

            if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="<?php echo esc_attr('Enter Your Username *', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
            </p>

        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="<?php echo esc_attr('Your email id *', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
        </p>

        <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php echo esc_attr('Enter Your Password *', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
            </p>

        <?php endif;
        
        if (wc_get_page_id('terms') > 0) {
        ?>
            <div class="form-row terms wc-terms-and-conditions socialv-check">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input type="checkbox" required class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); ?> id="terms" /> <span class="text-check"><?php printf(__('By creating an account, you agree to the <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">Terms and Conditions</a>', IQONIC_EXTENSION_TEXT_DOMAIN), esc_url(wc_get_page_permalink('terms'))); ?></span> <span class="required">*</span><span class="checkmark"></span>
                </label>
                <input type="hidden" name="terms-field" value="1" />
            </div>
        <?php }  
        
        do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-FormRow form-row sign-up-btn">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <!-- register button  -->
            <button type="submit" class="socialv-box-shadow socialv-btn woocommerce-Button" name="register" value="<?php esc_attr_e('Register', IQONIC_EXTENSION_TEXT_DOMAIN); ?>">
                <span class="socialv-btn-line-holder">
                    <span class="socialv-btn-line-hidden"></span>
                    <span class="socialv-btn-text"><?php esc_html_e('Register', IQONIC_EXTENSION_TEXT_DOMAIN); ?></span>
                    <span class="socialv-btn-line"></span>
                    <i class="fas fa-chevron-right"></i>
                </span>
            </button>
        </p>

        <?php do_action('woocommerce_register_form_end'); ?>

    </form>
    <?php
    return ob_get_clean();
}

/* woocommerce login shortcode */
add_shortcode('iqonic-login-form', 'iqonic_woocommerce_login_form');
function iqonic_woocommerce_login_form($attr)
{
    if (is_admin() || is_user_logged_in()) {
        return;
    }
    ob_start();
    $args = shortcode_atts(array(
        'btn_text_string' => '',
        'button_text' => 'Sign Up',
        'url' => '#',

    ), $attr); ?>
    <form class="woocommerce-form woocommerce-form-login login" method="post">

        <?php do_action('woocommerce_login_form_start'); ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php echo esc_attr('Enter Username or Email Address*', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr('Enter Password*', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" type="password" name="password" id="password" autocomplete="current-password" />
        </p>

        <?php do_action('woocommerce_login_form'); ?>

        <div class="login-inner">
            <div class="socialv-check">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /><span class="checkmark"></span>
                    <span class="text-check"><?php esc_html_e('Remember me', IQONIC_EXTENSION_TEXT_DOMAIN); ?></span>
                </label>
            </div>
            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgot Password?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></a>
            </p>
        </div>

        <p class="form-row form-submit-btn">
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <!-- login button -->
            <button type="submit" class="socialv-box-shadow socialv-btn woocommerce-Button" name="login" value="<?php esc_attr_e('Log in', IQONIC_EXTENSION_TEXT_DOMAIN); ?>">
                <span class="socialv-btn-line-holder">
                    <span class="socialv-btn-line-hidden"></span>
                    <span class="socialv-btn-text"><?php esc_html_e('Log in', IQONIC_EXTENSION_TEXT_DOMAIN); ?></span>
                    <span class="socialv-btn-line"></span>
                    <i class="fas fa-chevron-right"></i>
                </span>
            </button>
        </p>

        <div class="sign-link d-flex align-items-center">
            <p class="my-0"><?php echo esc_html($args['btn_text_string']); ?></p>
            <h5 class="sign_up_text mb-0 ml-2"><a href="<?php echo esc_url($args['url']); ?>"><?php echo esc_html($args['button_text']);  ?></a></h5>
        </div>

        <?php do_action('woocommerce_login_form_end'); ?>

    </form>
    <?php
    return ob_get_clean();
}
