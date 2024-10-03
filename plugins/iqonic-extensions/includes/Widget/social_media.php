<?php

function iqonic_social_media_widgets()
{
	register_widget('iq_socail_media');
}
add_action('widgets_init', 'iqonic_social_media_widgets');

/*-------------------------------------------
		Iqonic Social Media widget 
--------------------------------------------*/
class iq_socail_media extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_socail_media',

			// Widget name will appear in UI
			esc_html('Iqonic Social Media', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('iqonic Social Media ', IQONIC_EXTENSION_TEXT_DOMAIN),)
		);
	}

	// Creating widget front-end
	public function widget($args, $instance)
	{
		if (!isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}
		echo $args['before_widget'];
		$title = (!empty($instance['title'])) ? $instance['title'] : false;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		/* here add extra display item  */

		$social_text = isset($instance['social_text']) ? $instance['social_text'] : false;

		$text_array = [
			'facebook'     		=> [esc_html__('Facebook', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('fb', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'twitter'      	=> [esc_html__('Twitter', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('tw', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'instagram'      	=> [esc_html__('Instagram', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('in', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'linkedin'       	=> [esc_html__('LinkedIn', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('ln', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'pinterest'      	=> [esc_html__('Pinterest', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('pt', IQONIC_EXTENSION_TEXT_DOMAIN)], //
			'dribbble'       	=> [esc_html__('Dribbble', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('db', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'flickr'         	=> [esc_html__('Flicker', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('fc', IQONIC_EXTENSION_TEXT_DOMAIN)], //
			'skype'          	=> [esc_html__('skype', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('sp', IQONIC_EXTENSION_TEXT_DOMAIN)], //
			'youtube'   	    => [esc_html__('Youtube Play', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('yt', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'rss'            	=> [esc_html__('Rss', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('rs', IQONIC_EXTENSION_TEXT_DOMAIN)],
			'behance'        	=> [esc_html__('Behance', IQONIC_EXTENSION_TEXT_DOMAIN), esc_html__('bh', IQONIC_EXTENSION_TEXT_DOMAIN)],
		];

		$iq_option = get_option('socialv-options');
		if (isset($iq_option['social_media_options'])) {
			$top_social = $iq_option['social_media_options'];

			echo '<div class="socialv-social-media">';
				
				if ($title) {
					echo ($args['before_title'] . $title . $args['after_title']);
				}
				
				echo '<ul class="m-0">';
					
					foreach ($top_social as $key => $value) {
						if (!empty($value) && !empty($text_array[$key][0])) {
							if (!$social_text) {
								echo '<li class="list-inline-item media-icon"><a target="_blank" href="' . $value . '"><i class="icon-' . $key . '"></i></a></li>';
							} else {
								echo '<li class=""><a target="_blank" href="' . $value . '"> ' . $text_array[$key][0] . '</a></li>';
							}
						}
					}
					echo '</ul></div>';
	
		}
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form($instance)
	{
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__(' Stay connected', IQONIC_EXTENSION_TEXT_DOMAIN),
			)
		);
		$title = strip_tags($instance['title']);
		$social_text = isset($instance['social_text']) ? (bool) $instance['social_text'] : false;
		?>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($social_text); ?> id="<?php echo esc_html($this->get_field_id('social_text', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('social_text', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" checked />
			<label for="<?php echo esc_html($this->get_field_id('social_text', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display social text insted of icon?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>
<?php
	}
	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['social_text'] = isset($new_instance['social_text']) ? (bool) $new_instance['social_text'] : false;
		return $instance;
	}
}
