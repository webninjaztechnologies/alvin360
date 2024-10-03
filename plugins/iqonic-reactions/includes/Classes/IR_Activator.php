<?php

namespace IR\Classes;

use IR\Admin\Classes\IR_Database;

// use IR\Admin\Classes\IR_Report;

/**
 * Fired during plugin activation
 *
 * @link       https://iqonic.design/
 * @since      1.0.0
 *
 * @package    Iqonic_Reaction
 * @subpackage Iqonic_Reaction/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Iqonic_Reaction
 * @subpackage Iqonic_Reaction/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class IR_Activator
{
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $flag;
		$db_obj = new IR_Database();
        
		$db_obj->createReactionListTable();
		$db_obj->createReactionActivityTable();
		$db_obj->createCommentReactionTable();

		if($flag != 1) {
			$db_obj->insertDefaultReaction(); //saves default reaction in reaction list table
			$db_obj->insertDefaultReactionsList(); 
			set_default_reaction_redux();
		}
	}
	
}