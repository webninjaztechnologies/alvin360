<?php

use function SocialV\Utility\socialv;

function iqonic_footer_logo_widgets()
{
	register_widget('iq_footer_logo');
}
add_action('widgets_init', 'iqonic_footer_logo_widgets');

/*-------------------------------------------
		iqonic footer logo widget 
--------------------------------------------*/
class iq_footer_logo extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_footer_logo',

			// Widget name will appear in UI
			esc_html('Iqonic Footer Logo', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('iqonic logo', IQONIC_EXTENSION_TEXT_DOMAIN),)
		);
	}

	// Creating widget front-end

	public function widget($args, $instance)
	{

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}
		echo $args['before_widget'];
		$title = (!empty($instance['title'])) ? $instance['title'] : '';
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		if (!empty($title)) {
			echo ($title);
		}
		echo '<div class="footer-logo mb-3">'; 
		socialv()->socialv_logo(); 
		echo '</div>';
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form($instance)
	{
		$title = isset($instance['title']) ? esc_attr($instance['title']) : ''; ?>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
		</p>
<?php

	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		return $instance;
	}
}
