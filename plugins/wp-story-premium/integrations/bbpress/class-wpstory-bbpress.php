<?php
/**
 * BbPress forum plugin integration.
 *
 * @package WP Story Premium
 */

if ( ! WPSTORY()->options( 'bbpress_integration' ) || ! function_exists( 'bbp_is_single_user_profile' ) ) {
	return;
}

/**
 * Class Wpstory_Bbpress
 */
class Wpstory_Bbpress {
	/**
	 * Wpstory_Bbpress constructor.
	 */
	public function __construct() {
		$hook = apply_filters( 'wpstory_bbp_profile_displaying_hook', 'bbp_template_notices' );
		add_action( $hook, array( $this, 'display_stories' ) );
	}

	/**
	 * Display stories.
	 *
	 * @since 2.0.0
	 */
	public function display_stories() {
		if ( bbp_is_single_user_profile() ) {
			$displayed_user_id = bbp_get_displayed_user_id();
			echo do_shortcode( '[wpstory-user-public-stories id="' . $displayed_user_id . '"]' );
		}
	}
}

new Wpstory_Bbpress();

