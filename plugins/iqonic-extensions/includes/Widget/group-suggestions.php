<?php

function iq_group_suggestions_widgets()
{
	register_widget('Iqonic_Group_Suggestions');
}
add_action('widgets_init', 'iq_group_suggestions_widgets');

/*-------------------------------------------
		Iqonic Group Suggestions
--------------------------------------------*/
class Iqonic_Group_Suggestions extends WP_Widget
{

	function __construct()
	{
		if (defined('IQONIC_EXTENSION_VERSION')) {
			$this->version = IQONIC_EXTENSION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		parent::__construct(
			// Base ID of your widget
			'Iqonic_Group_Suggestions',
			// Widget name will appear in UI
			esc_html('Iqonic Group Suggestions', IQONIC_EXTENSION_TEXT_DOMAIN),
			// Widget description
			array('description' => esc_html('Display Group suggestions widget area', IQONIC_EXTENSION_TEXT_DOMAIN))
		);
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
				'title' => esc_html__('Group Suggestions', IQONIC_EXTENSION_TEXT_DOMAIN),
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

		// user not logged-in or groups feature not active Don't show anything.
		if (!is_user_logged_in() || !bp_is_active('groups') || !bp_is_active('friends')) {
			return false;
		}

		// Get User ID.
		$user_id = bp_loggedin_user_id();

		// Get Group Suggestions.
		$group_suggestions = $this->get_group_suggestions($user_id);

		// Hide Widget IF There's No suggestions.
		if (empty($group_suggestions)) {
			return false;
		}

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);


		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];

		$this->get_suggestions_list($instance);

		echo $args['after_widget'];
	}

	/**
	 * Get Suggestions Groups.
	 */
	function get_group_suggestions($user_id)
	{

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// Get List Of excluded Id's.
		$excluded_ids = (array) $this->get_excluded_groups_ids($user_id);

		// Get Friends Groups.
		$friends_groups = (array) $this->get_user_friends_groups($user_id);

		// Get Suggestion Groups.
		$group_suggestions = array_diff($friends_groups, $excluded_ids);

		// Randomize Order.
		shuffle($group_suggestions);

		// Return Group ID's.
		return $group_suggestions;
	}


	/**
	 * Get Suggestions List.
	 */
	function get_suggestions_list($args)
	{

		// Get User ID.
		$user_id = isset($args['user_id']) ? $args['user_id'] : bp_loggedin_user_id();

		// Get Suggestion Groups.
		$group_suggestions = $this->get_group_suggestions($user_id);

		// Limit Groups Number
		$group_suggestions = array_slice($group_suggestions, 0, $args['limit']);

		// Get 'Show Button' Option Value
		$show_buttons = $args['show_buttons'] ? 'on' : 'off';

	?>

		<div id="groups-dir-list" class="socialv-items-list-widget socialv-suggested-friends-widget socialv-list-avatar-circle">
			<?php foreach ($group_suggestions as $group_id) : ?>

				<?php $group = groups_get_group(array('group_id' => $group_id)); ?>
				<?php $group_url = bp_get_group_url($group); 
				
				if ('public' === $group->status) {
					$type = esc_html__("Public", IQONIC_EXTENSION_TEXT_DOMAIN);
				} elseif ('hidden' === $group->status) {
					$type = esc_html__("Hidden", IQONIC_EXTENSION_TEXT_DOMAIN);
				} elseif ('private' === $group->status) {
					$type = esc_html__("Private", IQONIC_EXTENSION_TEXT_DOMAIN);
				} else {
					$type = ucwords($group->status) . ' ' . esc_html__('Group', IQONIC_EXTENSION_TEXT_DOMAIN);
				}

				?>
				<div class="d-flex socialv-friend-request">
					<div class="item-img">
						<div class="item-img">
							<a href="<?php echo esc_url($group_url); ?>" class="socialv-item-avatar"><?php echo bp_core_fetch_avatar(array('item_id' => $group_id, 'object' => 'group', 'type' => 'full', 'class' => 'rounded-circle', 'width' => '60', 'height' => '60')); ?></a>
						</div>
					</div>
					<div class="flex-grow-1 d-flex justify-content-between item-details ms-3">
						<div class="item-detail-data">
							<h6 class="item-title"><a href="<?php echo esc_url($group_url); ?>" class="socialv-item-name"><?php echo esc_html($group->name); ?></a></h6>
							<p class="m-0 socialv-nik-name text-capitalize"><?php echo esc_html($type); ?></p>
						</div>
						<?php 
						if ('on' == $show_buttons) : ?>
							<div class="request-button group-button ">
								<?php
								// Get Join Group Url.
								if ('public' == $group->status) {
									echo '<a id="group-' . esc_attr($group->id) . '" class="btn socialv-btn-outline-primary group-button p-0 join-group" rel="join" href="' . wp_nonce_url(bp_get_group_url($group) . 'join', 'groups_join_group') . '"><i class="icon-add" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="' . esc_attr__('Join', IQONIC_EXTENSION_TEXT_DOMAIN) . '"></i></a>';
								} else if ('private' == $group->status) {
									echo '<a id="group-' . esc_attr($group->id) . '" class="btn socialv-btn-outline-primary group-button p-0 request-membership" rel="join" href="' . wp_nonce_url(bp_get_group_url($group) . 'request-membership', 'groups_request_membership') . '"><i class="icon-add" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="' . esc_attr__('Join', IQONIC_EXTENSION_TEXT_DOMAIN) . '"></i></a>';
								}
								?>
								<a href="<?php echo bp_get_root_url() . "/refuse-group-suggestion/?suggestion_id=" . $group_id . "&_wpnonce=" . wp_create_nonce('group-suggestion-refused-' . $group_id); ?>" class="btn group-button socialv-btn-outline-danger p-0 item-btn reject">
									<i class="icon-close-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="<?php esc_attr_e('Remove', IQONIC_EXTENSION_TEXT_DOMAIN); ?>"></i>
								</a>
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
	function get_user_friends_groups($user_id = null)
	{

		global $bp, $wpdb;

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// Get All User Friends List.
		$user_friends = (array) friends_get_friend_user_ids($user_id);

		// Check If User have friends.
		if (empty($user_friends)) {
			return;
		}

		// Convert Friends List into string an separate user ids by commas.
		$friends_ids = '(' . join(',', $user_friends) . ')';

		// Prepare Friends SQL.
		$friends_groups_sql = "SELECT DISTINCT group_id FROM {$bp->groups->table_name} g, {$bp->groups->table_name_members} m WHERE g.id=m.group_id AND ( g.status='public' OR g.status='private' ) AND m.user_id in {$friends_ids} AND is_confirmed= 1";

		// Get Friend Groups ID's.
		$friends_groups_result = $wpdb->get_col($friends_groups_sql);

		return $friends_groups_result;
	}

	/**
	 * Get User Excluded Groups
	 */
	function get_excluded_groups_ids($user_id = null)
	{

		global $bp, $wpdb;

		// Get User ID.
		$user_id = ($user_id) ? $user_id : bp_loggedin_user_id();

		// Get Sql Result.
		$groups_ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT group_id FROM {$bp->groups->table_name_members} WHERE user_id = %d ", $user_id));

		// List of Refused Suggestions
		$refused_groups = (array) get_user_meta($user_id, 'socialv_refused_group_suggestions', true);

		// Make an array of users group+groups hidden by user & Remove Repeated ID's
		$excluded_ids = array_unique(array_merge($groups_ids, $refused_groups));

		return $excluded_ids;
	}

	/**
	 * Save New Refused Suggestions.
	 */
	public function hide_suggestion()
	{

		// Get Suggested Group ID.
		$suggestion_id = isset($_POST['suggestion_id']) ? sanitize_text_field($_POST['suggestion_id']) : 0;

		check_ajax_referer('group-suggestion-refused-' . $suggestion_id);

		if (empty($suggestion_id) || !is_user_logged_in()) {
			return;
		}

		// Get Current User ID.
		$user_id = bp_loggedin_user_id();

		// Get Old Refused Suggestions.
		$refused_suggestions = (array) get_user_meta($user_id, 'socialv_refused_group_suggestions', true);

		// Add The new Refused Suggestion to the old refused suggetions list.
		if (!in_array($suggestion_id, $refused_suggestions)) {
			$refused_suggestions[] = $suggestion_id;
		}

		// Save New Refused Suggestion
		update_user_meta($user_id, 'socialv_refused_group_suggestions', $refused_suggestions);

		die();
	}
}
