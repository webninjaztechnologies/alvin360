<?php

function iqonic_courses_widgets()
{
	register_widget('iq_courses');
}
add_action('widgets_init', 'iqonic_courses_widgets');

/*-------------------------------------------
		Iqonic Recent Course Widget																																																																																																																																																																																																																																																																																													widget 
--------------------------------------------*/
class iq_courses extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'iq_courses',

			// Widget name will appear in UI
			esc_html('Iqonic Courses', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('Display Courses', IQONIC_EXTENSION_TEXT_DOMAIN),)
		);
	}

	// Creating widget front-end

	public function widget($args, $instance)
	{

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		$number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
		if (!$number) {
			$number = 5;
		}
		$description_length = (!empty($instance['description_length'])) ? absint($instance['description_length']) : 30;
		if (!$description_length) {
			$description_length = 30;
		}
		$show_featured = isset($instance['show_featured']) ? (bool) $instance['show_featured'] : false;
		$title_tag = empty($instance['title_tag']) ? 'h6' : $instance['title_tag'];
		$show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
		$show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
		$show_course_image = isset($instance['show_course_image']) ? (bool) $instance['show_course_image'] : true;

?>

		<div class="blog_widget socialv-recentpost widget socialv-post-sidebar">
			<div class="socialv-widget-menu">
				<div class="socialv-post">
					<?php

					$condition = array(
						'post_type'           => 'lp_course',
						'posts_per_page'      => $number,
						'ignore_sticky_posts' => true,
					);

					if ($show_featured == true) {
						$condition['meta_query'] = array(
							array(
								'key'   => '_lp_featured',
								'value' => 'yes',
							)
						);
					}
					$the_query = new WP_Query($condition);
					if ($the_query->have_posts()) :
						if ($title) {
							echo ($args['before_title'] . $title . $args['after_title']);
						}
						while ($the_query->have_posts()) : $the_query->the_post(); 	?>
							<div class="socialv-image-content-wrap">
								<!-- Post Image Start -->
								<div class="post-img">
									<?php if ($show_course_image == true) { ?>
										<div class="post-img-blog">
											<?php
											$image_url =	wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail');
											if (!empty($image_url[0])) {
												$image_url = $image_url[0];
											} else {
												$image_url = LP()->image('no-image.png');
											}
											?>
											<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
												<img src="<?php echo esc_url($image_url); ?>" loading="lazy" alt="<?php esc_attr_e('post-img', IQONIC_EXTENSION_TEXT_DOMAIN); ?>">
											</a>
										</div>
									<?php } ?>
								</div>
								<!-- Post Image End -->

								<div class="post-blog-deatil">
									<div class="blog-box">

										<!-- Title Start -->
										<a class="new-link socialv-post-title" href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
											<<?php echo esc_attr($title_tag);  ?> class="socialv-heading-title">
												<?php echo get_the_title(get_the_ID()); ?>
											</<?php echo esc_attr($title_tag); ?>>
										</a>
										<!-- Title End -->
										<!-- Description Start -->
										<?php if ($show_excerpt == true) : ?>
											<div class="socialv-widget-content">
												<?php echo substr(get_the_excerpt(get_the_ID()), 0, $description_length);  ?>
											</div>
										<?php endif; ?>
										<!-- Description End -->
										<?php if ($show_price == true) :
											$course = learn_press_get_course( get_the_ID() );
											?>
											<div class="course-price"><?php echo wp_kses_post( $course->get_course_price_html() ); ?></div>
										<?php endif; ?>
									</div>
								</div>

							</div>
					<?php
						endwhile;
					wp_reset_postdata();

					endif;

					?>
				</div>
			</div>
		</div>
	<?php
	}


	// Widget Backend
	public function form($instance)
	{
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__('Recent Courses', IQONIC_EXTENSION_TEXT_DOMAIN),
			)
		);
		$title = strip_tags($instance['title']);
		$number    = isset($instance['number']) ? absint($instance['number']) : 5;
		$description_length    = isset($instance['description_length']) ? absint($instance['description_length']) : 30;
		$show_featured = isset($instance['show_featured']) ? (bool) $instance['show_featured'] : false;
		$title_tag = isset($instance['title_tag']) ? absint($instance['title_tag']) : 'h6';
		$show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
		$show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
		$show_course_image = isset($instance['show_course_image']) ? (bool) $instance['show_course_image'] : true;

	?>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Number of courses to show:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="tiny-text" id="<?php echo esc_html($this->get_field_id('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="number" step="1" min="1" value="<?php echo esc_html($number, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" size="3" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_featured); ?> id="<?php echo esc_html($this->get_field_id('show_featured', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_featured', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_featured', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display featured courses?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title_tag', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Select Title Tag:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<select id="<?php echo esc_html($this->get_field_id('title_tag', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_id('title_tag', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" class="widefat">
				<option <?php if ('h1' == $title_tag) echo 'selected="selected"'; ?> value="h1">H1</option>
				<option <?php if ('h2' == $title_tag) echo 'selected="selected"'; ?> value="h2">H2</option>
				<option <?php if ('h3' == $title_tag) echo 'selected="selected"'; ?> value="h3">H3</option>
				<option <?php if ('h4' == $title_tag) echo 'selected="selected"'; ?> value="h4">H4</option>
				<option <?php if ('h5' == $title_tag) echo 'selected="selected"'; ?> value="h5">H5</option>
				<option <?php if ('h6' == $title_tag) echo 'selected="selected"'; ?> value="h6">H6</option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_html($this->get_field_id('show_price', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_price', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_price', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Show Price?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_html($this->get_field_id('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Show instructor?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('description_length', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Description Length:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="tiny-text" id="<?php echo esc_html($this->get_field_id('description_length', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('description_length', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="number" step="1" min="1" value="<?php echo esc_html($description_length, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" size="3" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_course_image); ?> id="<?php echo esc_html($this->get_field_id('show_course_image', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_course_image', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_course_image', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Show Image?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>
<?php
	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['description_length'] = (int) $new_instance['description_length'];
		$instance['title_tag'] = $new_instance['title_tag'];
		$instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
		$instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
		$instance['show_course_image'] = isset($new_instance['show_course_image']) ? (bool) $new_instance['show_course_image'] : true;
		$instance['show_featured'] =  isset($new_instance['show_featured']) ? (bool) $new_instance['show_featured'] : false;

		return $instance;
	}
}
