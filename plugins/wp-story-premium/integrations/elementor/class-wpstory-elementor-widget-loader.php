<?php

namespace Wpstory;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Story widget for Elementor.
 *
 * @since 1.2.0
 */
class Wpstory_Elementor_Widget_Loader {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {
		if ( 'elementor' === WPSTORY()->script_mode() ) {
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		}

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/widgets/class-wpstory-elementor-widget.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		$this->include_widgets_files();
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Wpstory_Elementor_Widget() );
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_style( 'wp-story-premium', plugin_dir_url( dirname( __DIR__ ) ) . 'public/css/wp-story-premium.min.css', array(), WPSTORY_PREMIUM_VERSION, false );
		wp_register_script( 'wp-story-premium', plugin_dir_url( dirname( __DIR__ ) ) . 'public/js/wp-story-premium.min.js', array( 'jquery' ), WPSTORY_PREMIUM_VERSION, false );
		wp_localize_script( 'wp-story-premium', 'wpStoryObject', WPSTORY()->story_strings() );
	}
}

// Fire class!
Wpstory_Elementor_Widget_Loader::instance();