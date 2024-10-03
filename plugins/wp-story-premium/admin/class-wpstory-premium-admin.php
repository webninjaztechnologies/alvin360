<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpuzman.com/
 * @since      1.0.0
 *
 * @package    Wpstory_Premium
 * @subpackage Wpstory_Premium/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpstory_Premium
 * @subpackage Wpstory_Premium/admin
 * @author     wpuzman <info@wpuzman.com>
 */
class Wpstory_Premium_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin unique prefix.
	 *
	 * @since  3.0.0
	 * @access private
	 * @var    string $prefix Plugin unique prefix.
	 */
	private $prefix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->prefix = 'wpstory-premium';

	}

	/**
	 * Plugin options.
	 *
	 * @param string $key Option key.
	 *
	 * @return mixed|null
	 * @since 1.0.1
	 */
	public function opt( $key ) {
		return isset( get_option( 'wp-story-premium' )[ $key ] ) ? get_option( 'wp-story-premium' )[ $key ] : null;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook Page hook.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		wp_enqueue_style( $this->prefix . '-admin', WPSTORY_DIR . 'dist/wpstory-premium-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the scripts for the admin area.
	 *
	 * @param string $hook Page hook.
	 *
	 * @since    3.0.0
	 */
	public function enqueue_scripts( $hook ) {
		wp_enqueue_script( $this->prefix . '-admin', WPSTORY_DIR . 'dist/wpstory-premium-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			$this->prefix . '-admin',
			'wpstoryAdminObject',
			wpstory_premium_helpers()->story_admin_strings()
		);
	}

	/**
	 * Create options page under wp-story post type.
	 *
	 * @since 1.0.0
	 */
	public function create_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=wp-story',
			esc_html__( 'Story Boxes', 'wp-story-premium' ),
			esc_html__( 'Story Boxes', 'wp-story-premium' ),
			'manage_options',
			'edit.php?post_type=wp-story-box'
		);

		add_submenu_page(
			'edit.php?post_type=wp-story',
			esc_html__( 'User Stories', 'wp-story-premium' ),
			esc_html__( 'User Stories', 'wp-story-premium' ),
			'manage_options',
			'edit.php?post_type=wpstory-user'
		);

		add_submenu_page(
			'edit.php?post_type=wp-story',
			esc_html__( 'User Stories (Public)', 'wp-story-premium' ),
			esc_html__( 'User Stories (Public)', 'wp-story-premium' ),
			'manage_options',
			'edit.php?post_type=wpstory-public'
		);

		if ( '1' === WPSTORY()->opt( 'story_reports', '1' ) ) {
			add_submenu_page(
				'edit.php?post_type=wp-story',
				esc_html__( 'Reports', 'wp-story-premium' ),
				esc_html__( 'Reports', 'wp-story-premium' ),
				'manage_options',
				'edit.php?post_type=wpstory-report'
			);
		}

		if ( '1' === WPSTORY()->opt( 'enable_web_stories' ) ) {
			add_submenu_page(
				'edit.php?post_type=wp-story',
				esc_html__( 'Web Stories', 'wp-story-premium' ),
				esc_html__( 'Web Stories', 'wp-story-premium' ),
				'manage_options',
				'edit.php?post_type=wpstory-web-story'
			);
		}
	}

	/**
	 * Add shortcode column to wp-story-box post_type list.
	 *
	 * @param array $columns Default columns from WordPress.
	 *
	 * @return array mixed Edited columns with story box shortcodes.
	 * @since 1.0.0
	 */
	public function story_box_shortcode_column( $columns ) {
		$columns['shortcode'] = esc_html__( 'Shortcode', 'wp-story-premium' );

		return $columns;
	}

	/**
	 * Display story box shortcode in "shortcode" column.
	 *
	 * @param string $column Current column slug name.
	 * @param int $post_id Current story box id.
	 *
	 * @since 1.0.0
	 */
	public function story_box_shortcode_column_content( $column, $post_id ) {
		if ( 'shortcode' === $column ) {
			echo "<input type='text' value='[wpstory id=\"$post_id\"]' readonly>"; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}

	/**
	 * Block ajax requests in demo mode.
	 *
	 * @since 2.0.0
	 */
	public function ajax_demo_blocker() {
		if ( apply_filters( 'wpstory_is_demo', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'This feature is disabled in demo mode.', 'wp-story-premium' ) ) );
		}
	}

	/**
	 * Add custom link in the plugin list screen.
	 *
	 * @param array $links Custom links.
	 *
	 * @return array
	 * @sicne 2.0.0
	 */
	public function settings_link( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'edit.php?post_type=wp-story&page=wp-story-premium-options' ) . '">' . esc_html__( 'Settings', 'wp-story-premium' ) . '</a>',
		);

		return array_merge( $links, $settings_link );
	}

	/**
	 * Print some content before options page.
	 *
	 * @since 3.0.0
	 */
	public function options_before_html() {
		if ( 'edit.php?post_type=wp-story' !== get_current_screen()->parent_file ) {
			return;
		}

		echo '<div class="wrap"><h2></h2>';
	}

	/**
	 * Print some content after options page.
	 *
	 * @since 3.0.0
	 */
	public function options_after_html() {
		if ( 'edit.php?post_type=wp-story' !== get_current_screen()->parent_file ) {
			return;
		}

		echo '</div>';
	}

	/**
	 * Get attachment ID details.
	 *
	 * @since 3.0.0
	 */
	public function video_metabox_handle() {
		check_ajax_referer( 'wpstory-admin-nonce', 'nonce' );

		$attachment_id = isset( $_POST['attachmentID'] ) ? (int) wp_unslash( $_POST['attachmentID'] ) : null;

		if ( empty( $attachment_id ) ) {
			exit();
		}

		if ( 'attachment' !== get_post_type( $attachment_id ) ) {
			exit();
		}

		wp_send_json_success(
			array(
				'isVideo' => wp_attachment_is( 'video', $attachment_id ),
				'url'     => wp_get_attachment_url( $attachment_id )
			)
		);
	}

	/**
	 * Remove notice ajax handler.
	 *
	 * @return void
	 *
	 * @sicne 3.2.0
	 */
	public function remove_notice() {
		check_ajax_referer( 'wpstory-admin-nonce', 'nonce' );
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

		if ( empty( $this ) ) {
			exit();
		}

		$const_type = strtoupper( $type );

		$notices            = get_option( 'wpstory_premium_notices' );
		$notices['license'] = array( 'version' => constant( 'WPSTORY_' . $const_type . '_NOTICE_VERSION' ) );
		update_option( 'wpstory_premium_notices', $notices );

		wp_send_json_success();
	}

}
