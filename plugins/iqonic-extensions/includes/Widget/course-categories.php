<?php

function iqonic_course_categories_widgets()
{
	register_widget('iq_course_categories');
}
add_action('widgets_init', 'iqonic_course_categories_widgets');

/*-------------------------------------------
		Iqonic Course Categories widget 
--------------------------------------------*/
class iq_course_categories extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_course_categories',

			// Widget name will appear in UI
			esc_html('Iqonic Course Categories', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('iqonic course categories', IQONIC_EXTENSION_TEXT_DOMAIN),)
		);
	}

	// Creating widget front-end

	public function widget($args, $instance)
	{
		$title = $instance['title'] ?? '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$count        = !empty($instance['count']) ? '1' : '0';
		$hierarchical = !empty($instance['hierarchical']) ? '1' : '0';
		echo $args['before_widget'];

		if ($title) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$cat_args = array(
			'orderby'      => 'name',
			'show_count'   => $count,
			'taxonomy'     => 'course_category',
			'hierarchical' => $hierarchical,
			'title_li' => '',
		);

		$format = current_theme_supports('html5', 'navigation-widgets') ? 'html5' : 'xhtml';

		/** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
		$format = apply_filters('navigation_widgets_format', $format);

		if ('html5' === $format) {
			// The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
			$title      = trim(strip_tags($title));
			$aria_label = $title ? $title : '';
			echo '<nav aria-label="' . esc_attr($aria_label) . '">';
		}  ?>

		<ul>
			<?php

			/**
			 * Filters the arguments for the Categories widget.
			 *
			 * @since 2.8.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @param array $cat_args An array of Categories widget options.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_list_categories(apply_filters('widget_categories_args', $cat_args, $instance));
			?>
		</ul>

		<?php
		if ('html5' === $format) {
			echo '</nav>';
		}

		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form($instance)
	{
		// Defaults.

		$defaults = [
			'title' => esc_html__('Course Categories', IQONIC_EXTENSION_TEXT_DOMAIN),
		];
		$instance = wp_parse_args((array) $instance, $defaults);
		
		$count        = isset($instance['count']) ? (bool) $instance['count'] : false;
		$hierarchical = isset($instance['hierarchical']) ? (bool) $instance['hierarchical'] : false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($count); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php esc_html_e('Show post counts',IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>" <?php checked($hierarchical); ?> />
			<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php esc_html_e('Show hierarchy', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>
<?php
	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance                 = $old_instance;
		$instance['title']        = sanitize_text_field($new_instance['title']);
		$instance['count']        = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		return $instance;
	}
}
