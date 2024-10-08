<?php

/**
 * BuddyPress - Members Activate
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

$socialv_option = get_option('socialv-options');
?>
<div id="buddypress">
	<?php

	/**
	 * Fires before the display of the member activation page.
	 *
	 * @since 1.1.0
	 */
	do_action('bp_before_activation_page'); ?>

	<div class="page" id="activate-page">
		<div class="card-main">
			<div class="card-inner">
				<div id="template-notices" role="alert" aria-atomic="true">
					<?php

					/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
					do_action('template_notices'); ?>

				</div>

				<?php

				/**
				 * Fires before the display of the member activation page content.
				 *
				 * @since 1.1.0
				 */
				do_action('bp_before_activate_content'); ?>

				<?php if (bp_account_was_activated()) : ?>

					<?php if (isset($_GET['e'])) : ?>
						<p><?php esc_html_e('Your account was activated successfully! Your account details have been sent to you in a separate email.', 'socialv'); ?></p>
					<?php else : ?>
						<p>
							<?php
							$login_link = isset($socialv_option['site_login_link']) ? get_page_link($socialv_option['site_login_link']) : wp_login_url(bp_get_root_domain());

							/* translators: %s: login url */
							printf(__('Your account was activated successfully! You can now <a href="%s">log in</a> with the username and password you provided when you signed up.', 'socialv'), $login_link );
							?>
						</p>
					<?php endif; ?>

				<?php else : ?>

					<p><?php esc_html_e('Please provide a valid activation key.', 'socialv'); ?></p>

					<form action="" method="post" class="standard-form" id="activation-form">

						<label for="key"><?php esc_html_e('Activation Key:', 'socialv'); ?></label>
						<input type="text" name="key" id="key" value="<?php echo esc_attr(bp_get_current_activation_key()); ?>" />

						<p class="submit d-inline-block">
							<input type="submit" name="submit" value="<?php esc_attr_e('Activate', 'socialv'); ?>" class="btn socialv-btn-primary"/>
						</p>

					</form>

				<?php endif; ?>

				<?php

				/**
				 * Fires after the display of the member activation page content.
				 *
				 * @since 1.1.0
				 */
				do_action('bp_after_activate_content'); ?>
			</div>
		</div>
	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 *
	 * @since 1.1.0
	 */
	do_action('bp_after_activation_page'); ?>

</div><!-- #buddypress -->