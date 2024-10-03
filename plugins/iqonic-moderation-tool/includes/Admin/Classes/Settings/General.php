<?php

namespace IMT\Admin\Classes\Settings;

use IMT\Admin\Classes\IMT_Settings;
use Redux;

class General extends IMT_Settings
{

	public function __construct()
	{
		$this->set_widget_option();
	}

	protected function set_widget_option()
	{

		Redux::set_section($this->opt_name, array(
			'title' 			=> esc_html__('Block/Unblock', IQONIC_MODERATION_TEXT_DOMAIN),
			'id' 				=> 'imt_block',
			'icon' 				=> 'el el-ban-circle',
			'desc'				=> esc_html__('Manage settings related to the block/unblock user', IQONIC_MODERATION_TEXT_DOMAIN),
			'customizer_width' 	=> '500px',
			'fields' => array(
				array(
					'id' 		=> 'is_block_unblock_feature',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Block/Unblock', IQONIC_MODERATION_TEXT_DOMAIN),
					'desc' 		=> esc_html__('Select this option for allowing user to block/unblock other users.', IQONIC_MODERATION_TEXT_DOMAIN),
					'options' 	=> array(
						'enable'	=> esc_html__('Enable', IQONIC_MODERATION_TEXT_DOMAIN),
						'disable' 	=> esc_html__('Disable', IQONIC_MODERATION_TEXT_DOMAIN)
					),
					'default' => 'enable'
				)
			)
		));

		Redux::set_section($this->opt_name, array(
			'title' 			=> esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
			'id' 				=> 'imt_report',
			'icon' 				=> 'el el-flag',
			'desc'				=> esc_html__('Manage settings related to the report activity,group,users', IQONIC_MODERATION_TEXT_DOMAIN),
			'customizer_width' 	=> '500px',
			'fields' => array(

				array(
					'id' 		=> 'is_report_feature',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Report', IQONIC_MODERATION_TEXT_DOMAIN),
					'desc' 		=> esc_html__('Enable this for allowing users to report activity, group, users.', IQONIC_MODERATION_TEXT_DOMAIN),
					'options' 	=> array(
						'enable'	=> esc_html__('Enable', IQONIC_MODERATION_TEXT_DOMAIN),
						'disable' 	=> esc_html__('Disable', IQONIC_MODERATION_TEXT_DOMAIN)
					),
					'default' => 'enable'
				),

				array(
					'id' 		=> 'is_user_report_feature',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Report User', IQONIC_MODERATION_TEXT_DOMAIN),
					'desc' 		=> esc_html__('Enable/Disable report user.', IQONIC_MODERATION_TEXT_DOMAIN),
					'options' 	=> array(
						'enable'	=> esc_html__('Enable', IQONIC_MODERATION_TEXT_DOMAIN),
						'disable' 	=> esc_html__('Disable', IQONIC_MODERATION_TEXT_DOMAIN)
					),
					'required'  => array('is_report_feature', '=', 'enable'),
					'default' => 'enable'
				),

				array(
					'id' 		=> 'is_activity_report_feature',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Report Activity', IQONIC_MODERATION_TEXT_DOMAIN),
					'desc' 		=> esc_html__('Enable/Disable report activity.', IQONIC_MODERATION_TEXT_DOMAIN),
					'options' 	=> array(
						'enable'	=> esc_html__('Enable', IQONIC_MODERATION_TEXT_DOMAIN),
						'disable' 	=> esc_html__('Disable', IQONIC_MODERATION_TEXT_DOMAIN)
					),
					'required'  => array('is_report_feature', '=', 'enable'),
					'default' => 'enable'
				),

				array(
					'id' 		=> 'is_group_report_feature',
					'type' 		=> 'button_set',
					'title' 	=> esc_html__('Report Group', IQONIC_MODERATION_TEXT_DOMAIN),
					'desc' 		=> esc_html__('Enable/Disable report groups.', IQONIC_MODERATION_TEXT_DOMAIN),
					'options' 	=> array(
						'enable'	=> esc_html__('Enable', IQONIC_MODERATION_TEXT_DOMAIN),
						'disable' 	=> esc_html__('Disable', IQONIC_MODERATION_TEXT_DOMAIN)
					),
					'required'  => array('is_report_feature', '=', 'enable'),
					'default' => 'enable'
				)
			)
		));

	}
}
