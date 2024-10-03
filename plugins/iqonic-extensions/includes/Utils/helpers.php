<?php
function get_iqonic_config()
{
    return $GLOBALS['iqonic_config']['Elements'];
}
// time link
if (!function_exists('iqonic_blog_time_link')) :
    /**
     * Gets a nicely formatted string for the published date.
     */
    function iqonic_blog_time_link($class = '')
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        $time_string = sprintf(
            $time_string,
            get_the_date(DATE_W3C),
            get_the_date(),
            get_the_modified_date(DATE_W3C),
            get_the_modified_date()
        );
        $archive_year = get_the_time('Y');
        $archive_month = get_the_time('m');
        $archive_day = get_the_time('d');
        // Wrap the time string in a link, and preface it with 'Posted on'.
        return sprintf(
            /* translators: %s: post date */
            __('<span class="screen-reader-text">Posted on</span> %s', IQONIC_EXTENSION_TEXT_DOMAIN),
            '<a class="no-drag-ball ' . $class . '" href="' . esc_url(get_day_link($archive_year, $archive_month, $archive_day)) . '" rel="bookmark">' . $time_string . '</a>'
        );
    }
endif;


if (!function_exists('socialv_get_post_format_dynamic')) {
    function socialv_get_post_format_dynamic()
    {
        if (current_theme_supports('post-formats')) {
            $post_format_list = get_transient('blog_post_format_slug');
            if (false === $post_format_list) {

                $get_post_formats_slug = get_post_format_slugs();
                $post_format_list = array();

                foreach ($get_post_formats_slug as $name => $slug) {
                    $post_format_list[$name] = $slug;
                }

                set_transient('blog_post_format_slug', $post_format_list, HOUR_IN_SECONDS);
            }
            return $post_format_list;
        }
    }
}


// get custom post type

function iqonic_get_posts($post_type = 'post', $post_return_key = true, $show_item_badges = false)
{
    static $iqonic_posts, $directory_page_ids;

    // Check if posts are already fetched
    if (!isset($iqonic_posts)) {
        $args = array(
            'post_type'      => $post_type,
            'post_status'    => array('publish', 'private'),
            'posts_per_page' => -1,
        );
        $wp_query = get_posts($args);
        $iqonic_posts = [];

        if ($wp_query) {
            foreach ($wp_query as $post) {
                $badge = $show_item_badges ? ' - ( ' . get_post_type($post->ID) . ' ) ' : '';
                $key   = $post_return_key ? $post->post_name : $post->ID;
                $iqonic_posts[$key] = get_the_title($post->ID) . $badge;
            }
        }
    }

    // Check if directory page IDs are already fetched
    if (!isset($directory_page_ids)) {
        $directory_page_ids = function_exists('bp_core_get_directory_page_ids') ? array_flip(bp_core_get_directory_page_ids()) : '';
    }

    return array(
        'Normal_pages'      => $iqonic_posts,
        'Buddypress_pages'  => $directory_page_ids,
    );
}


//return taxonomies
if (!function_exists('iqonic_get_taxonomies')) {
    function iqonic_get_taxonomies($taxo = '')
    {
        if (empty($taxo)) return;

        $show_count = 0; // 1 for yes, 0 for no
        $pad_counts = 0; // 1 for yes, 0 for no
        $hierarchical = 1; // 1 for yes, 0 for no
        $array = array();
        $args = array(
            'taxonomy' => $taxo,
            'show_count' => $show_count,
            'pad_counts' => $pad_counts,
            'hierarchical' => $hierarchical,
            'hide_empty' => true,
            'parent' => 0
        );
        $wp_object = get_categories($args);

        if (!empty($wp_object)) {
            foreach ($wp_object as $val) {
                $array[$val->slug] = $val->name;
            }
        }

        return $array;
    }
}


//return elementor render icon/svg
if (!function_exists('get_render_icon')) {
    function get_render_icon($icon)
    {
        if ($icon['library'] === 'svg') {
            return !empty($icon['value']['url']) ? file_get_contents($icon['value']['url']) : '';
        }

        return !empty($icon['value']) ? '<i class="' . $icon['value'] . '"></i>' : '';
    }
}

if (!function_exists('layout_get_nav_menus')) {
    function layout_get_nav_menus()
    {

        $menus = wp_get_nav_menus();
        $iqonic_menu_list = [];
        if ($menus) {
            foreach ($menus as $key => $val) {
                $iqonic_menu_list[$val->slug] = $val->name;
            }
            return $iqonic_menu_list;
        }
        wp_reset_postdata();
    }
}

if (!function_exists('iqonic_get_post_types')) {
    function iqonic_get_post_types()
    {
        $options = get_transient('custom_post_types');

        if (false === $options) {
            $excluded_post_types = ['attachment', 'elementor_library', 'page', 'mpp-gallery', 'reply', 'lp_lesson', 'lp_quiz', 'lp_question', 'wp-story', 'wp-story-box', 'wpstory-user', 'wpstory-public'];

            $options = [];

            // Get all public and exportable post types
            $post_types = get_post_types(['public' => true, 'can_export' => true]);

            // Filter out the excluded post types
            $filtered_post_types = array_diff($post_types, $excluded_post_types);

            // Retrieve post type labels
            foreach ($filtered_post_types as $post_type) {
                $post_type_object = get_post_type_object($post_type);
                if ($post_type_object) {
                    $options[$post_type] = $post_type_object->labels->singular_name;
                }
            }
            set_transient('custom_post_types', $options, HOUR_IN_SECONDS);
        }
        return $options;
    }
}

// Story Plugin
add_filter('wpstory_bp_activity_displaying_hook', function ($r) {
    return "";
}, 99999);
add_filter('wpstory_bp_profile_displaying_hook', function ($r) {
    return "socialv_members_content_before";
}, 99999);

// PMP Pricing Plans
if (!function_exists('socialv_pmpro_subscription_plan_list')) {
    function socialv_pmpro_subscription_plan_list()
    {
        $pmp_levels = array();
        if (function_exists('pmpro_sort_levels_by_order')) {
            $pmpro_levels = pmpro_sort_levels_by_order(pmpro_getAllLevels(false, true));

            foreach ($pmpro_levels as $level) {
                $pmp_levels[$level->id] = $level->name;
            }
        }
        return $pmp_levels;
    }
}

if (!function_exists('socialv_pmpro_discount_code_list')) {
    function socialv_pmpro_discount_code_list()
    {
        global $wpdb;
        $discount_code = array();

        $pmp_discount_code = $wpdb->get_results("SELECT code FROM $wpdb->pmpro_discount_codes");
        if (!empty($pmp_discount_code)) {
            foreach ($pmp_discount_code as $discount_codes) {
                $discount_code[$discount_codes->code] = $discount_codes->code;
            }
        }
        return $discount_code;
    }
}

function remove_all_minified_files()
{
    $upload_dir = wp_get_upload_dir()['basedir'];
    $css_dir    = $upload_dir . "/socialv/css/";
    $js_dir     = $upload_dir . "/socialv/js/";

    if (!class_exists('WP_Filesystem_Direct')) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    }
    $rm = new WP_Filesystem_Direct([]);

    if (isset($_GET['post_id']) && !empty($_GET['post_id']) && isset($_GET['mode']) && !empty($_GET['mode'])) {
        $mode    = $_GET['mode'];
        $page_id = $_GET['post_id'];

        update_post_meta($page_id, "iqonic_page_dependent_scripts_" . $page_id, '');

        if ($mode === "current") {
            $css_filename = $css_dir . 'iqonic-post-' . $page_id . '.min.css';
            $js_filename  = $js_dir . 'iqonic-post-' . $page_id . '.min.js';

            if (file_exists($css_filename)) $rm->delete($css_filename, true, 'd');
            if (file_exists($js_filename))  $rm->delete($js_filename, true, 'd');
        } elseif ($mode === "all") {
            if (file_exists($css_dir)) $rm->delete($css_dir, true, 'd');
            if (file_exists($js_dir))  $rm->delete($js_dir, true, 'd');
        }

        $redirect_link = get_the_permalink($page_id);
        wp_redirect($redirect_link);
        exit;
    }
    add_action('elementor/editor/after_save', 'save_data');
    function save_data($post_id)
    {
        $upload_dir = wp_get_upload_dir()['basedir'] . "/socialv";
        $css = $upload_dir . "/css/iqonic-post-$post_id.min.css";
        $js = $upload_dir . "/js/iqonic-post-$post_id.min.js";

        if (file_exists($css))
            unlink($css);
        if (file_exists($js))
            unlink($js);
    }
    
    
}

function socialv_generate_new_activation_key($user_id, $user_email)
{
    global $wpdb;
    
    //Generating activation key
    $activation_key = wp_generate_password(32, false);
    //echo "new Activation key: " . $new_activation_key . "<br>";

    // update the old activation key with new activation key - wp-signups table
    $table_name = $wpdb->prefix . 'signups';
    $data = array(
        'activation_key' => $activation_key,
    );
    $where = array(
        'user_email' => $user_email,
    );
    $format = array('%s');
    $wpdb->update($table_name, $data, $where, $format);
    update_user_meta($user_id,'activation_key',$activation_key);
    return $activation_key;
}
