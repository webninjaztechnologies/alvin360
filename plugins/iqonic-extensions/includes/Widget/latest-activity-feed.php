<?php

function iq_latest_activity_feed_widgets()
{
	register_widget('Iqonic_Latest_Activity_Feed');
}
add_action('widgets_init', 'iq_latest_activity_feed_widgets');

/*-------------------------------------------
		Iqonic Latest Activity Feed
--------------------------------------------*/
class Iqonic_Latest_Activity_Feed extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'Iqonic_Latest_Activity_Feed',

			// Widget name will appear in UI
			esc_html('Iqonic Latest Activity Feed', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('Display the latest updates of the post author to widget areas', IQONIC_EXTENSION_TEXT_DOMAIN))

		);
	}
	// Creating widget front-end
	public function widget($args, $instance)
	{
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html($title) . $args['after_title'];
		$max_posts = (!empty($instance['max_posts'])) ? absint($instance['max_posts']) : 5;
		if (!$max_posts) {
			$max_posts = 5;
		}
		$search_terms = false;
		if (is_search()) {
			$search_terms = ' ';
		}
		if (!empty(is_user_logged_in()) &&  bp_has_activities(bp_ajax_querystring('activity') . '&max=' . $max_posts . '&search_terms=' . $search_terms)) :
			echo '<ul class="socialv-activity-items-list">';
			while (bp_activities()) : bp_the_activity();
				global $activities_template;
				$activity_type = $activities_template->activity->type;
				if (bp_get_activity_action(['no_timestamp' => false]) !== '' || in_array($activity_type, ['activity_photo', 'activity_status', 'activity_share'])) :
				   echo '<li class="socialv-activity-item">';

					echo bp_core_fetch_avatar(array(
						'item_id' => $activities_template->activity->user_id,
						'type'    => 'thumb',
						'size'  => 40,
						'class' => 'd-block rounded-circle',
					));
					bp_activity_action(array('no_timestamp' => false));
					echo'</li>';
				else :
					echo '<li class="socialv-activity-item"> ' . esc_html_e('No activity found!', IQONIC_EXTENSION_TEXT_DOMAIN) . '</li>';
				endif;
			endwhile;
			echo '</ul>';
		else :
			echo '<div>' . esc_html_e('No activity found!', IQONIC_EXTENSION_TEXT_DOMAIN) . '</div>';
		endif;
		echo $args['after_widget'];
	}

	// Widget Backend
	public function form($instance)
	{
		// Get Widget Data.
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__('Latest Activities', IQONIC_EXTENSION_TEXT_DOMAIN),
				'max_posts' => '5'
			)
		);
		$title = strip_tags($instance['title']);
		$max_posts = absint($instance['max_posts']);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
				<input class="tiny-text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 100%" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('max_posts'); ?>">
				<?php esc_html_e('Number of posts:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
				<input class="tiny-text" id="<?php echo esc_html($this->get_field_id('max_posts', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('max_posts', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="number" value="<?php echo esc_html($max_posts, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
			</label>
		</p>
<?php
	}
	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']       = strip_tags($new_instance['title']);
		$instance['max_posts'] = (int) $new_instance['max_posts'];

		return $instance;
	}
}
