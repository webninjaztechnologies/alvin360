<?php
/**
 * BBPress Integration.
 *
 * @package WP Story Premium
 */

if ( ! WPSTORY()->options( 'buddypress_integration' ) ) {
	return;
}

/**
 * Class Wpstory_Bbpress
 */
class Wpstory_Buddypress {
	/**
	 * Wpstory_Bbpress constructor.
	 */
	public function __construct() {
		$hook          = apply_filters( 'wpstory_bp_profile_displaying_hook', 'bp_before_member_home_content' );
		$activity_hook = apply_filters( 'wpstory_bp_activity_displaying_hook', 'bp_before_directory_activity' );

		if ( apply_filters( 'wpstory_display_bp_profile_stories', true ) ) {
			add_action( $hook, array( $this, 'display_stories' ) );
		}

		add_action( $activity_hook, array( $this, 'display_activities' ) );
		add_filter( 'wpstory_login_url', array( $this, 'replace_login_url' ) );

		// WP Story overrides.
		add_filter( 'wpstory_profile_url', array( $this, 'replace_profile_url' ), 10, 2 );
		add_filter( 'wpstory_user_friends', array( $this, 'replace_friends' ), 10, 2 );
		add_filter( 'wpstory_displayed_user_id', array( $this, 'replace_displayed_user_id' ) );
	}

	/**
	 * Display stories on the user's avatar.
	 */
	public function display_stories() {
		wpstory_buddypress();
	}

	/**
	 * Display story activities.
	 */
	public function display_activities() {
		if ( ! WPSTORY()->options( 'buddypress_users_activities' ) ) {
			return;
		}

		$display_form = WPSTORY()->options( 'buddypress_activities_form', false );
		$form_attr    = $display_form ? 'yes' : null;
		?>
		<div class="wpstory-buddypress-activities">
			<?php echo do_shortcode( '[wpstory-activities form="' . $form_attr . '"]' ); ?>
		</div>
		<?php
	}

	/**
	 * Replace profile url.
	 *
	 * @param string $current_url Current profile url.
	 *
	 * @return string
	 */
	public function replace_login_url( $current_url ) {
		$custom_login_url = WPSTORY()->options( 'buddypress_activities_login_url', null );

		if ( ! empty( $custom_login_url ) ) {
			return esc_url( $custom_login_url );
		}

		return $current_url;
	}

	/**
	 * WP Story is using default WordPress author archive url for users' profile link.
	 * Replace this with PeepSo profile url.
	 *
	 * @param string $url Default profile url.
	 * @param integer $user_id User ID.
	 */
	public function replace_profile_url( $url, $user_id ) {
		return bp_core_get_userlink( $user_id, false, true );
	}

	/**
	 * Get PeepSo friends.
	 *
	 * @param array $friends Default friends.
	 * @param int $user_id User ID.
	 *
	 * @return array
	 * @sicne 3.0.0
	 */
	public function replace_friends( $friends, $user_id ) {
		return friends_get_friend_user_ids( $user_id );
	}

	/**
	 * Get displayed user ID.
	 *
	 * @sicne 3.0.0
	 */
	public function replace_displayed_user_id() {
		return bp_displayed_user_id();
	}
}

if ( WPSTORY()->options( 'buddypress_integration' ) ) {
	new Wpstory_Buddypress();
}

/**
 * Display stories for BuddyPress.
 *
 * @param bool $single Display single stories.
 * @param bool $public Display public stories.
 */
function wpstory_buddypress( $single = true, $public = true ) {
	if ( ! WPSTORY()->options( 'buddypress_integration' ) ) {
		return;
	}

	$has_single_stories = WPSTORY()->options( 'buddypress_single_stories' ) && $single && function_exists( 'friends_get_friend_user_ids' );
	$has_public_stories = WPSTORY()->options( 'buddypress_public_stories' ) && $public;

	if ( $has_single_stories ) {
		echo do_shortcode( '[wpstory-user-single-stories]' );
	}

	if ( $has_public_stories ) {
		echo do_shortcode( '[wpstory-user-public-stories]' );
	}
}
