<?php

use function SocialV\Utility\socialv;

function iq_recently_active_members_widgets()
{
	register_widget('Iqonic_Recently_Active_Members');
}
add_action('widgets_init', 'iq_recently_active_members_widgets');

/*-------------------------------------------
		Iqonic Recently Active Members
--------------------------------------------*/
class Iqonic_Recently_Active_Members extends WP_Widget
{

	function __construct()
	{
		parent::__construct(

			// Base ID of your widget
			'Iqonic_Recently_Active_Members',

			// Widget name will appear in UI
			esc_html('Iqonic Recently Active Members', IQONIC_EXTENSION_TEXT_DOMAIN),

			// Widget description
			array('description' => esc_html('Profile photos of recently active members ', IQONIC_EXTENSION_TEXT_DOMAIN))

		);
	}
	// Creating widget front-end
	public function widget($args, $instance)
	{
		global $members_template;

		if (!is_user_logged_in()) return;

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);


		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		$max_members = (!empty($instance['max_members'])) ? absint($instance['max_members']) : 5;
		if (!$max_members) {
			$max_members = 5;
		}

		// Setup args for querying members.
		$members_args = array(
			'type'            	=> 'online',
			'per_page'        	=> $max_members,
			'max'             	=> $max_members,
			'user_id'			=> bp_loggedin_user_id(),
			'populate_extras' 	=> true,
			'search_terms'    	=> false
		);

		// Back up global.
		$old_members_template = $members_template;

?>

		<?php if (bp_has_members($members_args)) :

			echo '<div class="avatar-block"><ul class="list-inline m-0">';
			while (bp_members()) : bp_the_member(); ?>
				<li class="socialv-widget-image-content-wrap">
					<div class="item-avatar">
						<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(array('type' => 'full', 'class'   => 'rounded-circle', 'width' => 60, 'height' => 60)); ?></a>
					</div>
					<div class="avtar-details">
						<div class="member-name">
							<h6 class="title">
								<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
							</h6>
							<span class="socialv-user-status <?php echo esc_attr(socialv()->socialv_is_user_online(bp_get_member_user_id())['status']);; ?>"><span><?php echo esc_html(socialv()->socialv_is_user_online(bp_get_member_user_id())['status_text']); ?></span></span>

						</div>
						<?php
						if (socialv()->socialv_is_user_online(bp_get_member_user_id())['status'] == 'online') : ?>
							<span class="socialv-e-last-activity"><?php esc_html_e('Active', IQONIC_EXTENSION_TEXT_DOMAIN); ?></span>
						<?php else : ?>
							<span class="socialv-e-last-activity" data-livestamp="<?php bp_core_iso8601_date(bp_get_member_last_active(array('relative' => false))); ?>"><?php bp_member_last_active(); ?></span>
				<?php endif;
						echo '</div></li>';
					endwhile;
					echo '</ul></div>';

				else :

					echo '<div class="widget-error">';
					esc_html_e('There are no recently active members', IQONIC_EXTENSION_TEXT_DOMAIN);
					echo '</div>';

				endif;

				echo $args['after_widget'];

				// Restore the global.
				$members_template = $old_members_template;
			}

			// Widget Backend
			public function form($instance)
			{
				$instance = wp_parse_args(
					(array) $instance,
					array(
						'title' => esc_html__('Active User', IQONIC_EXTENSION_TEXT_DOMAIN),
						'max_members' => '5'
					)
				);
				$title = strip_tags($instance['title']);
				$max_members    =  absint($instance['max_members']);
				?>

				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>">
						<?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
						<input class="tiny-text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 100%" />
					</label>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('max_members'); ?>">
						<?php esc_html_e('Max members to show:', IQONIC_EXTENSION_TEXT_DOMAIN); ?>
						<input class="tiny-text" id="<?php echo esc_html($this->get_field_id('max_members', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('max_members', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="number" value="<?php echo esc_html($max_members, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
					</label>
				</p>
		<?php
			}
			// Updating widget replacing old instances with new
			public function update($new_instance, $old_instance)
			{
				$instance = $old_instance;

				$instance['title']       = strip_tags($new_instance['title']);
				$instance['max_members'] = (int) $new_instance['max_members'];
				return $instance;
			}
		}
