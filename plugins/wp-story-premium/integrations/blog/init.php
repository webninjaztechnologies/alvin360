<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Display user's public stories in author blog archive page.
 *
 * @return void
 * @since 3.0.0
 */
function wpstory_blog_author_stories() {
	if ( is_author() ) {
		$user_id = get_queried_object_id();
		echo do_shortcode( '[wpstory-user-public-stories id="' . $user_id . '"]' );
	}
}
