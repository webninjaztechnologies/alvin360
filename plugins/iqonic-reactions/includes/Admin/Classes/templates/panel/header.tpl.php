<?php

/**
 * The template for the panel header area.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version:    4.0.0
 */

$tip_title = esc_html__('Developer Mode Enabled', IQONIC_REACTION_TEXT_DOMAIN);

if ($this->parent->args_class->dev_mode_forced) {
	$is_debug     = false;
	$is_localhost = false;
	$debug_bit    = '';

	if (Redux_Helpers::is_wp_debug()) {
		$is_debug  = true;
		$debug_bit = esc_html__('WP_DEBUG is enabled', IQONIC_REACTION_TEXT_DOMAIN);
	}

	$localhost_bit = '';
	if (Redux_Helpers::is_local_host()) {
		$is_localhost  = true;
		$localhost_bit = esc_html__('you are working in a localhost environment', IQONIC_REACTION_TEXT_DOMAIN);
	}

	$conjunction_bit = '';
	if ($is_localhost && $is_debug) {
		$conjunction_bit = ' ' . esc_html__('and', IQONIC_REACTION_TEXT_DOMAIN) . ' ';
	}

	$tip_msg = esc_html__('This has been automatically enabled because', IQONIC_REACTION_TEXT_DOMAIN) . ' ' . $debug_bit . $conjunction_bit . $localhost_bit . '.';
} else {
	$tip_msg = esc_html__('If you are not a developer, your theme/plugin author shipped with developer mode enabled. Contact them directly to fix it.', IQONIC_REACTION_TEXT_DOMAIN);
}
global $ir_options;

echo '<div id="redux-header">';
	 if (!empty($this->parent->args['display_name'])) { 
		echo '<div class="display_header">
			<div class="logo-wrap">';
				 if (isset($this->parent->args['dev_mode']) && $this->parent->args['dev_mode']) { 
					echo '<div class="redux-dev-mode-notice-container redux-dev-qtip" qtip-title="'. esc_attr($tip_title) .'" qtip-content="'. esc_attr($tip_msg) .'">
						<span class="redux-dev-mode-notice">';
						 esc_html_e('Developer Mode Enabled', IQONIC_REACTION_TEXT_DOMAIN); 
						echo '</span></div>';
				 }
				echo '<a href="'. esc_url("https://iqonic.design/") .'" target="_blank">'; 
				echo '<img class="img-fluid redux-brand logo" src="'. esc_url(IQONIC_REACTION_URL . 'includes/assets/images/logo-white.webp') .'" alt="'. esc_attr('logo', IQONIC_REACTION_TEXT_DOMAIN) .'"></a>';

				 if (!empty($this->parent->args['display_version'])) { 
					echo '<span>'. wp_kses_post($this->parent->args['display_version']) .'</span>';
				 } 
		    echo '</div>
		</div>';
	 } 
	echo '<div id="info_bar">
		<a href="javascript:void(0);" class="expand_options '. esc_attr(($this->parent->args['open_expanded']) ? ' expanded' : '') .'"'. (true === $this->parent->args['hide_expand'] ? ' style="display: none;"' : '') .'>
			'. esc_attr('Expand', IQONIC_REACTION_TEXT_DOMAIN) .'
		</a>
		<div class="redux-search">
			<span class="search-input">
				<i class="custom-Search"></i>
				<input type="text" class="socialv-redux-search" placeholder="'. esc_attr("search", IQONIC_REACTION_TEXT_DOMAIN) .'" />
			</span>
		</div>
		<a href="javascript:void(0);" class="redux-dark-mode">
			<i class="custom-moon-icon"></i>
		</a>

		<div class="redux-action_bar">
			<span class="spinner"></span>';
			
			if (false === $this->parent->args['hide_save']) {
				submit_button(esc_html__('Save Changes', IQONIC_REACTION_TEXT_DOMAIN), 'primary', 'redux_save', false, array('id' => 'redux_top_save'));
			}
			
		echo '</div>
		<div class="redux-ajax-loading" alt="'. esc_attr('Working...', IQONIC_REACTION_TEXT_DOMAIN) .'">&nbsp;</div>
		<div class="clear"></div>
	</div>
</div>';