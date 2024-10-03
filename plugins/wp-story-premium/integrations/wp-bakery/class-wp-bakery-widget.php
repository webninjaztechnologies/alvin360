<?php

/**
 * Class Wpstory_Wpbakery_Widget
 *
 * Wp Bakery Page Builder widget.
 * @sicne 1.2.0
 * @author wpuzman
 */
class Wpstory_Wpbakery_Widget {

	public function __construct() {
		add_action( 'vc_before_init', array( $this, 'create_widget' ) );
	}

	/**
	 * Create widget.
	 *
	 * @throws Exception
	 * @sicne 1.2.0
	 */
	public function create_widget() {
		vc_map( array(
			'name'                    => esc_html__( 'WP Story', 'wp-story-premium' ),
			'base'                    => 'wp-story',
			'class'                   => 'wp-story-wpb-backend',
			'icon'                    => plugin_dir_url( dirname( __DIR__ ) ) . 'admin/img/instagram.svg',
			'show_settings_on_create' => true,
			'category'                => 'Content',
			'params'                  => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'admin_label' => true,
					'heading'     => esc_html__( 'Story Box', 'wp-story-premium' ),
					'param_name'  => 'id',
					'value'       => array_flip( wpstory_premium_helpers()->get_story_boxes() ),
				),
			)
		) );
	}
}

new Wpstory_Wpbakery_Widget();
