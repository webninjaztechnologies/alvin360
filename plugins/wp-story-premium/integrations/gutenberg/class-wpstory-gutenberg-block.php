<?php
/**
 * Gutenberg integration.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Gutenberg_Block
 */
class Wpstory_Gutenberg_Block {
	/**
	 * Wpstory_Gutenberg_Block constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'block_categories_all', array( $this, 'block_categories' ), 10, 2 );
	}

	/**
	 * Register block and scripts.
	 *
	 * @sicne 1.2.0
	 */
	public function init() {
		wp_register_script(
			'wp-story-gutenberg',
			plugin_dir_url( dirname( __DIR__ ) ) . 'integrations/gutenberg/dist/shortcode.js',
			array( 'wp-block-editor', 'wp-editor', 'wp-blocks', 'wp-element', 'wp-polyfill', 'wp-core-data' ),
			WPSTORY_PREMIUM_VERSION,
			true
		);

		wp_register_style(
			'wp-story-gutenberg',
			plugin_dir_url( dirname( __DIR__ ) ) . 'integrations/gutenberg/dist/shortcode.css',
			array(),
			WPSTORY_PREMIUM_VERSION
		);

		register_block_type(
			'wp-story/shortcode',
			array(
				'editor_script' => 'wp-story-gutenberg',
				'editor_style'  => 'wp-story-gutenberg',
			)
		);
	}

	/**
	 * Register custom block category.
	 * Category Slug: wp-story
	 * Category Name: WP Story
	 *
	 * @param array  $categories Default Gutenberg categories.
	 * @param object $post Current post object.
	 * @return mixed
	 * @sicne 1.2.0
	 * @author wpuzman
	 */
	public function block_categories( $categories, $post ) {
		$categories[] = array(
			'slug'  => 'wp-story',
			'title' => esc_html__( 'WP Story', 'wp-story-premium' ),
		);

		return $categories;
	}
}

new Wpstory_Gutenberg_Block();
