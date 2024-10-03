<?php
/**
 * Dynamic contents class
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Dynamic_Contents
 */
class Wpstory_Premium_Dynamic_Contents {
	/**
	 * Display story adding button.
	 *
	 * @param int $user_id User ID.
	 * @param array $args Story shortcode args.
	 *
	 * @return false|string
	 */
	public function story_adding_button( $user_id, $args ) {
		ob_start();
		$type        = $args['type'];
		$render_type = $type;
		$style       = $args['style'];

		if ( 'activities' === $type ) {
			$render_type = 'user-single';
		}

		switch ( $type ) {
			case 'user-public':
				$add_title = esc_html__( 'Add highlight story', 'wp-story-premium' );
				break;

			default:
				$add_title = esc_html__( 'Add story', 'wp-story-premium' );
		}

		$tag      = 'button';
		$tag_type = 'type="button"';

		if ( 'activities' === $type && $args['canAdd'] && ! is_user_logged_in() ) {
			$login_url = isset( $args['loginUrl'] ) && ! empty( $args['loginUrl'] ) ? $args['loginUrl'] : WPSTORY()->get_login_url();
			$tag       = 'a';
			$tag_type  = 'href="' . esc_url( $login_url ) . '"';
		}

		switch ( $style ) {
			case 'instagram':
				?>
				<<?php echo $tag; ?>
					<?php echo $tag_type; ?>
					class="wpstory-add wpstory-add-<?php echo esc_attr( $render_type ); ?> wpstory-slider-item wpstory-feed-item-ins"
					data-type="<?php echo esc_attr( $render_type ); ?>"
					title="<?php echo $add_title; ?>"
					style="display: none"
				>
					<span class="wpstory-circle-image">
						<?php echo get_avatar( $user_id ); ?>
						<span class="wpstory-add-icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none" /><path fill="#64B5F6" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" /></svg>
						</span>
					</span>
					<span class="wpstory-circle-title"><?php echo $add_title; ?></span>
				</<?php echo $tag; ?>>
				<?php
				break;

			case 'facebook':
				?>
				<<?php echo $tag; ?>
					<?php echo $tag_type; ?>
					class="wpstory-add wpstory-add-<?php echo esc_attr( $render_type ); ?> wpstory-slider-item wpstory-feed-item-fb"
					data-type="<?php echo esc_attr( $render_type ); ?>"
					title="<?php echo $add_title; ?>"
					style="display: none"
				>
					<span class="wpstory-fb-cover">
						<?php echo get_avatar( $user_id, 150 ); ?>
					</span>
					<span class="wpstory-fb-title"><?php echo $add_title; ?></span>
					<span class="wpstory-fb-add-icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none" /><path fill="#64B5F6" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" /></svg>
					</span>
				</<?php echo $tag; ?>>
				<?php
				break;
		}

		return ob_get_clean();
	}
}

/**
 * Class returner function.
 *
 * @return Wpstory_Dynamic_Contents
 */
function wpstory_premium_dynamic_contents() {
	return new Wpstory_Premium_Dynamic_Contents();
}
