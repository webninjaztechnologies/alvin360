<?php

function iq_recent_post_widgets()
{
	register_widget('Iqonic_Recent_Post');
}
add_action('widgets_init', 'iq_recent_post_widgets');

/*-------------------------------------------
		iqonic Recent Post 
--------------------------------------------*/
class Iqonic_Recent_Post extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'Iqonic_Recent_Post',

			// Widget name will appear in UI
			esc_html('Iqonic Recent Post', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('Most recent Posts. ', IQONIC_EXTENSION_TEXT_DOMAIN))

		);
	}
	// Creating widget front-end
	public function widget($args, $instance)
	{
		if (!isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		$number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
		if (!$number) {
			$number = 5;
		}
		$title_tag = empty($instance['title_tag']) ? 'h6' : $instance['title_tag'];
		$show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
		$show_category = isset($instance['show_category']) ? $instance['show_category'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
		$show_button = isset($instance['show_button']) ? $instance['show_button'] : false;
		$iq_image = isset($instance['socialv-image']) ? $instance['socialv-image'] : '';
		
		echo '<div class="blog_widget socialv-recentpost widget socialv-post-sidebar">';
		if ($title) {
			echo ($args['before_title'] . $title . $args['after_title']);
		}

		echo '<div class="socialv-widget-menu"><div class="socialv-post">';

		$args = [
			'post_type' => 'post',
			'post_status'       => array('private', 'publish'),
			'posts_per_page' => $number
		];
		$recent_posts = get_posts($args);
		if ($recent_posts) :
			foreach ($recent_posts as $post) :
?>

				<div class="socialv-image-content-wrap">

					<!-- Post Image Start -->
					<div class="post-img">
						<?php if ($iq_image) { ?>
							<div class="post-img-blog">
								<a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
									<img src="<?php echo get_the_post_thumbnail_url($post->ID); ?>" loading="lazy" alt="<?php esc_attr_e('post-img', IQONIC_EXTENSION_TEXT_DOMAIN); ?>">
								</a>
							</div>
						<?php } ?>
					</div>
					<!-- Post Image End -->

					<div class="post-blog-deatil">
						<div class="blog-box">

							<?php if ($show_category) : ?>
								<!-- Category Start -->
								<div class="socialv-category">
									<ul class="list-inline">
										<?php
										$postcat = get_the_category($post->ID);
										if ($postcat) {
											foreach ($postcat as $cat) {
										?>
												<li class="blog-category">
													<a href="<?php echo get_category_link($cat->cat_ID) ?>">
														<?php echo esc_html($cat->name); ?>
													</a>
												</li>
										<?php
											}
										}
										?>
									</ul>
								</div>
								<!-- Category End -->
							<?php endif; ?>


							<!-- Date Start -->
							<?php
							if ($show_date) :
								$archive_year  = get_the_time('Y');
								$archive_month = get_the_time('m');
								$archive_day   = get_the_time('d');
							?>
								<span class="list-inline-item blog-date">
									<a class="ajax-effect-link" href="<?php echo esc_url(get_day_link($archive_year, $archive_month, $archive_day)); ?>" rel="bookmark">
										<time class="entry-date published" datetime="<?php echo get_the_date(DATE_W3C, $post->ID); ?>">
											<?php echo get_the_date('', $post->ID); ?>
										</time>
									</a>

								</span>
							<?php endif; ?>
							<!-- Date End -->

							<!-- Title Start -->
							<a class="new-link socialv-post-title" href="<?php echo esc_url(get_permalink($post->ID)); ?>">
								<<?php echo esc_attr($title_tag);  ?> class="socialv-heading-title">
									<?php echo get_the_title($post->ID); ?>
								</<?php echo esc_attr($title_tag); ?>>
							</a>
							<!-- Title End -->

							<!-- Description Start -->
							<?php if ($show_excerpt) :
								echo '<div class="socialv-widget-content">get_the_excerpt($post->ID)</div>';
							endif;
							//  Description End -

							//  Button Start 
							if ($show_button) : 
								echo '<div class="socialv-btn-container socialv-btn-container">
									<a class="socialv-button socialv-button-link socialv-button socialv-button-link" href="<?php echo esc_url(get_permalink($post->ID)); ?>">
										<span class="socialv-link-line">' . esc_html__("Read More", IQONIC_EXTENSION_TEXT_DOMAIN) .'
										</span>
									</a>
								</div>';
							 endif; 
							//  Button End

						echo '</div></div></div>';

		
			endforeach;
		endif;
		wp_reset_postdata();
		echo '</div></div></div>';
	
	}

	// Widget Backend
	public function form($instance)
	{
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => esc_html__('Recent Post', IQONIC_EXTENSION_TEXT_DOMAIN),
			)
		);
		$title = strip_tags($instance['title']);
		$number    = isset($instance['number']) ? absint($instance['number']) : 5;
		$title_tag = isset($instance['title_tag']) ? absint($instance['title_tag']) : 'h6';
		$show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;
		$show_category = isset($instance['show_category']) ? (bool) $instance['show_category'] : false;
		$show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
		$show_button = isset($instance['show_button']) ? (bool) $instance['show_button'] : false;

		if (isset($instance['socialv-image'])) {
			$iq_image = $instance['socialv-image'];
			if ($iq_image == "image") {
				$ch_image = "checked";
			}
		}
	?>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Number of posts to show:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<input class="tiny-text" id="<?php echo esc_html($this->get_field_id('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('number', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="number" step="1" min="1" value="<?php echo esc_html($number, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo esc_html($this->get_field_id('title_tag', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Select Title Tag:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
			<select id="<?php echo $this->get_field_id('title_tag'); ?>" name="<?php echo $this->get_field_name('title_tag'); ?>" class="widefat">
				<option <?php if ('h1' == $title_tag) echo 'selected="selected"'; ?> value="h1">H1</option>
				<option <?php if ('h2' == $title_tag) echo 'selected="selected"'; ?> value="h2">H2</option>
				<option <?php if ('h3' == $title_tag) echo 'selected="selected"'; ?> value="h3">H3</option>
				<option <?php if ('h4' == $title_tag) echo 'selected="selected"'; ?> value="h4">H4</option>
				<option <?php if ('h5' == $title_tag) echo 'selected="selected"'; ?> value="h5">H5</option>
				<option <?php if ('h6' == $title_tag) echo 'selected="selected"'; ?> value="h6">H6</option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_html($this->get_field_id('show_date', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_date', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_date', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display post Date?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_category); ?> id="<?php echo esc_html($this->get_field_id('show_category', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_category', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_category', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display post Category?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_html($this->get_field_id('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_excerpt', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display post Excerpt?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($show_button); ?> id="<?php echo esc_html($this->get_field_id('show_button', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_button', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
			<label for="<?php echo esc_html($this->get_field_id('show_button', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display Button?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo esc_attr($this->get_field_id('socialv-image', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('socialv-image[]', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" value="image" <?php if (isset($ch_image)) echo esc_html($ch_image, IQONIC_EXTENSION_TEXT_DOMAIN); ?>>
			<label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php echo esc_html('Image', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label></br />
		</p>
<?php
	}
	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];

		$instance['title_tag'] = $new_instance['title_tag'];
		$instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
		$instance['show_category'] = isset($new_instance['show_category']) ? (bool) $new_instance['show_category'] : false;
		$instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
		$instance['show_button'] = isset($new_instance['show_button']) ? (bool) $new_instance['show_button'] : false;
		$instance['socialv-image'] = isset($new_instance['socialv-image']) ? (bool) $new_instance['socialv-image'] : false;
		return $instance;
	}
}
