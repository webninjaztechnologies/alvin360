<?php

namespace IMT\Classes;

use IMT\Admin\Classes\IMT_Report;

/**
 * Fired during plugin activation
 *
 * @link       https://iqonic.design/
 * @since      1.2.0
 *
 * @package    Iqonic_Moderation_Tool
 * @subpackage Iqonic_Moderation_Tool/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.0
 * @package    Iqonic_Moderation_Tool
 * @subpackage Iqonic_Moderation_Tool/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class IMT_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.2.0
	 */
	public static function activate()
	{
		$cpt_report = new IMT_Report();
		$cpt_report->imt_cpt_report();

		if (!term_exists("Spam", "report-types"))
			wp_insert_term(
				'Spam',
				// the term
				'report-types'
			);

		if (!term_exists("Offensive", "report-types"))
			wp_insert_term(
				'Offensive',
				// the term
				'report-types'
			);

		if (!term_exists("Misleading or scam", "report-types"))
			wp_insert_term(
				'Misleading or scam',
				// the term
				'report-types'
			);

		if (!term_exists("Violent or abusive", "report-types"))
			wp_insert_term(
				'Violent or abusive',
				// the term
				'report-types'
			);
	}
}
