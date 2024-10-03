<?php

namespace IR\Rest_API\API;

use IR\Admin\Classes\IR_Database;
use WP_REST_Server;

class IR_Rest_Activity_Reaction extends IR_Database
{

	public $module = 'reaction';
	public $component = 'activity';
	public $name_space;
	public $count_reaction_query;
	public $reaction_list;

	function __construct()
	{
		$this->name_space = IR_API_NAMESPACE;
		parent::__construct();
		add_action('rest_api_init', [$this, 'ir_register_rest_activity_reaction_routes']);
	}
	public function ir_register_rest_activity_reaction_routes()
	{

		$activity_endpoint = '/' . $this->component . '/(?P<id>[\d]+)';
		register_rest_route(
			$this->name_space . '/api/v1/' . $this->module,
			$activity_endpoint,
			[
				'args'   => [
					'id' => array(
						'description' => __('A unique numeric ID for the activity.', IQONIC_REACTION_TEXT_DOMAIN),
						'type'        => 'integer',
					)
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [$this, 'ir_activity_reaction_get'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [$this, 'ir_activity_reaction_editable'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [$this, 'ir_activity_reaction_remove'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}

	/**
	 * Retrieve activity reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response List of activitie reaction response data.
	 */
	public function ir_activity_reaction_get($request)
	{

		/**
		 * @var $parameters retrive reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Activity id for retriving all reactions.
		 * @param int $reaction_id Pass reaction id if only wants to retrive perticular type reactions.
		 * @param int $page Current page number.
		 * @param int $per_page Number of posts to show per page.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = apply_filters("ir_rest_get_activity_reaction_params", irRecursiveSanitizeTextField($parameters));

		$args = [
			'page' 		=> isset($parameters['page']) ? $parameters['page'] : 0,
			'per_page' 	=> isset($parameters['per_page']) ? $parameters['per_page'] : 20
		];

		$activity_id = $parameters['id'];
		
		$this->count_reaction_query = $this->get_count_act_reaction_query();
		$result = $this->execute_query($this->count_reaction_query . " WHERE activity_id={$activity_id}");
		$count = $this->count_obj($result);
		if (isset($parameters['reaction_id'])) {
			$fetch_reactions = $this->getReactionByReactionId($activity_id, $parameters['reaction_id'], $args);
			if (!$fetch_reactions) return [];

			$rest_single_reaction_list = rest_single_reaction_list($fetch_reactions, $this->component);

			$response = ["reactions" => $rest_single_reaction_list, "count" 	=> $count];
			return ir_comman_custom_response(apply_filters("ir_rest_activity_reaction_response", $response, $request));
		}

		$fetch_reactions = $this->getReactions($activity_id, $args);
		if (!$fetch_reactions) return [];

		$rest_reaction_list = rest_reaction_list($fetch_reactions, $this->component);
		$response = ["reactions" => $rest_reaction_list, "count" 	=> $count];

		return ir_comman_custom_response(apply_filters("ir_rest_activity_reaction_response", $response, $request));
	}


	/**
	 * Add/Edit activity reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response boolean is reacted.
	 */
	public function ir_activity_reaction_editable($request)
	{
		/**
		 * @var $parameters add/edit reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Activity id to add/edit reaction in.
		 * @param int $user_id User id who reacted.
		 * @param int $reaction_id Add / Edit perticular reaction.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = svRecursiveSanitizeTextField($parameters);
		$parameters = apply_filters("ir_rest_insert/update_activity_reaction_params", irRecursiveSanitizeTextField($parameters));

		do_action("ir_before_rest_activity_reaction_insert/update", $request);

		$user_id = isset($parameters['user_id']) ? (int) $parameters['user_id'] : '';
		if (empty($user_id)) return [];

		$id = isset($parameters['id']) ? (int) $parameters['id'] : '';
		$reaction_id = isset($parameters['reaction_id']) ? (int) $parameters['reaction_id'] : '';


		$args = array(
			'reaction_id'   => $reaction_id,
			'activity_id'   => $id,
			'user_id'       => $user_id,
		);
		$where = array(
			'activity_id'   => $id,
			'user_id'       => $user_id,
		);

		$is_reacted = $this->insertReactionActivity($args, $where);

		if (!$is_reacted) return [];

		do_action("ir_after_rest_activity_reaction_insert/update", $is_reacted, $request);

		iqonic_set_user_reaction_notification($id, $user_id);
		return ir_comman_custom_response(["is_reacted" => $is_reacted], 200);
	}

	/**
	 * Remove activity reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response boolean is removed.
	 */
	public function ir_activity_reaction_remove($request)
	{
		/**
		 * @var $parameters remove reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Activity id to remove reaction.
		 * @param int $user_id User id who remove reaction.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = apply_filters("ir_rest_remove_activity_reaction_params", irRecursiveSanitizeTextField($parameters));

		do_action("ir_before_rest_activity_reaction_removed", $request);

		$user_id = isset($parameters['user_id']) ? (int) $parameters['user_id'] : '';
		if (empty($user_id)) return [];

		$id = isset($parameters['id']) ? $parameters['id'] : '';

		$args = [
			'activity_id'	=> $id,
			'user_id' 		=> $user_id,
		];

		$is_removed = $this->deleteUserReactions($args);

		if (!$is_removed) $is_removed = false;

		do_action("ir_after_rest_activity_reaction_removed", $is_removed, $request);

		iqonic_remove_user_reaction_notification($id, $user_id);
		return ir_comman_custom_response(["is_removed" => $is_removed], 200);
	}

	public function get_count_act_reaction_query()
	{
		$this->reaction_list = $this->getAllReactionsList();
		$columns = ["COUNT(*) AS `total`"];
		foreach ($this->reaction_list as $reaction) {
			$columns[] = "sum(CASE WHEN r.reaction_id ={$reaction->id} AND u.id IS NOT NULL THEN 1 ELSE 0 END) AS `{$reaction->id}`";
		}

		$columns = implode(",", $columns);
		$table = $this->iq_reaction_activity . " r LEFT JOIN wp_users u ON r.user_id = u.id";
		$query = "SELECT {$columns} FROM {$table}";

		return $query;
	}

	public function count_obj($result)
	{
		if (!isset($result[0])) return [];
		$result = (array) $result[0];
		$total = 0;
		$count_obj = $this->reaction_list;

		foreach ($count_obj as $reaction) {
			$id = $reaction->id;
			$reaction->title = $reaction->name;
			$reaction->icon = $reaction->image_url;
			$reaction->count = $result[$id] ? (int)$result[$id] : 0;

			$total += $result[$id];

			unset($reaction->image_url);
			unset($reaction->name);
		}

		$count[] = (object)["id" => 0, "title" => "all", "icon" => "", "count" => (int) $total];
		$count = array_merge($count, $count_obj);

		return $count;
	}
}
