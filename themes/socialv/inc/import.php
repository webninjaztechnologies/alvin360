<?php

add_filter('pt-ocdi/import_files', 'socialv_import_files');
add_action('pt-ocdi/after_import', 'socialv_after_import_setup');

function socialv_import_files()
{
    return array(
        array(
            'import_file_name'             => esc_html__('All Content', 'socialv'),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit(get_template_directory()) . 'inc/Import/Demo/socialv_redux.json',
                    'option_name' => 'socialv-options',
                ),
            ),
            'local_import_file'            => trailingslashit(get_template_directory()) . 'inc/Import/Demo/socialv-content.xml',
            'local_import_widget_file'     => trailingslashit(get_template_directory()) . 'inc/Import/Demo/socialv-widget.wie',
            'local_import_customizer_file' => trailingslashit(get_template_directory()) . 'inc/Import/Demo/socialv-export.dat',

            'import_preview_image_url'     => get_template_directory_uri() . '/screenshot.png',
            'import_notice' => esc_html__('DEMO IMPORT REQUIREMENTS: Memory Limit of 128 MB and max execution time (php time limit) of 300 seconds. ', 'socialv') . '</br></br>' . esc_html__('Based on your INTERNET SPEED it could take 5 to 25 minutes. ', 'socialv'),
            'preview_url'                  => 'http://wordpress.iqonic.design/product/wp/socialv/',
        ),
    );
}

function socialv_after_import_setup($selected_import)
{
    global $wp_filesystem;
		$content    =   '';
		global $wpdb;
		$table = $wpdb->prefix . 'bp_xprofile_fields';
		// // Buddypress Add Field in profile table
		if (get_option('socialv-import-user_fields') == 'false') {
			$table_name = $wpdb->prefix . 'bp_xprofile_groups';
			$data = array(
				'name'        => 'Social Networks',
				'description' => 'Enter details about your social networks',
				'can_delete'  => 1, // Set to 1 if the group can be deleted
			);
			$social_sql = $wpdb->prepare(
				"INSERT INTO $table_name (name, description, can_delete) VALUES (%s, %s, %d)",
				$data['name'],
				$data['description'],
				$data['can_delete']
			);
			$wpdb->query($social_sql);
			$social_id = $wpdb->insert_id;
			if (!empty($social_id)) {
				$sql =  "INSERT INTO $table (`group_id`, `parent_id`, `type`, `name`, `description`, `is_required`, `is_default_option`, `field_order`, `option_order`, `order_by`, `can_delete`) VALUES
( 3, 0, 'textarea', 'Movies', 'Write your favorite movies name here.', 0, 0, 3, 0, 'custom', 1),
( 1, 0, 'textarea', 'Biography', '', 0, 0, 5, 0, 'custom', 1),
(1, 0, 'textbox', 'Website', '', 0, 0, 4, 0, 'custom', 1),
( 1, 0, 'datebox', 'Birthdate', '', 1, 0, 2, 0, '', 1),
( 1, 25, 'option', 'Male', '', 0, 1, 0, 1, '', 1),
( 1, 25, 'option', 'Female', '', 0, 0, 0, 2, '', 1),
( 1, 0, 'textbox', 'Location', '', 1, 0, 3, 0, '', 1),
( $social_id , 0, 'textbox', 'Facebook', 'Put your Facebook profile link here', 0, 0, 0, 0, '', 1),
( $social_id , 0, 'textbox', 'Twitter', 'Put your Twitter profile link here', 0, 0, 1, 0, '', 1),
( $social_id , 0, 'textbox', 'Dribbble', 'Put your Dribbble profile link here', 0, 0, 2, 0, '', 1),
( $social_id , 0, 'textbox', 'Behance', 'Put your Behance profile link here', 0, 0, 3, 0, '', 1),
( $social_id , 0, 'textbox', 'YouTube', 'Put your YouTube profile link here', 0, 0, 4, 0, '', 1),
( $social_id , 0, 'textbox', 'Instagram', 'Put your Instagram profile link here', 0, 0, 5, 0, '', 1)";

				$query_result = $wpdb->prepare($sql);
				$wpdb->query($query_result);
			}


			// Hobby xprofile Group
			$hoby_data = array(
				'name'        => 'Hobbies And Intrest',
				'description' => 'Enter details about your hobbies',
				'can_delete'  => 1, // Set to 1 if the group can be deleted
			);
			$hoby_sql = $wpdb->prepare(
				"INSERT INTO $table_name (name, description, can_delete) VALUES (%s, %s, %d)",
				$hoby_data['name'],
				$hoby_data['description'],
				$hoby_data['can_delete']
			);
			$wpdb->query($hoby_sql);
			$hoby_id = $wpdb->insert_id;
			$hobi_sql =  "INSERT INTO $table (`group_id`, `parent_id`, `type`, `name`, `description`, `is_required`, `is_default_option`, `field_order`, `option_order`, `order_by`, `can_delete`) VALUES
( $hoby_id , 0, 'textarea', 'Movies', 'Write your favorite movies name here.', 0, 0, 3, 0, 'custom', 1),
( $hoby_id , 0, 'textbox', 'Games', 'Write your others activities.', 0, 0, 4, 0, '', 1),
( $hoby_id , 0, 'textarea', 'My Hobbies', 'Write here your favorite hobbies', 0, 0, 0, 0, '', 1),
( $hoby_id , 0, 'textarea', 'Music Brands', 'Write here your favorite brands name or artists', 0, 0, 1, 0, '', 1),
( $hoby_id , 0, 'textarea', 'Tv Shows', 'Write your favorite Tv Shows name here.', 0, 0, 2, 0, '', 1)";
			$hoby_query_result = $wpdb->prepare($hobi_sql);
			$wpdb->query($hoby_query_result);


			update_option('socialv-import-user_fields', 'true');

		} else if (get_option('socialv-import-user_fields') == 'true') {
			$sql = "UPDATE $table SET `type` = 'textarea' WHERE $table .`id` = 6; ";
		}

		// Assign menus to their locations.
		$locations = get_theme_mod('nav_menu_locations'); // registered menu locations in theme
		$menus = wp_get_nav_menus(); // registered menus
		if ($menus) {
			foreach ($menus as $menu) { // assign menus to theme locations

				if ($menu->name == 'Main Menu') {
					$locations['primary'] = $menu->term_id;
				}
				if ($menu->name == 'Side Setting Menu') {
					$locations['side_menu'] = $menu->term_id;
				}
			}
		}
		set_theme_mod('nav_menu_locations', $locations); // set menus to locations 

		if ('All Content' === $selected_import['import_file_name']) {
			$blog_page_id  = get_page_by_path('blog');
			update_option('show_on_front', 'page');
			update_option('page_for_posts', $blog_page_id->ID);
		}
		require_once(ABSPATH . '/wp-admin/includes/file.php');
		WP_Filesystem();
		//post-types selection for edit with elementor option
		$enable_edit_with_elementor = [
			"post",
			"page",
		];
		update_option('elementor_cpt_support', $enable_edit_with_elementor);

		// Live Chat Setting
		if (class_exists('BP_Better_Messages')) {
			do_action('demo_import_messages_settings');
		}

		// Media Post Setting
		if (class_exists('mediapress')) {
			do_action('demo_import_media_settings');
		}

		// WooCommerce Setting
		$woof_setting_file =  trailingslashit(get_template_directory()) . 'inc/Import/Demo/socialv-woof-setting.json';

		if (file_exists($woof_setting_file)) {
			$content =  $wp_filesystem->get_contents($woof_setting_file);
			if (!empty($content)) {
				$woof_options = json_decode($content, true);
				foreach ($woof_options as $option_name => $option_data) {
					if (is_serialized($option_data)) {
						$option_data = unserialize($option_data);
					}
					update_option($option_name, $option_data);
				}
			}
		}

		update_option('woosq_button_type', 'link');
		update_option('woosq_button_position', '0');

		// remove default post
		wp_delete_post(1, true);

		//buddypress dublicate page delete
		if (class_exists('BuddyPress')) {
			$buddypress_data = get_plugin_data(WP_PLUGIN_DIR . '/buddypress/bp-loader.php');
			$buddypress_version = $buddypress_data['Version'];
			if (version_compare($buddypress_version, '12.0.0', '>=')) {
				// Get the IDs of BuddyPress pages
				$args = array(
					'post_type'      => 'buddypress', // Specify the post type
					'posts_per_page' => -1,            // Retrieve all pages (use -1 for all)
					'fields'         => 'ids',         // Retrieve only the post IDs
				);

				$custom_query = new WP_Query($args);

				if ($custom_query->have_posts()) {
					$page_ids = $custom_query->posts;

					foreach ($page_ids as $page_id) {
						wp_delete_post($page_id, true); // Set the second parameter to true to force deletion
					}

					// Restore original post data
					wp_reset_postdata();
				}
			}
			$front_page_id = bp_core_get_directory_page_ids();
			update_option('page_on_front', $front_page_id['activity']);
		}
}
