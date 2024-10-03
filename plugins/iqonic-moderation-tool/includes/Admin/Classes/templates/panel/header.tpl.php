<?php

/**
 * The template for the panel header area.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version:    4.0.0
 */

$tip_title = esc_html__('Developer Mode Enabled', IQONIC_MODERATION_TEXT_DOMAIN);

if ($this->parent->args_class->dev_mode_forced) {
	$is_debug     = false;
	$is_localhost = false;
	$debug_bit    = '';

	if (Redux_Helpers::is_wp_debug()) {
		$is_debug  = true;
		$debug_bit = esc_html__('WP_DEBUG is enabled', IQONIC_MODERATION_TEXT_DOMAIN);
	}

	$localhost_bit = '';
	if (Redux_Helpers::is_local_host()) {
		$is_localhost  = true;
		$localhost_bit = esc_html__('you are working in a localhost environment', IQONIC_MODERATION_TEXT_DOMAIN);
	}

	$conjunction_bit = '';
	if ($is_localhost && $is_debug) {
		$conjunction_bit = ' ' . esc_html__('and', IQONIC_MODERATION_TEXT_DOMAIN) . ' ';
	}

	$tip_msg = esc_html__('This has been automatically enabled because', IQONIC_MODERATION_TEXT_DOMAIN) . ' ' . $debug_bit . $conjunction_bit . $localhost_bit . '.';
} else {
	$tip_msg = esc_html__('If you are not a developer, your theme/plugin author shipped with developer mode enabled. Contact them directly to fix it.', IQONIC_MODERATION_TEXT_DOMAIN);
}
global $imt_options;
?>
<div id="redux-header">
	<?php if (!empty($this->parent->args['display_name'])) { ?>
		<div class="display_header">
			<div class="logo-wrap">
				<?php if (isset($this->parent->args['dev_mode']) && $this->parent->args['dev_mode']) { ?>
					<div class="redux-dev-mode-notice-container redux-dev-qtip" qtip-title="<?php echo esc_attr($tip_title); ?>" qtip-content="<?php echo esc_attr($tip_msg); ?>">
						<span class="redux-dev-mode-notice"><?php esc_html_e('Developer Mode Enabled', IQONIC_MODERATION_TEXT_DOMAIN); ?></span>
					</div>
				<?php } ?>
				<a href="<?php echo esc_url("https://iqonic.design/"); ?>" target="_blank">
				<img class="img-fluid redux-brand logo" src="<?php echo esc_url(IQONIC_MODERATION_TOOL_URL . 'includes/Admin/assets/images/logo-white.webp'); ?>'" alt="iqonic-moderation-tool"></a>

				<?php if (!empty($this->parent->args['display_version'])) { ?>
					<span><?php echo wp_kses_post($this->parent->args['display_version']); ?></span>
				<?php } ?>
		    </div>
		</div>
	<?php } ?>
	<div id="info_bar">
		<a href="javascript:void(0);" class="expand_options<?php echo esc_attr(($this->parent->args['open_expanded']) ? ' expanded' : ''); ?>" <?php echo (true === $this->parent->args['hide_expand'] ? ' style="display: none;"' : ''); ?>>
			<?php esc_attr_e('Expand', IQONIC_MODERATION_TEXT_DOMAIN); ?>
		</a>
		<div class="redux-search">
			<span class="search-input">
				<i class="custom-Search"></i>
				<input type="text" class="socialv-redux-search" placeholder="<?php esc_attr_e("search", IQONIC_MODERATION_TEXT_DOMAIN); ?>" />
			</span>
		</div>
		<a href="javascript:void(0);" class="redux-dark-mode">
			<i class="custom-moon-icon"></i>
		</a>

		<div class="redux-action_bar">
			<span class="spinner"></span>
			<?php
			if (false === $this->parent->args['hide_save']) {
				submit_button(esc_attr__('Save Changes', IQONIC_MODERATION_TEXT_DOMAIN), 'primary', 'redux_save', false, array('id' => 'redux_top_save'));
			}
			?>
		</div>
		<div class="redux-ajax-loading" alt="<?php esc_attr_e('Working...', IQONIC_MODERATION_TEXT_DOMAIN); ?>">&nbsp;</div>
		<div class="clear"></div>
	</div>
</div>