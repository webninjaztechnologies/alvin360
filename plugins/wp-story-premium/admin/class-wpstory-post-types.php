<?php
/**
 * Create custom post types.
 *
 * @package WP Story Premium
 */

/**
 * Class Wpstory_Post_Types
 *
 * @sicne 1.2.0
 * @author wpuzman
 */
class Wpstory_Post_Types {
	/**
	 * Wpstory_Post_Types constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'create_story_post_type' ) );
		add_action( 'init', array( $this, 'create_story_box_post_type' ) );
		add_action( 'init', array( $this, 'create_user_stories_post_type' ) );
		add_action( 'init', array( $this, 'create_user_public_stories_post_type' ) );
		add_action( 'admin_footer-post-new.php', array( $this, 'print_scripts' ) );
		add_action( 'admin_footer-post.php', array( $this, 'print_scripts' ) );

		if ( '1' === WPSTORY()->opt( 'story_reports', '1' ) ) {
			add_action( 'init', array( $this, 'create_report_post_type' ) );
		}

		if ( '1' === WPSTORY()->opt( 'enable_web_stories' ) ) {
			add_action( 'init', array( $this, 'create_web_stories_post_type' ) );
		}
	}

	/**
	 * Create story post type.
	 * post_type = wp-story
	 *
	 * @since 1.0.0
	 */
	public function create_story_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'Stories', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'Story', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'Stories', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'Story', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'Story Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'Story Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent Story:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All Stories', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New Story', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New Story', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New Story', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit Story', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update Story', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View Story', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View Stories', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search Story', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to story', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this story', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'Stories list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'Stories list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter stories list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'Story', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/menu-icon.png',
			'supports'            => array( 'title', 'thumbnail', 'author' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'wp-story-api',
		);

		register_post_type( 'wp-story', $args );
	}

	/**
	 * Create story box post type.
	 * post_type = wp-story-box
	 *
	 * @since 1.0.0
	 */
	public function create_story_box_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'Story Boxes', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'Story Box', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'Story Boxes', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'Story Box', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'Story Box Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'Story Box Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent Story Box:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All Stories Boxes', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New Story Box', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New Story Box', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit Story Box', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update Story Box', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View Story Box', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View Stories Boxes', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search Story Box', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to story box', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this story box', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'Story boxes list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'Story boxes list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter story boxes list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'Story Boxes', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'wp-story-box-api',
		);

		register_post_type( 'wp-story-box', $args );
	}

	/**
	 * Create user stories post type.
	 * post_type = wpstory-user
	 *
	 * @since 2.0.0
	 */
	public function create_user_stories_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'User Stories', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'User Story', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'User Stories', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'User Story', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'User Story Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'User Story Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent User Story:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All User Stories', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New User Story', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New User Story', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit User Story', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update User Story', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View User Story', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View User Stories', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search User Story', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to user story', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this user story', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'User story list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'User tories list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter story stories list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'User Stories', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => false,
			),
			'map_meta_cap'        => true,
			'show_in_rest'        => true,
			'rest_base'           => 'wpstory-user-api',
		);

		register_post_type( 'wpstory-user', $args );
	}

	/**
	 * Create user public stories post type.
	 * post_type = wpstory-public
	 *
	 * @since 2.0.0
	 */
	public function create_user_public_stories_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'User Stories (Public)', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'User Story', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'User Stories', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'User Story', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'User Story Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'User Story Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent User Story:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All User Stories', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New User Public Story', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New User Story', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit User Story', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update User Story', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View User Story', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View User Stories', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search User Story', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to user story', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this user story', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'User story list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'User tories list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter story stories list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'User Stories (Public)', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title', 'thumbnail' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => false,
			),
			'map_meta_cap'        => true,
			'show_in_rest'        => true,
			'rest_base'           => 'wpstory-public-api',
		);

		register_post_type( 'wpstory-public', $args );
	}

	/**
	 * Report system posts.
	 *
	 * @since 3.0.0
	 */
	public function create_report_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'User Reports', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'User Report', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'User Reports', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'User Report', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'User Report Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'User Report Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent User Report:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All User Reports', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New User Report', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New User Report', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit User Report', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update User Report', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View User Report', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View User Reports', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search User Report', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to user story', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this user report', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'User story list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'User tories list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter story stories list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'User Reports', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title', 'author', 'editor' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => false,
			),
			'map_meta_cap'        => true,
			'show_in_rest'        => false,
		);

		register_post_type( 'wpstory-report', $args );
	}

	/**
	 * Report web stories posts.
	 * post_type = wpstory-web-story
	 *
	 * @since 3.5.0
	 */
	public function create_web_stories_post_type() {
		$labels = array(
			'name'                  => esc_html_x( 'Web Stories', 'Post Type General Name', 'wp-story-premium' ),
			'singular_name'         => esc_html_x( 'Web Story', 'Post Type Singular Name', 'wp-story-premium' ),
			'menu_name'             => esc_html_x( 'Web Stories', 'Admin Menu text', 'wp-story-premium' ),
			'name_admin_bar'        => esc_html_x( 'Web Story', 'Add New on Toolbar', 'wp-story-premium' ),
			'archives'              => esc_html__( 'Web Story Archives', 'wp-story-premium' ),
			'attributes'            => esc_html__( 'Web Story Attributes', 'wp-story-premium' ),
			'parent_item_colon'     => esc_html__( 'Parent Web Story:', 'wp-story-premium' ),
			'all_items'             => esc_html__( 'All Web Stories', 'wp-story-premium' ),
			'add_new_item'          => esc_html__( 'Add New Web Story', 'wp-story-premium' ),
			'add_new'               => esc_html__( 'Add New', 'wp-story-premium' ),
			'new_item'              => esc_html__( 'New Web Story', 'wp-story-premium' ),
			'edit_item'             => esc_html__( 'Edit Web Story', 'wp-story-premium' ),
			'update_item'           => esc_html__( 'Update Web Story', 'wp-story-premium' ),
			'view_item'             => esc_html__( 'View Web Story', 'wp-story-premium' ),
			'view_items'            => esc_html__( 'View Web Stories', 'wp-story-premium' ),
			'search_items'          => esc_html__( 'Search Web Story', 'wp-story-premium' ),
			'not_found'             => esc_html__( 'Not found', 'wp-story-premium' ),
			'not_found_in_trash'    => esc_html__( 'Not found in trash', 'wp-story-premium' ),
			'featured_image'        => esc_html__( 'Featured Image', 'wp-story-premium' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'wp-story-premium' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-story-premium' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-story-premium' ),
			'insert_into_item'      => esc_html__( 'Add to user story', 'wp-story-premium' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this user report', 'wp-story-premium' ),
			'items_list'            => esc_html__( 'User story list', 'wp-story-premium' ),
			'items_list_navigation' => esc_html__( 'User tories list navigation', 'wp-story-premium' ),
			'filter_items_list'     => esc_html__( 'Filter story stories list', 'wp-story-premium' ),
		);

		$args = array(
			'label'               => esc_html__( 'Web Stories', 'wp-story-premium' ),
			'description'         => '',
			'labels'              => $labels,
			'menu_icon'           => '',
			'supports'            => array( 'title', 'author', 'thumbnail' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'show_in_rest'        => false,
		);

		register_post_type( 'wpstory-web-story', $args );
	}

	/**
	 * Print custom scripts.
	 */
	public function print_scripts() {
		$screen = get_current_screen();

		if ( ! isset( $screen->id ) ) {
			return;
		}

		if ( in_array( $screen->id, array( 'wpstory-user', 'wpstory-public', 'wpstory-report', 'wp-story-box', 'wpstory-web-story' ) ) ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#menu-posts-wp-story').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
					$('#menu-posts-wp-story > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		if ( 'wp-story-box' === $screen->id ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('a[href$="edit.php?post_type=wp-story-box"]').parent().addClass('current');
					$('a[href$="edit.php?post_type=wp-story-box"]').addClass('current');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		if ( 'wpstory-user' === $screen->id ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('a[href$="edit.php?post_type=wpstory-user"]').parent().addClass('current');
					$('a[href$="edit.php?post_type=wpstory-user"]').addClass('current');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		if ( 'wpstory-public' === $screen->id ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('a[href$="edit.php?post_type=wpstory-public"]').parent().addClass('current');
					$('a[href$="edit.php?post_type=wpstory-public"]').addClass('current');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		if ( 'wpstory-report' === $screen->id ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('a[href$="edit.php?post_type=wpstory-report"]').parent().addClass('current');
					$('a[href$="edit.php?post_type=wpstory-report"]').addClass('current');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		if ( 'wpstory-web-story' === $screen->id ) {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('a[href$="edit.php?post_type=wpstory-web-story"]').parent().addClass('current');
					$('a[href$="edit.php?post_type=wpstory-web-story"]').addClass('current');
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}

new Wpstory_Post_Types();
