<?php
/**
 * Story submit form.
 *
 * @package WP Story Premium
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Wpstory_Premium_Submit
 */
class Wpstory_Premium_Submit {
	/**
	 * Wpstory_Premium_Submit constructor.
	 */
	public function __construct() {
		// Call publishing modal.
		add_action( 'wp_ajax_wpstory_do_submit_form_user_public', array( $this, 'submit_handler_user_public' ) );
		add_action( 'wp_ajax_wpstory_do_submit_form_user_single', array( $this, 'submit_handler_user_single' ) );
		add_action( 'wp_ajax_wpstory_call_submit_form', array( $this, 'call_form' ) );

		// Process publishing modal.
		add_action( 'wp_ajax_wpstory_do_item_delete', array( $this, 'ajax_item_delete' ) );
		add_action( 'wp_ajax_wpstory_do_story_delete', array( $this, 'ajax_story_delete' ) );

		// Ajax file upload.
		add_action( 'wp_ajax_wpstory_do_ajax_file_upload', array( $this, 'ajax_upload' ) );
		add_action( 'wp_ajax_nopriv_wpstory_do_ajax_file_upload', array( $this, 'ajax_upload' ) );
	}

	/**
	 * Fixed story fields.
	 * Story cycle image and title.
	 *
	 * @return false|string
	 * @sicne 2.0.0
	 */
	public function public_story_fields() {
		ob_start();
		$parents_args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'wpstory-public',
			'post_status'    => 'publish',
			'post_parent'    => 0,
			'author'         => get_current_user_id(),
		);

		$parents_query = new WP_Query( $parents_args );
		if ( $parents_query->have_posts() ) :
			?>
			<div class="wpstory-form-row wpstory-current-category-wrapper">
				<label for="wpstory-story-parent">
					<span><?php esc_html_e( 'Story Category', 'wp-story-premium' ); ?></span>
					<button class="wpstory-inline-button wpstory-parent-open-new" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
							<path d="M0 0h24v24H0V0z" fill="none" />
							<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
						</svg>
						<?php esc_html_e( 'create new', 'wp-story-premium' ); ?>
					</button>
					<select class="wpstory-input" name="wpstory-story-parent" id="wpstory-story-parent">
						<?php
						while ( $parents_query->have_posts() ) {
							$parents_query->the_post();
							echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput
						}
						?>
					</select>
				</label>
			</div>
		<?php endif; ?>
		<div class="wpstory-new-category-wrapper<?php echo $parents_query->have_posts() ? ' wpstory-new-category-wrapper--has-parent' : ''; ?>">
			<?php if ( $parents_query->have_posts() ) : ?>
				<button class="wpstory-inline-button wpstory-parent-open-current" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
						<path d="M0 0h24v24H0V0z" fill="none" />
						<path d="M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21v-2z" />
					</svg>
					<?php esc_html_e( 'select category', 'wp-story-premium' ); ?>
				</button>
			<?php endif; ?>
			<div class="wpstory-form-row wpstory-form-row-center">
				<label>
					<span><?php esc_html_e( 'Story Category Image', 'wp-story-premium' ); ?></span>
				</label>
				<input type="file" class="wpstory-story-media wpstory-story-media--parent">
				<input type="hidden" class="wpstory-story-media-id" data-required="false" name="wpstory-story-media-id"<?php disabled( $parents_query->have_posts() ); ?>>
				<span class="wpstory-form-description"><small><?php esc_html_e( 'Recommended sizes: 180x180 px.' ); ?></small></span>
			</div>
			<div class="wpstory-form-row">
				<label for="wpstory-story-circle-title">
					<span><?php esc_html_e( 'Story Category Title', 'wp-story-premium' ); ?></span>
					<input class="wpstory-input wpstory-link-text" type="text" name="wpstory-story-circle-title" id="wpstory-story-circle-title"<?php disabled( $parents_query->have_posts() ); ?>>
				</label>
			</div>
		</div>
		<?php
		wp_reset_postdata();

		return '<div class="wpstory-submit-item">' . ob_get_clean() . '</div>';
	}

	/**
	 * Get form html.
	 *
	 * @param string $type For public stories or single stories.
	 *
	 * @return false|string
	 */
	public function form_content( $type = '' ) {
		ob_start();
		?>
		<div class="wpstory-submit-form-wrapper">
			<form class="wpstory-submit-form" enctype="multipart/form-data" data-type="<?php echo esc_attr( $type ); ?>">
				<?php echo 'user-public' === $type ? $this->public_story_fields() : null; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div class="wpstory-submit-item">
					<?php if ( wpstory_premium_helpers()->options( 'allow_link', true ) ) : ?>
						<div class="wpstory-form-row">
							<label>
								<span><?php esc_html_e( 'Story Link Text', 'wp-story-premium' ); ?></span>
								<input class="wpstory-input wpstory-link-text" type="text" name="wpstory-story-link-text">
							</label>
							<span class="wpstory-form-description"><small><?php esc_html_e( 'Ie: "See Article"', 'wp-story-premium' ); ?></small></span>
						</div>
						<div class="wpstory-form-row">
							<label>
								<span><?php esc_html_e( 'Story Link', 'wp-story-premium' ); ?></span>
								<input class="wpstory-input" type="text" name="wpstory-story-link">
							</label>
						</div>
					<?php endif; ?>
					<div class="wpstory-form-row wpstory-edit-wrapper">
						<label>
							<span><?php esc_html_e( 'Story Media', 'wp-story-premium' ); ?></span>
							<span class="wpstory-need-text"><?php esc_html_e( 'Upload File', 'wp-story-premium' ); ?></span>
						</label>
						<?php
						$allowed_types      = wpstory_premium_helpers()->options( 'allowed_types' );
						$allowed_types_attr = ! empty( $allowed_types ) ? '.' . implode( ',.', $allowed_types ) : 'image/*,video/*';
						$allowed_types_str  = ! empty( $allowed_types ) ? '.' . implode( ', .', $allowed_types ) : 'image/*,video/*';
						?>
						<input type="file" class="wpstory-story-media wpstory-story-media--attachment">
						<input type="hidden" class="wpstory-story-media-id" data-required="true" name="wpstory-story-item-media-id">
						<?php if ( ! empty( $allowed_types ) ) : ?>
							<span class="wpstory-form-description"><small><?php printf( /* translators: %s: file types */ esc_html__( 'Allowed types: %s.' ), esc_attr( $allowed_types_str ) ); ?></small></span>
						<?php endif; ?>
						<span class="wpstory-form-description"><small><?php esc_html_e( 'Recommended sizes: 1080x1920 px.' ); ?></small></span>
					</div>
					<div class="wpstory-form-row wpstory-story-duration-wrapper">
						<label>
							<span><?php esc_html_e( 'Duration', 'wp-story-premium' ); ?></span>
							<input class="wpstory-input" type="number" name="wpstory-story-duration" value="3" min="1">
						</label>
					</div>
				</div>
				<?php wp_nonce_field( 'wpstory-submit-action', 'wpstory-submit-field' ); ?>
				<div class="wpstory-submit-footer">
					<button class="wpstory-button wpstory-submit-button" type="submit">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M440 6.5L24 246.4c-34.4 19.9-31.1 70.8 5.7 85.9L144 379.6V464c0 46.4 59.2 65.5 86.6 28.6l43.8-59.1 111.9 46.2c5.9 2.4 12.1 3.6 18.3 3.6 8.2 0 16.3-2.1 23.6-6.2 12.8-7.2 21.6-20 23.9-34.5l59.4-387.2c6.1-40.1-36.9-68.8-71.5-48.9zM192 464v-64.6l36.6 15.1L192 464zm212.6-28.7l-153.8-63.5L391 169.5c10.7-15.5-9.5-33.5-23.7-21.2L155.8 332.6 48 288 464 48l-59.4 387.3z"/></svg>
						<span><?php esc_html_e( 'Publish', 'wp-story-premium' ); ?></span>
					</button>
				</div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Submit handler for public story form.
	 */
	public function submit_handler_user_public() {
		check_ajax_referer( 'wpstory-submit-action', 'wpstory-submit-field' );

		do_action( 'wpstory_before_story_submit', 'public' );

		$user             = wp_get_current_user();
		$user_story_count = wpstory_premium_helpers()->user_story_count( $user->ID, 'wpstory-public' );
		$story_limit      = wpstory_premium_helpers()->options( 'user_public_story_limit', 10 );

		// Check story count.
		if ( ! empty( $story_limit ) && $user_story_count >= $story_limit ) {
			wp_send_json_error(
				array(
					'message' => sprintf( /* translators: %1$s: story limit %2$s: story count */
						_n(
							'You can publish only %1$s story! Currently story count is %2$s.',
							'You can publish only %1$s stories! Currently story count is %2$s.',
							$story_limit,
							'wp-story-premium'
						),
						$story_limit,
						$user_story_count
					),
				)
			);
		}

		if ( isset( $_POST['wpstory-story-parent'] ) ) {
			$parent_id        = (int) wp_unslash( $_POST['wpstory-story-parent'] );
			$user_item_count  = (array) wpstory_premium_helpers()->user_story_item_count( $user->ID, $parent_id );
			$story_item_limit = wpstory_premium_helpers()->options( 'user_public_story_item_limit', 10 );

			// Check story item count.
			if ( ! empty( $story_item_limit ) && count( $user_item_count ) > $story_item_limit ) {
				wp_send_json_error(
					array(
						'message' => sprintf( /* translators: %s: story item limit */
							_n( 'You can create only %s item per story!', 'You can create only %s items per story!', $story_item_limit, 'wp-story-premium' ),
							$story_item_limit
						),
					)
				);
			}

			// Check user has privileges to append story to this story parent.
			if ( ! wpstory_premium_helpers()->user_can_manage_story( $user->ID, $parent_id ) ) {
				wp_send_json_error( array( 'message' => 'error 1000' ) );
			}

			$parent_title = get_the_title( $parent_id );
			$parent_thumb = get_post_thumbnail_id( $parent_id );
		} else {
			$parent_id    = null;
			$parent_title = isset( $_POST['wpstory-story-circle-title'] ) ? sanitize_text_field( wp_unslash( $_POST['wpstory-story-circle-title'] ) ) : '';
			$parent_thumb = isset( $_POST['wpstory-story-media-id'] ) && ! empty( $_POST['wpstory-story-media-id'] ) ? (int) wp_unslash( $_POST['wpstory-story-media-id'] ) : null;
		}

		$attachment_id = isset( $_POST['wpstory-story-item-media-id'] ) && ! empty( $_POST['wpstory-story-item-media-id'] ) ? (int) wp_unslash( $_POST['wpstory-story-item-media-id'] ) : null;

		// If there is an attachment ID hack, abort ajax.
		if ( empty( $attachment_id ) || 'attachment' !== get_post_type( $attachment_id ) ) {
			wp_send_json_error( array( 'message' => 'error 1001' ) );
		}

		// If there is an attachment ID hack, abort ajax.
		if ( ! empty( $parent_thumb ) && 'attachment' !== get_post_type( $parent_thumb ) ) {
			wp_send_json_error( array( 'message' => 'error 1002' ) );
		}

		if ( ! $parent_id ) {
			$parent_id = wp_insert_post(
				array(
					'post_author' => $user->ID,
					'post_title'  => $parent_title,
					'post_type'   => 'wpstory-public',
					'post_status' => 'publish',
				)
			);

			if ( is_wp_error( $parent_id ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Story can not be published. Try again later.', 'wp-story-premium' ) ) );
			}

			if ( ! empty( $parent_thumb ) ) {
				set_post_thumbnail( $parent_id, $parent_thumb );
			}
		}

		$link_text = isset( $_POST['wpstory-story-link-text'] ) && ! empty( $_POST['wpstory-story-link-text'] ) ? sanitize_text_field( wp_unslash( $_POST['wpstory-story-link-text'] ) ) : '';
		$link      = isset( $_POST['wpstory-story-link'] ) && ! empty( $_POST['wpstory-story-link'] ) ? esc_url_raw( wp_unslash( $_POST['wpstory-story-link'] ) ) : '';
		$duration  = isset( $_POST['wpstory-story-duration'] ) && ! empty( $_POST['wpstory-story-duration'] ) ? (int) wp_unslash( $_POST['wpstory-story-duration'] ) : '';
		$status    = wpstory_premium_helpers()->options( 'user_publish_status', 'draft' );

		if ( ! wpstory_premium_helpers()->options( 'allow_link', true ) ) {
			$link_text = '';
			$link      = '';
		}

		$post_object = array(
			'text'     => $link_text,
			'link'     => $link,
			'duration' => $duration > 0 ? $duration : wpstory_premium_helpers()->options( 'default_story_duration', 3 ),
			'image'    => array(
				'url'       => wp_get_attachment_url( $attachment_id ),
				'id'        => $attachment_id,
				'thumbnail' => wp_get_attachment_image_url( $attachment_id, 'thumbnail', true ),
			),
		);

		$story_id = wp_insert_post(
			array(
				'post_author' => $user->ID,
				'post_title'  => wpstory_premium_helpers()->get_user_name( $user->ID ),
				'post_type'   => 'wpstory-public',
				'post_status' => $status,
				'meta_input'  => $post_object,
				'post_parent' => $parent_id,
			)
		);

		if ( is_wp_error( $story_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Story can not be published. Try again later.', 'wp-story-premium' ) ) );
		}

		// Attach uploaded images to story.
		wp_update_post(
			array(
				'ID'          => $attachment_id,
				'post_parent' => $story_id,
			)
		);

		$published_message = esc_html__( 'Story published!', 'wp-story-premium' );

		if ( 'publish' !== $status ) {
			$published_message = esc_html__( 'Your story sent for review!', 'wp-story-premium' );
		}

		wp_send_json_success( array( 'message' => $published_message ) );
	}

	/**
	 * Submit handler for single story form.
	 */
	public function submit_handler_user_single() {
		check_ajax_referer( 'wpstory-submit-action', 'wpstory-submit-field' );

		do_action( 'wpstory_before_story_submit', 'single' );

		$user             = wp_get_current_user();
		$user_story_count = wpstory_premium_helpers()->user_story_count( $user->ID );
		$story_limit      = wpstory_premium_helpers()->options( 'user_single_story_limit', 10 );

		// Check story count.
		if ( ! empty( $story_limit ) && $user_story_count >= $story_limit ) {
			wp_send_json_error(
				array(
					'message' => sprintf( /* translators: %1$s: story limit %2$s: story count */
						_n(
							'You can publish only %1$s story! Currently story count is %2$s.',
							'You can publish only %1$s stories! Currently story count is %2$s.',
							$story_limit,
							'wp-story-premium'
						),
						$story_limit,
						$user_story_count
					),
				)
			);
		}

		$attachment_id = isset( $_POST['wpstory-story-item-media-id'] ) && ! empty( $_POST['wpstory-story-item-media-id'] ) ? (int) wp_unslash( $_POST['wpstory-story-item-media-id'] ) : null;

		// If there is an attachment ID hack, abort ajax.
		if ( empty( $attachment_id ) || 'attachment' !== get_post_type( $attachment_id ) ) {
			wp_send_json_error( array( 'message' => 'error 1001' ) );
		}

		$link_text = isset( $_POST['wpstory-story-link-text'] ) && ! empty( $_POST['wpstory-story-link-text'] ) ? sanitize_text_field( wp_unslash( $_POST['wpstory-story-link-text'] ) ) : '';
		$link      = isset( $_POST['wpstory-story-link'] ) && ! empty( $_POST['wpstory-story-link'] ) ? esc_url_raw( wp_unslash( $_POST['wpstory-story-link'] ) ) : '';
		$duration  = isset( $_POST['wpstory-story-duration'] ) && ! empty( $_POST['wpstory-story-duration'] ) ? (int) wp_unslash( $_POST['wpstory-story-duration'] ) : '';
		$status    = wpstory_premium_helpers()->options( 'user_publish_status', 'draft' );

		if ( ! wpstory_premium_helpers()->options( 'allow_link', true ) ) {
			$link_text = '';
			$link      = '';
		}

		$post_object = array(
			'text'     => $link_text,
			'link'     => $link,
			'duration' => $duration > 0 ? $duration : wpstory_premium_helpers()->options( 'default_story_duration', 3 ),
			'image'    => array(
				'url'       => wp_get_attachment_url( $attachment_id ),
				'id'        => $attachment_id,
				'thumbnail' => wp_get_attachment_image_url( $attachment_id, 'thumbnail', true ),
			),
		);

		$story_id = wp_insert_post(
			array(
				'post_author' => $user->ID,
				'post_title'  => wpstory_premium_helpers()->get_user_name( $user->ID ),
				'post_type'   => 'wpstory-user',
				'post_status' => $status,
				'meta_input'  => $post_object,
			)
		);

		if ( is_wp_error( $story_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Story can not be published. Try again later.', 'wp-story-premium' ) ) );
		}

		// Attach uploaded images to story.
		wp_update_post(
			array(
				'ID'          => $attachment_id,
				'post_parent' => $story_id,
			)
		);

		$published_message = esc_html__( 'Story published!', 'wp-story-premium' );

		if ( 'publish' !== $status ) {
			$published_message = esc_html__( 'Your story sent for review!', 'wp-story-premium' );
		}

		update_user_meta( $user->ID, 'wpstory_last_updated', current_time( 'mysql' ) );
		wp_send_json_success( array( 'message' => $published_message ) );
	}

	/**
	 * Call submit form with ajax.
	 */
	public function call_form() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

		if ( ! in_array( $type, array( 'user-single', 'user-public' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error 4000' ) );
		}

		ob_start();

		echo $this->form_content( $type ); // phpcs:ignore WordPress.Security.EscapeOutput

		wp_send_json_success( array( 'data' => ob_get_clean() ) );
	}

	/**
	 * Delete story item from author story box.
	 */
	public function ajax_item_delete() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		do_action( 'wpstory_before_item_delete' );

		$box_id = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : null;
		$index  = isset( $_POST['index'] ) ? absint( wp_unslash( $_POST['index'] ) ) : null;

		if ( empty( $box_id ) ) {
			wp_send_json_error( array( 'message' => 'error 5000' ) );
		}

		$box = get_post( $box_id );

		if ( 'wpstory-user' !== get_post_type( $box_id ) ) {
			wp_send_json_error( array( 'message' => 'error 5001' ) );
		}

		$user = wp_get_current_user();

		if ( (int) $user->ID !== (int) $box->post_author ) {
			wp_send_json_error( array( 'message' => 'error 5002' ) );
		}

		$items = get_post_meta( $box_id, 'wp_story_items', true );

		$items[ $index ]['disabled'] = true;

		update_post_meta( $box_id, 'wp_story_items', $items );

		wp_send_json_success();
	}

	/**
	 * Delete story box from author.
	 */
	public function ajax_story_delete() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		do_action( 'wpstory_before_story_delete' );

		$box_id = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : null;
		$index  = isset( $_POST['index'] ) ? absint( wp_unslash( $_POST['index'] ) ) : null;

		if ( empty( $box_id ) ) {
			wp_send_json_error( array( 'message' => 'error 6000' ) );
		}

		$box = get_post( $box_id );

		if ( 'wpstory-user' !== get_post_type( $box_id ) ) {
			wp_send_json_error( array( 'message' => 'error 6001' ) );
		}

		$user = wp_get_current_user();

		if ( (int) $user->ID !== (int) $box->post_author ) {
			wp_send_json_error( array( 'message' => 'error 6002' ) );
		}

		wp_update_post(
			array(
				'ID'          => $box_id,
				'post_status' => 'draft',
			)
		);

		wp_send_json_success();
	}

	/**
	 * Upload image with base64 code.
	 *
	 * @param string $base_64 Image code.
	 * @param string $file_name Image name.
	 *
	 * @return int|WP_Error
	 */
	public function upload_with_base_64( $base_64, $file_name ) {
		$upload_dir      = wp_upload_dir();
		$upload_path     = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
		$image_parts     = explode( ';base64,', $base_64 );
		$decoded         = base64_decode( $image_parts[1] ); // phpcs:ignore
		$hashed_filename = md5( $file_name . microtime() ) . '_' . $file_name;
		$image_upload    = file_put_contents( $upload_path . $hashed_filename, $decoded ); // phpcs:ignore
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = 'image/png';
		$file['size']     = filesize( $upload_path . $hashed_filename );

		$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );
		$filename    = $file_return['file'];
		$attachment  = array(
			'post_mime_type' => $file_return['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir['url'] . '/' . basename( $filename ),
		);

		$attach_id       = wp_insert_attachment( $attachment, $filename );
		$attachment_meta = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attachment_meta );

		return $attach_id;
	}

	/**
	 * Upload files with ajax.
	 */
	public function ajax_upload() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$blob            = $_FILES['file']; // phpcs:ignore
		$blob_mime_type  = $blob['type'];
		$blob_file_type  = explode( '/', $blob_mime_type )[0];
		$blob_check_type = explode( '/', $blob_mime_type )[1];
		$allowed_types   = wpstory_premium_helpers()->get_allowed_file_types( 'array' );

		/**
		 * Check allowed file types.
		 * This method required for security.
		 * First control is being on frontend. If someone hack, block it here.
		 */
		if ( ! empty( $allowed_types ) && ! in_array( $blob_check_type, $allowed_types, true ) ) {
			wp_send_json_error( array( 'message' => 'error 2000' ) );
		}

		if ( 'video' === $blob_file_type ) {
			$video_id = media_handle_upload( 'file', 0 );

			if ( is_wp_error( $video_id ) ) {
				wp_send_json_error( array( 'message' => 'error 2001' ) );
			}

			wp_send_json_success( array( 'message' => $video_id ) );
		}

		$blob_name       = $blob['name'];
		$blob_type       = '.' . explode( '/', $blob_mime_type )[1];
		$upload_dir      = wp_upload_dir();
		$upload_path     = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
		$upload_url      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['url'] ) . DIRECTORY_SEPARATOR;
		$hashed_filename = md5( $blob_name . microtime() ) . '_' . $blob_name . $blob_type;

		move_uploaded_file( $blob['tmp_name'], $upload_path . $hashed_filename );

		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = $blob_mime_type;
		$file['size']     = filesize( $upload_path . $hashed_filename );

		$file_return = wp_handle_sideload(
			$file,
			array(
				'test_form' => false,
				'test_type' => false,
			)
		);

		$filename   = $file_return['file'];
		$attachment = array(
			'post_mime_type' => $blob_mime_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_url . basename( $filename ),
		);

		$attachment_id   = wp_insert_attachment( $attachment, $filename );
		$attachment_meta = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attachment_meta );

		wp_send_json_success( array( 'message' => $attachment_id ) );
	}
}

new Wpstory_Premium_Submit();
