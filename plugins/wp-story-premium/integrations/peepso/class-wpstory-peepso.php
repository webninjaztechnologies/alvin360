<?php
/**
 * Peepso plugin integration.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_PeepSo
 *
 * @since 2.4.0
 */
class Wpstory_PeepSo {
	/**
	 * Wpstory_PeepSo constructor.
	 */
	public function __construct() {
		add_action( 'peepso_action_before_exec_template', array( $this, 'display_stories' ), 10, 4 );

		// WP Story overrides.
		add_filter( 'wpstory_author_name', array( $this, 'replace_author_name' ), 10, 2 );
		add_filter( 'wpstory_profile_url', array( $this, 'replace_profile_url' ), 10, 2 );
		add_filter( 'wpstory_user_friends', array( $this, 'replace_friends' ), 10, 2 );
		add_filter( 'wpstory_displayed_user_id', array( $this, 'replace_displayed_user_id' ) );
	}

	/**
	 * Display stories on the PeepSo profile.
	 *
	 * @param string $section PeepSo section.
	 * @param string $template PeepSo template.
	 * @param string $data PeepSo data.
	 * @param string $return_output Output string.
	 *
	 * @since 2.4.0
	 */
	public function display_stories( $section, $template, $data, $return_output ) {
		if ( 'auto' !== WPSTORY()->options( 'peepso_placement', 'auto' ) ) {
			return;
		}

		if ( 'profile' === $section && 'focus' === $template ) {
			wpstory_peepso();
		}
	}

	/**
	 * WP Story is using display name but PeepSo is using first_name + last_name.
	 * So we need change this.
	 *
	 * @param string $name User's display name.
	 * @param int    $user_id User's unique ID.
	 *
	 * @return string|null
	 * @since 2.4.0
	 */
	public function replace_author_name( $name, $user_id ) {
		$user = PeepSoUser::get_instance( $user_id );

		if ( is_object( $user ) ) {
			return $user->get_fullname();
		}

		return $name;
	}

	/**
	 * WP Story is using default WordPress author archive url for users' profile link.
	 * Replace this with PeepSo profile url.
	 *
	 * @param string  $url Default profile url.
	 * @param integer $user_id User ID.
	 */
	public function replace_profile_url( $url, $user_id ) {
		$user = PeepSoUser::get_instance( $user_id );

		if ( is_object( $user ) ) {
			return $user->get_profileurl();
		}

		return $url;
	}

	/**
	 * Get PeepSo friends.
	 *
	 * @param array $friends Default friends.
	 * @param int   $user_id User ID.
	 *
	 * @return array
	 * @sicne 3.0.0
	 */
	public function replace_friends( $friends, $user_id ) {
		if ( ! class_exists( 'PeepSoFriendsModel' ) ) {
			return array();
		}

		return (array) PeepSoFriendsModel::get_instance()->get_friends( $user_id );
	}

	/**
	 * Get displayed user ID.
	 *
	 * @sicne 3.0.0
	 */
	public function replace_displayed_user_id() {
		$pro = PeepSoProfileShortcode::get_instance();
		return PeepSoUrlSegments::get_view_id( $pro->get_view_user_id() );
	}
}

if ( WPSTORY()->options( 'peepso_integration' ) ) {
	new Wpstory_PeepSo();
}

/**
 * Display stories for PeepSo.
 *
 * @param bool $single Display single stories.
 * @param bool $public Display public stories.
 */
function wpstory_peepso( $single = true, $public = true ) {
	if ( ! WPSTORY()->options( 'peepso_integration' ) ) {
		return;
	}

	$has_single_stories = WPSTORY()->options( 'peepso_single_stories' ) && $single && class_exists( 'PeepSoFriendsModel' );
	$has_public_stories = WPSTORY()->options( 'peepso_public_stories' ) && $public;

	if ( $has_single_stories ) {
		echo do_shortcode( '[wpstory-user-single-stories]' );
	}

	if ( $has_public_stories ) {
		echo do_shortcode( '[wpstory-user-public-stories]' );
	}
}
