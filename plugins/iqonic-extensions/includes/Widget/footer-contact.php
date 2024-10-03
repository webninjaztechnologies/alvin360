<?php

function iqonic_contact_info()
{
	register_widget('iq_contact');
}
add_action('widgets_init', 'iqonic_contact_info');

/*-------------------------------------------
		iq Contact Information widget 
--------------------------------------------*/
class iq_contact extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_contact',

			// Widget name will appear in UI
			esc_html('iqonic Contact', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('iq Contact. ', IQONIC_EXTENSION_TEXT_DOMAIN),)
		);
	}

	// Creating widget front-end

	public function widget($args, $instance)
	{

		$iq_option = get_option('socialv-options');

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}

		echo $args['before_widget'];


		$title = (!empty($instance['title'])) ? $instance['title'] : false;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$phone = isset($instance['phone']) ? $instance['phone'] : false;
		$email = isset($instance['email']) ? $instance['email'] : false;
		$address = isset($instance['address']) ? $instance['address'] : false;

		/* here add extra display item  */
?>
		<div class="widget">
			<?php if ($title) { ?>
				<h4 class="footer-title contact-info iq-heading-title">
					<?php echo $title ?>
				</h4>
			<?php } ?>

			<div class="row">
				<div class="col-sm-12">
					<ul class="iq-contact">
						<?php
						if ($address && !empty($iq_option['address'])) : ?>
							<li>
								<a>
									<i class="fa fa-map-marker" aria-hidden="true"></i>
									<span>
										<?php echo esc_html($iq_option['address']); ?>
									</span>
								</a>
							</li>
						<?php
						endif; ?>
						<?php
						if ($phone && !empty($iq_option['phone'])) : ?>
							<li>
								<a href="tel:<?php echo str_replace(str_split('(),-" '), '', $iq_option['phone']); ?>">
									<i class="fas fa-phone-alt"></i>
									<span>
										<?php echo wp_kses($iq_option['phone'], array('br' => true)); ?>
									</span>
								</a>
							</li>
						<?php
						endif; ?>
						<?php
						if ($email && !empty($iq_option['email'])) :  ?>
							<li>
								<a href="mailto:<?php echo esc_html($iq_option['email']); ?>">
									<i class="fa fa-envelope"></i>
									<span><?php echo esc_attr($iq_option['email']); ?></span>
								</a>
							</li>
						<?php
						endif; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form($instance)
	{
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$phone = isset($instance['phone']) ? (bool) $instance['phone'] : false;
		$email = isset($instance['email']) ? (bool) $instance['email'] : false;
		$address = isset($instance['address']) ? (bool) $instance['address'] : false;
	?>

		<p><label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
		</p>

		<p><input class="checkbox" type="checkbox" <?php checked($phone); ?> id="<?php echo esc_html($this->get_field_id('phone', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('phone', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('phone', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display Phone Number?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p><input class="checkbox" type="checkbox" <?php checked($email); ?> id="<?php echo esc_html($this->get_field_id('email', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('email', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('email', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display Email?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p><input class="checkbox" type="checkbox" <?php checked($address); ?> id="<?php echo esc_html($this->get_field_id('address', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('address', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('address', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display Address?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

<?php
	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['phone'] = isset($new_instance['phone']) ? (bool) $new_instance['phone'] : false;
		$instance['email'] = isset($new_instance['email']) ? (bool) $new_instance['email'] : false;
		$instance['address'] = isset($new_instance['address']) ? (bool) $new_instance['address'] : false;
		return $instance;
	}
} 
/*---------------------------------------
		Class wpb_widget ends here
----------------------------------------*/
