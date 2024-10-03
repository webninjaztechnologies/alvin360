<?php
/**
 * Story modal more buttons.
 *
 * @package Wpstory_Premium
 */

/**
 * Story modal buttons class.
 *
 * @since 3.0.0
 */
class Wpstory_Premium_More_Buttons {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		/**
		 * Get buttons html.
		 */
		add_action( 'wp_ajax_wpstory_modal_more_buttons', array( $this, 'get_buttons' ) );
		add_action( 'wp_ajax_nopriv_wpstory_modal_more_buttons', array( $this, 'get_buttons' ) );

		/**
		 * Delete story.
		 */
		add_action( 'wp_ajax_wpstory_delete_story', array( $this, 'delete_story' ) );

		/**
		 * Check report enabled.
		 */
		if ( '1' === wpstory_premium_helpers()->options( 'story_reports', '1' ) ) {
			/**
			 * Get report form html.
			 */
			add_action( 'wp_ajax_wpstory_modal_report', array( $this, 'report' ) );
			add_action( 'wp_ajax_nopriv_wpstory_modal_report', array( $this, 'report' ) );

			/**
			 * Handle report submit form.
			 */
			add_action( 'wp_ajax_wpstory_report_submit', array( $this, 'report_submit' ) );
			add_action( 'wp_ajax_nopriv_wpstory_report_submit', array( $this, 'report_submit' ) );
		}

		/**
		 * Check insights enabled.
		 */
		if ( '1' === wpstory_premium_helpers()->options( 'story_insights', '0' ) ) {
			add_action( 'wp_ajax_wpstory_modal_insights', array( $this, 'insights' ) );
			add_action( 'wp_ajax_wpstory_set_viewers', array( $this, 'set_viewers' ) );

			add_action( 'wp_ajax_wpstory_get_viewers', array( $this, 'get_viewers' ) );
		}
	}

	/**
	 * Get buttons html.
	 */
	public function get_buttons() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$story_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : null;
		$type     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

		if ( empty( $story_id ) || empty( $type ) ) {
			wp_send_json_error( array( 'message' => 'error 3000' ) );
		}

		if ( ! in_array( $type, array( 'box', 'user-single', 'user-public', 'activities' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error 3001' ) );
		}

		if ( 'publish' !== get_post_status( $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error 3002' ) );
		}

		if ( ! in_array( get_post_type( $story_id ), array( 'wp-story', 'wpstory-user', 'wpstory-public' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error 3003' ) );
		}

		$user_id         = get_current_user_id();
		$user_can_manage = wpstory_premium_helpers()->user_can_manage_story( $user_id, $story_id );

		ob_start();
		?>
		<div class="wpstory-more-buttons">
			<ul class="wpstory-more-buttons-list">
				<?php do_action( 'wpstory_before_more_buttons' ); ?>

				<?php if ( $user_can_manage && 'box' !== $type ) : ?>
					<li class="wpstory-more-buttons-list-item">
						<button type="button" class="wpstory-more-buttons-button wpstory-more-buttons-delete wpstory-more-buttons-delete-first" data-id="<?php echo esc_attr( $story_id ); ?>"><?php esc_html_e( 'Delete', 'wp-story-premium' ); ?></button>
					</li>
				<?php endif; ?>

				<?php
				if (
					$user_can_manage &&
					in_array( $type, array( 'user-single', 'user-public', 'activities' ), true ) &&
					'1' === wpstory_premium_helpers()->options( 'story_insights', '0' )
				) : ?>
					<li class="wpstory-more-buttons-list-item">
						<button type="button" class="wpstory-more-buttons-button wpstory-more-buttons-insights" data-id="<?php echo esc_attr( $story_id ); ?>"><?php esc_html_e( 'View Insights', 'wp-story-premium' ); ?></button>
					</li>
				<?php endif; ?>

				<?php if ( '1' === wpstory_premium_helpers()->options( 'story_reports', '1' ) ) : ?>
					<li class="wpstory-more-buttons-list-item">
						<button type="button" class="wpstory-more-buttons-button wpstory-more-buttons-report" data-id="<?php echo esc_attr( $story_id ); ?>"><?php esc_html_e( 'Report as inappropriate', 'wp-story-premium' ); ?></button>
					</li>
				<?php endif; ?>

				<li class="wpstory-more-buttons-list-item">
					<button type="button" class="wpstory-more-buttons-button wpstory-more-buttons-cancel wpstory-more-close"><?php esc_html_e( 'Cancel', 'wp-story-premium' ); ?></button>
				</li>
				<?php do_action( 'wpstory_after_more_buttons' ); ?>
			</ul>
		</div>
		<?php
		$result = ob_get_clean();

		wp_send_json_success( array( 'result' => $result ) );
	}

	/**
	 * Response report form html.
	 *
	 * @since 3.0.0
	 */
	public function report() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$story_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : null;

		if ( empty( $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error 4000' ) );
		}

		if ( 'publish' !== get_post_status( $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error 4001' ) );
		}

		if ( ! in_array( get_post_type( $story_id ), array( 'wp-story', 'wpstory-user', 'wpstory-public' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error 4002' ) );
		}

		ob_start();
		?>
		<div class="wpstory-own-modal">
			<div class="wpstory-own-modal-head">
				<span class="wpstory-own-modal-head-holder"></span>
				<span class="wpstory-own-modal-title"><?php esc_html_e( 'Report', 'wp-story-premium' ); ?></span>
				<button type="button" class="wpstory-own-modal-close wpstory-more-close">
					<svg class="wpstory-svg-close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
						<path d="M0 0h24v24H0V0z" fill="none" />
						<path d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z" />
					</svg>
				</button>
			</div>
			<div class="wpstory-own-modal-body">
				<form class="wpstory-report-form">
					<label for="wpstory-report-content" class="wpstory-own-modal-desc"><?php esc_html_e( 'Why are you reporting this post?', 'wp-story-premium' ); ?></label>
					<textarea class="wpstory-own-modal-content" name="wpstory-report-content" id="wpstory-report-content" required></textarea>
					<button type="submit" class="wpstory-own-modal-submit"><?php esc_html_e( 'Send Report', 'wp-story-premium' ); ?></button>
					<input type="hidden" name="wpstory-report-id" value="<?php echo esc_attr( $story_id ); ?>">
					<?php wp_nonce_field( 'wpstory-report-submit', 'wpstory-report-submit' ); ?>
				</form>
			</div>
		</div>
		<?php
		$result = ob_get_clean();

		wp_send_json_success( array( 'result' => $result ) );
	}

	/**
	 * Handle report submit form.
	 *
	 * @since 3.0.0
	 */
	public function report_submit() {
		check_ajax_referer( 'wpstory-report-submit', 'wpstory-report-submit' );

		$story_id = isset( $_POST['wpstory-report-id'] ) ? (int) $_POST['wpstory-report-id'] : null;
		$content  = isset( $_POST['wpstory-report-content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wpstory-report-content'] ) ) : null;

		if ( empty( $story_id ) ) {
			exit();
		}

		if ( empty( $content ) ) {
			exit();
		}

		if ( 'publish' !== get_post_status( $story_id ) ) {
			exit();
		}

		if ( ! in_array( get_post_type( $story_id ), array( 'wp-story', 'wpstory-user' ), true ) ) {
			exit();
		}

		ob_start();
		?>
		<div class="wpstory-own-modal-success">
			<div class="wpstory-own-modal-success-head">
				<svg class="wpstory-svg-tick" xmlns="http://www.w3.org/2000/svg" height="48px" width="48px" fill="#58c322" viewBox="0 0 48 48">
					<path d="M24 48C10.8 48 0 37.2 0 24S10.8 0 24 0s24 10.8 24 24-10.8 24-24 24zm0-45C12.4 3 3 12.4 3 24s9.4 21 21 21 21-9.4 21-21S35.6 3 24 3z"></path>
					<path d="M19.9 33.7c-.4 0-.8-.2-1.1-.4l-8.2-8.2c-.6-.6-.6-1.5 0-2.1.6-.6 1.5-.6 2.1 0l7.1 7.1 15.3-15.3c.6-.6 1.5-.6 2.1 0 .6.6.6 1.5 0 2.1L21 33.3c-.3.2-.7.4-1.1.4z"></path>
				</svg>
			</div>
			<div class="wpstory-own-modal-success-body">
				<h4 class="wpstory-own-modal-success-title"><?php esc_html_e( 'Thanks for letting us know', 'wp-story-premium' ); ?></h4>
				<p><?php esc_html_e( 'Your feedback is important in helping us keep the our website safe.', 'wp-story-premium' ); ?></p>
			</div>
			<button type="button" class="wpstory-own-modal-success-close wpstory-more-close"><?php esc_html_e( 'Close', 'wp-story-premium' ); ?></button>
		</div>
		<?php
		$result = ob_get_clean();

		if ( is_user_logged_in() ) {
			$user       = wp_get_current_user();
			$post_title = sprintf( /* translators: %s: user's username. */ esc_html__( '%s\'s Report!', 'wp-story-premium' ), $user->user_login );
			$user_id    = $user->ID;
		} else {
			$post_title = sprintf( /* translators: %s: user's username. */ esc_html__( '%s\'s Report!', 'wp-story-premium' ), wpstory_premium_helpers()->get_user_ip() );
			$user_id    = apply_filters( 'wpstory_default_report_author_id', 1 );
		}

		$args = array(
			'post_type'    => 'wpstory-report',
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_title'   => $post_title,
			'post_content' => $content,
		);

		$post_id = wp_insert_post( $args );

		do_action( 'wpstory_new_report_submitted', $post_id, $user_id );

		wp_send_json_success( array( 'result' => $result ) );
	}

	/**
	 * Delete story ajax handler.
	 *
	 * @since 3.0.0
	 */
	public function delete_story() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$story_id = isset( $_POST['id'] ) ? (int) wp_unslash( $_POST['id'] ) : null;
		$rest_id  = isset( $_POST['rest_id'] ) ? sanitize_text_field( wp_unslash( $_POST['rest_id'] ) ) : null;
		$type     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

		if ( empty( $story_id ) || empty( $type ) ) {
			wp_send_json_error( array( 'message' => 'error ajax_delete_story_1' ) );
		}

		if ( ! in_array( $type, array( 'user-single', 'user-public', 'activities' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error ajax_delete_story_2' ) );
		}

		if ( 'publish' !== get_post_status( $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error ajax_delete_story_3' ) );
		}

		if ( ! in_array( get_post_type( $story_id ), array( 'wpstory-user', 'wpstory-public' ), true ) ) {
			wp_send_json_error( array( 'message' => 'error ajax_delete_story_4' ) );
		}

		$user_id         = get_current_user_id();
		$user_can_manage = wpstory_premium_helpers()->user_can_manage_story( $user_id, $story_id );

		if ( ! $user_can_manage ) {
			wp_send_json_error( array( 'message' => 'error ajax_delete_story_5' ) );
		}

		$status = wpstory_premium_helpers()->options( 'user_deleting_status', 'draft' );

		if ( 'draft' === $status || 'trash' === $status ) {
			wp_update_post(
				array(
					'ID'          => $story_id,
					'post_status' => $status,
				)
			);
		}

		if ( 'delete' === $status ) {
			wp_delete_post( $story_id, true );
		}

		ob_start();
		?>
		<div class="wpstory-own-modal-success">
			<div class="wpstory-own-modal-success-head">
				<svg class="wpstory-svg-tick" xmlns="http://www.w3.org/2000/svg" height="48px" width="48px" fill="#58c322" viewBox="0 0 48 48">
					<path d="M24 48C10.8 48 0 37.2 0 24S10.8 0 24 0s24 10.8 24 24-10.8 24-24 24zm0-45C12.4 3 3 12.4 3 24s9.4 21 21 21 21-9.4 21-21S35.6 3 24 3z"></path>
					<path d="M19.9 33.7c-.4 0-.8-.2-1.1-.4l-8.2-8.2c-.6-.6-.6-1.5 0-2.1.6-.6 1.5-.6 2.1 0l7.1 7.1 15.3-15.3c.6-.6 1.5-.6 2.1 0 .6.6.6 1.5 0 2.1L21 33.3c-.3.2-.7.4-1.1.4z"></path>
				</svg>
			</div>
			<div class="wpstory-own-modal-success-body">
				<h4 class="wpstory-own-modal-success-title"><?php esc_html_e( 'It\'s done!', 'wp-story-premium' ); ?></h4>
				<p><?php esc_html_e( 'Your story has been deleted successfully.', 'wp-story-premium' ); ?></p>
			</div>
			<button type="button" class="wpstory-own-modal-success-close wpstory-more-close"><?php esc_html_e( 'Close', 'wp-story-premium' ); ?></button>
		</div>
		<?php
		$result = ob_get_clean();

		switch ( $type ) {
			case 'activities':
				$items = wpstory_premium_creator()->get_activity_stories();
				break;

			case 'user-public':
				$items = wpstory_premium_creator()->get_user_public_stories( $rest_id );
				break;

			case 'user-single':
				$items = wpstory_premium_creator()->get_user_single_stories( $rest_id );
				break;
		}

		wp_send_json_success(
			array(
				'result' => $result,
				'items'  => wp_json_encode( $items ),
			)
		);
	}

	/**
	 * Set story viewers.
	 *
	 * @since 3.3.0
	 */
	public function set_viewers() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$story_id = isset( $_POST['id'] ) ? (int) wp_unslash( $_POST['id'] ) : null;
		$user_id  = get_current_user_id();

		$story_post = get_post( $story_id );
		$author_id  = (int) $story_post->post_author;
		$friends    = WPSTORY()->get_user_friends( $user_id );

		if ( empty( $author_id ) ) {
			wp_send_json_error( array( 'message' => 'error 3102' ) );
		}

		if ( $user_id === $author_id ) {
			wp_send_json_error( array( 'message' => 'error 3102.1' ) );
		}

		if ( ! in_array( $author_id, $friends, true ) ) {
			wp_send_json_error( array( 'message' => 'error 3103' ) );
		}

		$current_viewers = (array) get_post_meta( $story_id, 'wpstory-viewers', true );
		if ( ! in_array( $user_id, $current_viewers ) ) {
			$current_viewers[] = $user_id;
		}

		update_post_meta( $story_id, 'wpstory-viewers', array_filter( $current_viewers ) );

		wp_send_json_success();
	}

	/**
	 * Get story viewers.
	 *
	 * @since 3.3.0
	 */
	public function get_viewers() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$page     = isset( $_POST['paged'] ) ? (int) wp_unslash( $_POST['paged'] ) : null;
		$story_id = isset( $_POST['id'] ) ? (int) wp_unslash( $_POST['id'] ) : null;

		if ( empty( $page ) ) {
			wp_send_json_error( array( 'message' => 'error 3200' ) );
		}

		$user_id = get_current_user_id();

		if ( ! WPSTORY()->user_can_manage_story( $user_id, $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error 3201' ) );
		}

		$viewers = get_post_meta( $story_id, 'wpstory-viewers', true );
		$users   = get_users(
			array(
				'include' => $viewers,
				'number'  => 10,
				'paged'   => $page,
			)
		);

		ob_start();
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user_id    = $user->ID;
				$username   = WPSTORY()->get_user_name( $user_id );
				$user_url   = WPSTORY()->get_user_profile_url( $user_id );
				$avatar_url = WPSTORY()->get_user_avatar( $user_id );
				?>
				<a
					href="<?php echo esc_url( $user_url ); ?>"
					target="_blank"
					title="<?php echo esc_attr( $username ); ?>"
					class="wpstory-viewers__user"
					rel="noopener noreferrer"
				>
							<span class="wpstory-viewers__user-avatar">
								<img
									src="<?php echo esc_url( $avatar_url ); ?>"
									alt="<?php echo esc_attr( $username ); ?>"
								>
							</span>
					<span class="wpstory-viewers__username"><?php echo esc_html( $username ); ?></span>
				</a>
				<?php
			}
			wp_send_json_success( array( 'result' => ob_get_clean() ) );
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'No result for now.', 'wp-story-premium' ) ) );
		}
	}

	/**
	 * Get story insights.
	 *
	 * @since 3.3.0
	 */
	public function insights() {
		check_ajax_referer( 'wpstory-nonce', 'nonce' );

		$story_id = isset( $_POST['id'] ) ? (int) wp_unslash( $_POST['id'] ) : null;
		$user_id  = get_current_user_id();

		if ( ! WPSTORY()->user_can_manage_story( $user_id, $story_id ) ) {
			wp_send_json_error( array( 'message' => 'error 3300' ) );
		}

		$viewers = get_post_meta( $story_id, 'wpstory-viewers', true );

		if ( ! empty( $viewers ) ) {
			$users = get_users(
				array(
					'include' => $viewers,
					'number'  => 10,
				)
			);

			$users_total = new WP_User_Query(
				array(
					'include'     => $viewers,
					'number'      => - 1,
					'count_total' => true,
				)
			);

			$users_total = WPSTORY()->format_number( $users_total->get_total() );
		} else {
			$users       = null;
			$users_total = 0;
		}

		ob_start();
		?>
		<div class="wpstory-own-modal wpstory-viewers">
			<div class="wpstory-own-modal-head">
				<span class="wpstory-own-modal-head-holder"></span>
				<span class="wpstory-own-modal-title"><?php printf( /* translators: %s: viewers count */ esc_html__( 'Viewers (%s)', 'wp-story-premium' ), $users_total ); ?></span>
				<button type="button" class="wpstory-own-modal-close wpstory-more-close">
					<svg class="wpstory-svg-close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
						<path d="M0 0h24v24H0V0z" fill="none" />
						<path d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z" />
					</svg>
				</button>
			</div>
			<div class="wpstory-own-modal-body">
				<?php
				if ( ! empty( $users ) ) {
					foreach ( $users as $user ) {
						$user_id    = $user->ID;
						$username   = WPSTORY()->get_user_name( $user_id );
						$user_url   = WPSTORY()->get_user_profile_url( $user_id );
						$avatar_url = WPSTORY()->get_user_avatar( $user_id );
						?>
						<a
							href="<?php echo esc_url( $user_url ); ?>"
							target="_blank"
							title="<?php echo esc_attr( $username ); ?>"
							class="wpstory-viewers__user"
							rel="noopener noreferrer"
						>
							<span class="wpstory-viewers__user-avatar">
								<img
									src="<?php echo esc_url( $avatar_url ); ?>"
									alt="<?php echo esc_attr( $username ); ?>"
								>
							</span>
							<span class="wpstory-viewers__username"><?php echo esc_html( $username ); ?></span>
						</a>
						<?php
					}
				} else {
					echo esc_html__( 'No result for now.', 'wp-story-premium' );
				}
				?>
			</div>
		</div>
		<?php
		wp_send_json_success( array( 'result' => ob_get_clean() ) );
	}
}

new Wpstory_Premium_More_Buttons();
