<?php
/**
 * Story item metaboxes.
 *
 * @package Wp Story Premium
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'CSF' ) ) {
	return;
}

$prefix = 'wp-story-metabox';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'Stories', 'wp-story-premium' ),
		'post_type' => array( 'wp-story' ),
		'data_type' => 'unserialize',
		'priority'  => 'high',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'           => 'wp_story_items',
				'type'         => 'group',
				'button_title' => esc_html__( '+ Add Story Item', 'wp-story-premium' ),
				'fields'       => array(
					array(
						'id'    => 'text',
						'type'  => 'text',
						'title' => esc_html__( 'Button Title', 'wp-story-premium' ),
					),
					array(
						'id'    => 'link',
						'type'  => 'text',
						'title' => esc_html__( 'Button Link', 'wp-story-premium' ),
					),
					array(
						'id'         => 'new_tab',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Open In New Tab', 'wp-story-premium' ),
						'help'       => esc_html__( 'Open story in a new browser tab, when click story button.', 'wp-story-premium' ),
						'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
						'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
						'text_width' => 75,
					),
					array(
						'id'           => 'image',
						'type'         => 'media',
						'title'        => esc_html__( 'Media', 'wp-story-premium' ),
						'button_title' => esc_html__( 'Add Story Media', 'wp-story-premium' ),
						'remove_title' => esc_html__( 'Remove Story Image', 'wp-story-premium' ),
						'class'        => 'wpstory-story-media-metabox',
					),
					array(
						'id'      => 'duration',
						'type'    => 'spinner',
						'title'   => esc_html__( 'Duration', 'wp-story-premium' ),
						'unit'    => esc_html__( 'Second', 'wp-story-premium' ),
						'default' => 3,
					),
					array(
						'id'         => 'disabled',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Disable Item', 'wp-story-premium' ),
						'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
						'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
						'text_width' => 75,
					),
				),
			),
		),
	)
);

$prefix = 'wp-story-box-metabox';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'Story Box', 'wp-story-premium' ),
		'post_type' => 'wp-story-box',
		'priority'  => 'high',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Stories', 'wp-story-premium' ),
		'icon'   => 'fab fa-instagram',
		'fields' => array(
			array(
				'id'      => 'ids_type',
				'type'    => 'radio',
				'inline'  => true,
				'options' => array(
					'story'  => esc_html__( 'From Stories', 'wp-story-premium' ),
					'post'   => esc_html__( 'From Posts', 'wp-story-premium' ),
					'cat'    => esc_html__( 'From Categories', 'wp-story-premium' ),
					'cpt'    => esc_html__( 'From Ids', 'wp-story-premium' ),
					'ws'     => esc_html__( 'From Web Stories', 'wp-story-premium' ),
					'linked' => esc_html__( 'Linked Stories', 'wp-story-premium' ),
				),
				'default' => 'story',
			),
			array(
				'id'          => 'ids',
				'type'        => 'select',
				'desc'        => esc_html__( 'Drag & drop for changing order.', 'wp-story-premium' ),
				'placeholder' => esc_html__( 'Search story item...', 'wp-story-premium' ),
				'options'     => 'posts',
				'chosen'      => true,
				'multiple'    => true,
				'ajax'        => apply_filters( 'wpstory_story_ids_select_ajax', false ),
				'sortable'    => true,
				'query_args'  => array(
					'posts_per_page' => - 1,
					'post_type'      => 'wp-story',
				),
				'settings'    => array(
					'typing_text'     => esc_html__( 'Please enter %s or more characters...', 'wp-story-premium' ), // phpcs:ignore
					'searching_text'  => esc_html__( 'Searching...', 'wp-story-premium' ),
					'no_results_text' => esc_html__( 'No results found:', 'wp-story-premium' ),
				),
				'dependency'  => array( 'ids_type', '==', 'story' ),
			),
			array(
				'id'         => 'button_title',
				'type'       => 'text',
				'title'      => esc_html__( 'Button Title', 'wp-story-premium' ),
				'default'    => esc_html_x( 'Read Article', 'Default button title.', 'wp-story-premium' ),
				'dependency' => array( 'ids_type', 'any', 'post,cpt,cat' ),
			),
			array(
				'id'         => 'duration',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Duration', 'wp-story-premium' ),
				'unit'       => esc_html__( 'Second', 'wp-story-premium' ),
				'default'    => 3,
				'dependency' => array( 'ids_type', 'any', 'post,cpt,cat' ),
			),
			array(
				'id'         => 'max_post',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Max Post Count', 'wp-story-premium' ),
				'unit'       => esc_html__( 'Post', 'wp-story-premium' ),
				'default'    => 10,
				'dependency' => array( 'ids_type', '==', 'cat' ),
			),
			array(
				'id'         => 'fetch_type',
				'type'       => 'select',
				'title'      => esc_html__( 'Fetch Type', 'wp-story-premium' ),
				'options'    => array(
					'auto'   => esc_html__( 'Auto (From Latest Posts)', 'wp-story-premium' ),
					'manual' => esc_html__( 'Manual', 'wp-story-premium' ),
				),
				'dependency' => array( 'ids_type', '==', 'post' ),
			),
			array(
				'id'         => 'posts_count',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Posts Count', 'wp-story-premium' ),
				'default'    => 10,
				'min'        => - 1,
				'dependency' => array( 'ids_type|fetch_type', '==|==', 'post|auto' ),
			),
			array(
				'id'         => 'categories',
				'type'       => 'select',
				'title'      => esc_html__( 'Categories', 'wp-story-premium' ),
				'options'    => 'categories',
				'multiple'   => true,
				'sortable'   => true,
				'chosen'     => true,
				'ajax'       => apply_filters( 'wpstory_story_categories_select_ajax', false ),
				'dependency' => array( 'ids_type|fetch_type', '==|==', 'post|auto' ),
			),
			array(
				'id'         => 'cat_categories',
				'type'       => 'select',
				'title'      => esc_html__( 'Categories', 'wp-story-premium' ),
				'desc'       => esc_html__( 'Leave blank to show all non-blank categories in name order.', 'wp-story-premium' ),
				'options'    => 'categories',
				'multiple'   => true,
				'sortable'   => true,
				'chosen'     => true,
				'ajax'       => apply_filters( 'wpstory_story_categories_select_ajax', false ),
				'dependency' => array( 'ids_type', '==', 'cat' ),
			),
			array(
				'id'          => 'post_ids',
				'type'        => 'select',
				'desc'        => esc_html__( 'Drag & drop for changing order.', 'wp-story-premium' ),
				'placeholder' => esc_html__( 'Search post...', 'wp-story-premium' ),
				'options'     => 'posts',
				'chosen'      => true,
				'multiple'    => true,
				'ajax'        => apply_filters( 'wpstory_story_blog_post_ids_select_ajax', true ),
				'sortable'    => true,
				'query_args'  => array(
					'posts_per_page' => - 1,
				),
				'settings'    => array(
					'typing_text'     => esc_html__( 'Please enter %s or more characters...', 'wp-story-premium' ), // phpcs:ignore
					'searching_text'  => esc_html__( 'Searching...', 'wp-story-premium' ),
					'no_results_text' => esc_html__( 'No results found:', 'wp-story-premium' ),
				),
				'dependency'  => array( 'ids_type|fetch_type', '==|==', 'post|manual' ),
			),
			array(
				'id'         => 'cpt_ids',
				'type'       => 'text',
				'title'      => esc_html__( 'ID Numbers', 'wp-story-premium' ),
				'subtitle'   => esc_html__( 'For any custom post type.', 'wp-story-premium' ),
				'desc'       => esc_html__( 'Comma separated post IDs. (I.e: 100,101,102', 'wp-story-premium' ),
				'dependency' => array( 'ids_type', '==', 'cpt' ),
			),
			array(
				'id'          => 'ws_ids',
				'type'        => 'select',
				'desc'        => esc_html__( 'Drag & drop for changing order.', 'wp-story-premium' ),
				'placeholder' => esc_html__( 'Search story item...', 'wp-story-premium' ),
				'options'     => 'posts',
				'chosen'      => true,
				'multiple'    => true,
				'ajax'        => apply_filters( 'wpstory_story_ids_select_ajax', false ),
				'sortable'    => true,
				'query_args'  => array(
					'posts_per_page' => - 1,
					'post_type'      => [ 'wpstory-web-story', 'web-story' ],
				),
				'settings'    => array(
					'typing_text'     => esc_html__( 'Please enter %s or more characters...', 'wp-story-premium' ), // phpcs:ignore
					'searching_text'  => esc_html__( 'Searching...', 'wp-story-premium' ),
					'no_results_text' => esc_html__( 'No results found:', 'wp-story-premium' ),
				),
				'dependency'  => array( 'ids_type', '==', 'ws' ),
			),
			array(
				'id'         => 'linked_stories',
				'type'       => 'group',
				'dependency' => array( 'ids_type', '==', 'linked' ),
				'fields'     => array(
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'wp-story-premium' ),
					),
					array(
						'id'    => 'url',
						'type'  => 'text',
						'title' => esc_html__( 'URL', 'wp-story-premium' ),
					),
					array(
						'id'    => 'new_tab',
						'type'  => 'switcher',
						'title' => esc_html__( 'Open in New Tab', 'wp-story-premium' ),
					),
					array(
						'id'    => 'image',
						'type'  => 'media',
						'title' => esc_html__( 'Image', 'wp-story-premium' ),
					),
				)
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'General Options', 'wp-story-premium' ),
		'icon'   => 'fas fa-cogs',
		'fields' => array(
			array(
				'id'      => 'render',
				'type'    => 'select',
				'title'   => esc_html__( 'Render Type', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'client' => esc_html__( 'Client Side', 'wp-story-premium' ),
					'server' => esc_html__( 'Server Side', 'wp-story-premium' ),
				),
			),
			array(
				'id'      => 'style',
				'type'    => 'select',
				'title'   => esc_html__( 'Style', 'wp-story-premium' ),
				'options' => array(
					'global'    => esc_html__( 'Global', 'wp-story-premium' ),
					'instagram' => esc_html__( 'Instagram Style', 'wp-story-premium' ),
					'facebook'  => esc_html__( 'Facebook Style', 'wp-story-premium' ),
				),
			),
			array(
				'id'      => 'full_screen',
				'type'    => 'select',
				'title'   => esc_html__( 'Full Screen', 'wp-story-premium' ),
				'help'    => esc_html__( 'It will be full screen in mobile devices.', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'true'   => esc_html__( 'Enabled', 'wp-story-premium' ),
					'false'  => esc_html__( 'Disabled', 'wp-story-premium' ),
				),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Displaying Options', 'wp-story-premium' ),
		'icon'   => 'far fa-eye',
		'fields' => array(
			array(
				'id'      => 'timer_enable',
				'type'    => 'select',
				'title'   => esc_html__( 'Enable Custom Timer', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'custom' => esc_html__( 'Custom', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'story_timer',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Enable Story Timer', 'wp-story-premium' ),
				'help'       => esc_html__( 'Stories will not be deleted. It will be only hidden.', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
				'dependency' => array( 'timer_enable', '==', 'custom' ),
			),
			array(
				'id'         => 'story_time_value',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Display Stories For X Days', 'wp-story-premium' ),
				'min'        => 1,
				'default'    => 1,
				'unit'       => esc_html__( 'Day(s)', 'wp-story-premium' ),
				'dependency' => array( 'story_timer|timer_enable', '==|==', 'true|custom' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Story Background', 'wp-story-premium' ),
		'icon'   => 'fas fa-palette',
		'fields' => array(
			array(
				'id'      => 'bg_style_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Enable Custom Style', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'custom' => esc_html__( 'Custom', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'story_background_type',
				'type'       => 'select',
				'title'      => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options'    => array(
					'auto'     => esc_html__( 'Auto', 'wp-story-premium' ),
					'normal'   => esc_html__( 'Single Color', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
				'dependency' => array( 'bg_style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'story_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => '#000',
				'dependency' => array( 'story_background_type|bg_style_type', '==|==', 'normal|custom' ),
			),
			array(
				'id'         => 'story_gradient',
				'type'       => 'color_group',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'options'    => array(
					'color-1' => esc_html__( 'Left', 'wp-story-premium' ),
					'color-2' => esc_html__( 'Right', 'wp-story-premium' ),
				),
				'default'    => array(
					'color-1' => '#647dee',
					'color-2' => '#7f53ac',
				),
				'dependency' => array( 'story_background_type|bg_style_type', '==|==', 'gradient|custom' ),
			),
			array(
				'id'      => 'full_size_media',
				'type'    => 'select',
				'title'   => esc_html__( 'Full Size Media', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'true'   => esc_html__( 'Enabled', 'wp-story-premium' ),
					'false'  => esc_html__( 'Disabled', 'wp-story-premium' ),
				),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Button Style', 'wp-story-premium' ),
		'icon'   => 'fas fa-palette',
		'fields' => array(
			array(
				'id'      => 'swipe_button',
				'type'    => 'select',
				'title'   => esc_html__( 'Swipe Up Button', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'true'   => esc_html__( 'Enabled', 'wp-story-premium' ),
					'false'  => esc_html__( 'Disabled', 'wp-story-premium' ),
				),
			),
			array(
				'id'      => 'style_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Enable Custom Style', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'custom' => esc_html__( 'Custom', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'button_background_type',
				'type'       => 'select',
				'title'      => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options'    => array(
					'normal'   => esc_html__( 'Normal', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
				'dependency' => array( 'style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'button_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => 'rgba(0, 0, 0, 0.5)',
				'dependency' => array( 'button_background_type|style_type', '==|==', 'normal|custom' ),
			),
			array(
				'id'         => 'button_gradient',
				'type'       => 'color_group',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'options'    => array(
					'color-1' => esc_html__( 'Left', 'wp-story-premium' ),
					'color-2' => esc_html__( 'Right', 'wp-story-premium' ),
				),
				'default'    => array(
					'color-1' => '#647dee',
					'color-2' => '#7f53ac',
				),
				'dependency' => array( 'button_background_type|style_type', '==|==', 'gradient|custom' ),
			),
			array(
				'id'         => 'text_color',
				'type'       => 'color',
				'title'      => esc_html__( 'Text Color', 'wp-story-premium' ),
				'default'    => '#ffffff',
				'dependency' => array( 'style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'font_size',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Font Size', 'wp-story-premium' ),
				'default'    => '16',
				'unit'       => 'px',
				'min'        => 0,
				'dependency' => array( 'style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'button_padding',
				'type'       => 'dimensions',
				'title'      => esc_html__( 'Padding', 'wp-story-premium' ),
				'units'      => array( 'px' ),
				'default'    => array(
					'width'  => '12',
					'height' => '24',
					'unit'   => 'px',
				),
				'dependency' => array( 'style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'button_radius',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Border Radius', 'wp-story-premium' ),
				'default'    => '24',
				'unit'       => 'px',
				'min'        => 0,
				'dependency' => array( 'style_type', '==', 'custom' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Cycle Style', 'wp-story-premium' ),
		'icon'   => 'far fa-circle',
		'fields' => array(
			array(
				'id'      => 'cycle_position',
				'type'    => 'select',
				'title'   => esc_html__( 'Position', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'auto'   => esc_html__( 'Auto', 'wp-story-premium' ),
					'center' => esc_html__( 'Center', 'wp-story-premium' ),
				),
			),
			array(
				'id'      => 'cycle_style_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Enable Custom Color', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'custom' => esc_html__( 'Custom', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'cycle_background_type',
				'type'       => 'select',
				'title'      => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options'    => array(
					'normal'   => esc_html__( 'Normal', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
				'dependency' => array( 'cycle_style_type', '==', 'custom' ),
			),
			array(
				'id'         => 'cycle_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => '#000',
				'dependency' => array( 'cycle_background_type|cycle_style_type', '==|==', 'normal|custom' ),
			),
			array(
				'id'         => 'cycle_gradient',
				'type'       => 'color_group',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'options'    => array(
					'color-1' => esc_html__( 'Left', 'wp-story-premium' ),
					'color-2' => esc_html__( 'Right', 'wp-story-premium' ),
				),
				'default'    => array(
					'color-1' => '#ee583f',
					'color-2' => '#bd3381',
				),
				'dependency' => array( 'cycle_background_type|cycle_style_type', '==|==', 'gradient|custom' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Title Style', 'wp-story-premium' ),
		'icon'   => 'fas fa-font',
		'fields' => array(
			array(
				'id'      => 'title_color_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Title Color', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'custom' => esc_html__( 'Custom', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'title_color',
				'type'       => 'color',
				'title'      => esc_html__( 'Title Color', 'wp-story-premium' ),
				'default'    => '#000',
				'dependency' => array( 'title_color_type', '==', 'custom' ),
			),
			array(
				'id'      => 'uncropped_titles',
				'type'    => 'select',
				'title'   => esc_html__( 'Uncropped Titles', 'wp-story-premium' ),
				'options' => array(
					'global' => esc_html__( 'Global', 'wp-story-premium' ),
					'true'   => esc_html__( 'Enabled', 'wp-story-premium' ),
					'false'  => esc_html__( 'Disabled', 'wp-story-premium' ),
				),
			),
		),
	)
);

$prefix = 'wp-story-box-side-metabox';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'Shortcode', 'wp-story-premium' ),
		'post_type' => 'wp-story-box',
		'data_type' => 'unserialize',
		'context'   => 'side',
		'priority'  => 'high',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'         => 'wp-story-shortcode',
				'type'       => 'text',
				'value'      => isset( $_GET['post'] ) ? '[wpstory id="' . (int) $_GET['post'] . '"]' : '[wpstory id=""]', // phpcs:ignore
				'attributes' => array(
					'readonly' => true,
				),
				'sanitize'   => '__return_false',
			),
		),
	)
);

$prefix = 'wpstory-user-metabox';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'Story Fields', 'wp-story-premium' ),
		'post_type' => array( 'wpstory-user', 'wpstory-public' ),
		'data_type' => 'unserialize',
		'priority'  => 'high',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'    => 'text',
				'type'  => 'text',
				'title' => esc_html__( 'Button Title', 'wp-story-premium' ),
			),
			array(
				'id'    => 'link',
				'type'  => 'text',
				'title' => esc_html__( 'Button Link', 'wp-story-premium' ),
			),
			array(
				'id'         => 'new_tab',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Open In New Tab', 'wp-story-premium' ),
				'help'       => esc_html__( 'Open story in a new browser tab, when click story button.', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
			),
			array(
				'id'           => 'image',
				'type'         => 'media',
				'title'        => esc_html__( 'Media', 'wp-story-premium' ),
				'button_title' => esc_html__( 'Add Story Media', 'wp-story-premium' ),
				'remove_title' => esc_html__( 'Remove Story Image', 'wp-story-premium' ),
			),
			array(
				'id'      => 'duration',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Duration', 'wp-story-premium' ),
				'unit'    => esc_html__( 'Second', 'wp-story-premium' ),
				'default' => 3,
			),
		),
	),
);

if ( wpstory_premium_helpers()->options( 'posts_story_options', true ) ) {
	$prefix = 'wp-story-blog-posts-metabox';

	CSF::createMetabox(
		$prefix,
		array(
			'title'     => esc_html__( 'WP Story Options', 'wp-story-premium' ),
			'post_type' => 'post',
			'data_type' => 'unserialize',
			'context'   => 'side',
			'theme'     => 'light',
		)
	);

	CSF::createSection(
		$prefix,
		array(
			'fields' => array(
				array(
					'id'    => 'wp-story-cycle-image',
					'type'  => 'media',
					'title' => esc_html__( 'Story Cycle', 'wp-story-premium' ),
					'desc'  => esc_html__( 'Leave blank to use post thumbnail.', 'wp-story-premium' ),
				),
				array(
					'id'    => 'wp-story-image',
					'type'  => 'media',
					'title' => esc_html__( 'Story Image', 'wp-story-premium' ),
					'desc'  => esc_html__( 'Leave blank to use post thumbnail.', 'wp-story-premium' ),
				),
			),
		)
	);
}

$prefix = 'wpstory-category-terms-metabox';

CSF::createTaxonomyOptions(
	$prefix,
	array(
		'taxonomy'  => 'category',
		'data_type' => 'unserialize',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'    => 'wpstory-image',
				'type'  => 'media',
				'title' => esc_html__( 'Story Image', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Leave blank to use last post thumbnail.', 'wp-story-premium' ),
			),
		),
	)
);


$prefix = 'wpstory-web-story-metabox';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'Story Pages', 'wp-story-premium' ),
		'post_type' => 'wpstory-web-story',
		'data_type' => 'unserialize',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'           => 'wp_story_items',
				'type'         => 'group',
				'button_title' => esc_html__( '+ Add Story Item', 'wp-story-premium' ),
				'fields'       => array(
					array(
						'id'    => 'text',
						'type'  => 'text',
						'title' => esc_html__( 'Button Title', 'wp-story-premium' ),
					),
					array(
						'id'    => 'link',
						'type'  => 'text',
						'title' => esc_html__( 'Button Link', 'wp-story-premium' ),
					),
					array(
						'id'      => 'duration',
						'type'    => 'spinner',
						'title'   => esc_html__( 'Duration', 'wp-story-premium' ),
						'unit'    => esc_html__( 'Second', 'wp-story-premium' ),
						'default' => 3,
					),
					array(
						'id'           => 'image',
						'type'         => 'media',
						'title'        => esc_html__( 'Media', 'wp-story-premium' ),
						'button_title' => esc_html__( 'Add Story Media', 'wp-story-premium' ),
						'remove_title' => esc_html__( 'Remove Story Image', 'wp-story-premium' ),
						'class'        => 'wpstory-story-media-metabox',
					),
					array(
						'id'    => 'title',
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'wp-story-premium' ),
					),
				),
			),
		),
	)
);

$prefix = 'wpstory-web-story-metabox-side';

CSF::createMetabox(
	$prefix,
	array(
		'title'     => esc_html__( 'WP Story Options', 'wp-story-premium' ),
		'post_type' => 'wpstory-web-story',
		'data_type' => 'unserialize',
		'context'   => 'side',
		'theme'     => 'light',
	)
);

CSF::createSection(
	$prefix,
	array(
		'fields' => array(
			array(
				'id'    => 'wpstory_publisher_logo',
				'type'  => 'media',
				'title' => esc_html__( 'Publisher Logo', 'wp-story-premium' ),
			),
		),
	)
);
