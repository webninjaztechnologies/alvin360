<?php

function iq_friend_suggestions_widgets()
{
	register_widget('Iqonic_Friend_Suggestions');
}
add_action('widgets_init', 'iq_friend_suggestions_widgets');

/*-------------------------------------------
		Iqonic Friend Suggestions
--------------------------------------------*/
class Iqonic_Friend_Suggestions extends WP_Widget
{

	protected $friend_suggestions;

	function __construct()
	{
		if (defined('IQONIC_EXTENSION_VERSION')) {
			$this->version = IQONIC_EXTENSION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		parent::__construct(
			// Base ID of your widget
			'Iqonic_Friend_Suggestions',
			// Widget name will appear in UI
			esc_html('Iqonic Friend Suggestions', IQONIC_EXTENSION_TEXT_DOMAIN),
			// Widget description
			array('description' => esc_html('Display Friend suggestions widget area', IQONIC_EXTENSION_TEXT_DOMAIN))
		);

		// Save Removed Suggestions.
		add_action('wp_ajax_socialv_friends_refused_suggestion', array($this, 'hide_suggestion'));
	}

	public function call_dependent_scripts()
	{
		wp_enqueue_script('iqonic-refused_suggestion', IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/js/custom.js', array('jquery'), $this->version, true);
	}
	/**
	 * Back-end widget form.
	 */
	public function form($instance)
	{

		// Get Widget Data.
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__('Suggestion For You', IQONIC_EXTENSION_TEXT_DOMAIN),
				'show_buttons' => 'on',
				'limit' => '5',
			)
		);

		// Get Input's Data.
		$limit = absint($instance['limit']);
		$title = strip_tags($instance['title']); ?>

		<!-- Title. -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" class="tiny-text" value="<?php echo esc_attr($title); ?>">
		</p>

		<!-- Suggestions Number. -->
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Suggestions Number:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
				<input class="tiny-text" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" style="width: 30%">
			</label>
		</p>

		<!-- Display Buttons -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_buttons'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_buttons')); ?>" name="<?php echo esc_attr($this->get_field_name('show_buttons')); ?>">
			<label for="<?php echo $this->get_field_id('show_buttons'); ?>"><?php esc_html_e('Show Buttons', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update($new_instance, $old_instance)
	{

		$instance = array();

		$instance = $old_instance;
		$instance['limit'] = absint($new_instance['limit']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_buttons'] = $new_instance['show_buttons'];

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget($args, $instance)
	{

		if (!is_user_logged_in() || !bp_is_active('friends')) {
			return false;
		}

		// Get Friend Suggestions
		$this->friend_suggestions = $this->get_friend_suggestions(bp_loggedin_user_id(), $instance);

		// Hide Widget IF There's No suggestions.
		if (empty($this->friend_suggestions)) {
			return false;
		}

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);


		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];

		$this->get_suggestions_list($instance);

		echo $args['after_widget'];
	}

	/**
	 * Get Friend Suggestions.
	 */
	function get_friend_suggestions($user_id, $instance)
	{

		// Get List Of excluded Id's.
		$excluded_ids = (array) $this->get_excluded_friends_ids($user_id);
		$excluded_ids[] = bp_loggedin_user_id();

		// Get Friends of Friends.
		$friends_of_friends = (array) $this->get_user_friends_of_friends($user_id);

		$friend_suggestions = array_diff($friends_of_friends, $excluded_ids);

		$friend_suggestions = array_filter($friend_suggestions);
		$suggestions_count = count($friend_suggestions);

		if (empty($friend_suggestions)) {
			$friend_suggestions = $this->get_all_user_ids($excluded_ids);
		} else if ($instance["limit"] > $suggestions_count) {
			$excluded_ids = array_merge($excluded_ids, $friend_suggestions);
			$get_all_user = $this->get_all_user_ids($excluded_ids);
			shuffle($get_all_user);

			$all_user = array_slice($get_all_user, $instance["limit"] - $suggestions_count);
			$friend_suggestions = array_merge($friend_suggestions, $all_user);
		}
		// Randomize Order.
		shuffle($friend_suggestions);

		// Return Friends ID's.
		return $friend_suggestions;
	}

	/**
	 * Get Suggestions List.
	 */
	function get_suggestions_list($args)
	{

		// Get Friend Suggestions.
		$friend_suggestions = $this->friend_suggestions;

		// Limit Groups Number
		$friend_suggestions = array_slice($friend_suggestions, 0, $args['limit']);

		// Get 'Show Button' Option Value
		$show_buttons = $args['show_buttons'] ? 'on' : 'off';

	?>

		<div class="socialv-items-list-widget socialv-suggested-friends-widget socialv-list-avatar-circle">

			<?php foreach ($friend_suggestions as $friend_id) : ?>

				<?php $profile_url = bp_members_get_user_url($friend_id); ?>

				<div class="d-flex  socialv-friend-request">
					<div class="item-img">
						<div class="item-img">
							<a href="<?php echo $profile_url; ?>" class="socialv-item-avatar"><?php echo bp_core_fetch_avatar(array('item_id' => $friend_id, 'type' => 'full', 'class' => 'rounded-circle', 'width' => '60', 'height' => '60')); ?></a>
						</div>
					</div>
					<div class="flex-grow-1 d-flex justify-content-between item-details ms-3">
						<div class="item-detail-data">
							<h6 class="item-title"><a href="<?php echo $profile_url; ?>" class="socialv-item-name"><?php echo bp_core_get_user_displayname($friend_id); ?></a></h6>
							<p class="m-0 socialv-nik-name">@<?php echo bp_members_get_user_slug($friend_id); ?></p>
						</div>
						<?php if ('on' == $show_buttons) : ?>
							<div class="request-button">
								<a href="<?php echo  wp_nonce_url(
												bp_loggedin_user_url(bp_members_get_path_chunks(array(bp_get_friends_slug(), 'add-friend', array($friend_id)))),
												'friends_add_friend'
											); ?>" class="p-0 btn socialv-btn-outline-primary item-btn accept"><i class="icon-add" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="<?php esc_attr_e('Add', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"></i></a>
								<a href="<?php echo bp_get_root_url() . "/refuse-friend-suggestion/?suggestion_id=" . $friend_id . "&_wpnonce=" . wp_create_nonce('friend-suggestion-refused-' . $friend_id); ?>" class="p-0 btn socialv-btn-outline-danger item-btn reject"><i class="icon-close-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="<?php esc_attr_e('Remove', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"></i></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<input type="hidden" value="<?php echo esc_url(site_url() . '/wp-admin/admin-ajax.php'); ?>" name="ajax_url" class="ajax-url">

		</div>

<?php
		//script 
		$this->call_dependent_scripts();
	}
	/**
	 * Get User Friends Groups
	 */
	function get_user_friends_of_friends($user_id = null)
	{

		// Init Vars.
		$friends_of_friends = array();

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// Get All User Friends List.
		$user_friends = (array) friends_get_friend_user_ids($user_id);

		// Check If User have friends.
		if (empty($user_friends)) {
			return $this->get_all_user_ids();
		}

		foreach ($user_friends as $friend_id) {

			$friends = friends_get_friend_user_ids($friend_id);

			if (!empty($friends)) {

				foreach ($friends as $id) {
					$friends_of_friends[] = $id;
				}
			}
		}

		// Remove Repeated ID's.
		$friends_of_friends = array_unique($friends_of_friends);
		if (empty($friends_of_friends)) {
			return $this->get_all_user_ids();
		}
		return $friends_of_friends;
	}
	/**
	 * Get all user _ids if no friends / new user
	 */
	function get_all_user_ids($exclude = [])
	{
		$args = ["fields" => "ID"];
		if (!empty($exclude))
			$args["exclude"] = implode(",", $exclude);
		$user_ids = get_users($args);
		return !empty($user_ids) ? $user_ids : [];
	}
	/**
	 * Get User Excluded Groups
	 */
	function get_excluded_friends_ids($user_id = null)
	{

		// Get User Friends
		$user_friends = (array) friends_get_friend_user_ids($user_id);

		// Get User Friendship requests List.
		$friendship_requests = $this->get_user_friendship_requests($user_id);

		// List of Refused Suggestions
		$refused_friends = (array) self::get_refused_friend_suggestions($user_id);

		// blocked users
		$blocked = $this->socialv_get_blocked_user();

		// pending users
		$pending_users = $this->socialv_get_pending_users();

		// make an array of users group+groups hidden by user
		$excluded_ids = array_merge($user_friends, $friendship_requests, $refused_friends, $pending_users, $blocked);

		// Remove Repeated ID's.
		$excluded_ids = array_unique($excluded_ids);

		return $excluded_ids;
	}
	/**
	 * User Friendship requests
	 */
	function get_user_friendship_requests($user_id)
	{

		global $wpdb;

		// Init Vars.
		$bp = buddypress();

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// SQL
		$sql = "SELECT friend_user_id FROM {$bp->friends->table_name} WHERE initiator_user_id = %d AND is_confirmed = 0";

		// Get List of Membership Requests.
		$friendship_requests = $wpdb->get_col($wpdb->prepare($sql, $user_id));

		return $friendship_requests;
	}

	/**
	 * Save New Refused Suggestions.
	 */
	public function hide_suggestion()
	{

		// Get Suggested Group ID.
		$suggestion_id = isset($_POST['suggestion_id']) ? absint($_POST['suggestion_id']) : 0;

		check_ajax_referer('friend-suggestion-refused-' . $suggestion_id);

		if (empty($suggestion_id) || !is_user_logged_in()) {
			die();
		}

		// Get Current User ID.
		$user_id = bp_loggedin_user_id();

		// Get Old Refused Suggestions.
		$refused_suggestions = (array) get_user_meta($user_id, 'socialv_refused_friend_suggestions', true);

		// Add The new Refused Suggestion to the old refused suggetions list.
		if (!in_array($suggestion_id, $refused_suggestions)) {
			$refused_suggestions[] = $suggestion_id;
		}

		// Save New Refused Suggestion
		update_user_meta($user_id, 'socialv_refused_friend_suggestions', $refused_suggestions);

		die();
	}

	/**
	 * Get Refused Suggestions.
	 */
	public static function get_refused_friend_suggestions($user_id = null)
	{

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// Get Refused Groups.
		return get_user_meta($user_id, 'socialv_refused_friend_suggestions', true);
	}
	/**
	 * Get blocked/blocked-by user ids.
	 */
	public function socialv_get_blocked_user()
	{
		$exclude_members = [];
		$user_id = isset($this->user_id) ? $this->user_id : '';

		$bloked_id = function_exists("imt_get_blocked_members_ids") ? imt_get_blocked_members_ids($user_id) : [];
		$blocked_by = function_exists("imt_get_members_blocked_by_ids") ? imt_get_members_blocked_by_ids($user_id) : [];
		if ($bloked_id)
			$exclude_members = $bloked_id;

		if ($blocked_by)
			$exclude_members = array_merge($exclude_members, $blocked_by);
			
		return $exclude_members;
	}
	/**
	 * Get Pending Users.
	 */
	public function socialv_get_pending_users()
	{
		$args = [
			'fields'        => 'ids',
			"meta_query"    => [
				[
					"key" => "activation_key",
					"value" => "",
					"compare" => "!="
				]
			]

		];
		return get_users($args);
	}
}
