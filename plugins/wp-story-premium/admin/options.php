<?php
/**
 * Register theme options.
 *
 * @package WP Story Premium
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$prefix = 'wp-story-premium';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'         => esc_html__( 'Options', 'wp-story-premium' ),
		'menu_slug'          => 'wp-story-premium-options',
		'menu_type'          => 'submenu',
		'menu_parent'        => 'edit.php?post_type=wp-story',
		'framework_title'    => esc_html__( 'WP Story Options', 'wp-story-premium' ),
		'footer_text'        => sprintf('by %s', '<a href="https://codecanyon.net/user/wpuzman/" target="_blank">wpuzman</a>' ),
		'theme'              => 'light',
		'footer_credit'      => sprintf( '<a href="mailto:%1$s">%1$s</a>', 'wpuzmann@gmail.com' ),
		'show_in_customizer' => false,
		'show_bar_menu'      => false,
		'show_all_options'   => false,
		'database'           => 'option',
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Welcome', 'wp-story-premium' ),
		'icon'   => 'fas fa-door-open',
		'fields' => array(
			array(
				'type'    => 'heading',
				'content' => esc_html__( 'Welcome to WP Story!', 'wp-story-premium' ),
			),
			array(
				'type'    => 'content',
				'content' => esc_html__( 'You can customize your plugin settings in this panel.', 'wp-story-premium' ),
			),
			array(
				'type'    => 'content',
				'content' => esc_html__( 'Read documentation here:', 'wp-story-premium' ) . ' ' . '<a href="https://docs.wpstory.me/" target="_blank">https://docs.wpstory.me/</a>',
			),
			array(
				'type'    => 'content',
				'content' => esc_html__( 'Still no luck? Create a support ticket here:', 'wp-story-premium' ) . ' ' . '<a href="https://support.wpuzman.com/" target="_blank">https://support.wpuzman.com/</a>',
			),
		)
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'General', 'wp-story-premium' ),
		'icon'   => 'fas fa-cogs',
		'fields' => array(
			array(
				'id'      => 'render',
				'type'    => 'select',
				'title'   => esc_html__( 'Render Type', 'wp-story-premium' ),
				'options' => array(
					'client' => esc_html__( 'Client Side', 'wp-story-premium' ),
					'server' => esc_html__( 'Server Side', 'wp-story-premium' ),
				),
				'default' => 'client',
			),
			array(
				'id'      => 'style',
				'type'    => 'select',
				'title'   => esc_html__( 'Style', 'wp-story-premium' ),
				'options' => array(
					'instagram' => esc_html__( 'Instagram Style', 'wp-story-premium' ),
					'facebook'  => esc_html__( 'Facebook Style', 'wp-story-premium' ),
				),
				'default' => 'snapgram',
			),
			array(
				'id'    => 'story_reports',
				'type'  => 'switcher',
				'title' => esc_html__( 'Story Reporting System', 'wp-story-premium' ),
			),
			array(
				'id'    => 'full_screen',
				'type'  => 'switcher',
				'title' => esc_html__( 'Full Screen', 'wp-story-premium' ),
				'desc'  => esc_html__( 'It will be available for only touch devices and some browsers.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'video_silent',
				'type'  => 'switcher',
				'title' => esc_html__( 'Start Videos Silent', 'wp-story-premium' ),
			),
			array(
				'id'    => 'routing',
				'type'  => 'switcher',
				'title' => esc_html__( 'Browser Routing', 'wp-story-premium' ),
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
				'id'         => 'story_timer',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Enable Story Timer', 'wp-story-premium' ),
				'help'       => esc_html__( 'Stories will not be deleted. It will be only hidden.', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
			),
			array(
				'id'         => 'single_stories_timer',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Enable Story Timer For Single Stories', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
				'default'    => true,
				'dependency' => array( 'story_timer', '==', 'true' ),
			),
			array(
				'id'         => 'public_stories_timer',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Enable Story Timer For Public Stories', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
				'default'    => true,
				'dependency' => array( 'story_timer', '==', 'true' ),
			),
			array(
				'id'         => 'story_time_value',
				'type'       => 'spinner',
				'title'      => esc_html__( 'Display Stories For X Days', 'wp-story-premium' ),
				'min'        => 1,
				'default'    => 1,
				'unit'       => esc_html__( 'Day(s)', 'wp-story-premium' ),
				'dependency' => array( 'story_timer', '==', 'true' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'User Publishing Options', 'wp-story-premium' ),
		'icon'   => 'far fa-paper-plane',
		'id'     => 'user-publishing-options',
		'fields' => array(),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'User Publishing Options', 'wp-story-premium' ),
		'icon'   => 'far fa-paper-plane',
		'parent' => 'user-publishing-options',
		'fields' => array(
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'This options for only user publishing form!', 'wp-story-premium' ),
			),
			array(
				'id'      => 'default_story_duration',
				'type'    => 'text',
				'title'   => esc_html__( 'Default Story Duration', 'wp-story-premium' ),
				'default' => 3,
			),
			array(
				'id'       => 'allowed_image_types',
				'type'     => 'select',
				'title'    => esc_html__( 'Allowed Image Types', 'wp-story-premium' ),
				'desc'     => esc_html__( 'Leave empty to allow all types.', 'wp-story-premium' ),
				'chosen'   => true,
				'multiple' => true,
				'options'  => WPSTORY()->get_default_allowed_image_types(),
			),
			array(
				'id'       => 'allowed_video_types',
				'type'     => 'select',
				'title'    => esc_html__( 'Allowed Video Types', 'wp-story-premium' ),
				'desc'     => esc_html__( 'Leave empty to allow all types.', 'wp-story-premium' ),
				'chosen'   => true,
				'multiple' => true,
				'options'  => WPSTORY()->get_default_allowed_video_types(),
			),
			array(
				'id'      => 'user_publish_status',
				'type'    => 'select',
				'title'   => esc_html__( 'Story Published Status', 'wp-story-premium' ),
				'options' => 'get_post_statuses',
			),
			array(
				'id'      => 'user_deleting_status',
				'type'    => 'select',
				'title'   => esc_html__( 'Story Deleting Status', 'wp-story-premium' ),
				'options' => array(
					'draft'  => esc_html__( 'Make story draft', 'wp-story-premium' ),
					'trash'  => esc_html__( 'Move story to trash', 'wp-story-premium' ),
					'delete' => esc_html__( 'Delete story permanently', 'wp-story-premium' ),
				),
				'default' => 'draft',
			),
			array(
				'id'      => 'user_single_story_limit',
				'type'    => 'spinner',
				'title'   => esc_html__( 'User Single Story Limit', 'wp-story-premium' ),
				'desc'    => esc_html__( 'Set 0 to unlimited.', 'wp-story-premium' ),
				'default' => 10,
				'unit'    => 'stories',
				'min'     => 0,
			),
			array(
				'id'      => 'user_public_story_limit',
				'type'    => 'spinner',
				'title'   => esc_html__( 'User Public Story Limit', 'wp-story-premium' ),
				'desc'    => esc_html__( 'Set 0 to unlimited.', 'wp-story-premium' ),
				'default' => 10,
				'unit'    => 'stories',
				'min'     => 0,
			),
			array(
				'id'      => 'user_public_story_item_limit',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Story Item Limit', 'wp-story-premium' ),
				'desc'    => esc_html__( 'Limit for per public story. Set 0 to unlimited.', 'wp-story-premium' ),
				'default' => 10,
				'unit'    => 'items',
				'min'     => 0,
			),
			array(
				'id'      => 'max_file_size',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Max Uploading File Size', 'wp-story-premium' ),
				'desc'    => esc_html__( 'Set 0 to unlimited.', 'wp-story-premium' ),
				'default' => 10,
				'unit'    => 'mb',
				'min'     => 0,
			),
			array(
				'id'         => 'allow_link',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Allow Story Link', 'wp-story-premium' ),
				'desc'       => esc_html__( 'Users can add link button when creating a story.', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
				'default'    => true,
			),
			array(
				'id'         => 'story_insights',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Story Insights', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Image Compression', 'wp-story-premium' ),
		'icon'   => 'fas fa-compress-alt',
		'parent' => 'user-publishing-options',
		'fields' => array(
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'Image compression works only in the user publishing form!', 'wp-story-premium' ),
			),
			array(
				'id'         => 'image_compression',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Enable Image Compression Before Upload', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
			),
			array(
				'id'      => 'image_compression_level',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Compression Quality', 'wp-story-premium' ),
				'default' => 0.6,
				'min'     => 0.1,
				'max'     => 1,
				'step'    => 0.1,
			),
			array(
				'id'      => 'image_max_width',
				'type'    => 'number',
				'title'   => esc_html__( 'Image Max Width', 'wp-story-premium' ),
				'default' => 1080,
			),
			array(
				'id'      => 'image_max_height',
				'type'    => 'number',
				'title'   => esc_html__( 'Image Max Height', 'wp-story-premium' ),
				'default' => 1920,
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Styling Options', 'wp-story-premium' ),
		'icon'   => 'fas fa-palette',
		'id'     => 'styling-options',
		'fields' => array(),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Story Background', 'wp-story-premium' ),
		'icon'   => 'far fa-window-restore',
		'parent' => 'styling-options',
		'fields' => array(
			array(
				'id'      => 'story_background_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options' => array(
					'auto'     => esc_html__( 'Auto', 'wp-story-premium' ),
					'normal'   => esc_html__( 'Single Color', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'story_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => '#1a1a1a',
				'dependency' => array( 'story_background_type', '==', 'normal' ),
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
				'dependency' => array( 'story_background_type', '==', 'gradient' ),
			),
			array(
				'id'    => 'full_size_media',
				'type'  => 'switcher',
				'title' => esc_html__( 'Full Size Media', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Button Style', 'wp-story-premium' ),
		'icon'   => 'fas fa-link',
		'parent' => 'styling-options',
		'fields' => array(
			array(
				'id'      => 'swipe_button',
				'type'    => 'switcher',
				'title'   => esc_html__( 'Enable Swipe Up Button', 'wp-story-premium' ),
				'desc'    => esc_html__( 'It will be available for only touch devices.', 'wp-story-premium' ),
				'default' => false,
			),
			array(
				'id'      => 'button_background_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options' => array(
					'normal'   => esc_html__( 'Normal', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
			),
			array(
				'id'         => 'button_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => 'rgba(0, 0, 0, 1)',
				'dependency' => array( 'button_background_type', '==', 'normal' ),
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
				'dependency' => array( 'button_background_type', '==', 'gradient' ),
			),
			array(
				'id'      => 'text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text Color', 'wp-story-premium' ),
				'default' => '#ffffff',
			),
			array(
				'id'      => 'font_size',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size', 'wp-story-premium' ),
				'default' => '16',
				'unit'    => 'px',
				'min'     => 0,
			),
			array(
				'id'      => 'button_padding',
				'type'    => 'dimensions',
				'title'   => esc_html__( 'Padding', 'wp-story-premium' ),
				'units'   => array( 'px' ),
				'default' => array(
					'width'  => '12',
					'height' => '24',
					'unit'   => 'px',
				),
			),
			array(
				'id'      => 'button_radius',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Border Radius', 'wp-story-premium' ),
				'default' => '5',
				'unit'    => 'px',
				'min'     => 0,
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Cycle Style', 'wp-story-premium' ),
		'icon'   => 'far fa-circle',
		'parent' => 'styling-options',
		'fields' => array(
			array(
				'id'      => 'cycle_position',
				'type'    => 'select',
				'title'   => esc_html__( 'Position', 'wp-story-premium' ),
				'options' => array(
					'auto'   => esc_html__( 'Auto', 'wp-story-premium' ),
					'center' => esc_html__( 'Center', 'wp-story-premium' ),
				),
				'default' => 'auto',
			),
			array(
				'id'      => 'cycle_background_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Background Color Type', 'wp-story-premium' ),
				'options' => array(
					'normal'   => esc_html__( 'Normal', 'wp-story-premium' ),
					'gradient' => esc_html__( 'Gradient', 'wp-story-premium' ),
				),
				'default' => 'gradient',
			),
			array(
				'id'         => 'cycle_bg',
				'type'       => 'color',
				'title'      => esc_html__( 'Background Color', 'wp-story-premium' ),
				'default'    => '',
				'dependency' => array( 'cycle_background_type', '==', 'normal' ),
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
				'dependency' => array( 'cycle_background_type', '==', 'gradient' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Title Style', 'wp-story-premium' ),
		'icon'   => 'fas fa-font',
		'parent' => 'styling-options',
		'fields' => array(
			array(
				'id'      => 'title_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Title Color', 'wp-story-premium' ),
				'default' => '#000',
			),
			array(
				'id'    => 'uncropped_titles',
				'type'  => 'switcher',
				'title' => esc_html__( 'Uncropped Titles', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title' => esc_html__( 'Integrations', 'wp-story-premium' ),
		'icon'  => 'fas fa-plug',
		'id'    => 'integrations',
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'BuddyPress', 'wp-story-premium' ),
		'icon'   => 'fas fa-users',
		'parent' => 'integrations',
		'fields' => array(
			array(
				'type'    => 'submessage',
				'style'   => 'warning',
				'content' => esc_html__( '"Single Stories" is requiring to enable BuddyPress "Friend Connections" feature in BuddyPress settings.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_integration',
				'type'  => 'switcher',
				'title' => esc_html__( 'BuddyPress Integration', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_single_stories',
				'type'  => 'switcher',
				'title' => esc_html__( 'Single Stories', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Create stories for only subscribers. (Like Instagram)', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_public_stories',
				'type'  => 'switcher',
				'title' => esc_html__( 'Public Stories', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Create stories for public profile.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_users_activities',
				'type'  => 'switcher',
				'title' => esc_html__( 'Users\' Activities', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Display users\' latest stories on activity wall.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_activities_form',
				'type'  => 'switcher',
				'title' => esc_html__( 'Users\' Activities Publishing Form', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Display story publishing form on users\' activity wall.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'buddypress_activities_login_url',
				'type'  => 'text',
				'title' => esc_html__( 'Users\' Activities Form Login URL', 'wp-story-premium' ),
				'desc'  => esc_html__( 'If users not logged in, when click the "Add Story" button, redirect your own login page. Default is wp-login.php.', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'bbPress', 'wp-story-premium' ),
		'icon'   => 'fas fa-comments',
		'parent' => 'integrations',
		'fields' => array(
			array(
				'id'    => 'bbpress_integration',
				'type'  => 'switcher',
				'title' => esc_html__( 'bbPress Integration', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'PeepSo', 'wp-story-premium' ),
		'icon'   => 'fas fa-users',
		'parent' => 'integrations',
		'fields' => array(
			array(
				'type'    => 'submessage',
				'style'   => 'warning',
				'content' => esc_html__( '"Single Stories" is requiring "PeepSo Core: Friends" plugin.', 'wp-story-premium' ),
			),
			array(
				'id'    => 'peepso_integration',
				'type'  => 'switcher',
				'title' => esc_html__( 'PeepSo Integration', 'wp-story-premium' ),
			),
			array(
				'id'      => 'peepso_placement',
				'type'    => 'select',
				'title'   => esc_html__( 'Placement', 'wp-story-premium' ),
				'options' => array(
					'auto'   => esc_html__( 'Auto', 'wp-story-premium' ),
					'manual' => esc_html__( 'Manual', 'wp-story-premium' ),
				),
			),
			array(
				'id'    => 'peepso_single_stories',
				'type'  => 'switcher',
				'title' => esc_html__( 'Single Stories', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Create stories for only subscribers. (Like Instagram)', 'wp-story-premium' ),
			),
			array(
				'id'    => 'peepso_public_stories',
				'type'  => 'switcher',
				'title' => esc_html__( 'Public Stories', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Create stories for public profile.', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Web Stories', 'wp-story-premium' ),
		'icon'   => 'fas fa-mobile-alt',
		'parent' => 'integrations',
		'fields' => array(
			array(
				'id'    => 'enable_web_stories',
				'type'  => 'switcher',
				'title' => esc_html__( 'Enable Web Stories', 'wp-story-premium' ),
			),
		),
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Advanced', 'wp-story-premium' ),
		'icon'   => 'fas fa-cog',
		'fields' => array(
			array(
				'id'         => 'clean_on_delete',
				'type'       => 'switcher',
				'title'      => esc_html__( 'Delete Plugin Options When Plugin Uninstall', 'wp-story-premium' ),
				'text_on'    => esc_html__( 'YES', 'wp-story-premium' ),
				'text_off'   => esc_html__( 'NO', 'wp-story-premium' ),
				'text_width' => 75,
			),
			array(
				'id'      => 'posts_story_options',
				'type'    => 'switcher',
				'title'   => esc_html__( 'Story Options For Posts', 'wp-story-premium' ),
				'desc'    => esc_html__( 'Display story options for posts to set custom story images.', 'wp-story-premium' ),
				'default' => true,
			),
			array(
				'id'    => 'opener',
				'type'  => 'text',
				'title' => esc_html__( 'Opener Selector', 'wp-story-premium' ),
				'desc'  => esc_html__( 'Css selector for opening stories on click.', 'wp-story-premium' ),
			),
		),
	)
);

ob_start();
require_once WPSTORY_PATH . 'admin/partials/wpstory-changelog.php';
$changelog = ob_get_clean();

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'Changelog', 'wp-story-premium' ),
		'icon'   => 'far fa-calendar-alt',
		'fields' => array(
			array(
				'id'      => 'changelog',
				'type'    => 'content',
				'content' => $changelog,
			),
		),
	)
);
