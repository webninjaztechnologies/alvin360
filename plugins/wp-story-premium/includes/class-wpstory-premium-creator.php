<?php
/**
 * Story elements creator class.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Premium_Creator
 */
class Wpstory_Premium_Creator {
	/**
	 * Wpstory_Premium_Creator constructor.
	 */
	public function __construct() {
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
		return WPSTORY()->get_option( $box_id, $option_key, $check, $from_opt, $default );
	}

	/**
	 * Get story items from array.
	 *
	 * @param array $story_items Story items.
	 * @param int $story_id Story ID.
	 *
	 * @return array
	 * @sicne 2.0.0
	 */
	public function get_story_items( $story_items, $story_id ) {
		$story_items_arr = array();

		if ( ! empty( $story_items ) && is_array( $story_items ) ) {
			$i = 0;
			foreach ( $story_items as $story_item ) {
				if ( $story_item['disabled'] ) {
					continue;
				}

				$image_id  = isset( $story_item['image']['id'] ) ? $story_item['image']['id'] : '';
				$mime_type = wp_attachment_is( 'video', $image_id ) ? 'video' : 'image';

				$duration = isset( $story_item['duration'] ) ? $story_item['duration'] : 3;
				$src      = isset( $story_item['image']['id'] ) ? wp_get_attachment_url( $story_item['image']['id'] ) : '';

				if ( 'video' === $mime_type ) {
					$video_meta  = get_post_meta( $image_id, '_wp_attachment_metadata', true );
					$time_length = $video_meta['length_formatted'] ?? null;
					$duration    = WPSTORY()->time_to_sec( $time_length );
				}

				$story_items_arr[] = array(
					'type'   => $mime_type,
					'length' => $duration,
					'src'    => $src,
					'srcID'  => $story_item['image']['id'] ?? null,
					'date'   => null,
					'dbID'   => $story_id,
					'button' => array(
						'link'     => $story_item['link'],
						'linkText' => $story_item['text'],
						'target'   => isset( $story_item['new_tab'] ) && $story_item['new_tab'] ? '_blank' : '_self',
					),
				);
				$i ++;
			}
		}

		return $story_items_arr;
	}

	/**
	 * Get user story items from array.
	 *
	 * @param int $story_id Story ID.
	 *
	 * @return array
	 * @sicne 3.0.0
	 */
	public function get_user_story_items( $story_id ) {
		$story_item = array(
			'text'     => get_post_meta( $story_id, 'text', true ),
			'link'     => get_post_meta( $story_id, 'link', true ),
			'image'    => get_post_meta( $story_id, 'image', true ),
			'duration' => get_post_meta( $story_id, 'duration', true ),
			'new_tab'  => get_post_meta( $story_id, 'new_tab', true ),
		);

		$image_id = isset( $story_item['image']['id'] ) ? $story_item['image']['id'] : '';

		if ( empty( $image_id ) ) {
			return;
		}

		$mime_type = wp_attachment_is( 'video', $image_id ) ? 'video' : 'image';

		$duration = isset( $story_item['duration'] ) ? $story_item['duration'] : 3;
		$src      = isset( $story_item['image']['id'] ) ? wp_get_attachment_url( $story_item['image']['id'] ) : '';

		if ( 'video' === $mime_type ) {
			$video_meta  = get_post_meta( $image_id, '_wp_attachment_metadata', true );
			$time_length = $video_meta['length_formatted'] ?? null;
			$duration    = WPSTORY()->time_to_sec( $time_length );
		}

		return array(
			'type'    => $mime_type,
			'length'  => $duration,
			'src'     => $src,
			'preview' => $story_item['image']['url'],
			'date'    => get_the_time( 'U', $story_id ),
			'dbID'    => $story_id,
			'button'  => array(
				'link'     => $story_item['link'],
				'linkText' => $story_item['text'],
				'target'   => isset( $story_item['new_tab'] ) && $story_item['new_tab'] ? '_blank' : '_self',
			),
		);
	}

	/**
	 * Get story items.
	 *
	 * @param int $box_id Story box id.
	 * @param array $query_args Extra wp_query args.
	 * @param string $type Story type.
	 *
	 * @return array|bool|WP_Error
	 * @since 1.0.0
	 */
	public function get_stories( $box_id, $query_args = array(), $type = 'box' ) {
		$stories = true;

		$stories = get_post_meta( $box_id, 'wp-story-box-metabox', true );
		$stories = isset( $stories['ids'] ) ? $stories['ids'] : null;

		if ( ! $stories ) {
			return false;
		}

		$args = array(
			'order'          => 'DESC',
			'post_type'      => 'wp-story',
			'posts_per_page' => - 1,
			'post__in'       => $stories,
			'orderby'        => 'post__in',
			'post_status'    => 'publish',
		);

		/**
		 * Skip timer with adding custom parameter into $query_args argument.
		 * 'skip_timer' => true will ignore timer feature.
		 */
		$skip_timer = isset( $query_args['skip_timer'] ) && $query_args['skip_timer'];

		if ( $this->get_option( $box_id, 'story_timer', 'timer_enable' ) && ! $skip_timer ) {
			$timer_day          = (int) $this->get_option( $box_id, 'story_time_value', 'timer_enable' );
			$args['date_query'] = array(
				'after' => gmdate( 'c', strtotime( '-' . $timer_day . ' days' ) ),
			);
		}

		if ( ! empty( $query_args ) && is_array() ) {
			foreach ( $query_args as $key => $value ) {
				$args[ $key ] = $value;
			}
		}

		$query = new WP_Query( $args );
		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		$i             = 0;
		$ids           = array();
		while ( $query->have_posts() ) {
			$query->the_post();

			$post        = $query->post;
			$author_id   = $post->post_author;
			$story_id    = get_the_ID();
			$story_items = get_post_meta( $story_id, 'wp_story_items', true );

			$story_items_arr = $this->get_story_items( $story_items, $story_id );
			if ( empty( $story_items_arr ) ) {
				continue;
			}

			if ( has_post_thumbnail() ) {
				$circle_image    = get_the_post_thumbnail_url( get_the_ID(), WPSTORY()->story_thumbnail_size( $box_id ) );
				$circle_image_ID = get_the_ID();
			} else {
				$circle_image    = $story_items_arr[0]['src'];
				$circle_image_ID = $story_items_arr[0]['srcID'];
			}

			$circle_title = get_the_title();

			if ( wp_attachment_is( 'video', $circle_image_ID ) ) {
				$mime_type = 'video';
			} else {
				$mime_type = 'image';
			}

			$circles_append = array(
				'authorImage'      => $circle_image,
				'coverAuthorImage' => '',
				'coverImage'       => $circle_image,
				'authorName'       => $circle_title,
				'coverName'        => $circle_title,
				'commonName'       => '',
				'link'             => 'javascript:void(0);',
				'hasMore'          => 'true' === WPSTORY()->options( 'story_reports', 'true' ),
				'items'            => $story_items_arr,
				'type'             => $mime_type,
			);

			$story_circles[] = $circles_append;
		}

		wp_reset_postdata();

		return array(
			'id'      => wp_hash( $type . $box_id ),
			'circles' => $story_circles,
		);
	}

	/**
	 * Get stories from post ids.
	 *
	 * @param int $box_id Story box id.
	 *
	 * @return array|bool|WP_Error
	 * @since 1.0.1
	 * @author wpuzman
	 */
	public function get_post_stories( $box_id ) {
		$meta    = get_post_meta( $box_id, 'wp-story-box-metabox', true );
		$stories = $meta;

		if ( isset( $meta['ids_type'] ) ) {
			if ( 'post' === $meta['ids_type'] ) {
				if ( isset( $meta['fetch_type'] ) && 'auto' === $meta['fetch_type'] ) {
					$fetch_args = array(
						'order'          => 'DESC',
						'posts_per_page' => isset( $meta['posts_count'] ) ? (int) $meta['posts_count'] : 10, // phpcs:ignore
						'fields'         => 'ids',
					);

					if ( isset( $meta['categories'] ) && ! empty( $meta['categories'] ) ) {
						$fetch_args['category__in'] = $meta['categories'];
					}

					$fetch_query = new WP_Query( $fetch_args );

					$stories = $fetch_query->posts;
					wp_reset_postdata();
				} else {
					$stories = isset( $meta['post_ids'] ) && ! empty( $meta['post_ids'] ) ? $meta['post_ids'] : null;
				}
			}

			if ( 'cpt' === $meta['ids_type'] ) {
				$cpt_comma_ids = WPSTORY()->comma_separated_arr( $meta['cpt_ids'] );
				$stories_cpt   = isset( $meta['cpt_ids'] ) && ! empty( $meta['cpt_ids'] ) ? $cpt_comma_ids : null;
				$stories       = ! empty( $stories_cpt ) && is_array( $stories_cpt ) ? $stories_cpt : null;
			}

			if ( 'ws' === $meta['ids_type'] ) {
				return $this->get_wpstory_web_stories( $box_id );
			}

			if ( 'cat' === $meta['ids_type'] ) {
				return $this->get_category_stories( $box_id );
			}

			if ( 'linked' === $meta['ids_type'] ) {
				return $this->get_linked_stories( $box_id );
			}
		}

		if ( ! $stories ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$args = array(
			'order'          => 'DESC',
			'posts_per_page' => - 1,
			'post__in'       => $stories,
			'orderby'        => 'post__in',
		);

		if ( $this->get_option( $box_id, 'story_timer', 'timer_enable' ) ) {
			$timer_day          = (int) $this->get_option( $box_id, 'story_time_value', 'story_time_value' );
			$args['date_query'] = array(
				'after' => gmdate( 'c', strtotime( '-' . $timer_day . ' days' ) ),
			);
		}

		$post_story_query = new WP_Query( $args );

		if ( ! $post_story_query->have_posts() ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		while ( $post_story_query->have_posts() ) {
			$post_story_query->the_post();
			$story_id = get_the_ID();

			$meta_thumbnail = get_post_meta( get_the_ID(), 'wp-story-cycle-image', true );
			$meta_thumbnail = isset( $meta_thumbnail['id'] ) ? $meta_thumbnail['id'] : null;
			$meta_image     = get_post_meta( get_the_ID(), 'wp-story-image', true );

			// Skip if no thumb.
			if ( ! has_post_thumbnail( $story_id ) && empty( $meta_thumbnail ) ) {
				continue;
			}

			if ( ! empty( $meta_thumbnail ) ) {
				$thumbnail_id = $meta_thumbnail;
			} else {
				$thumbnail_id = get_post_thumbnail_id( $story_id );
			}

			if ( isset( $meta_image['id'] ) && ! empty( $meta_image['id'] ) ) {
				$story_image_id = $meta_image['id'];
			} else {
				$story_image_id = get_post_thumbnail_id( $story_id );
			}

			if ( wp_attachment_is( 'video', $story_image_id ) ) {
				$mime_type = 'video';
			} else {
				$mime_type = 'image';
			}

			$post           = get_post( $story_id );
			$author_id      = $post->post_author;
			$thumbnail_meta = wp_get_attachment_metadata( $thumbnail_id );

			$story_items_arr   = array();
			$story_items_arr[] = array(
				'type'   => $mime_type,
				'length' => (int) $meta['duration'],
				'src'    => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
				'bg'     => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
				'date'   => get_the_time( 'U', $story_id ),
				'vH'     => isset( $thumbnail_meta['width'] ) && $thumbnail_meta['width'] > $thumbnail_meta['height'] ? 'horizontal' : 'vertical',
				'dbID'   => $story_id,
				'button' => array(
					'link'     => get_the_permalink(),
					'linkText' => esc_html( $meta['button_title'] ),
					'target'   => '_blank',
				),
			);

			$circles_append = array(
				'authorImage'      => WPSTORY()->get_user_avatar( $author_id ),
				'coverAuthorImage' => WPSTORY()->get_user_avatar( $author_id ),
				'coverImage'       => wp_get_attachment_image_url( $thumbnail_id, 'medium' ),
				'authorName'       => WPSTORY()->get_user_name( $author_id ),
				'coverName'        => get_the_title(),
				'commonName'       => get_the_title(),
				'authorLink'       => WPSTORY()->get_user_profile_url( $author_id ),
				'hasMore'          => false,
				'items'            => $story_items_arr,
				'type'             => $mime_type,
			);

			$story_circles[] = $circles_append;
		}

		wp_reset_postdata();

		return array(
			'id'      => wp_hash( 'box' . $box_id ),
			'circles' => $story_circles,
		);
	}

	/**
	 * Get wpstory web stories.
	 *
	 * @param int $box_id Story box id.
	 *
	 * @return array|bool|WP_Error
	 * @since 3.5.0
	 * @author wpuzman
	 */
	public function get_wpstory_web_stories( $box_id ) {
		$meta    = get_post_meta( $box_id, 'wp-story-box-metabox', true );
		$stories = (array) $meta['ws_ids'] ?? null;

		if ( ! $stories ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$args = array(
			'order'          => 'DESC',
			'post_type'      => [ 'wpstory-web-story', 'web-story' ],
			'posts_per_page' => - 1,
			'post__in'       => $stories,
			'orderby'        => 'post__in',
			'post_status'    => 'publish',
		);

		/**
		 * Skip timer with adding custom parameter into $query_args argument.
		 * 'skip_timer' => true will ignore timer feature.
		 */
		$skip_timer = isset( $query_args['skip_timer'] ) && $query_args['skip_timer'];

		if ( $this->get_option( $box_id, 'story_timer', 'timer_enable' ) && ! $skip_timer ) {
			$timer_day          = (int) $this->get_option( $box_id, 'story_time_value', 'timer_enable' );
			$args['date_query'] = array(
				'after' => gmdate( 'c', strtotime( '-' . $timer_day . ' days' ) ),
			);
		}

		if ( ! empty( $query_args ) && is_array() ) {
			foreach ( $query_args as $key => $value ) {
				$args[ $key ] = $value;
			}
		}

		$query = new WP_Query( $args );
		if ( ! $query->have_posts() ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		$i             = 0;
		$ids           = array();
		while ( $query->have_posts() ) {
			$query->the_post();

			$post      = $query->post;
			$author_id = $post->post_author;
			$story_id  = get_the_ID();

			$circle_image = get_the_post_thumbnail_url( get_the_ID(), WPSTORY()->story_thumbnail_size( $box_id ) );
			$circle_title = get_the_title();

			$circles_append = array(
				'authorImage'      => $circle_image,
				'coverAuthorImage' => '',
				'coverImage'       => $circle_image,
				'authorName'       => $circle_title,
				'coverName'        => $circle_title,
				'commonName'       => '',
				'link'             => 'javascript:void(0);',
				'hasMore'          => false,
				'type'             => 'image',
				'url'              => get_the_permalink(),
			);

			$story_circles[] = $circles_append;
		}

		wp_reset_postdata();

		return array(
			'id'      => wp_hash( 'box' . $box_id ),
			'circles' => $story_circles,
		);
	}

	/**
	 * Get categorised stories.
	 *
	 * @param int $box_id Story box id.
	 *
	 * @return array|WP_Error
	 * @since 3.2.0
	 */
	public function get_category_stories( $box_id ) {
		$meta     = get_post_meta( $box_id, 'wp-story-box-metabox', true );
		$cats_ids = (array) $meta['cat_categories'];

		$cat_terms_args = array(
			'taxonomy' => 'category',
			'include'  => $cats_ids,
		);

		if ( ! empty( $cats_ids ) ) {
			$cat_terms_args['orderby'] = 'include';
		}

		$cat_terms = get_terms( $cat_terms_args );

		if ( is_wp_error( $cat_terms ) || empty( $cat_terms ) ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		foreach ( $cat_terms as $cat_term ) {
			$meta_thumbnail = get_term_meta( $cat_term->term_id, 'wpstory-image', true );
			$story_image_id = $meta_thumbnail['id'] ?? null;
			$max            = (int) $meta['max_post'] ?? 10;

			$cat_query = new WP_Query(
				array(
					'posts_per_page' => $max,
					'tax_query'      => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'id',
							'terms'    => array( $cat_term->term_id ),
						)
					)
				)
			);

			if ( ! $cat_query->have_posts() ) {
				continue;
			}

			$story_items_arr = array();
			$i               = 0;
			while ( $cat_query->have_posts() ) {
				$cat_query->the_post();
				$cur_post_id = get_the_ID();
				$post        = get_post( $cur_post_id );
				$author_id   = $post->post_author;

				$meta_thumbnail = get_post_meta( $cur_post_id, 'wp-story-cycle-image', true );
				$meta_thumbnail = isset( $meta_thumbnail['id'] ) ? $meta_thumbnail['id'] : null;
				$meta_image     = get_post_meta( $cur_post_id, 'wp-story-image', true );

				// Skip if no thumb.
				if ( ! has_post_thumbnail( $cur_post_id ) && empty( $meta_thumbnail ) ) {
					continue;
				}

				if ( ! empty( $meta_thumbnail ) ) {
					$thumbnail_id = $meta_thumbnail;
				} else {
					$thumbnail_id = get_post_thumbnail_id( $cur_post_id );
				}

				if ( $i === 0 ) {
					// If category image is empty, get last post's image.
					if ( empty( $story_image_id ) ) {
						$story_image_id = $thumbnail_id;
					}
				}

				$story_items_arr[] = array(
					'type'       => 'image',
					'mediaTitle' => get_the_title(),
					'length'     => (int) $meta['duration'],
					'src'        => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
					'bg'         => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
					'date'       => WPSTORY()->time_ago( $cur_post_id ),
					'vH'         => isset( $thumbnail_meta['width'] ) && $thumbnail_meta['width'] > $thumbnail_meta['height'] ? 'horizontal' : 'vertical',
					'dbID'       => $cur_post_id,
					'button'     => array(
						'link'     => get_the_permalink(),
						'linkText' => esc_html( $meta['button_title'] ),
						'target'   => '_blank',
					),
				);
				$i ++;
			}

			wp_reset_postdata();

			$circles_append = array(
				'authorImage'      => wp_get_attachment_image_url( $story_image_id, 'medium' ),
				'coverAuthorImage' => '',
				'coverImage'       => wp_get_attachment_image_url( $story_image_id, 'medium' ),
				'authorName'       => $cat_term->name,
				'coverName'        => $cat_term->name,
				'authorLink'       => get_term_link( $cat_term->term_id ),
				'hasMore'          => false,
				'items'            => $story_items_arr,
				'type'             => 'image',
			);

			$story_circles[] = $circles_append;
		}

		return array(
			'id'      => wp_hash( 'box' . $box_id ),
			'circles' => array_values( array_filter( $story_circles ) ),
		);
	}

	/**
	 * Get linked stories.
	 *
	 * @param int $box_id Story box id.
	 *
	 * @return array|WP_Error
	 * @since 3.3.0
	 */
	public function get_linked_stories( $box_id ) {
		$meta           = get_post_meta( $box_id, 'wp-story-box-metabox', true );
		$linked_stories = (array) $meta['linked_stories'];

		if ( empty( $linked_stories ) ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		foreach ( $linked_stories as $linked_story ) {
			$arr_thumbnail  = $linked_story['image'];
			$story_image_id = $arr_thumbnail['id'] ?? null;

			if ( empty( $story_image_id ) ) {
				continue;
			}

			$story_circles[] = array(
				'authorImage'      => '',
				'coverAuthorImage' => '',
				'coverImage'       => wp_get_attachment_image_url( $story_image_id, 'medium' ),
				'authorName'       => '',
				'coverName'        => $linked_story['title'],
				'authorLink'       => '',
				'hasMore'          => false,
				'items'            => '',
				'type'             => 'image',
				'url'              => esc_url( $linked_story['url'] ),
				'newTab'           => $linked_story['new_tab'] === '1',
			);
		}

		return array(
			'id'      => wp_hash( 'box' . $box_id ),
			'circles' => array_values( array_filter( $story_circles ) ),
		);
	}

	/**
	 * Get user single stories.
	 *
	 * @param int $user_id Authors id.
	 * @param array $query_args Extra wp_query args.
	 * @param int $first_user_id Display this user for first on story circles.
	 *
	 * @return array|bool|WP_Error
	 * @since 3.0.0
	 */
	public function get_user_single_stories( $user_id = null, $query_args = array(), $first_user_id = null ) {
		if ( is_array( $user_id ) ) {
			$author_ids = $user_id; // Get manual. Usually for activities.
			$hash_key   = 'activities';

			if ( apply_filters( 'wpstory_reorder_user_ids', true ) ) {
				$author_ids = WPSTORY()->order_users( $author_ids );
			}

			if ( ! empty( $first_user_id ) ) {
				$author_ids = array_diff( $author_ids, array( $first_user_id ) );
				array_unshift( $author_ids, $first_user_id );
			}
		} else {
			$author_ids = WPSTORY()->get_user_friends( $user_id ); // Friends.
			if ( apply_filters( 'wpstory_reorder_user_ids', true ) ) {
				$author_ids = WPSTORY()->order_users( $author_ids );
			}
			array_unshift( $author_ids, $user_id ); // Prepend user id for own self stories.
			$hash_key = $user_id;
		}

		$story_circles = array();
		foreach ( $author_ids as $author_id ) {
			$story_circles[ 'user-' . (int) $author_id ] = '';
		}

		$args = array(
			'post_type'      => 'wpstory-user',
			'posts_per_page' => - 1,
			'author__in'     => $author_ids,
			'post_status'    => 'publish',
		);

		/**
		 * Skip timer with adding custom parameter into $query_args argument.
		 * 'skip_timer' => true will ignore timer feature.
		 */
		$skip_timer = isset( $query_args['skip_timer'] ) && $query_args['skip_timer'];

		if ( WPSTORY()->options( 'story_timer' ) && ! $skip_timer ) {
			$timer_day          = (int) WPSTORY()->options( 'story_time_value' );
			$args['date_query'] = array(
				'after' => gmdate( 'c', strtotime( '-' . $timer_day . ' days' ) ),
			);
		}

		if ( ! empty( $query_args ) ) {
			foreach ( $query_args as $key => $value ) {
				$args[ $key ] = $value;
			}
		}

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_items = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$current_author_id   = (int) get_the_author_meta( 'ID' );
			$current_author_str  = 'user-' . $current_author_id;
			$current_story_items = $this->get_user_story_items( get_the_ID() );

			$append_circles = array(
				'authorImage'      => WPSTORY()->get_user_avatar( $current_author_id, 100 ),
				'coverAuthorImage' => WPSTORY()->get_user_avatar( $current_author_id, 100 ),
				'coverImage'       => $current_story_items['preview'],
				'type'             => $current_story_items['type'],
				'authorName'       => WPSTORY()->get_user_name( $current_author_id ),
				'coverName'        => WPSTORY()->get_user_name( $current_author_id ),
				'commonName'       => '',
				'authorLink'       => WPSTORY()->get_user_profile_url( $current_author_id ),
				'hasMore'          => true,
			);

			if ( empty( $story_circles[ $current_author_str ] ) ) {
				$story_circles[ $current_author_str ] = $append_circles;
			}

			$story_circles[ $current_author_str ]['items'][] = $current_story_items;
		}

		wp_reset_postdata();

		return array(
			'id'      => wp_hash( 'user-single' . $hash_key ),
			'circles' => array_values( array_filter( $story_circles ) ),
		);
	}

	/**
	 * Get user public stories.
	 *
	 * @param int $author_id Author id.
	 * @param array $query_args Extra wp_query args.
	 *
	 * @return array|bool|WP_Error
	 * @since 1.0.0
	 */
	public function get_user_public_stories( $author_id, $query_args = array() ) {
		$args = array(
			'order'          => 'DESC',
			'post_type'      => 'wpstory-public',
			'author'         => $author_id,
			'posts_per_page' => - 1,
			'orderby'        => 'date',
			'post_status'    => 'publish',
			'post_parent'    => 0,
		);

		/**
		 * Skip timer with adding custom parameter into $query_args argument.
		 * 'skip_timer' => true will ignore timer feature.
		 */
		$skip_timer = isset( $query_args['skip_timer'] ) && $query_args['skip_timer'];

		if ( WPSTORY()->options( 'story_timer' ) && ! $skip_timer ) {
			$timer_day          = (int) WPSTORY()->options( 'story_time_value' );
			$args['date_query'] = array(
				'after' => gmdate( 'c', strtotime( '-' . $timer_day . ' days' ) ),
			);
		}

		if ( ! empty( $query_args ) && is_array() ) {
			foreach ( $query_args as $key => $value ) {
				$args[ $key ] = $value;
			}
		}

		$parent_stories = new WP_Query( $args );
		if ( ! $parent_stories->have_posts() ) {
			wp_reset_postdata();

			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$story_circles = array();
		while ( $parent_stories->have_posts() ) {
			$parent_stories->the_post();

			$story_parent_id = get_the_ID();

			$child_stories = new WP_Query(
				array(
					'order'          => 'DESC',
					'post_type'      => 'wpstory-public',
					'author'         => $author_id,
					'posts_per_page' => - 1,
					'orderby'        => 'date',
					'post_status'    => 'publish',
					'post_parent'    => $story_parent_id,
				)
			);

			if ( ! $child_stories->have_posts() ) {
				$child_stories->reset_postdata();
				continue;
			}

			$story_items_arr = array();
			while ( $child_stories->have_posts() ) {
				$child_stories->the_post();
				$child_story_id    = get_the_ID();
				$story_items_arr[] = $this->get_user_story_items( $child_story_id );
			}

			wp_reset_postdata();

			if ( has_post_thumbnail( $story_parent_id ) ) {
				$circle_image_id   = get_post_thumbnail_id( $story_parent_id );
				$circle_image      = wp_get_attachment_image_url( $circle_image_id, 'medium' );
				$circle_image_type = wp_attachment_is( 'image', $circle_image_id ) ? 'image' : 'video';
			} else {
				$circle_image      = $story_items_arr[0]['preview'];
				$circle_image_type = $story_items_arr[0]['type'];
			}

			$circle_title = get_the_title( $story_parent_id );

			$circles_append = array(
				'authorImage'      => $circle_image,
				'coverAuthorImage' => '',
				'coverImage'       => $circle_image,
				'authorName'       => $circle_title,
				'type'             => $circle_image_type,
				'coverName'        => $circle_title,
				'commonName'       => '',
				'authorLink'       => WPSTORY()->get_user_profile_url( $author_id ),
				'hasMore'          => true,
				'items'            => $story_items_arr,
			);

			$story_circles[] = $circles_append;
		}

		wp_reset_postdata();

		return array(
			'id'      => wp_hash( 'user-public' . $author_id ),
			'circles' => $story_circles,
		);
	}

	/**
	 * Get users stories, like activities.
	 *
	 * @param int $user_id Display this user stories at first.
	 *
	 * @return array|WP_Error
	 * @sicne 2.2.0
	 */
	public function get_activity_stories( $user_id = null ) {
		$q_args = array(
			'posts_per_page' => apply_filters( 'wpstory_activity_count', 50 ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'post_status'    => 'publish',
			'post_type'      => 'wpstory-user',
			'orderby'        => 'date',
		);

		$query = new WP_Query( $q_args );

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$author_ids = array();

		while ( $query->have_posts() ) {
			$query->the_post();
			$current_post = get_post( get_the_ID() );
			$author_id    = (int) $current_post->post_author;
			$author_ids[] = $author_id;
		}

		$activity_user_ids = array_values( array_unique( $author_ids ) );

		if ( ! empty( $user_id ) ) {
			$stories = $this->get_user_single_stories( $activity_user_ids, array(), $user_id );
		} else {
			$stories = $this->get_user_single_stories( $activity_user_ids );
		}

		if ( is_wp_error( $stories ) ) {
			return new WP_Error( 'stories-not-found', esc_html__( 'No stories found!', 'wp-story-premium' ) );
		}

		$stories['id'] = wp_hash( 'activities' . $user_id );

		return $stories;
	}

	/**
	 * Get story shortcode data attributes.
	 *
	 * @param int $box_id Story box id.
	 * @param string $type Story type for unique id. 'box, user, author-single'.
	 * @param boolean $from_opt From only global options. It will be pass the meta options.
	 *
	 * @return string
	 */
	public function get_story_shortcode_attr( $box_id, $type = 'box', $from_opt = false ) {
		$full_screen     = $this->get_option( $box_id, 'full_screen', 'full_screen', $from_opt, '1' );
		$full_size_media = $this->get_option( $box_id, 'full_size_media', 'full_size_media', $from_opt, '0' );
		$modal_bg_type   = $this->get_option( $box_id, 'story_background_type', 'bg_style_type', $from_opt, 'auto' );
		$style           = $this->get_option( $box_id, 'style', 'style', $from_opt, 'instagram' );
		$slide_effect    = $this->get_option( $box_id, 'slide_effect', 'slide_effect', $from_opt, 'slide' );
		$arrows          = $this->get_option( $box_id, 'navigation_arrows', 'navigation_arrows', $from_opt, '1' );
		$seen            = $this->get_option( $box_id, 'seen', 'seen', $from_opt, '0' );
		$rtl             = $this->get_option( $box_id, 'rtl_support', 'rtl_support', $from_opt, '0' );
		$swipe           = $this->get_option( $box_id, 'swipe_button', 'swipe_button', $from_opt, '0' );
		$video_silent    = $this->get_option( $box_id, 'video_silent', 'video_silent', $from_opt, '1' );
		$position        = $this->get_option( $box_id, 'cycle_position', 'cycle_position', $from_opt, 'auto' );
		$ids_type        = 'user';

		if ( ! empty( $box_id ) && 'wp-story-box' === get_post_type( $box_id ) ) {
			$meta = get_post_meta( $box_id, 'wp-story-box-metabox', true );

			$ids_type = $meta['ids_type'];
		}

		$story_args = array(
			'id'            => wp_hash( $type . $box_id ),
			'restID'        => $box_id,
			'fullScreen'    => '1' === $full_screen,
			'fullSizeMedia' => '1' === $full_size_media,
			'style'         => $style,
			'position'      => $position,
			'slideEffect'   => $slide_effect,
			'muted'         => '1' === $video_silent,
			'swipe'         => '1' === $swipe,
			'type'          => $type,
			'renderType'    => 'client',
			'idsType'       => $ids_type,
			'modalBgType'   => $modal_bg_type,
		);

		return $story_args;
	}

	/**
	 * Generate css styles for each story box.
	 * It will print with <style> tag.
	 *
	 * @param int $box_id Story box id.
	 * @param boolean $from_opt From only global options. It will be pass the meta options.
	 * @param string $unique Shortcode unique id.
	 *
	 * @return string
	 */
	public function get_story_shortcode_css( $box_id, $from_opt = false, $unique = null ) {
		$circle_bg_type    = $this->get_option( $box_id, 'cycle_background_type', 'cycle_style_type', $from_opt );
		$circle_bg         = $this->get_option( $box_id, 'cycle_bg', 'cycle_style_type', $from_opt );
		$circle_gradient_1 = $this->get_option( $box_id, 'cycle_gradient', 'cycle_style_type', $from_opt )['color-1'];
		$circle_gradient_2 = $this->get_option( $box_id, 'cycle_gradient', 'cycle_style_type', $from_opt )['color-2'];

		if ( 'gradient' !== $circle_bg_type ) {
			$circle_gradient_1 = $circle_bg;
			$circle_gradient_2 = $circle_bg;
		}

		$circle_style_atts = array(
			'--wpstory-circle-gradient-1' => ! empty( $circle_gradient_1 ) ? $circle_gradient_1 : '#e6683c',
			'--wpstory-circle-gradient-2' => ! empty( $circle_gradient_2 ) ? $circle_gradient_2 : '#bc1888',
			'--wpstory-circle-text-color' => $this->get_option( $box_id, 'title_color', 'title_color_type', $from_opt ),
		);

		$button_x          = $this->get_option( $box_id, 'button_padding', '', $from_opt )['width'];
		$button_y          = $this->get_option( $box_id, 'button_padding', '', $from_opt )['height'];
		$button_bg_type    = $this->get_option( $box_id, 'button_background_type', '', $from_opt );
		$button_bg         = $this->get_option( $box_id, 'button_bg', '', $from_opt );
		$button_gradient_1 = $this->get_option( $box_id, 'button_gradient', '', $from_opt )['color-1'];
		$button_gradient_2 = $this->get_option( $box_id, 'button_gradient', '', $from_opt )['color-2'];

		if ( 'gradient' !== $circle_bg_type ) {
			$button_gradient_1 = $button_bg;
			$button_gradient_2 = $button_bg;
		}

		$modal_bg_type    = $this->get_option( $box_id, 'story_background_type', 'bg_style_type', $from_opt, 'auto' );
		$modal_bg         = $this->get_option( $box_id, 'story_bg', 'bg_style_type', $from_opt );
		$modal_gradient_1 = $this->get_option( $box_id, 'story_gradient', 'bg_style_type', $from_opt )['color-1'];
		$modal_gradient_2 = $this->get_option( $box_id, 'story_gradient', 'bg_style_type', $from_opt )['color-2'];

		if ( 'gradient' !== $modal_bg_type ) {
			$modal_gradient_1 = $modal_bg;
			$modal_gradient_2 = $modal_bg;
		}

		if ( 'auto' === $modal_bg_type ) {
			$modal_gradient_1 = '#000';
			$modal_gradient_2 = '#000';
		}

		$modal_atts = array(
			'--wpstory-modal-gradient-1'  => $modal_gradient_1,
			'--wpstory-modal-gradient-2'  => $modal_gradient_2,
			'--wpstory-button-gradient-1' => $button_gradient_1,
			'--wpstory-button-gradient-2' => $button_gradient_2,
			'--wpstory-button-text-color' => $this->get_option( $box_id, 'text_color', '', $from_opt ),
			'--wpstory-button-font-size'  => $this->get_option( $box_id, 'font_size', '', $from_opt ),
			'--wpstory-button-padding'    => sprintf( '%1$spx %2$spx', $button_x, $button_y ),
			'--wpstory-button-radius'     => sprintf( '%spx', $this->get_option( $box_id, 'button_radius', '', $from_opt ) ),
		);

		return array(
			'circle' => $circle_style_atts,
			'modal'  => $modal_atts,
		);
	}
}

/**
 * Class returner function.
 *
 * @return Wpstory_Premium_Helpers
 */
function wpstory_premium_creator() {
	return new Wpstory_Premium_Creator();
}
