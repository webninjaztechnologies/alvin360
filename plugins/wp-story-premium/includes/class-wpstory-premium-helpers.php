<?php
/**
 * Class Wpstory_Premium_Helpers
 * Plugin helper functions.
 *
 * @package WP Story Premium
 * @sicne 1.2.0
 * @author wpuzman
 */

/**
 * Class Wpstory_Premium_Helpers
 */
class Wpstory_Premium_Helpers {
	/**
	 * Plugin options.
	 *
	 * @var $options
	 */
	public $options;

	/**
	 * Wpstory_Premium_Helpers constructor.
	 */
	public function __construct() {
		$this->options = get_option( 'wp-story-premium' );
	}

	/**
	 * Story strings.
	 *
	 * @return array
	 */
	public function story_strings() {
		return array(
			'homeUrl'              => home_url(),
			'ajaxUrl'              => admin_url( 'admin-ajax.php' ),
			'apply'                => esc_html__( 'Apply', 'wp-story-premium' ),
			'yes'                  => esc_html__( 'Yes', 'wp-story-premium' ),
			'close'                => esc_html__( 'Close', 'wp-story-premium' ),
			'nonce'                => wp_create_nonce( 'wpstory-nonce' ),
			'user'                 => array(
				'isLoggedIn' => is_user_logged_in(),
			),
			'options'              => array(
				'routing'  => $this->options( 'routing' ) === '1',
				'insights' => $this->options( 'story_insights' ) === '1',
			),
			'date_strings'         => [
				'secondAgo'  => esc_html__( 'second ago', 'wp-story-premium' ),
				'secondsAgo' => esc_html__( 'seconds ago', 'wp-story-premium' ),
				'minuteAgo'  => esc_html__( 'minute ago', 'wp-story-premium' ),
				'minutesAgo' => esc_html__( 'minutes ago', 'wp-story-premium' ),
				'hourAgo'    => esc_html__( 'hour ago', 'wp-story-premium' ),
				'hoursAgo'   => esc_html__( 'hours ago', 'wp-story-premium' ),
				'dayAgo'     => esc_html__( 'day ago', 'wp-story-premium' ),
				'daysAgo'    => esc_html__( 'days ago', 'wp-story-premium' ),
				'monthAgo'   => esc_html__( 'month ago', 'wp-story-premium' ),
				'monthsAgo'  => esc_html__( 'months ago', 'wp-story-premium' ),
				'yearAgo'    => esc_html__( 'year ago', 'wp-story-premium' ),
				'yearsAgo'   => esc_html__( 'years ago', 'wp-story-premium' ),
			],
			'editor_lang'          => array(
				'Undo'            => esc_html__( 'Undo', 'wp-story-premium' ),
				'Redo'            => esc_html__( 'Redo', 'wp-story-premium' ),
				'Reset'           => esc_html__( 'Reset', 'wp-story-premium' ),
				'Delete'          => esc_html__( 'Delete', 'wp-story-premium' ),
				'Delete All'      => esc_html__( 'Delete All', 'wp-story-premium' ),
				'Crop'            => esc_html__( 'Crop', 'wp-story-premium' ),
				'Flip'            => esc_html__( 'Flip', 'wp-story-premium' ),
				'Rotate'          => esc_html__( 'Rotate', 'wp-story-premium' ),
				'Draw'            => esc_html__( 'Draw', 'wp-story-premium' ),
				'Shape'           => esc_html__( 'Shape', 'wp-story-premium' ),
				'Icon'            => esc_html__( 'Icon', 'wp-story-premium' ),
				'Text'            => esc_html__( 'Text', 'wp-story-premium' ),
				'Mask'            => esc_html__( 'Mask', 'wp-story-premium' ),
				'Filter'          => esc_html__( 'Filter', 'wp-story-premium' ),
				'Custom'          => esc_html__( 'Custom', 'wp-story-premium' ),
				'Square'          => esc_html__( 'Square', 'wp-story-premium' ),
				'Apply'           => esc_html__( 'Apply', 'wp-story-premium' ),
				'Cancel'          => esc_html__( 'Cancel', 'wp-story-premium' ),
				'Flip X'          => esc_html__( 'Flip X', 'wp-story-premium' ),
				'Flip Y'          => esc_html__( 'Flip Y', 'wp-story-premium' ),
				'Range'           => esc_html__( 'Range', 'wp-story-premium' ),
				'Free'            => esc_html__( 'Free', 'wp-story-premium' ),
				'Straight'        => esc_html__( 'Straight', 'wp-story-premium' ),
				'Rectangle'       => esc_html__( 'Rectangle', 'wp-story-premium' ),
				'Circle'          => esc_html__( 'Circle', 'wp-story-premium' ),
				'Triangle'        => esc_html__( 'Triangle', 'wp-story-premium' ),
				'Fill'            => esc_html__( 'Fill', 'wp-story-premium' ),
				'Stroke'          => esc_html__( 'Stroke', 'wp-story-premium' ),
				'Arrow'           => esc_html__( 'Arrow', 'wp-story-premium' ),
				'Arrow-2'         => esc_html__( 'Arrow-2', 'wp-story-premium' ),
				'Arrow-3'         => esc_html__( 'Arrow-3', 'wp-story-premium' ),
				'Star-1'          => esc_html__( 'Star-1', 'wp-story-premium' ),
				'Star-2'          => esc_html__( 'Star-2', 'wp-story-premium' ),
				'Polygon'         => esc_html__( 'Polygon', 'wp-story-premium' ),
				'Location'        => esc_html__( 'Location', 'wp-story-premium' ),
				'Heart'           => esc_html__( 'Heart', 'wp-story-premium' ),
				'Bubble'          => esc_html__( 'Bubble', 'wp-story-premium' ),
				'Custom icon'     => esc_html__( 'Custom icon', 'wp-story-premium' ),
				'Color'           => esc_html__( 'Color', 'wp-story-premium' ),
				'Bold'            => esc_html__( 'Bold', 'wp-story-premium' ),
				'Italic'          => esc_html__( 'Italic', 'wp-story-premium' ),
				'Underline'       => esc_html__( 'Underline', 'wp-story-premium' ),
				'Left'            => esc_html__( 'Left', 'wp-story-premium' ),
				'Center'          => esc_html__( 'Center', 'wp-story-premium' ),
				'Underline'       => esc_html__( 'Underline', 'wp-story-premium' ),
				'Text size'       => esc_html__( 'Text size', 'wp-story-premium' ),
				'Mask'            => esc_html__( 'Mask', 'wp-story-premium' ),
				'Load Mask Image' => esc_html__( 'Load Mask Image', 'wp-story-premium' ),
				'Grayscale'       => esc_html__( 'Grayscale', 'wp-story-premium' ),
				'Invert'          => esc_html__( 'Invert', 'wp-story-premium' ),
				'Sepia'           => esc_html__( 'Sepia', 'wp-story-premium' ),
				'Sepia2'          => esc_html__( 'Sepia2', 'wp-story-premium' ),
				'Blur'            => esc_html__( 'Blur', 'wp-story-premium' ),
				'Sharpen'         => esc_html__( 'Sharpen', 'wp-story-premium' ),
				'Emboss'          => esc_html__( 'Emboss', 'wp-story-premium' ),
				'Remove White'    => esc_html__( 'Remove White', 'wp-story-premium' ),
				'Distance'        => esc_html__( 'Distance', 'wp-story-premium' ),
				'Brightness'      => esc_html__( 'Brightness', 'wp-story-premium' ),
				'Noise'           => esc_html__( 'Noise', 'wp-story-premium' ),
				'Pixelate'        => esc_html__( 'Pixelate', 'wp-story-premium' ),
				'Color Filter'    => esc_html__( 'Color Filter', 'wp-story-premium' ),
				'Threshold'       => esc_html__( 'Threshold', 'wp-story-premium' ),
				'Tint'            => esc_html__( 'Tint', 'wp-story-premium' ),
				'Multiply'        => esc_html__( 'Multiply', 'wp-story-premium' ),
				'Blend'           => esc_html__( 'Blend', 'wp-story-premium' ),
			),
			'opener'               => ! empty( get_option( 'wp-story-premium' )['opener'] ) ? esc_js( get_option( 'wp-story-premium' )['opener'] ) : 'false',
			'delete_story_confirm' => esc_html__( 'Are you sure want to delete this story item?', 'wp-story-premium' ),
			'image_compression'    => array(
				'enabled' => ! empty( $this->options( 'image_compression' ) ),
				'level'   => $this->options( 'image_compression_level' ),
				'width'   => $this->options( 'image_max_width' ),
				'height'  => $this->options( 'image_max_height' ),
			),
			'image_editor'         => array(),
			'media_error'          => esc_html__( 'Please add an image or video for all story items!', 'wp-story-premium' ),
			'allowed_file_types'   => $this->get_allowed_file_types(),
			'max_file_size'        => wpstory_premium_helpers()->options( 'max_file_size', 10 ),
			'max_file_size_text'   => esc_html__( 'File is too large!', 'wp-story-premium' ),
			'max_file_size_error'  => esc_html__( 'Allowed max file size is: {filesize}.', 'wp-story-premium' ),
			'deleteConfirm'        => esc_html__( 'Are you sure want to delete?', 'wp-story-premium' ),
		);
	}

	/**
	 * Admin js vars.
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function story_admin_strings() {
		return array(
			'homeUrl' => home_url(),
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wpstory-admin-nonce' ),
		);
	}

	/**
	 * Get allowed default image mime types.
	 *
	 * @return array
	 * @sicne 3.4.0
	 */
	public function get_default_allowed_image_types() {
		return apply_filters(
			'wpstory_allowed_image_types',
			array(
				'jpg'  => 'jpg',
				'jpeg' => 'jpeg',
				'png'  => 'png',
				'gif'  => 'gif',
			)
		);
	}

	/**
	 * Get allowed default video mime types.
	 *
	 * @return array
	 * @sicne 3.4.0
	 */
	public function get_default_allowed_video_types() {
		return apply_filters(
			'wpstory_allowed_video_types',
			array(
				'mp4' => 'mp4',
				'mov' => 'mov',
				'wmv' => 'wmv',
				'avi' => 'avi',
				'mpg' => 'mpg',
				'3gp' => '3gp',
			)
		);
	}

	/**
	 * Get allowed file types.
	 *
	 * @param string $return_type Return type. String or Array.
	 *
	 * @return array|string
	 */
	public function get_allowed_file_types( $return_type = 'string' ) {
		$image_types = wpstory_premium_helpers()->options( 'allowed_image_types', array() );
		$video_types = wpstory_premium_helpers()->options( 'allowed_video_types', array() );

		$image_types = ! empty( $image_types ) ? $image_types : $this->get_default_allowed_image_types();
		$video_types = ! empty( $video_types ) ? $video_types : $this->get_default_allowed_video_types();

		if ( 'string' === $return_type ) {
			$allowed_images = array();
			if ( ! empty( $image_types ) ) {
				foreach ( $image_types as $image_type ) {
					$allowed_images[] = 'image/' . $image_type;
				}
			} else {
				$allowed_images = array( 'image/*' );
			}

			$allowed_videos = array();
			if ( ! empty( $video_types ) ) {
				foreach ( $video_types as $video_type ) {
					$allowed_videos[] = 'video/' . $video_type;
				}
			} else {
				$allowed_videos = array( 'video/*' );
			}

			$types = array_merge( $allowed_images, $allowed_videos );

			return wp_json_encode( wp_unslash( $types ) );
		}

		return array_merge( $image_types, $video_types );
	}

	/**
	 * Get plugin script mode.
	 *
	 * @return mixed|string
	 * @sicne 1.2.0
	 */
	public function script_mode() {
		return isset( $this->options['script_mode'] ) ? $this->options['script_mode'] : 'automatic';
	}

	/**
	 * Get story box ids and names.
	 * Maybe can use for any <select> item.
	 * Returns: (array) box_id => box_name
	 *
	 * @param boolean $choose Choose options.
	 *
	 * @return array Story box ids.
	 * @sicne 1.2.0
	 */
	public function get_story_boxes( $choose = false ) {
		$args = array(
			'order'          => 'DESC',
			'post_type'      => 'wp-story-box',
			'posts_per_page' => - 1,
		);

		$query = new \WP_Query( $args );

		$arr = array();

		if ( $choose ) {
			$arr[0] = esc_html__( 'Select a Story Box', 'wp-story-premium' );
		}

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$arr[ get_the_ID() ] = get_the_title();
			}
		}

		wp_reset_postdata();

		return $arr;
	}

	/**
	 * Get options from database.
	 *
	 * @param string $key Option key.
	 * @param string $default Default value.
	 *
	 * @return mixed|null
	 */
	public function options( $key, $default = null ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
	}

	/**
	 * Get styling options.
	 *
	 * @param int $box_id Story box id.
	 * @param string $option_key Option key.
	 * @param boolean $check Check metabox value for condition.
	 * @param boolean $from_opt Fetch from only options.
	 * @param string $default Default val.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_option( $box_id, $option_key, $check = 'style_type', $from_opt = false, $default = null ) {
		$opt = $this->options;

		if ( $from_opt ) {
			return isset( $opt[ $option_key ] ) ? $opt[ $option_key ] : $default;
		}

		$box_meta = get_post_meta( $box_id, 'wp-story-box-metabox', true );

		if ( empty( $check ) ) {
			$check = 'style_type';
		}

		if ( isset( $box_meta[ $check ] ) && 'global' === $box_meta[ $check ] ) {
			return isset( $opt[ $option_key ] ) ? $opt[ $option_key ] : $default;
		}

		if ( isset( $box_meta[ $check ] ) && isset( $box_meta[ $option_key ] ) && ( 'custom' === $box_meta[ $check ] || 'global' !== $box_meta[ $check ] ) ) {
			return $box_meta[ $option_key ];
		}

		return isset( $opt[ $option_key ] ) ? $opt[ $option_key ] : $default;
	}

	/**
	 * Get directly option from options.
	 *
	 * @param string $key Option key.
	 * @param string $default Default value (if empty).
	 *
	 * @return mixed|string|null|boolean
	 */
	public function opt( $key, $default = '' ) {
		return wpstory_premium_helpers()->get_option( '', $key, '', true, $default );
	}

	/**
	 * Get story circle thumbnail size.
	 * It is available only when thumbnail option is set true.
	 *
	 * @param int $box_id Story box id.
	 *
	 * @return string
	 * @since 1.0.1
	 */
	public function story_thumbnail_size( $box_id ) {
		$skin = $this->get_option( $box_id, 'style', 'style' );

		switch ( $skin ) {
			case 'snapgram' === $skin && $this->options( 'circle_size' ):
				$size = 'wpstory-circle';
				break;

			case 'snapssenger' === $skin && $this->options( 'square_size' ):
				$size = 'wpstory-square';
				break;

			case 'vemdezap' === $skin && $this->options( 'list_size' ):
				$size = 'wpstory-list';
				break;

			default:
				$size = 'full';
		}

		return $size;
	}

	/**
	 * Create an array from comma separated string.
	 *
	 * @param string $val Comma separated string.
	 *
	 * @return array
	 */
	public function comma_separated_arr( $val ) {
		return array_map( 'trim', preg_split( '@,@', $val, null, PREG_SPLIT_NO_EMPTY ) );
	}

	/**
	 * Display post's date and time by WordPress settings.
	 *
	 * @param int $id Post ID.
	 *
	 * @return false|string
	 */
	public function date_time( $id ) {
		return get_the_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), $id );
	}

	/**
	 * Calculate user's live stories count.
	 *
	 * @param int $user_id User id.
	 * @param string $post_type WP Post type name.
	 *
	 * @return int|void
	 */
	public function user_story_count( $user_id, $post_type = 'wpstory-user' ) {
		return count_user_posts( $user_id, $post_type, true );
	}

	/**
	 * Get story items count.
	 *
	 * @param integer $user_id User ID.
	 * @param integer $parent Parent post ID.
	 * @param string $post_type Post type.
	 *
	 * @return int
	 * @since 3.0.0
	 */
	public function user_story_item_count( $user_id, $parent = '', $post_type = 'wpstory-public' ) {
		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
				'post_parent'    => $parent,
				'post_status'    => 'publish',
				'author'         => $user_id,
			)
		);

		return $query->found_posts;
	}

	/**
	 * Get user avatar url.
	 *
	 * @param int $user_id User id.
	 * @param int $size Avatar image size.
	 *
	 * @sicne 2.4.0
	 */
	public function get_user_avatar( $user_id = null, $size = 96 ) {
		$avatar_url = get_avatar_url( $user_id, array( 'size' => $size ) );

		return apply_filters( 'wpstory_avatar_url', $avatar_url );
	}

	/**
	 * Get story's author name.
	 *
	 * @param int $user_id User unique ID.
	 *
	 * @return mixed|void
	 *
	 * @sicne 2.4.0
	 */
	public function get_user_name( $user_id = null ) {
		$user_data = get_userdata( $user_id );
		$name      = null;

		if ( $user_data ) {
			$name = $user_data->display_name;
		}

		return apply_filters( 'wpstory_author_name', $name, $user_id );
	}

	/**
	 * Get user public profile url.
	 *
	 * @param integer $user_id User DB ID.
	 */
	public function get_user_profile_url( $user_id ) {
		$url = get_author_posts_url( $user_id );

		return apply_filters( 'wpstory_profile_url', $url, $user_id );
	}

	/**
	 * Get user public profile url.
	 *
	 * @param integer $user_id User DB ID.
	 *
	 * @sicne 3.0.0
	 */
	public function get_user_friends( $user_id ) {
		$friends = apply_filters( 'wpstory_user_friends', array(), $user_id );

		return $this->sanitize_fiends_ids( $friends );
	}

	/**
	 * Get displayed user ID.
	 *
	 * @sicne 3.0.0
	 */
	public function get_displayed_user_id() {
		$user_id = get_current_user_id();

		return apply_filters( 'wpstory_displayed_user_id', $user_id );
	}

	/**
	 * Return login url.
	 *
	 * @return mixed|void
	 */
	public function get_login_url() {
		return apply_filters( 'wpstory_login_url', wp_login_url() );
	}

	/**
	 * Convert time string to second.
	 * Ie: 03:30 => 210
	 *
	 * @param string $time Time string.
	 *
	 * @return float|int|string|null
	 */
	public function time_to_sec( $time ) {
		if ( empty( $time ) ) {
			return 3;
		}

		$seconds  = 0;
		$time_arr = explode( ':', $time );

		$seconds += (int) $time_arr[0] * 60;
		$seconds += (int) $time_arr[1];

		return (int) $seconds;
	}

	/**
	 * Calculate time ago.
	 *
	 * @param int $post_id Story post id.
	 *
	 * @return string
	 */
	public function time_ago( $post_id ) {
		return human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ); // phpcs:ignore
	}

	/**
	 * Get admin notice is active.
	 *
	 * @param string $notice Notice name.
	 *
	 * @since 3.0.
	 */
	public function get_admin_notice( $notice ) {
		$notices = get_option( 'wpstory_premium_admin_notices' );

		return isset( $notices[ $notice ] ) ? $notices[ $notice ] : false;
	}

	/**
	 * Update admin notice value.
	 *
	 * @param string $notice Notice name.
	 * @param strong|boolean $value Notice value.
	 *
	 * @since 3.0.0
	 */
	public function update_admin_notice( $notice, $value ) {
		$notices            = get_option( 'wpstory_premium_admin_notices' );
		$notices[ $notice ] = $value;
		update_option( 'wpstory_premium_notices', $notices );
	}

	/**
	 * Get div loader html.
	 *
	 * @return string
	 *
	 * @since 3.0.0
	 */
	public function loader_html() {
		return '<div class="wpstory-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
	}

	/**
	 * Get user unsafe IP address.
	 *
	 * @return false|string
	 */
	public function get_user_ip() {
		$client_ip = false;

		// In order of preference, with the best ones for this purpose first.
		$address_headers = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		foreach ( $address_headers as $header ) {
			if ( array_key_exists( $header, $_SERVER ) ) {
				/*
				 * HTTP_X_FORWARDED_FOR can contain a chain of comma-separated
				 * addresses. The first one is the original client. It can't be
				 * trusted for authenticity, but we don't need to for this purpose.
				 */
				$address_chain = explode( ',', $_SERVER[ $header ] ); // phpcs:ignore
				$client_ip     = trim( $address_chain[0] );

				break;
			}
		}

		if ( ! $client_ip ) {
			return false;
		}

		$anon_ip = wp_privacy_anonymize_ip( $client_ip, true );

		if ( '0.0.0.0' === $anon_ip || '::' === $anon_ip ) {
			return false;
		}

		return $anon_ip;
	}

	/**
	 * Check if user can manage story.
	 *
	 * @param integer $user_id User ID.
	 * @param integer $story_id Story ID.
	 */
	public function user_can_manage_story( $user_id, $story_id ) {
		// Allow all permissions to admin.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		if ( empty( $user_id ) ) {
			return false;
		}

		if ( empty( $story_id ) ) {
			return false;
		}

		$post = get_post( $story_id );

		/**
		 * Check if post exists.
		 */
		if ( empty( $post ) ) {
			return false;
		}

		/**
		 * Post must be published.
		 */
		if ( 'publish' !== $post->post_status ) {
			return false;
		}

		/**
		 * Check post_type.
		 * post_type must be 'wpstory-user', 'wpstory-public' or 'wp-story'.
		 */
		if ( ! in_array( get_post_type( $story_id ), array( 'wpstory-user', 'wpstory-public', 'wp-story' ), true ) ) {
			return false;
		}

		if ( (int) $post->post_author !== (int) $user_id ) {
			return false;
		}

		return true;
	}

	/**
	 * Re-Order users by last uploaded story time.
	 * Works for only single stories.
	 *
	 * @param array $user_ids User IDs.
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function order_users( $user_ids ) {
		$args = array(
			'number'     => - 1,
			'order'      => 'DESC',
			'fields'     => 'ID',
			'orderby'    => 'meta_value',
			'include'    => $user_ids,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'wpstory_last_updated',
					'value'   => current_time( 'mysql' ),
					'type'    => 'DATE',
					'compare' => '<=',
				),
				array(
					'key'     => 'wpstory_last_updated',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$user_query = new WP_User_Query( $args );

		return array_map( 'intval', $user_query->get_results() );
	}


	/**
	 * Get video cover for stories.
	 *
	 * @param $story_id int Story post ID.
	 *
	 * @return string Cover image URL.
	 * @since 3.0.0
	 */
	public function get_video_cover( $story_id ) {
		$cover     = get_post_meta( $story_id, 'video_cover', true );
		$cover_url = ! empty( $cover ) ? wp_get_attachment_image_url( $cover, 'full' ) : WPSTORY_DIR . '/public/img/video-cover.png';

		return apply_filters( 'wpstory_preview_image', esc_url( $cover_url ) );
	}

	/**
	 * Get option with bool.
	 *
	 * @param int $id Story Box id.
	 * @param string $key Option key.
	 * @param bool $from_opt Force to get option from options table.
	 * @param bool $default Default value if no options saved.
	 *
	 * @return bool Returned boolean.
	 *
	 * @sicne 3.3.0
	 */
	public function bool_opt( $id, $key, $from_opt = false, $default = false ) {
		if ( empty( $id ) ) {
			$from_opt = true;
		}

		$opt_val = $this->get_option( $id, $key, $key, $from_opt, $default );

		return filter_var( $opt_val, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Convert number to short.
	 *
	 * @param $num int Number to convert.
	 *
	 * @return mixed|string
	 * @since 3.4.0
	 */
	public function format_number( $num ) {
		if ( $num > 1000 ) {
			$k = esc_html_x( 'k', 'thousand', 'wp-story-premium' );
			$m = esc_html_x( 'm', 'million', 'wp-story-premium' );
			$b = esc_html_x( 'b', 'billion', 'wp-story-premium' );
			$t = esc_html_x( 't', 'trillion', 'wp-story-premium' );

			$x               = round( $num );
			$x_number_format = number_format( $x );
			$x_array         = explode( ',', $x_number_format );
			$x_parts         = array( 'k', 'm', 'b', 't' );
			$x_count_parts   = count( $x_array ) - 1;
			$x_display       = $x;
			$x_display       = $x_array[0] . ( (int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '' );
			$x_display       .= $x_parts[ $x_count_parts - 1 ];

			return $x_display;
		}

		return $num;
	}

	/**
	 * Convert array to style attributes.
	 *
	 * @param $arr array Array to convert.
	 *
	 * @return string
	 */
	public function array_to_style_atts( $arr ) {
		$atts = '';
		foreach ( $arr as $key => $value ) {
			$atts .= $key . ':' . $value . ';';
		}

		return $atts;
	}

	/**
	 * Sanitize friends' ids and remove empty ids.
	 *
	 * @param array $non_sanitized_ids Friends' ids.
	 *
	 * @return void
	 * @since 3.4.3
	 */
	public function sanitize_fiends_ids( $non_sanitized_ids ) {
		$sanitized_ids = array_filter( array_map( 'intval', $non_sanitized_ids ) );

		return apply_filters( 'wpstory_sanitized_user_friends', $sanitized_ids, $non_sanitized_ids );
	}

}

/**
 * Class returner function.
 *
 * @return Wpstory_Premium_Helpers
 */
function wpstory_premium_helpers() {
	return new Wpstory_Premium_Helpers();
}

function WPSTORY() {
	return new Wpstory_Premium_Helpers();
}
