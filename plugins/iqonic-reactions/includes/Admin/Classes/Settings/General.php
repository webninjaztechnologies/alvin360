<?php

namespace IR\Admin\Classes\Settings;

use IR\Admin\Classes\IR_Database;
use IR\Admin\Classes\IR_Settings;
use Redux;

class General extends IR_Settings
{
	public function __construct()
	{
		$this->set_widget_option();
		$this->redux_save_change_function();
	}

	public function set_widget_option()
	{
		Redux::set_section($this->opt_name, array(
			'title' 			=> esc_html__('Reactions', IQONIC_REACTION_TEXT_DOMAIN),
			'id' 				=> 'ir_reaction',
			'icon' 				=> 'custom-smile',
			'desc'				=> esc_html__('Enable/Disable the reactions module on the buddypress activity feed', IQONIC_REACTION_TEXT_DOMAIN),
			'customizer_width' 	=> '500px',
			'fields' => array(
				array(
					'id' 			=> 'reactions_field',
					'type' 			=> 'repeater',
					'title' 		=> esc_html__('Reaction Lists', IQONIC_REACTION_TEXT_DOMAIN),
					'subtitle' 		=> esc_html__('You can have upto maximum of 7 reactions', IQONIC_REACTION_TEXT_DOMAIN),
					'limit' 		=> 7,
					'sortable' 		=> false,
					'fields' 		=> array(
						array(
							'id' 			=> 'reaction_name',
							'type' 			=> 'text',
							'title' 		=>  esc_html__('Reaction Name', IQONIC_REACTION_TEXT_DOMAIN),
							'placeholder'	=> esc_html__("Enter Reaction Name", IQONIC_REACTION_TEXT_DOMAIN),
						),
						array(
							'id' 			=> 'reaction_image',
							'type' 			=> 'media',
							'title' 		=>  esc_html__('Upload Reaction Image/Logo/SVG', IQONIC_REACTION_TEXT_DOMAIN),
							'url'      		=> false,
							'read-only'		=> false,
						),
					),
				),
				array(
					'id' 		=> 'default_reaction',
					'type' 		=> 'select',
					'title' 	=>  esc_html__('Default Reaction', IQONIC_REACTION_TEXT_DOMAIN),
					'desc' 		=>  esc_html__('Changes The Default Reaction, When User Clicks On The Reaction Button.', IQONIC_REACTION_TEXT_DOMAIN),
					'options' 	=> active_reaction_list(),
					'default' 	=> esc_html__("like", IQONIC_REACTION_TEXT_DOMAIN),
				),
				array(
					'id' 		=> 'default_reaction_image',
					'type' 		=> 'media',
					'title' 	=>  esc_html__('Upload Reaction Image/Logo/SVG', IQONIC_REACTION_TEXT_DOMAIN),
					'desc' 		=>  esc_html__('Default Image for reaction.', IQONIC_REACTION_TEXT_DOMAIN),
					'url'      	=> false,
					'read-only'	=> false,
				),
			)
		));

		$theme = wp_get_theme();
		if ($theme->name == 'SocialV') {
			Redux::set_section($this->opt_name, array(
				'title' 			=> esc_html__('Convert Like\'s Into Reaction', IQONIC_REACTION_TEXT_DOMAIN),
				'id' 				=> 'ir_convert_bp_likes',
				'icon' 				=> 'custom-reset',
				'customizer_width' 	=> '500px',
				'fields' 			=> array(
					array(
						'id' 		=> 'convert_bp_likes_into_reaction',
						'type' 		=> 'checkbox',
						'title' 	=>  esc_html__('Convert\'s The BuddyPress Likes into the Reaction "Like"', IQONIC_REACTION_TEXT_DOMAIN),
						'desc' 		=>  esc_html__('This option will convert the buddypress likes into the reaction "like". The Option will automatically be set to no after all the reactions are converted.', IQONIC_REACTION_TEXT_DOMAIN),
						'default'   => '1'
					),
				),
			));
		}
	}

	public function redux_save_change_function()
	{
		add_action('redux/options/ir_options/saved', [$this, 'save_reaction_settings']);
		add_filter('redux/validate/ir_options/defaults_section', function () {
			$db_obj = new IR_Database();
			$db_obj->truncateReactionListTable();
			return $db_obj->insertDefaultReaction();
		});
		add_filter('redux/validate/ir_options/defaults', function () {
			$db_obj = new IR_Database();
			$db_obj->truncateReactionListTable();
			return $db_obj->insertDefaultReaction();
		});
	}

	function save_reaction_settings()
	{
		$theme = wp_get_theme();
		if ($theme->name == 'SocialV') {
			global $ir_options;
			$is_convert = $ir_options['convert_bp_likes_into_reaction'];
			if ($is_convert == "1") {
				$db_obj = new IR_Database();
				$db_obj->convert_bp_likes($ir_options);
			}
		}
	}
}
