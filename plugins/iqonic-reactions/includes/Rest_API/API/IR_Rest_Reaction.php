<?php

namespace IR\Rest_API\API;

use IR\Admin\Classes\IR_Database;
use IR\Admin\Classes\IR_Settings;
use WP_REST_Server;

class IR_Rest_Reaction extends IR_Database
{

	public $module = 'reaction';

	public $name_space;

	function __construct()
	{
		$this->name_space = IR_API_NAMESPACE;
		parent::__construct();
		add_action('rest_api_init', [$this, 'ir_register_rest_reaction_routes']);
	}
	public function ir_register_rest_reaction_routes()
	{

		register_rest_route($this->name_space . '/api/v1/' . $this->module, '/reaction-list', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'ir_reaction_list'],
			'permission_callback' => '__return_true'
		));
		register_rest_route($this->name_space . '/api/v1/' . $this->module, '/default-reaction', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'ir_default_reaction'],
			'permission_callback' => '__return_true'
		));
	}

	public function ir_reaction_list($request)
	{
		$response = $this->getAllReactionsList();

		if ($response)
			return ir_comman_custom_response(apply_filters("ir_rest_reaction_list_response", $response, $request));

		return [];
	}

	public function ir_default_reaction($request)
	{
		$default_reaction 		= IR_Settings::get_ir_option("default_reaction");
		$default_reaction_image = IR_Settings::get_ir_option("default_reaction_image");

		if (empty($default_reaction)) return [];

		$response = $this->get_default_reaction($default_reaction);
		if ($response) {
			$response[0]->default_image_url = isset($default_reaction_image["url"]) ? $default_reaction_image["url"] : "";
			return ir_comman_custom_response(apply_filters("ir_rest_default_reaction_response", $response, $default_reaction, $request));
		}
		return [];
	}
}
