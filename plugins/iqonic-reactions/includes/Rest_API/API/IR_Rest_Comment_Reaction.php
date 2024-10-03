<?php

namespace IR\Rest_API\API;

use IR\Admin\Classes\IR_Database;
use WP_REST_Server;

class IR_Rest_Comment_Reaction extends IR_Database
{

	public $module = 'reaction';
	public $component = 'comment';
	public $name_space;
	public $reaction_list;
	public $count_reaction_query;

	function __construct()
	{
		$this->name_space = IR_API_NAMESPACE;
		parent::__construct();
		add_action('rest_api_init', [$this, 'ir_register_rest_comment_reaction_routes']);
	}
	public function ir_register_rest_comment_reaction_routes()
	{

		$comment_endpoint = '/' . $this->component . '/(?P<id>[\d]+)';
		register_rest_route(
			$this->name_space . '/api/v1/' . $this->module,
			$comment_endpoint,
			[
				'args'   => [
					'id' => array(
						'description' => __('A unique numeric ID for the comment.', IQONIC_REACTION_TEXT_DOMAIN),
						'type'        => 'integer',
					)
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [$this, 'ir_comment_reaction_get'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [$this, 'ir_comment_reaction_editable'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [$this, 'ir_comment_reaction_remove'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}


	/**
	 * Retrieve comment reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response List of activitie reaction response data.
	 */
	public function ir_comment_reaction_get($request)
	{

		/**
		 * @var $parameters retrive reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Comment id for retriving all reactions.
		 * @param int $reaction_id Pass reaction id if only wants to retrive perticular type reactions.
		 * @param int $page Current page number.
		 * @param int $per_page Number of posts to show per page.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = apply_filters("ir_rest_get_comment_reaction_params", irRecursiveSanitizeTextField($parameters));

		$id = isset($parameters['id']) ? (int) $parameters['id'] : '';
		$reaction_id = isset($parameters['reaction_id']) ? (int) $parameters['reaction_id'] : '';
		$comment = bp_activity_get(["in" => $id, "display_comments" => 1]);

		if (!$comment) return [];

		$comment = $comment["activities"];
		$activity_id = $comment[0]->item_id;

		$args = apply_filters(
			"ir_rest_comment_reaction_args",
			[
				'page' 		=> isset($parameters['page']) ? $parameters['page'] : 0,
				'per_page' 	=> isset($parameters['per_page']) ? $parameters['per_page'] : 20
			],
			$request
		);
		$this->count_reaction_query = $this->get_count_cmt_reaction_query();
		$result = $this->execute_query($this->count_reaction_query . " WHERE comment_id={$id}");
		$count = $this->count_obj($result);
		if (!empty($reaction_id)) {
			$fetch_reactions = $this->getCommentReactionByReactionId($activity_id, $reaction_id, $id, $args);
			if (!$fetch_reactions) return [];

			$rest_single_reaction_list = rest_single_reaction_list($fetch_reactions, $this->component);
			$response = ["reactions" => $rest_single_reaction_list, "count" 	=> $count];
			return ir_comman_custom_response(apply_filters("ir_rest_comment_reaction_response", $response, $request));
		}

		$fetch_reactions = $this->getCommentsReactionList($activity_id, $id, $args);
		if (!$fetch_reactions) return [];

		$rest_reaction_list = rest_reaction_list($fetch_reactions, $this->component);
		$response = ["reactions" => $rest_reaction_list, "count" 	=> $count];
		return ir_comman_custom_response(apply_filters("ir_rest_comment_reaction_response", $response, $request));
	}

	/**
	 * Add/Edit comment reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response Boolean is reacted.
	 */
	public function ir_comment_reaction_editable($request)
	{

		/**
		 * @var $parameters add/edit reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Comment id to add/edit reaction in.
		 * @param int $user_id User id who reacted.
		 * @param int $reaction_id Add / Edit perticular reaction.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = svRecursiveSanitizeTextField($parameters);
		$parameters = apply_filters("ir_rest_insert/update_comment_reaction_params", irRecursiveSanitizeTextField($parameters));

		do_action("ir_before_rest_comment_reaction_insert/update", $request);

		$user_id = isset($parameters['user_id']) ? (int) $parameters['user_id'] : '';
		if (empty($user_id)) return [];

		$id = isset($parameters['id']) ? (int) $parameters['id'] : '';
		$reaction_id = isset($parameters['reaction_id']) ? (int) $parameters['reaction_id'] : '';



		$comment = bp_activity_get(["in" => $id, "display_comments" => 1]);

		if (!$comment) return [];

		$comment = $comment["activities"][0];
		$activity_id = $comment->item_id;

		$args = array(
			'reaction_id'   => $reaction_id,
			'comment_id'	=> $id,
			'activity_id'   => $activity_id,
			'user_id'       => $user_id,
		);

		$where = array(
			'comment_id'	=> $id,
			'activity_id'   => $activity_id,
			'user_id'       => $user_id,
		);

		$is_reacted = $this->insertCommentReactionActivity($args, $where);

		if (!$is_reacted) return [];

		do_action("ir_after_rest_comment_reaction_insert/update", $is_reacted, $request);

		iqonic_set_comment_reaction_notification($activity_id, $id, $user_id);

		return ir_comman_custom_response(["is_reacted" => $is_reacted], 200);
	}

	/**
	 * Remove comment reaction.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response boolean is removed.
	 */
	public function ir_comment_reaction_remove($request)
	{
		/**
		 * @var $parameters remove reaction.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id Comment id to remove reaction.
		 * @param int $user_id User id who remove reaction.
		 * 
		 */
		$parameters = $request->get_params();
		$parameters = apply_filters("ir_rest_remove_comment_reaction_params", irRecursiveSanitizeTextField($parameters));

		do_action("ir_before_rest_comment_reaction_removed", $request);

		$user_id = isset($parameters['user_id']) ? (int) $parameters['user_id'] : '';
		if (empty($user_id)) return [];

		$id = isset($parameters['id']) ? $parameters['id'] : '';

		$comment = bp_activity_get(["in" => $id, "display_comments" => 1]);
		if (!$comment)  return [];

		$comment = $comment["activities"];
		$activity_id = $comment[0]->item_id;

		$args = [
			'comment_id'	=> $id,
			'activity_id'   => $activity_id,
			'user_id' 		=> $user_id,
		];


		$is_removed = $this->deleteCommentReactionActivity($args);

		if (!$is_removed) $is_removed = false;

		do_action("ir_after_rest_comment_reaction_removed", $is_removed, $request);

		iqonic_remove_comment_reaction_notification($activity_id, $user_id, $id);
		return ir_comman_custom_response(["is_removed" => $is_removed], 200);
	}
	public function get_count_cmt_reaction_query()
	{
		$this->reaction_list = $this->getAllReactionsList();
		$columns = ["COUNT(*) AS `total`"];
		foreach ($this->reaction_list as $reaction) {
			$columns[] = "sum(CASE WHEN reaction_id ={$reaction->id}  THEN 1 ELSE 0 END) AS `{$reaction->id}`";
		}

		$columns = implode(",", $columns);
		$table = $this->iq_comment_reaction;
		$query = "SELECT {$columns} FROM {$table}";

		return $query;
	}
	public function count_obj($result)
	{
		if (!isset($result[0])) return [];
		$result = (array) $result[0];

		$count_obj = $this->reaction_list;

		foreach ($count_obj as $reaction) {
			$id = $reaction->id;
			$reaction->title = $reaction->name;
			$reaction->icon = $reaction->image_url;
			$reaction->count = $result[$id] ? (int)$result[$id] : 0;

			unset($reaction->image_url);
			unset($reaction->name);
		}

		$count[] = (object)["id" => 0, "title" => "all",  "icon" => "", "count" => (int) $result["total"]];
		$count = array_merge($count, $count_obj);

		return $count;
	}
}
