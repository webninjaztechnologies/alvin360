<?php

/**
 * User Password Generator
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Filters the display of the password fields
if ( apply_filters( 'show_password_fields', true, bbpress()->displayed_user ) ) : ?>

<div id="password" class="user-pass1-wrap">
	<label for="user_login"><?php esc_html_e( 'Password', 'socialv' ); ?></label>
	<button type="button" class="button wp-generate-pw hide-if-no-js"><?php esc_html_e( 'Generate Password', 'socialv' ); ?></button>

	<fieldset class="bbp-form password wp-pwd hide-if-js">
		<span class="password-input-wrapper">
			<input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="<?php echo esc_attr( wp_generate_password( 24 ) ); ?>" aria-describedby="pass-strength-result" />
		</span>

		<span class="password-button-wrapper">
			<button type="button" class="button wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', 'socialv' ); ?>">
				<span class="dashicons dashicons-hidden"></span>
				<span class="text"><?php esc_html_e( 'Hide', 'socialv' ); ?></span>
			</button><button type="button" class="button wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change', 'socialv' ); ?>">
				<span class="dashicons dashicons-no"></span>
				<span class="text"><?php esc_html_e( 'Cancel', 'socialv' ); ?></span>
			</button>
		</span>

		<div id="pass-strength-result" aria-live="polite"></div>
	</fieldset>
</div>

<div class="user-pass2-wrap hide-if-js">
	<label for="pass2"><?php esc_html_e( 'Repeat New Password', 'socialv' ); ?></label>
	<input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
	<p class="description"><?php esc_html_e( 'Type your new password again.', 'socialv' ); ?></p>
</div>

<div class="pw-weak">
	<label for="pw_weak"><?php esc_html_e( 'Confirm', 'socialv' ); ?></label>
	<input type="checkbox" name="pw_weak" id="pw_weak" class="pw-checkbox checkbox" />
	<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>
</div>

<?php endif;
