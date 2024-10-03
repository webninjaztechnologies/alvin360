<?php

/**
 * SocialV\Utility\Theme_Setup\Component class
 *
 * @package socialv
 */

namespace SocialV\Utility\Theme_Setup;

use Merlin;

use SocialV\Utility\Component_Interface;

/**
 * Class for integrating with setup wizard.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface
{

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string
	{
		return 'theme_setup';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize()
	{
		$this->socialv_setup_wizard_config();
	}

	/**
	 * Define Setup wizard default values
	 *
	 *
	 * This function init merlin class and set default values
	 */
	function socialv_setup_wizard_config()
	{
		$theme_detail = wp_get_theme();
		$wizard = new Merlin(

			$config = array(
				'directory'            => 'Merlin', // Location / directory where Merlin WP is placed in your theme.
				'merlin_url'           => 'socialv-setup', // The wp-admin page slug where Merlin WP loads.
				'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
				'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
				'child_action_btn_url' => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/', // URL for the 'child-action-link'.
				'dev_mode'             => true, // Enable development mode for testing.
				'license_step'         => false, // EDD license activation step.
				'license_required'     => false, // Require the license activation step.
				'license_help_url'     => '', // URL for the 'license-tooltip'.
				'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
				'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
				'ready_big_button_url' => home_url('/'), // Link for the big button on the ready step.
			),
			$strings = array(
				'admin-menu'               => esc_html__('Theme Setup', 'socialv'),

				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'            => esc_html__('%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'socialv'),
				'return-to-dashboard'      => esc_html__('Return to the dashboard', 'socialv'),
				'ignore'                   => esc_html__('Disable this wizard', 'socialv'),

				'btn-skip'                 => esc_html__('Skip', 'socialv'),
				'btn-next'                 => esc_html__('Next', 'socialv'),
				'btn-start'                => esc_html__('Start', 'socialv'),
				'btn-no'                   => esc_html__('Cancel', 'socialv'),
				'btn-plugins-install'      => esc_html__('Install', 'socialv'),
				'btn-child-install'        => esc_html__('Install', 'socialv'),
				'btn-content-install'      => esc_html__('Install', 'socialv'),
				'btn-import'               => esc_html__('Import', 'socialv'),
				'btn-license-activate'     => esc_html__('Activate', 'socialv'),
				'btn-license-skip'         => esc_html__('Later', 'socialv'),

				/* translators: Theme Name */
				'license-header%s'         => esc_html__('Activate %s', 'socialv'),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__('%s is Activated', 'socialv'),
				/* translators: Theme Name */
				'license%s'                => esc_html__('Enter your license key to enable remote updates and theme support.', 'socialv'),
				'license-label'            => esc_html__('License key', 'socialv'),
				'license-success%s'        => esc_html__('The theme is already registered, so you can go to the next step!', 'socialv'),
				'license-json-success%s'   => esc_html__('Your theme is activated! Remote updates and theme support are enabled.', 'socialv'),
				'license-tooltip'          => esc_html__('Need help?', 'socialv'),

				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__('Welcome to %s', 'socialv'),
				'welcome-header-success%s' => esc_html__('Hi. Welcome back', 'socialv'),
				'welcome%s'                => esc_html($theme_detail['Description']),
				'welcome-success%s'        => esc_html($theme_detail['Description']),

				'child-header'             => esc_html__('Install Child Theme', 'socialv'),
				'child-header-success'     => esc_html__('You\'re good to go!', 'socialv'),
				'child'                    => esc_html__('Let\'s build & activate a child theme so you may easily make theme changes.', 'socialv'),
				'child-success%s'          => esc_html__('Your child theme has already been installed and ready activated, if it wasn\'t already.', 'socialv'),
				'child-action-link'        => esc_html__('Learn more about child themes', 'socialv'),
				'child-json-success%s'     => esc_html__('Awesome. Your child theme has already been installed and ready to activated.', 'socialv'),
				'child-json-already%s'     => esc_html__('Awesome. Your child theme has been created and ready to activated.', 'socialv'),

				'plugins-header'           => esc_html__('Install Plugins', 'socialv'),
				'plugins-header-success'   => esc_html__('You\'re up to speed!', 'socialv'),
				'plugins'                  => esc_html__('Let\'s install some essential WordPress plugins to get your site up to speed.', 'socialv'),
				'plugins-success%s'        => esc_html__('The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'socialv'),
				'plugins-action-link'      => esc_html__('Advanced', 'socialv'),

				'import-header'            => esc_html__('Import Content', 'socialv'),
				'import'                   => esc_html__('Let\'s import content to your website, to help you get familiar with the theme.', 'socialv'),
				'import-action-link'       => esc_html__('Advanced', 'socialv'),

				'ready-header'             => esc_html__('All done. Have fun!', 'socialv'),

				/* translators: Theme Author */
				'ready%s'                  => esc_html__('Your theme has been all set up. Enjoy your new theme by %s.', 'socialv'),
				'ready-action-link'        => esc_html__('Extras', 'socialv'),
				'ready-big-button'         => esc_html__('View your website', 'socialv'),
				'ready-link-1'             => sprintf('<a href="%1$s" class="merlin__button merlin__button--knockout merlin__button--no-chevron merlin__button--external" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__('Explore WordPress', 'socialv')),
				'ready-link-2'             => sprintf('<a href="%1$s" class="merlin__button merlin__button--knockout merlin__button--no-chevron merlin__button--external" target="_blank">%2$s</a>', 'https://iqonic.desky.support/', esc_html__('Get Theme Support', 'socialv')),
				'ready-link-3'             => sprintf('<a href="%1$s" class="merlin__button merlin__button--knockout merlin__button--no-chevron merlin__button--external">%2$s</a>', admin_url('admin.php?page=_socialv_options&tab=1'), esc_html__('Start Customizing', 'socialv')),
			)
		);

		//woocomerce newly register user avoide
		if(!get_option( 'woocommerce_newly_installed', false ) || (get_option( 'woocommerce_newly_installed', false ) == "yes")){
			update_option( 'woocommerce_newly_installed', 'no' );
		}
	}
}
