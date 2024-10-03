<?php

function iqonic_course_tags_widgets()
{
	register_widget('iq_course_tags');
}
add_action('widgets_init', 'iqonic_course_tags_widgets');

/*-------------------------------------------
		Iqonic Course Tags widget 
--------------------------------------------*/
class iq_course_tags extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_course_tags',

			// Widget name will appear in UI
			esc_html('Iqonic Course Tags', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('iqonic course tags', IQONIC_EXTENSION_TEXT_DOMAIN), )
		);
	}

	// Creating widget front-end

	public function widget($args, $instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$count = !empty($instance['count']) ? '1' : '0';
		echo $args['before_widget'];

		if ($title) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$tags = get_terms('course_tag', array('hide_empty' => true));
		$page_count = '';
		if (!empty($tags)) {
			echo '<ul>';
			foreach ($tags as $tag) {
				if ($count == 1) {
					$course_count = $tag->count;
					$page_count = sprintf("(%u)", $course_count);
				}
				// Generate the link to the list of courses with the tag
				$tag_link = get_term_link($tag, 'course_tag');

				if (!is_wp_error($tag_link)) {
					echo '<li><a href="' . esc_url($tag_link) . '">' . esc_html($tag->name) . '</a> <span class="archiveCount">' . $page_count . '</span></li>';
				} else {
					echo '<li>' . esc_html($tag->name) . ' (' . $course_count . ')</li>';
				}
			}
			echo '</ul>';
		} else {
			echo esc_html('No tags found!');
		}
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form($instance)
	{
		// Defaults.

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__('Course Tags', IQONIC_EXTENSION_TEXT_DOMAIN),
			)
		);
		$count = isset($instance['count']) ? (bool) $instance['count'] : false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>" type="text"
				value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>"
				name="<?php echo $this->get_field_name('count'); ?>" <?php checked($count); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>">
				<?php esc_html_e('Show post counts', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
			</label>
			<br />

		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		return $instance;
	}
}