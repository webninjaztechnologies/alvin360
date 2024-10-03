<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpuzman.com/
 * @since      1.0.0
 *
 * @package    Wpstory_Premium
 * @subpackage Wpstory_Premium/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpstory_Premium
 * @subpackage Wpstory_Premium/public
 * @author     wpuzman <info@wpuzman.com>
 */
class Wpstory_Premium_Public {

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
	 * Debug slug
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $debug;

	/**
	 * Plugin scripts mode.
	 *
	 * @var string
	 */
	public $script_mode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->script_mode = WPSTORY()->script_mode();

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
		return WPSTORY()->options( $key );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// Story styles.
		wp_register_style( $this->prefix, WPSTORY_DIR . 'dist/wpstory-premium.css', array(), $this->version, 'all' );

		// Submitting styles.
		wp_register_style( $this->prefix . '-submit', WPSTORY_DIR . 'dist/wpstory-premium-submit.css', array(), $this->version );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// Story scripts.
		wp_register_script( $this->prefix, WPSTORY_DIR . 'dist/wpstory-premium.js', array( 'jquery' ), $this->version, true );
		// Main script parameters.
		wp_localize_script( $this->prefix, 'wpStoryObject', WPSTORY()->story_strings() );

		// Submitting scripts.
		wp_register_script( $this->prefix . '-submit', WPSTORY_DIR . 'dist/wpstory-premium-submit.js', array( 'wpstory-premium', 'jquery-ui-sortable' ), $this->version, true );

		// Web Stories player scripts.
		wp_register_script( 'amp-story-player', 'https://cdn.ampproject.org/amp-story-player-v0.js', array( 'jquery' ) );
		wp_register_style( 'amp-story-player', 'https://cdn.ampproject.org/amp-story-player-v0.css' );
	}

	/**
	 * Enqueue Web Stories player scripts.
	 *
	 * @return void
	 */
	public function enqueue_web_stories_player_scripts() {
		wp_enqueue_style( 'amp-story-player' );
		wp_enqueue_script( 'amp-story-player' );
	}

	/**
	 * Public scripts enqueue condition.
	 *
	 * @sicne 2.1.0
	 */
	public function enqueue() {
		// Styles.
		wp_enqueue_style( $this->prefix );

		// Scripts.
		wp_enqueue_script( $this->prefix );
	}

	/**
	 * Submitting scripts enqueue condition.
	 *
	 * @sicne 2.1.0
	 */
	public function enqueue_submit() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		// Styles.
		wp_enqueue_style( $this->prefix . '-editor' );
		wp_enqueue_style( $this->prefix . '-submit' );

		// Scripts.
		wp_enqueue_script( $this->prefix . '-editor' );
		wp_enqueue_script( $this->prefix . '-submit' );
	}

	/**
	 * Get styling options.
	 *
	 * @param int $box_id Story box id.
	 * @param string $option_key Option key.
	 * @param boolean $check Check metabox value for condition.
	 * @param boolean $from_opt Fetch from only options.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_option( $box_id, $option_key, $check, $from_opt = false ) {
		return WPSTORY()->get_option( $box_id, $option_key, $check, $from_opt = false );
	}

	/**
	 * Create shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @since 1.0.0
	 */
	public function shortcode( $atts ) {
		$atts = shortcode_atts(
			array( 'id' => null ),
			$atts,
			'wpstory'
		);

		$id = (int) $atts['id'];

		if ( empty( $id ) ) {
			return null;
		}

		// Check if post exists.
		if ( ! get_post_status( $id ) ) {
			return null;
		}

		$meta        = get_post_meta( $id, 'wp-story-box-metabox', true );
		$data_option = wpstory_premium_creator()->get_story_shortcode_attr( $id );
		$unique      = $data_option['id'];

		if ( isset( $meta['ids_type'] ) && in_array( $meta['ids_type'], array( 'post', 'cpt', 'cat', 'linked', 'ws' ), true ) ) {
			$stories = wpstory_premium_creator()->get_post_stories( $id );
		} else {
			$stories = wpstory_premium_creator()->get_stories( $id );
		}

		// Check if empty stories.
		if ( ! $stories ) {
			return null;
		}

		// Print public scripts.
		$this->enqueue();

		if ( 'ws' === $data_option['idsType'] ) {
			$this->enqueue_web_stories_player_scripts();
		}

		ob_start();

		// Inline css styles.
		$story_html      = '';
		$classes         = array( 'wpstory-shortcode', 'wpstory-feed-container', 'wpstory-item-circles', 'wpstory-slider-container', 'wpstory-story-circles-' . $unique );
		$classes[]       = 'wpstory-shortcode-style-' . $data_option['style'];
		$server_rendered = $this->get_option( $id, 'render', 'render', false );
		$server_rendered = 'server' === $server_rendered;
		$style_atts      = wpstory_premium_creator()->get_story_shortcode_css( $id, false );
		$circle_atts     = WPSTORY()->array_to_style_atts( $style_atts['circle'] );
		$modal_atts      = WPSTORY()->array_to_style_atts( $style_atts['modal'] );

		if ( WPSTORY()->bool_opt( $id, 'uncropped_titles' ) ) {
			$classes[] = 'wpstory-uncropped-titles';
		}

		$story_html .= '<div class="wpstory-shortcode-wrapper">';

		if ( $server_rendered ) {
			$story_html .= '<script>var wpStory' . $unique . '=' . wp_json_encode( $stories ) . '</script>';

			$data_option['renderType'] = 'server';
		}

		$story_html .= '<div id="wpstory-shortcode-' . $unique . '" style="' . $circle_atts . '" class="' . implode( ' ', $classes ) . '" data-args=' . wp_json_encode( $data_option );
		$story_html .= ' data-modal-style-atts=' . wp_json_encode( $modal_atts );
		$story_html .= '>';
		$story_html .= '<div class="wpstory-slider-wrapper">';

		if ( apply_filters( 'wpstory_skeleton_loaders', true ) ) {
			$skeleton_count = apply_filters( 'wpstory_skeleton_loaders_count', 20 );
			$story_html     .= str_repeat( '<span class="wpstory-skeleton-loader"><span></span><span></span><span></span><span></span></span>', $skeleton_count );
		}

		$story_html .= '</div>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-prev"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M14.71 6.71c-.39-.39-1.02-.39-1.41 0L8.71 11.3c-.39.39-.39 1.02 0 1.41l4.59 4.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L10.83 12l3.88-3.88c.39-.39.38-1.03 0-1.41z"/></svg></button>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-next"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.29 6.71c-.39.39-.39 1.02 0 1.41L13.17 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.7 6.7c-.38-.38-1.02-.38-1.41.01z"/></svg></button>';
		$story_html .= '</div>';
		$story_html .= '</div>';

		echo $story_html; // phpcs:ignore WordPress.Security.EscapeOutput

		return ob_get_clean();
	}

	/**
	 * Display users' public stories.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @sicne 2.0.0
	 */
	public function user_public_stories_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => null
			),
			$atts,
			'wpstory-user-public-stories'
		);

		$id      = $atts['id'] ?? WPSTORY()->get_displayed_user_id();
		$has_add = (int) get_current_user_id() === (int) $id;

		if ( empty( $id ) ) {
			return null;
		}

		// Print public scripts.
		$this->enqueue();

		// Print submitting scripts.
		$this->enqueue_submit();

		ob_start();

		$data_option     = wpstory_premium_creator()->get_story_shortcode_attr( $id, 'user-public', true );
		$unique          = $data_option['id'];
		$story_html      = '';
		$classes         = array( 'wpstory-shortcode', 'wpstory-item-circles', 'wpstory-slider-container', 'wpstory-story-circles-' . $unique );
		$classes[]       = 'wpstory-shortcode-style-' . $data_option['style'];
		$server_rendered = $this->get_option( $id, 'render', 'render', true );
		$server_rendered = 'server' === $server_rendered;
		$style_atts      = wpstory_premium_creator()->get_story_shortcode_css( $id, true );
		$circle_atts     = WPSTORY()->array_to_style_atts( $style_atts['circle'] );
		$modal_atts      = WPSTORY()->array_to_style_atts( $style_atts['modal'] );

		if ( $has_add ) {
			$classes[]             = 'wpstory-has-add';
			$data_option['canAdd'] = $has_add;
		}

		if ( WPSTORY()->bool_opt( null, 'uncropped_titles' ) ) {
			$classes[] = 'wpstory-uncropped-titles';
		}

		$story_html .= '<div class="wpstory-shortcode-wrapper">';

		if ( $server_rendered ) {
			$stories = $this->author_public_rest_api_callback( array( 'id' => $id ) );

			$story_html .= '<script>var wpStory' . $unique . '=' . wp_json_encode( $stories ) . '</script>';

			$data_option['renderType'] = 'server';
		}

		$story_html .= '<div id="wpstory-shortcode-' . $unique . '" style="' . $circle_atts . '" class="' . implode( ' ', $classes ) . '" data-args=' . wp_json_encode( $data_option );
		$story_html .= ' data-modal-style-atts=' . wp_json_encode( $modal_atts );
		$story_html .= '>';

		if ( $has_add ) {
			$story_html .= wpstory_premium_dynamic_contents()->story_adding_button( $id, $data_option );
		}

		$story_html .= '<div class="wpstory-slider-wrapper">';

		if ( apply_filters( 'wpstory_skeleton_loaders', true ) ) {
			$skeleton_count = apply_filters( 'wpstory_skeleton_loaders_count', 20 );
			$story_html     .= str_repeat( '<span class="wpstory-skeleton-loader"><span></span><span></span><span></span><span></span></span>', $skeleton_count );
		}

		$story_html .= '</div>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-prev"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M14.71 6.71c-.39-.39-1.02-.39-1.41 0L8.71 11.3c-.39.39-.39 1.02 0 1.41l4.59 4.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L10.83 12l3.88-3.88c.39-.39.38-1.03 0-1.41z"/></svg></button>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-next"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.29 6.71c-.39.39-.39 1.02 0 1.41L13.17 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.7 6.7c-.38-.38-1.02-.38-1.41.01z"/></svg></button>';
		$story_html .= '</div>';
		$story_html .= '</div>';

		echo $story_html; // phpcs:ignore WordPress.Security.EscapeOutput

		return ob_get_clean();
	}

	/**
	 * Display users' single stories.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @sicne 2.4.0
	 */
	public function user_single_stories_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => null
			),
			$atts,
			'wpstory-user-single-stories'
		);

		$id      = $atts['id'] ?? WPSTORY()->get_displayed_user_id();
		$has_add = (int) get_current_user_id() === (int) $id;

		if ( empty( $id ) || (int) get_current_user_id() !== (int) $id ) {
			return null;
		}

		// Print public scripts.
		$this->enqueue();

		// Print submit scripts.
		$this->enqueue_submit();

		ob_start();

		$data_option     = wpstory_premium_creator()->get_story_shortcode_attr( $id, 'user-single', true );
		$unique          = $data_option['id'];
		$story_html      = '';
		$classes         = array( 'wpstory-shortcode', 'wpstory-item-circles', 'wpstory-slider-container', 'wpstory-story-circles-' . $unique );
		$classes[]       = 'wpstory-shortcode-style-' . $data_option['style'];
		$server_rendered = $this->get_option( $id, 'render', 'render', true );
		$server_rendered = 'server' === $server_rendered;
		$style_atts      = wpstory_premium_creator()->get_story_shortcode_css( $id, true );
		$circle_atts     = WPSTORY()->array_to_style_atts( $style_atts['circle'] );
		$modal_atts      = WPSTORY()->array_to_style_atts( $style_atts['modal'] );

		if ( $has_add ) {
			$classes[]             = 'wpstory-has-add';
			$data_option['canAdd'] = $has_add;
		}

		if ( WPSTORY()->bool_opt( null, 'uncropped_titles' ) ) {
			$classes[] = 'wpstory-uncropped-titles';
		}

		$story_html .= '<div class="wpstory-shortcode-wrapper">';

		if ( $server_rendered ) {
			$stories = $this->author_single_rest_api_callback( array( 'id' => $id ) );

			$story_html .= '<script>var wpStory' . $unique . '=' . wp_json_encode( $stories ) . '</script>';

			$data_option['renderType'] = 'server';
		}

		$story_html .= '<div id="wpstory-shortcode-' . $unique . '" style="' . $circle_atts . '" class="' . implode( ' ', $classes ) . '" data-args=' . wp_json_encode( $data_option );
		$story_html .= ' data-modal-style-atts=' . wp_json_encode( $modal_atts );
		$story_html .= '>';

		if ( $has_add ) {
			$story_html .= wpstory_premium_dynamic_contents()->story_adding_button( $id, $data_option );
		}

		$story_html .= '<div class="wpstory-slider-wrapper">';

		if ( apply_filters( 'wpstory_skeleton_loaders', true ) ) {
			$skeleton_count = apply_filters( 'wpstory_skeleton_loaders_count', 20 );
			$story_html     .= str_repeat( '<span class="wpstory-skeleton-loader"><span></span><span></span><span></span><span></span></span>', $skeleton_count );
		}

		$story_html .= '</div>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-prev"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M14.71 6.71c-.39-.39-1.02-.39-1.41 0L8.71 11.3c-.39.39-.39 1.02 0 1.41l4.59 4.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L10.83 12l3.88-3.88c.39-.39.38-1.03 0-1.41z"/></svg></button>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-next"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.29 6.71c-.39.39-.39 1.02 0 1.41L13.17 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.7 6.7c-.38-.38-1.02-.38-1.41.01z"/></svg></button>';
		$story_html .= '</div>';
		$story_html .= '</div>';

		echo $story_html; // phpcs:ignore WordPress.Security.EscapeOutput

		return ob_get_clean();
	}

	/**
	 * Activities shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @sicne 2.0.0
	 */
	public function activities_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'form' => null,
				'url'  => null
			),
			$atts,
			'wpstory-activities'
		);

		ob_start();

		// Print public scripts.
		$this->enqueue();

		$cur_user_id     = ! empty( get_current_user_id() ) ? get_current_user_id() : '';
		$has_add         = strtolower( $atts['form'] ) === 'yes';
		$login_url       = $atts['url'];
		$data_option     = wpstory_premium_creator()->get_story_shortcode_attr( $cur_user_id, 'activities', true );
		$unique          = $data_option['id'];
		$story_html      = '';
		$classes         = array( 'wpstory-shortcode', 'wpstory-item-circles', 'wpstory-slider-container', 'wpstory-story-circles-' . $unique );
		$classes[]       = 'wpstory-shortcode-style-' . $data_option['style'];
		$server_rendered = $this->get_option( '', 'render', '', true );
		$server_rendered = 'server' === $server_rendered;
		$style_atts      = wpstory_premium_creator()->get_story_shortcode_css( 'activity', true );
		$circle_atts     = WPSTORY()->array_to_style_atts( $style_atts['circle'] );
		$modal_atts      = WPSTORY()->array_to_style_atts( $style_atts['modal'] );

		if ( $has_add ) {
			$classes[]               = 'wpstory-has-add';
			$data_option['canAdd']   = $has_add;
			$data_option['loginUrl'] = $login_url;
		}

		if ( WPSTORY()->bool_opt( null, 'uncropped_titles' ) ) {
			$classes[] = 'wpstory-uncropped-titles';
		}

		if ( $has_add && is_user_logged_in() ) {
			// Print submit scripts.
			$this->enqueue_submit();
		}

		$story_html .= '<div class="wpstory-shortcode-wrapper">';

		if ( $server_rendered ) {
			$stories = $this->activities_callback( array( 'id' => $cur_user_id ) );

			$story_html .= '<script>var wpStory' . $unique . '=' . wp_json_encode( $stories ) . '</script>';

			$data_option['renderType'] = 'server';
		}

		$story_html .= '<div id = "wpstory-shortcode-' . $unique . '" style="' . $circle_atts . '" class="' . implode( ' ', $classes ) . '" data-args=' . wp_json_encode( $data_option );
		$story_html .= ' data-modal-style-atts=' . wp_json_encode( $modal_atts );
		$story_html .= '>';

		if ( $has_add ) {
			$story_html .= wpstory_premium_dynamic_contents()->story_adding_button( get_current_user_id(), $data_option );
		}

		$story_html .= '<div class="wpstory-slider-wrapper">';

		if ( apply_filters( 'wpstory_skeleton_loaders', true ) ) {
			$skeleton_count = apply_filters( 'wpstory_skeleton_loaders_count', 20 );
			$story_html     .= str_repeat( '<span class="wpstory-skeleton-loader"><span></span><span></span><span></span><span></span></span>', $skeleton_count );
		}

		$story_html .= '</div>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-prev"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M14.71 6.71c-.39-.39-1.02-.39-1.41 0L8.71 11.3c-.39.39-.39 1.02 0 1.41l4.59 4.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L10.83 12l3.88-3.88c.39-.39.38-1.03 0-1.41z"/></svg></button>';
		$story_html .= '<button type="button" style="display: none" class="wpstory-slider-nav wpstory-slider-nav-next"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.29 6.71c-.39.39-.39 1.02 0 1.41L13.17 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.7 6.7c-.38-.38-1.02-.38-1.41.01z"/></svg></button>';
		$story_html .= '</div>';
		$story_html .= '</div>';

		echo $story_html; // phpcs:ignore WordPress.Security.EscapeOutput

		return ob_get_clean();
	}

	/**
	 * Story box rest api function.
	 */
	public function rest_api() {
		register_rest_route(
			'wp-story/v1',
			'/box/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_callback' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Author stories rest api function.
	 */
	public function author_rest_api() {
		register_rest_route(
			'wp-story/v1',
			'/user-public/(?P<id>.+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'author_public_rest_api_callback' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'wp-story/v1',
			'/user-single/(?P<id>.+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'author_single_rest_api_callback' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'wp-story/v1',
			'/activities(?:/(?P<id>\d+))?',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'activities_callback' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Story box rest api callback function.
	 *
	 * @param array $data Rest api endpoint attributes.
	 *
	 * @return array|bool|WP_Error
	 */
	public function rest_api_callback( $data ) {
		$box_id = (int) $data['id'];

		if ( 'wp-story-box' !== get_post_type( $box_id ) ) {
			return new WP_Error( 'invalid-data', esc_html__( 'Invalid data! ', 'wp-story-premium' ) );
		}

		$meta = get_post_meta( $box_id, 'wp-story-box-metabox', true );

		if ( isset( $meta['ids_type'] ) && in_array( $meta['ids_type'], array( 'post', 'cpt', 'cat', 'linked', 'ws' ), true ) ) {
			return wpstory_premium_creator()->get_post_stories( $box_id );
		} else {
			return wpstory_premium_creator()->get_stories( $box_id );
		}
	}

	/**
	 * Author rest api callback function.
	 * Public stories.
	 *
	 * @param array $data Rest api endpoint attributes.
	 *
	 * @return array|bool|WP_Error
	 */
	public function author_public_rest_api_callback( $data ) {
		$author_id   = (int) $data['id'];
		$custom_args = array();

		if ( ! WPSTORY()->options( 'public_stories_timer' ) ) {
			$custom_args = array( 'skip_timer' => true );
		}

		return wpstory_premium_creator()->get_user_public_stories( $author_id, $custom_args );
	}

	/**
	 * Author rest api callback function.
	 * Single stories.
	 *
	 * @param array $data Rest api endpoint attributes.
	 *
	 * @return array|bool|WP_Error
	 *
	 * @sicne 2.4.0
	 */
	public function author_single_rest_api_callback( $data ) {
		$author_id = (int) $data['id'];

		if ( ! WPSTORY()->options( 'public_stories_timer' ) ) {
			$custom_args['skip_timer'] = true;
		}

		return wpstory_premium_creator()->get_user_single_stories( $author_id );
	}

	/**
	 * Author merged rest api callback function.
	 *
	 * @param array $data Rest api endpoint attributes.
	 *
	 * @return array|bool|WP_Error
	 */
	public function author_merged_rest_api_callback( $data ) {
		$author_id_string = $data['id'];
		$author_ids       = WPSTORY()->comma_separated_arr( $author_id_string );

		return wpstory_premium_creator()->get_merged_stories( $author_ids );
	}

	/**
	 * Activities callback function.
	 *
	 * @param array $data Rest api endpoint attributes.
	 *
	 * @return array|WP_Error
	 * @sicne 2.2.0
	 */
	public function activities_callback( $data = array() ) {
		$author_id = ! empty( $data['id'] ) ? (int) $data['id'] : null;

		return wpstory_premium_creator()->get_activity_stories( $author_id );
	}

	/**
	 * [wp-story] deprecated alert.
	 *
	 * @param array $atts Shortcode vars.
	 *
	 * @since 3.0.0
	 */
	public function deprecated_shortcode( $atts ) {
		$atts = shortcode_atts(
			array( 'id' => null ),
			$atts,
			'wp-story'
		);

		$id = (int) $atts['id'];

		ob_start();

		if ( current_user_can( 'manage_options' ) ) {
			echo '<p>' . esc_html__( '[wp-story] shortcode is deprecated. Use [wpstory] instead.', 'wp-story-premium' ) . '</p>';
		}

		echo do_shortcode( '[wpstory id="' . $id . '"]' );

		return ob_get_clean();
	}

	/**
	 * [wp-story-user-stories] deprecated alert.
	 *
	 * @param array $atts Shortcode vars.
	 *
	 * @since 3.0.0
	 */
	public function user_stories_deprecated_shortcode( $atts ) {
		$atts = shortcode_atts(
			array( 'id' => null ),
			$atts,
			'wp-story-user-stories'
		);

		$id = (int) $atts['id'];

		ob_start();

		if ( current_user_can( 'manage_options' ) ) {
			echo '<p>' . esc_html__( '[wp-story-user-stories] shortcode is deprecated. Use [wpstory-user-public-stories] instead.', 'wp-story-premium' ) . '</p>';
		}

		echo do_shortcode( '[wpstory-user-public-stories id="' . $id . '"]' );

		return ob_get_clean();
	}

	/**
	 * [wp-story-user-single-stories] deprecated alert.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @sicne 2.4.0
	 */
	public function user_single_stories_deprecated_shortcode( $atts ) {
		$atts = shortcode_atts(
			array( 'id' => null ),
			$atts,
			'wp-story-user-single-stories'
		);

		$id = $atts['id'];

		ob_start();

		if ( current_user_can( 'manage_options' ) ) {
			echo '<p>' . esc_html__( '[wp-story-user-single-stories] shortcode is deprecated. Use [wpstory-user-single-stories] instead.', 'wp-story-premium' ) . '</p>';
		}

		echo do_shortcode( '[wpstory-user-single-stories id="' . $id . '"]' );

		return ob_get_clean();
	}

	/**
	 * Activities deprecated shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return false|string|null
	 * @sicne 2.0.0
	 */
	public function activities_deprecated_shortcode( $atts ) {
		$atts = shortcode_atts(
			array( 'count' => null ),
			$atts,
			'wp-story-activities'
		);

		ob_start();

		if ( current_user_can( 'manage_options' ) ) {
			echo '<p>' . esc_html__( '[wp-story-activities] shortcode is deprecated. Use [wpstory-activities] instead.', 'wp-story-premium' ) . '</p>';
		}

		echo do_shortcode( '[wpstory-activities]' );

		return ob_get_clean();
	}

}
