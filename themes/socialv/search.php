<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package socialv
 */

namespace SocialV\Utility;

use WP_Query;

get_header();
$socialv_options = get_option('socialv-options');
$post_section = socialv()->post_style();
$ajax_instance = new \SocialV\Utility\Custom_Helper\Helpers\Common();

$container_class = apply_filters('content_container_class', 'container');
$row_reverse_class = esc_attr($post_section['row_reverse']);

echo '<div class="site-content-contain"><div id="content" class="site-content"><div id="primary" class="content-area"><main id="main" class="site-main"><div id="buddypress"><div class="' . $container_class . '"><div class="row ' . $row_reverse_class . '">';
socialv()->socialv_the_layout_class();
$flag = 1;
$count_data = 10000000;
if (isset($socialv_options['display_search_pagination']) && ($socialv_options['display_search_pagination'] == 'yes')) {
    $count_data = isset($socialv_options['searchpage_pagination_limit']) ? $socialv_options['searchpage_pagination_limit'] : '5';
}
if (isset($_GET['tab']) && $_GET['tab'] === 'all') {
    $count_data = isset($socialv_options['header_search_limit']) ? $socialv_options['header_search_limit'] : '5';
}

if (isset($_GET['s']) && strlen($_GET['s']) > 3 && isset($_GET['ajax_search'])) {
    $variable[] = null;
    $content = $pagination = $banner = $all = [];
    $all['activity'] = $all['members'] = $all['group'] = $all['post'] = $all['page'] = $all['product'] = $all['course'] = $all['forum'] = $all['topic'] = $all['reply'] = '';
    $banner['activity'] = $banner['members'] = $banner['group'] = $banner['post'] = $banner['page'] = $banner['product'] = $banner['course'] = $banner['forum'] = $banner['topic'] = $banner['reply'] = '';
    $search = $_GET['s'];
    $search_txt = str_replace(' ', '+', $search);

    $search_content_list = isset($socialv_options['socialv_search_content_list']) ? $socialv_options['socialv_search_content_list'] : [];
    foreach ($search_content_list as $key => $value) {
        if ($value == 1) {
            $list_search_item[] = $key;
        }
    }

    //Memebrs Search
    if (in_array('member', $list_search_item)) {
        $mem_arg = array(
            'type' => 'active',
            'post_type' => 'member',
            'search_terms' => $search,
            'search_columns' => array('name'),
            'per_page' => $count_data,
            'paged' => (($_GET['tab'] === 'members') && (!empty(get_query_var('upage')))) ? get_query_var('upage') : 1,
            'count_total' => true,
        );

        if (bp_has_members($mem_arg)) :
            $flag = 2;
            $content['members'] = '';
            while (bp_members()) :
                bp_the_member();
                $members_user_id = bp_get_member_user_id();
                $content['members'] .= '<li><div class="socialv-author-heading">
                                        <div class="item-avatar">
                                            <a href="' . bp_get_member_permalink() . '">' . bp_get_member_avatar('type=full&width=70&height=70') . '</a>
                                        </div>
                                        <div class="item">
                                            <h5 class="item-title fn">
                                                <a href="' . bp_get_member_permalink() . '">' . bp_get_member_name() . '</a>'
                    . socialv()->socialv_get_verified_badge($members_user_id) . '
                                            </h5>
                                            <div class="item-meta mt-2">' . bp_get_member_last_active() . '</div>
                                        </div>
                                    </div>
                                    </li>';
            endwhile;
            if (!empty($content['members'])) {
                $banner['members'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Member', 'socialv') . '</h4>';
                $variable['members'] = '<a href="?s=' . $search_txt . '&tab=members&ajax_search=1" id="pills-members-tab" class="nav-link" type="button" role="tab" aria-controls="pills-members" aria-selected="true">' . esc_html__('Member', 'socialv') . '</a> ';
                $all['members'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=members&ajax_search=1">' . esc_html__('View All Members', 'socialv') . '</a></div>';
            }
            if ($_GET['tab'] === 'members') {
                if (function_exists('bp_get_members_pagination_links')) {
                    $pagination['members'] = bp_get_members_pagination_links();
                }
            }
            wp_reset_postdata();

        endif;
    }

    //Group Search
    if (in_array('group', $list_search_item)) {
        $grup_arg = array(
            'post_type' => 'group',
            'search_terms' => $search,
            'type' => "alphabetical",
            'search_columns' => array('name'),
            'paged' => (($_GET['tab'] === 'group') && (!empty(get_query_var('grpage')))) ? get_query_var('grpage') : 1,
            'per_page' => $count_data,
            'count_total' => true,
        );

        if (bp_has_groups($grup_arg)) :
            $flag = 2;
            $banner['group'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Group', 'socialv') . '</h4>';
            $variable['group'] = '<a href="?s=' . $search_txt . '&tab=group&ajax_search=1" id="pills-group-tab" class="nav-link" type="button" role="tab" aria-controls="pills-group" aria-selected="true">' . esc_html__('Group', 'socialv') . '</a> ';
            $all['group'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=group&ajax_search=1">' . esc_html__('View All Groups', 'socialv') . '</a></div>';
            $content['group'] = '';
            while (bp_groups()) :
                bp_the_group();
                $content['group'] .= '<li>
                    <div class="socialv-author-heading">
                        <div class="item-avatar">
                            <a href="' . bp_get_group_url() . '">' . bp_core_fetch_avatar(array('item_id' => bp_get_group_id(), 'avatar_dir' => 'group-avatars', 'object' => 'group', 'width' => 50, 'height' => 50, 'class' => 'rounded-circle')) . '</a>
                        </div>
                        <div class="item">
                            <h5 class="item-title fn">' . bp_get_group_link() . '</h5>
                            <div class="item-meta mt-2">' . bp_get_group_type() . '</div>
                        </div>
                    </div>
                    </li>';
            endwhile;
            if ($_GET['tab'] === 'group') {
                if (function_exists('bp_groups_pagination_links')) {
                    $pagination['group'] = bp_get_groups_pagination_links();
                }
            }
            wp_reset_postdata();

        endif;
    }

    //Activity
    if (in_array('activity', $list_search_item)) {
        $bp_new_blog_post_page_ids = array(); // Initialize an empty array.

        if (bp_has_activities()) :
            while (bp_activities()) :
                bp_the_activity();
                if (bp_get_activity_type() === 'new_blog_post') {
                    $bp_new_blog_post_page_ids[] = bp_get_activity_id();
                }
            endwhile;
            wp_reset_postdata();
        endif;

        $act_arg = array(
            'search_terms' => $search,
            'search_columns' => array('name'),
            'type' => 'alphabetical',
            'page' => (($_GET['tab'] === 'activity') && (!empty(get_query_var('acpage')))) ? get_query_var('acpage') : 1,
            'per_page' => $count_data,
            'count_total' => true,
        );

        if (!empty($bp_new_blog_post_page_ids)) {
            $act_arg['exclude'] = $bp_new_blog_post_page_ids;
        }
        if (bp_has_activities($act_arg)) :
            $flag = 2;
            $content['activity'] = '';
            while (bp_activities()) :
                bp_the_activity();

                $activity_id = bp_get_activity_id();
                $activity_user_id = bp_get_activity_user_id();
                $activity_avatar_html = bp_core_fetch_avatar(
                    array(
                        'item_id' => $activity_user_id,
                        'object' => 'user',
                        'type' => 'full',
                        'width' => 70,
                        'height' => 70,
                        // You can use different avatar types: 'thumb', 'full', etc.
                    )
                );
                $activity = bp_activity_get_specific(array('activity_ids' => $activity_id));
                $activity_content = $activity['activities'][0]->content;
                $activity_content = strip_tags($activity_content);

                $activity_link = esc_url(bp_get_activity_directory_permalink() . "p/" . $activity_id);
                $content['activity'] .= '<li> <div class="socialv-author-heading">
                                        <div class="item-avatar">' . $activity_avatar_html . '</div>
                                        <div class="item">
                                            <h5 class="item-title fn">' . bp_get_activity_action(array('no_timestamp' => true)) . '</h5>
                                            <a class="text-body mt-2" href="' . esc_url($activity_link) . '">' . $activity_content . '</a>
                                            <div class="item-meta mt-2"> ' . bp_insert_activity_meta() . '</div>
                                        </div>
                                    </div></li>';
            endwhile;
            if (!empty($content['activity'])) {
                $banner['activity'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Activity', 'socialv') . '</h4>';
                $all['activity'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=activity&ajax_search=1">' . esc_html__('View All Activity', 'socialv') . '</a></div>';
                $variable['activity'] = '<a href="?s=' . $search_txt . '&tab=activity&ajax_search=1" id="pills-activity-tab" class="nav-link" type="button" role="tab" aria-controls="pills-activity" aria-selected="true">' . esc_html__('Activity', 'socialv') . '</a> ';
            }
            if ($_GET['tab'] === 'activity') {
                $pagination['activity'] = bp_get_activity_pagination_links();
            }

            wp_reset_postdata();

        endif;
    }

    //Post Search
    if (in_array('post', $list_search_item)) {
        $current_post = (($_GET['tab'] === 'post') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1;
        $post_type = 'post';
        $results_post = '';
        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND (post_title LIKE %s OR post_content LIKE %s) ORDER BY post_date DESC",
            $post_type,
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );


        $results_post = $wpdb->get_results($query);
        $total_post = ceil(count($results_post) / $count_data);
        $offset = ($current_post - 1) * $count_data;
        $results_to_display_post = array_slice($results_post, $offset, $count_data);

        if ($results_to_display_post) {
            $flag = 2;
            $content['post'] = '';
            foreach ($results_to_display_post as $result) {
                $post_id = $result->ID;
                $image_url_post = '';
                if (has_post_thumbnail()) :
                    $image_url_post = '<div class="item-avatar"><a href="' . get_permalink($post_id) . '">' . get_the_post_thumbnail($post_id, array('thumbnail', '50', ' rounded avatar-70')) . '</a></div>';
                endif;

                $content['post'] .= '<li><div class="socialv-author-heading">' . $image_url_post . '
                            <div class="item">
                            <a href="' . get_permalink($post_id) . '">
                                <h5 class="item-title fn">
                                    ' . $result->post_title . '
                                </h5>
                                <div class="item-meta mt-2">' . get_the_date('', $post_id) . '</div>                                
                                <div class="text-body mt-2">' . get_the_excerpt($post_id) . '</div>
                            </a>
                            </div>
                        </div></li>';
            }
            if (!empty($content['post'])) {
                $banner['post'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Post', 'socialv') . '</h4>';
                $variable['post'] = '<a href="?s=' . $search_txt . '&tab=post&ajax_search=1" id="pills-post-tab" class="nav-link" type="button" role="tab" aria-controls="pills-post" aria-selected="true">' . esc_html__('Post', 'socialv') . '</a> ';
                $all['post'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=post&ajax_search=1">' . esc_html__('View All Posts', 'socialv') . '</a></div>';
            }
            if ($_GET['tab'] === 'post') {
                $pagination['post'] = $ajax_instance->socialv_pagination($total_post);
            }
            wp_reset_postdata();
        }
    }

    //product search
    if (in_array('product', $list_search_item)) {
        if (class_exists('WooCommerce')) {
            $image_url_product = '';
            $product_args = array(
                's' => $search,
                'search_columns' => array('name'),
                'post_type' => 'product',
                'posts_per_page' => $count_data,
                'paged' => (($_GET['tab'] === 'product') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1
            );
            $product_query = new WP_Query($product_args);

            if ($product_query->have_posts()) :
                $flag = 2;
                $content['product'] = '';
                while ($product_query->have_posts()) :
                    $product_query->the_post();

                    global $product;
                    if (!$product)
                        continue;
                    if ($product->get_image_id()) :
                        $product->get_image('shop_catalog');
                        $image_product = wp_get_attachment_image_src($product->get_image_id(), "thumbnail");
                        $image_url_product = '<div class="item-avatar"><a href="' . get_the_permalink($product->get_id()) . '"><img src="' . esc_url($image_product[0]) . '" alt="' . esc_attr('Image', 'socialv') . '" class="avatar rounded avatar-70 photo" loading="lazy"/></a></div>';
                    else :
                        $image_url_product = '<div class="item-avatar"><a href="' . get_the_permalink($product->get_id()) . '"><img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Awaiting product image', 'socialv') . '" class="avatar rounded avatar-70 photo" loading="lazy"/></a></div>';
                    endif;

                    $content['product'] .= '<li><div class="socialv-author-heading">' . $image_url_product . '
                                <div class="item">
                                    <h5 class="item-title fn">
                                        <a href="' . get_the_permalink($product->get_id()) . '">' . esc_html($product->get_name()) . '</a>
                                    </h5>
                                    <div>' . wp_kses($product->get_price_html(), 'socialv') . '</div>
                                </div>
                            </div> </li>';

                endwhile;
                if (!empty($content['product'])) {
                    $banner['product'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Product', 'socialv') . '</h4>';
                    $variable['product'] = '<a href="?s=' . $search_txt . '&tab=product&ajax_search=1" id="pills-product-tab" class="nav-link" type="button" role="tab" aria-controls="pills-product" aria-selected="true">' . esc_html__('Product', 'socialv') . '</a> ';
                    $all['product'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=product&ajax_search=1">' . esc_html__('View All Products', 'socialv') . '</a></div>';
                }
                if ($_GET['tab'] === 'product') {
                    $total_product_pages = $product_query->max_num_pages;
                    $pagination['product'] = $ajax_instance->socialv_pagination($total_product_pages);
                }
            endif;
            wp_reset_postdata();
        }
    }

    //Course Search
    if (in_array('course', $list_search_item)) {
        if (class_exists('LearnPress')) {
            $image_url_course = '';
            $course_args = array(
                's' => $search,
                'post_type' => 'lp_course',
                'fields' => 'ids',
                'posts_per_page' => $count_data,
                'paged' => (($_GET['tab'] === 'course') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1,
            );
            $course_query = new WP_Query($course_args);

            if ($course_query->have_posts()) :
                $flag = 2;
                $content['course'] = '';
                while ($course_query->have_posts()) :
                    $course_query->the_post();
                    $course = learn_press_get_course(get_the_ID());
                    if (!$course)
                        continue;
                    $image_url_course = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail');
                    if (!empty($image_url_course[0])) {
                        $image_url_course = $image_url_course[0];
                    } else {
                        $image_url_course = LP()->image('no-image.png');
                    }
                    $content['course'] .= '<li><div class="socialv-author-heading">
                                        <div class="item-avatar"><a href="' . esc_url(get_permalink(get_the_ID())) . '"><img src="' . esc_url($image_url_course) . '" alt="' . esc_attr('Image', 'socialv') . '" class="avatar rounded avatar-70 photo" loading="lazy" /></a></div>
                                            <div class="item">
                                                <h5 class="item-title fn">
                                                    <a href="' . get_the_permalink(get_the_ID()) . '">' . esc_html(get_the_title(get_the_ID())) . '</a>
                                                </h5>   
                                                <div>' . wp_kses_post($course->get_course_price_html()) . '</div>
                                            </div>
                                        </div></li>';

                endwhile;
                if (!empty($content['course'])) {
                    $banner['course'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Course', 'socialv') . '</h4>';
                    $variable['course'] = '<a href="?s=' . $search_txt . '&tab=course&ajax_search=1" id="pills-course-tab" class="nav-link" type="button" role="tab" aria-controls="pills-course" aria-selected="true">' . esc_html__('Course', 'socialv') . '</a>';
                    $all['course'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=course&ajax_search=1">' . esc_html__('View All Courses', 'socialv') . '</a></div>';
                }
                if ($_GET['tab'] === 'course') {
                    $total_course_pages = $course_query->max_num_pages;
                    $pagination['course'] = $ajax_instance->socialv_pagination($total_course_pages);
                }
            endif;
            wp_reset_postdata();
        }
    }

    //page search
    if (in_array('page', $list_search_item)) {
        $current_page = (($_GET['tab'] === 'page') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1;
        $post_type = 'page';
        $results_page = '';
        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND (post_title LIKE %s OR post_content LIKE %s) ORDER BY post_date DESC",
            $post_type,
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );

        $results_page = $wpdb->get_results($query);
        $total_pages = ceil(count($results_page) / $count_data);
        $offset = ($current_page - 1) * $count_data;
        $results_to_display_page = array_slice($results_page, $offset, $count_data);

        if ($results_to_display_page) {
            $flag = 2;
            $content['page'] = '';
            foreach ($results_to_display_page as $result) {
                $post_id = $result->ID;
                $image_url_page = '';
                if (has_post_thumbnail()) :
                    $image_url_page = '<div class="item-avatar"><a href="' . get_permalink($post_id) . '">' . get_the_post_thumbnail($post_id, array('thumbnail', '50', ' rounded avatar-70')) . '</a></div>';
                endif;

                $content['page'] .= '<li>
                        <div class="socialv-author-heading">' . $image_url_page . '
                            <div class="item">
                            <a href="' . get_permalink($post_id) . '">
                                <h5 class="item-title fn">
                                    ' . $result->post_title . '
                                </h5>
                                <div class="item-meta mt-2">' . get_the_date('', $post_id) . '</div>                                
                                <div class="text-body mt-2">' . get_the_excerpt($post_id) . '</div>
                            </a>
                            </div>
                        </div></li>';
            }
            if (!empty($content['page'])) {
                $banner['page'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Page', 'socialv') . '</h4>';
                $variable['page'] = '<a href="?s=' . $search_txt . '&tab=page&ajax_search=1" id="pills-page-tab" class="nav-link" type="button" role="tab" aria-controls="pills-page" aria-selected="true">' . esc_html__('Page', 'socialv') . '</a> ';
                $all['page'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=page&ajax_search=1">' . esc_html__('View All Page', 'socialv') . '</a></div>';
            }
            if ($_GET['tab'] === 'page') {
                $pagination['page'] = $ajax_instance->socialv_pagination($total_pages);
            }
            wp_reset_postdata();
        }
    }


    if (class_exists('bbPress')) {
        //forum search
        if (in_array('forum', $list_search_item)) {
            $current_page = (($_GET['tab'] === 'forum') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1;
            $post_type = 'forum';
            $results_forum_page = '';
            $query_forum = $wpdb->prepare(
                "SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND (post_title LIKE %s OR post_content LIKE %s) ORDER BY post_date DESC",
                $post_type,
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
            $results_forum_page = $wpdb->get_results($query_forum);
            $total_forum_pages = ceil(count($results_forum_page) / $count_data);
            $forum_offset = ($current_page - 1) * $count_data;
            $results_to_display_forum = array_slice($results_forum_page, $forum_offset, $count_data);

            if ($results_to_display_forum) {
                $flag = 2;
                $content['forum'] = '';
                foreach ($results_to_display_forum as $result) {
                    $forum_id = $result->ID;
                    $forum_last_activity = bbp_get_topic_last_active_time($forum_id);


                    $content['forum'] .= '<li>
            <div class="socialv-author-heading">
                <div class="item">
                    <a href="' . esc_url(bbp_get_topic_permalink($forum_id)) . '">
                    <h5 class="item-title fn">
                        ' . bbp_get_topic_title($forum_id) . '
                    </h5>
                    <div class="text-body mt-2">' . bbp_get_topic_content($forum_id) . '</div>
                    <div class="item-meta mt-2">' . bbp_get_forum_topic_count($forum_id) . ' ' . esc_html__('Topics', 'socialv') . '<span class="design_dott"></span>' . bbp_get_forum_reply_count($forum_id) . ' ' . esc_html__('Replies', 'socialv') . '<span class="design_dott"></span> ' . esc_html__('Last Activity', 'socialv') . ' ' . $forum_last_activity . ' </div>
                    </a>
                </div>
            </div></li>';
                }

                if (!empty($content['forum'])) {
                    $banner['forum'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Forum', 'socialv') . '</h4>';
                    $all['forum'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=forum&ajax_search=1">' . esc_html__('View All Forum', 'socialv') . '</a></div>';
                    $variable['forum'] = '<a href="?s=' . $search_txt . '&tab=forum&ajax_search=1" id="pills-forum-tab" class="nav-link" type="button" role="tab" aria-controls="pills-forum" aria-selected="true">' . esc_html__('Forum', 'socialv') . '</a> ';
                }
                if ($_GET['tab'] === 'forum') {
                    $pagination['forum'] = $ajax_instance->socialv_pagination($total_forum_pages);
                }
                wp_reset_postdata();
            }
        }

        //forum topic search
        if (in_array('topic', $list_search_item)) {
            $topics_args = array(
                's' => $search,
                'post_type' => 'topic',
                'posts_per_page' => $count_data,
                'paged' => (($_GET['tab'] === 'topic') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1,
            );
            $current_page = (($_GET['tab'] === 'topic') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1;
            $post_type = 'topic';
            $results_topic = '';
            $topic_query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND (post_title LIKE %s OR post_content LIKE %s) ORDER BY post_date DESC",
                $post_type,
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
            $results_topic = $wpdb->get_results($topic_query);
            $total_topic_pages = ceil(count($results_topic) / $count_data);
            $topic_offset = ($current_page - 1) * $count_data;
            $results_to_display_topic = array_slice($results_topic, $topic_offset, $count_data);
            if ($results_to_display_topic) {
                $flag = 2;
                $content['topic'] = '';
                foreach ($results_to_display_topic as $result) {
                    $topic_id = $result->ID;

                    $topic_author_id = get_post_field('post_author', $topic_id);

                    $topic_author_image = get_avatar($topic_author_id, 70);
                    $topic_author_image = str_replace('<img', '<img class="avatar user-' . $topic_author_id . '-avatar avatar-70 photo"', $topic_author_image);
                    $topic_content = get_post_field('post_content', $topic_id);
                    $content['topic'] .= '<li>
                <div class="socialv-author-heading">
                    <div class="item-avatar"><a href="' . get_author_posts_url($topic_author_id) . '">' . $topic_author_image . '</a>
                    </div>
                    <div class="item">
                        <a href="' . get_permalink($topic_id) . '">
                            <h5 class="item-title fn">
                                ' . get_the_title($topic_id) . '
                            </h5> 
                            <div class="text-body mt-2">' . strip_tags($topic_content) . '</div> </a>
                            <div class="item-meta mt-2">' . esc_html__('By', 'socialv') . ' ' . bbp_get_topic_author_link(array('post_id' => $topic_id, 'type' => 'name')) . '<span class="design_dott"></span>'
                        . bbp_get_topic_reply_count($topic_id) . ' ' . esc_html__('Replies', 'socialv') . '<span class="design_dott"></span> ' .
                        esc_html__('Started', 'socialv') . ' ' . bbp_get_topic_post_date($topic_id, true, false) . '</div>
               
                       
                    </div>
                </div>
            </li>';
                }

                if (!empty($content['topic'])) {
                    $banner['topic'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Topic', 'socialv') . '</h4>';
                    $all['topic'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=topic&ajax_search=1">' . esc_html__('View All Topic', 'socialv') . '</a></div>';
                    $variable['topic'] = '<a href="?s=' . $search_txt . '&tab=topic&ajax_search=1" id="pills-topic-tab" class="nav-link" type="button" role="tab" aria-controls="pills-topic" aria-selected="true">' . esc_html__('Topic', 'socialv') . '</a> ';
                }
                if ($_GET['tab'] === 'topic') {
                    $pagination['topic'] = $ajax_instance->socialv_pagination($total_topic_pages);
                }
                wp_reset_postdata();
            }
        }
        //forum reply search
        if (in_array('reply', $list_search_item)) {
            $replies_args = array(
                's' => $search,
                'post_type' => 'reply',
                'posts_per_page' => $count_data,
                'paged' => (($_GET['tab'] === 'reply') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1,
            );
            $current_page = (($_GET['tab'] === 'reply') && !empty(get_query_var('paged'))) ? get_query_var('paged') : 1;
            $post_type = 'reply';
            $results_reply = '';
            $reply_query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND (post_title LIKE %s OR post_content LIKE %s) ORDER BY post_date DESC",
                $post_type,
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );

            $results_reply_page = $wpdb->get_results($reply_query);
            $total_reply_pages = ceil(count($results_reply_page) / $count_data);
            $reply_offset = ($current_page - 1) * $count_data;
            $results_to_display_reply = array_slice($results_reply_page, $reply_offset, $count_data);
            if ($results_to_display_reply) {
                $flag = 2;
                $content['reply'] = '';
                foreach ($results_to_display_reply as $result) {
                    $reply_id = $result->ID;
                    $reply_content = get_post_field('post_content', $reply_id);

                    $reply_author_id = get_post_field('post_author', $reply_id);

                    $reply_author_image = get_avatar($reply_author_id, 70);
                    $reply_author_image = str_replace('<img', '<img class="avatar user-' . $reply_author_id . '-avatar avatar-70 photo"', $reply_author_image);


                    $content['reply'] .= '<li>
                    <div class="socialv-author-heading">
                        <div class="item-avatar">
                            <a href="' . bbp_get_reply_author_url($reply_id) . '">' . $reply_author_image . '</a>
                        </div>
                        <div class="item">
                        <h5 class="item-title fn">
                                ' . bbp_get_reply_author_link(array('post_id' => $reply_id, 'type' => 'name')) . '  <span class="rply_discuss">' . esc_html__('replied to a discussion', 'socialv') . '</span>
                            </h5>
                           <a class="text-body mt-2" href="' . esc_url(bbp_get_reply_url($reply_id)) . '">' . strip_tags($reply_content) . '</a>
                            <div class="item-meta mt-2">' . bbp_get_reply_post_date($reply_id, true, false) . '</div>
                        </div>
                    </div>  
            </li>';
                }

                if (!empty($content['reply'])) {
                    $banner['reply'] = '<div class="search-content-data"><h4 class="title">' . esc_html__('Reply', 'socialv') . '</h4>';
                    $all['reply'] = '<a class="socialv-button socialv-button-link" href="?s=' . $search_txt . '&tab=reply&ajax_search=1">' . esc_html__('View All Reply', 'socialv') . '</a></div>';
                    $variable['reply'] = '<a href="?s=' . $search_txt . '&tab=reply&ajax_search=1" id="pills-reply-tab" class="nav-link" type="button" role="tab" aria-controls="pills-reply" aria-selected="true">' . esc_html__('Reply', 'socialv') . '</a> ';
                }
                if ($_GET['tab'] === 'reply') {
                    $pagination['reply'] = $ajax_instance->socialv_pagination($total_reply_pages);
                }
                wp_reset_postdata();
            }
        }
    }


    if ($flag == 2) {
?>
        <!-- Tab List -->
        <div class="card-main card-space card-space-bottom">
            <div class="card-inner pt-0 pb-0 item-list-tabs no-ajax">
                <div class="socialv-subtab-lists">
                    <?php do_action('socialv_nav_direction'); ?>
                    <div class="socialv-subtab-container custom-nav-slider">
                        <ul class="list-inline m-0" id="pills-tab" role="tablist">
                            <?php
                            $count = count($variable);
                            if ($count !== 1) {
                                echo '<li class="nav-item" role="presentation">
                                    <a href="?s=' . $search_txt . '&tab=all&ajax_search=1" id="pills-all-tab" class="nav-link" type="button" role="tab" aria-controls="pills-all" aria-selected="true">' . esc_html__('All', 'socialv') . '</a>   </li>';
                            }
                            foreach ($variable as $key => $value) {
                                if ($key !== 0) {
                                    echo '<li class="nav-item" role="presentation">' . $value . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data list -->
        <div class="card-main card-space">
            <div class="card-inner">
                <div class="tab-content socialv-member-list" id="pills-tabContent">
                    <?php
                    //All Tab
                    if (isset($_GET['tab']) && $_GET['tab'] == 'all') {
                        echo '<div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">';
                        foreach ($content as $key => $value) {
                            echo wp_kses_post($banner[$key]);
                            echo '<ul class="list-inline">';
                            echo wp_kses_post($value);
                            echo '</ul>';
                            echo wp_kses_post($all[$key]);
                        }
                        echo '</div>';
                    } else {
                        //other tabs
                        $data_key = $_GET['tab'];
                        foreach ($content as $key => $value) {

                            if ($data_key == $key) {
                                echo '<div class="tab-pane fade" id="pills-' . $key . '" role="tabpanel" aria-labelledby="pills-' . $key . '-tab"><ul class="list-inline m-0">' . $value . '</ul></div>';
                                echo '<div class="search-pagination">' . $pagination[$key] . '</div>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    if (empty($content)) {
        get_template_part('template-parts/content/error');
    }
} elseif (strlen($_GET['s']) > 3 && isset($_GET['s'])) {


    $query = new WP_Query(
        array(
            'posts_per_page' => $count_data,
            'paged' => !empty(get_query_var('paged')) ? get_query_var('paged') : 1,
            's' => $_GET['s']
        )
    );
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content/entry', $query->get_post_type(), $post_section['post']);
        }
        if (!isset($socialv_options['display_search_pagination']) || $socialv_options['display_search_pagination'] == "yes") {
            $total_serach_pages = $query->max_num_pages;
            echo '<div class="search-pagination">';
            echo $ajax_instance->socialv_pagination($total_serach_pages);
            echo '</div>';
        }
    } else {
        get_template_part('template-parts/content/error');
    }
    wp_reset_postdata();
} else {
    get_template_part('template-parts/content/error');
}
wp_reset_postdata();
socialv()->socialv_sidebar();
echo '</div></div></div></main><!-- #primary --></div></div></div>';

get_footer();
