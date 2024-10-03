<?php
/**
 * Create story box widget.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Activity_Feed_Widget
 *
 * @sicne 3.0.0
 */
class Wpstory_Story_Box_Widget extends WP_Widget {
	/**
	 * Wpstory_Submission_Widget constructor.
	 */
	public function __construct() {

		parent::__construct(
			'wpstory-story-box-widget',
			esc_html__( 'WP Story - Story Box', 'wp-story-premium' ),
			array( 'description' => esc_html__( 'Display stories.', 'wp-story-premium' ) )
		);

		add_action(
			'widgets_init',
			function () {
				register_widget( 'Wpstory_Story_Box_Widget' );
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

		echo do_shortcode( '[wpstory id="' . $instance['box'] . '"]' );

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
		$box          = ! empty( $instance['box'] ) ? (int) $instance['box'] : '';

		$story_boxes = new WP_Query(
			array(
				'post_type'      => 'wp-story-box',
				'posts_per_page' => - 1,
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'wp-story-premium' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>">
		</p>
		<?php if ( $story_boxes->have_posts() ) : ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'box' ) ); ?>"><?php echo esc_html__( 'Story Box:', 'wp-story-premium' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'box' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'box' ) ); ?>">
					<option value="" selected disabled><?php esc_html_e( 'Select a Story Box', 'wp-story-premium' ); ?></option>
					<?php
					while ( $story_boxes->have_posts() ) {
						$story_boxes->the_post();
						$cur_id = get_the_ID();

						echo '<option value="' . $cur_id . '"' . selected( $cur_id === $box, true, false ) . '>' . get_the_title() . '</option>';
					}

					wp_reset_postdata();
					?>
				</select>
			</p>
			<?php
		endif;
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

		$instance['title'] = ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['box']   = ! empty( $new_instance['box'] ) ? wp_strip_all_tags( $new_instance['box'] ) : '';

		return $instance;
	}
}

new Wpstory_Story_Box_Widget();
