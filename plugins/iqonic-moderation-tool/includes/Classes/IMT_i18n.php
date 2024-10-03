<?php

namespace IMT\Classes;
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://iqonic.design/
 * @since      1.2.0
 *
 * @package    Iqonic_Moderation_Tool
 * @subpackage Iqonic_Moderation_Tool/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.2.0
 * @package    Iqonic_Moderation_Tool
 * @subpackage Iqonic_Moderation_Tool/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class IMT_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.2.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'iqonic-moderation-tool',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
