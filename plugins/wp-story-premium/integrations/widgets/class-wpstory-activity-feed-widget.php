<?php
/**
 * Create activity feed widget.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Activity_Feed_Widget
 *
 * @sicne 2.4.0
 */
class Wpstory_Activity_Feed_Widget extends WP_Widget {
	/**
	 * Wpstory_Submission_Widget constructor.
	 */
	public function __construct() {

		parent::__construct(
			'wpstory-activity-feed-widget',
			esc_html__( 'WP Story - Activity Feed', 'wp-story-premium' ),
			array( 'description' => esc_html__( 'Display all users\' single stories and story submitting form.', 'wp-story-premium' ) )
		);

		add_action(
			'widgets_init',
			function () {
				register_widget( 'Wpstory_Activity_Feed_Widget' );
			}
		);

	}

	/**
	 * Widget render function.
	 *
	 * @param array $args Default widget arguments.
	 * @param array $instance Saved values.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		$login_url = esc_url( $instance['login_url'] );

		echo do_shortcode( '[wpstory-activities form="' . $instance['form'] . '" url="' . $login_url . '"]' );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

	}

	/**
	 * Form rendering.
	 *
	 * @param array $instance Saved values.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$widget_title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$login_url    = ! empty( $instance['login_url'] ) ? $instance['login_url'] : '';
		$form         = ! empty( $instance['form'] ) ? 'yes' : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'wp-story-premium' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'login_url' ) ); ?>"><?php echo esc_html__( 'Login URL:', 'wp-story-premium' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'login_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'login_url' ) ); ?>" type="text" value="<?php echo esc_attr( $login_url ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>"><?php echo esc_html__( 'Publishing Form:', 'wp-story-premium' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form' ) ); ?>" type="checkbox" value="yes"<?php checked( $form, 'yes' ); ?>>
		</p>
		<?php

	}

	/**
	 * Update widget saved values.
	 *
	 * @param array $new_instance New values.
	 * @param array $old_instance Old values.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']     = ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['login_url'] = ! empty( $new_instance['login_url'] ) ? wp_strip_all_tags( $new_instance['login_url'] ) : '';
		$instance['form']      = ! empty( $new_instance['form'] ) ? wp_strip_all_tags( $new_instance['form'] ) : '';

		return $instance;
	}
}

new Wpstory_Activity_Feed_Widget();
