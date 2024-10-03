<?php

/**
 * SocialV\Utility\Custom_Helper\Helpers\Ajax class
 *
 * @package socialv
 */

namespace SocialV\Utility\Custom_Helper\Helpers;

use BP_Activity_Activity;
use SocialV\Utility\Custom_Helper\Component;
use function SocialV\Utility\socialv;
use function add_action;

class Ajax extends Component
{
    public $socialv_option;
    public function __construct()
    {
        $this->socialv_option = get_option('socialv-options');
        // Activity Share - Post activity share
        add_action('wp_ajax_socialv_post_share_activity', array($this, 'socialv_post_share_activity'));
        add_action('wp_ajax_nopriv_socialv_post_share_activity', array($this, 'socialv_post_share_activity'));

        // set the hide post option in activity page.
        if (isset($this->socialv_option['is_socialv_enable_hide_post']) && $this->socialv_option['is_socialv_enable_hide_post'] == '1') {
            add_action('wp_ajax_hide_activity_post', [$this, 'socialv_hide_activity_post']);
            add_action('wp_ajax_nopriv_hide_activity_post', [$this, 'socialv_hide_activity_post']);
        }

        //Search Content
        if (isset($this->socialv_option['header_display_search']) && $this->socialv_option['header_display_search'] == 'yes') {
            add_action('wp_ajax_ajax_search_content', [$this, 'socialv_ajax_search_content']);
            add_action('wp_ajax_nopriv_ajax_search_content', [$this, 'socialv_ajax_search_content']);
        }

        //mark all notification as read - notification
        add_action('wp_ajax_socialv_read_all_notification', [$this, 'socialv_read_all_notification']);
        add_action('wp_ajax_nopriv_socialv_read_all_notification', [$this, 'socialv_read_all_notification']);

        // Pop-up Activity
        add_action('wp_ajax_socialv_get_popup_activity', [$this, 'socialv_get_popup_activity']);
        add_action('wp_ajax_nopriv_socialv_get_popup_activity', [$this,  'socialv_get_popup_activity']);

        //on the click of share on activity option in share open a popup box with the post and add content text
        add_action('wp_ajax_socialv_share_on_activity_click', [$this, 'socialv_share_on_activity_click']);
        add_action('wp_ajax_nopriv_socialv_share_on_activity_click', [$this, 'socialv_share_on_activity_click']);

    }

    // AJAX || Post an Activity Share
    public function socialv_post_share_activity()
    {
        if (!is_user_logged_in()) {
            return;
        }

        global $wpdb;
        $table = $wpdb->base_prefix . 'bp_activity';
        $shared_activity_id = isset($_POST['activity_id']) ? intval($_POST['activity_id']) : 0;
        $comment_text = isset($_POST['commentText']) ? sanitize_text_field($_POST['commentText']) : ''; // Sanitize the comment text

        if ($shared_activity_id <= 0) {
            wp_send_json_error(__('Invalid activity ID.', 'text-domain'));
        }

        // Fetch the original post content and media
        $query = $wpdb->prepare("SELECT user_id, primary_link, content FROM {$table} WHERE id = %d", $shared_activity_id);
        $activity = $wpdb->get_results($query);

        if (!$activity) {
            wp_send_json_error(__('Activity not found.', 'text-domain'));
        }

        $activity_user_id = $activity[0]->user_id;
        $current_user_id = get_current_user_id();

        // Get media meta from the original post
        $media_meta = bp_activity_get_meta($shared_activity_id, 'media_meta', true);

        // Construct the action text based on user relationship
        if ($activity_user_id == $current_user_id) {
            $action = '<a href="' . bp_members_get_user_url($activity_user_id) . '">' . get_the_author_meta('display_name', $activity_user_id) . '</a>' . esc_html__(' shared their post', 'socialv');
        } else {
            $action = '<a href="' . bp_members_get_user_url($current_user_id) . '">' . get_the_author_meta('display_name', $current_user_id) . '</a> ' . sprintf(esc_html__('shared %s post', 'socialv'), '<a href="' . bp_members_get_user_url($activity_user_id) . '">' . get_the_author_meta('display_name', $activity_user_id) . '</a>');
        }

        // Construct the content of the shared activity
        $activity_content = ''; // Initialize activity content

        // Add the comment text if it exists
        if (!empty($comment_text)) {
            $activity_content .= $comment_text;
        }

        // Insert the shared activity
        $wpdb->insert(
            $table,
            array(
                'user_id' => $current_user_id,
                'component' => 'activity',
                'type' => 'activity_share',
                'action' => $action,
                'content' => $activity_content, // Include only comment text
                'primary_link' => $activity[0]->primary_link,
                'date_recorded' => current_time('mysql')
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        $activity_id = $wpdb->insert_id;

        // Update the shared activity's meta with media meta if it exists
        if ($activity_id && !empty($media_meta)) {
            bp_activity_update_meta($activity_id, 'media_meta', $media_meta);
        }

        // Update the shared activity's meta
        if ($activity_id) {
            bp_activity_update_meta($activity_id, 'shared_activity_id', $shared_activity_id);
        }

        // Send JSON response with success flag
        wp_send_json_success(__('Activity reposted successfully.', 'text-domain'));
    }

    public function socialv_hide_activity_post()
    {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $meta_key = "_socialv_activity_hiden_by_user";
        $data = '';
        if (!isset($_POST["activity_id"])) {
            esc_html_e("Id not present", "socialv");
            wp_die();
        }
        $activity_id = $_POST["activity_id"];
        $hidden_activities = get_user_meta($user_id, $meta_key, true);
        if ($_POST['data_type'] == 'hide') {
            if ($hidden_activities) {
                if (in_array($activity_id, $hidden_activities)) {
                    $unset_id = array_search($activity_id, $hidden_activities);
                    unset($hidden_activities[$unset_id]);
                    if (update_user_meta($user_id, $meta_key, array_values($hidden_activities))) {
                        $data .= esc_html__("Post is now visible", "socialv");
                    }
                } else {
                    $hidden_activities[] = $activity_id;
                    if (update_user_meta($user_id, $meta_key, $hidden_activities)) {
                        $data .= esc_html__("Post is now hidden", "socialv");
                    }
                }
            } else {
                $hidden_activities = [];
                $hidden_activities[] = $activity_id;
                if (update_user_meta($user_id, $meta_key, $hidden_activities)) {
                    $data .= esc_html__("Post is now hidden", "socialv");
                }
            }
        } else if ($_POST['data_type'] == 'undo') {
            if ($hidden_activities && in_array($activity_id, $hidden_activities)) {
                $unset_id = array_search($activity_id, $hidden_activities);
                unset($hidden_activities[$unset_id]);
                if (update_user_meta($user_id, $meta_key, array_values($hidden_activities))) {
                    $data .= esc_html__("Post is now visible", "socialv");
                }
            } else {
                $data .= esc_html__("Post was not hidden", "socialv");
            }
        }
        wp_send_json_success($data);
        wp_die();
    }

    public function socialv_ajax_search_content()
    {
        $data[] = null;
        $search = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
        $count_data = isset($this->socialv_option['header_search_limit']) ? $this->socialv_option['header_search_limit'] : '5';
        $search_content_list = [];
        $search_content_list = isset($this->socialv_option['socialv_search_content_list']) ? $this->socialv_option['socialv_search_content_list'] : [];
        $data['content'] = $this->socialv_search($search, $count_data, $search_content_list);
        $search = str_replace(' ', '+', $search);
        if (!empty($data['content'])) {
            $data['details'] = '<a class="btn-view-all" href="' . esc_url(home_url()) . '?s=' . $search . '&tab=all&ajax_search=1">' . esc_html__('View All', 'socialv') . '</a>';
            wp_send_json_success($data);
        } else {
            $data['content'] = '<div class="search_no_result">' . esc_html__('No Data Found', 'socialv') . '</div>';
            wp_send_json_success($data);
        }
    }

    function socialv_search($search, $data_count, $search_content_list)
    {
        $actdata = $post_data = $data = '';
        $list_search_item = '';

        foreach ($search_content_list as $key => $value) {
            if ($value == 1) {
                $list_search_item .= $key;
                $list_search_item .= ',';
            }
        }
        $list_search_item_array = explode(',', trim($list_search_item, ','));



        //Members Search
        if (in_array('member', $list_search_item_array)) {
            if (
                bp_has_members(
                    array(
                        'search_terms' => $search,
                        'search_columns' => array('name'),
                        'per_page' => $data_count,
                        'page' => 1,
                    )
                )
            ) :
                $data .= '<h6 class="socialv-header-title">' . esc_html__('Member', 'socialv') . '</h6>';
                while (bp_members()) :
                    bp_the_member();

                    $members_user_id = bp_get_member_user_id();
                    $data .= '<li>
                        <div class="socialv-author-heading">
                            <div class="item-avatar">
                                <a href="' . bp_get_member_permalink() . '">' . bp_get_member_avatar('type=thumb&width=50&height=50') . '</a>
                            </div>
                            <div class="item">
                            <a class="search-anch" href="' . bp_get_member_permalink() . '"> </a>
                                <h6 class="item-title fn">' . bp_get_member_name() . ''
                        . socialv()->socialv_get_verified_badge($members_user_id) . '
                                </h6>
                                <div class="item-meta">' . bp_get_member_last_active() . '</div>
                            </div>
                        </div>
				</li>';

                endwhile;
                $data .= '<br>';
            endif;
        }

        //Group Search
        if (in_array('group', $list_search_item_array)) {
            if (
                bp_has_groups(
                    array(
                        'search_terms' => $search,
                        'type' => "alphabetical",
                        'search_columns' => array('name'),
                        'per_page' => $data_count,
                    )
                )
            ) :
                $data .= '<h6 class="socialv-header-title">' . esc_html__('Group', 'socialv') . '</h6>';
                while (bp_groups()) :
                    bp_the_group();

                    $data .= '<li>
                        <div class="socialv-author-heading">
                            <div class="item-avatar">
                                <a href="' . bp_get_group_url() . '">' . bp_core_fetch_avatar(array('item_id' => bp_get_group_id(), 'avatar_dir' => 'group-avatars', 'object' => 'group', 'width' => 50, 'height' => 50, 'class' => 'rounded-circle')) . '</a>
                            </div>
                            <div class="item">
                            <a class="search-anch" href="' . esc_url(bp_get_group_url()) . '"> </a>

                                <h6 class="item-title fn">' . bp_get_group_link() . '</h6>
                                <div class="item-meta">' . bp_get_group_type() . '</div>
                            </div>
                      </div>
				</li>';

                endwhile;
                $data .= '<br>';
            endif;
        }

        //Activity Search
        if (in_array('activity', $list_search_item_array)) {
            $act_arg = array(
                'post_type' => 'activity',
                'search_terms' => $search,
                'per_page' => $data_count,
                'type' => 'alphabetical',
            );
            if (bp_has_activities($act_arg)) :
                while (bp_activities()) :
                    bp_the_activity();
                    if (bp_get_activity_type() === 'new_blog_post') {
                        continue; // Skip blog posts
                    }

                    $activity_id = bp_get_activity_id();
                    $activity_user_id = bp_get_activity_user_id();
                    $activity_avatar_html = bp_core_fetch_avatar(
                        array(
                            'item_id' => $activity_user_id,
                            'object' => 'user',
                            'type' => 'thumb',
                        )
                    );
                    $activity = bp_activity_get_specific(array('activity_ids' => $activity_id));
                    $activity_user_field = bp_get_activity_action(array('no_timestamp' => true));

                    $activity_content = $activity['activities'][0]->content;
                    $activity_content = strip_tags($activity_content);

                    $date_recorded = $activity['activities'][0]->date_recorded;

                    $truncated_content = strlen($activity_content) > 70 ? substr($activity_content, 0, 70) . '...' : $activity_content;
                    $activity_link = esc_url(bp_get_activity_directory_permalink() . "p/" . $activity_id);

                    $actdata .= '<li>
                            <div class="socialv-author-heading">
                                <div class="item-avatar">
                                ' . $activity_avatar_html . '
                                </div>
                                <a class="search-anch" href="' . esc_url($activity_link) . '"> </a>
                                <div class="item">
                                    <div class="socialv-activity-item item-title fn">' . $activity_user_field . '</div>
                                    <div class="search-desc">'
                        . $truncated_content . '
                                    </div> 
                                    <div class="item-meta mt-2 m-0"> ' . bp_core_time_since($date_recorded) . '</div>
                                </div>
                            </div>
                    </li>';

                endwhile;
                if (!empty($actdata)) {
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Activity', 'socialv') . '</h6>';
                    $data .= $actdata;
                    $data .= '<br>';
                }
            endif;
        }

        //Post Search
        if (in_array('post', $list_search_item_array)) {
            $image_url_post = '';
            $post_args = array(
                's' => $search,
                'post_type' => 'post',
                'posts_per_page' => $data_count,
            );

            query_posts($post_args);
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    if (get_post_type() == 'bp-email')
                        continue;
                    $post_discription = get_the_excerpt();
                    $post_discription = strlen($post_discription) > 125 ? substr($post_discription, 0, 125) . '...' : $post_discription;

                    if (has_post_thumbnail()) :
                        $image_url_post = '<div class="item-avatar"><a href="' . get_the_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), array('thumbnail', '50', ' rounded avatar-50')) . '</a></div>';
                    endif;

                    $post_data .= '<li>
                            <div class="socialv-author-heading">' . $image_url_post . '
                                <div class="item">
                                
                                    <h6 class="item-title fn">
                                        ' . get_the_title() . '
                                    </h6>
                                    <div class="item-meta mt-2">' . get_the_date() . '</div>
                                    <a class="search-anch" href="' . get_the_permalink() . '"></a>
                                    <div class="search-desc">' . $post_discription . '</div>
                               
                                </div>
                            </div>
			        	</li>';

                endwhile;
                if (!empty($post_data)) {
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Post', 'socialv') . '</h6>';
                    $data .= $post_data;
                    $data .= '<br>';
                }
                wp_reset_postdata();
            endif;
        }

        //product search
        if (in_array('product', $list_search_item_array)) {
            if (class_exists('WooCommerce')) {
                $image_url_product = '';
                $product_args = array(
                    's' => $search,
                    'search_columns' => array('name'),
                    'post_type' => 'product',
                    'posts_per_page' => $data_count,
                );

                query_posts($product_args);
                if (have_posts()) :

                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Product', 'socialv') . '</h6>';
                    while (have_posts()) :
                        the_post();
                        global $product;

                        if ($product->get_image_id()) :
                            $product->get_image('shop_catalog');
                            $image_product = wp_get_attachment_image_src($product->get_image_id(), "thumbnail");
                            $image_url_product = '<div class="item-avatar"><a href="' . get_the_permalink($product->get_id()) . '"><img src="' . esc_url($image_product[0]) . '" alt="' . esc_attr('Image', 'socialv') . '" class="avatar rounded avatar-50 photo" loading="lazy"/></a></div>';
                        else :
                            $image_url_product = '<div class="item-avatar"><a href="' . get_the_permalink($product->get_id()) . '"><img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Awaiting product image', 'socialv') . '" class="avatar rounded avatar-50 photo" loading="lazy"/></a></div>';
                        endif;
                        $data .= '<li>
                                <div class="socialv-author-heading">' . $image_url_product . '
                                    <div class="item">
                                    <a class="search-anch" href="' . get_the_permalink($product->get_id()) . '"></a>
                                        <h6 class="item-title fn">' . esc_html($product->get_name()) . '
                                        </h6>
                                        <div class="item-meta mt-2">' . wp_kses($product->get_price_html(), 'socialv') . '</div>
                                    </div>
                                </div>
				            </li>';

                    endwhile;
                    wp_reset_postdata();
                    $data .= '<br>';
                endif;
            }
        }

        //Course Search
        if (in_array('course', $list_search_item_array)) {
            if (class_exists('LearnPress')) {
                $image_url_course = '';
                $course_args = array(
                    's' => $search,
                    'post_type' => 'lp_course',
                    'fields' => 'ids',
                    'posts_per_page' => $data_count,
                );
                query_posts($course_args);

                if (have_posts()) :
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Course', 'socialv') . '</h6>';
                    while (have_posts()) :
                        the_post();

                        $course = learn_press_get_course(get_the_ID());
                        $image_url_course = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail');
                        if (!empty($image_url_course[0])) {
                            $image_url_course = $image_url_course[0];
                        } else {
                            $image_url_course = LP()->image('no-image.png');
                        }
                        $data .= '<li>
                                <div class="socialv-author-heading">
                                <div class="item-avatar"><a href="' . esc_url(get_permalink(get_the_ID())) . '"><img src="' . esc_url($image_url_course) . '" alt="' . esc_attr('Image', 'socialv') . '" class="avatar rounded avatar-50 photo" loading="lazy" /></a></div>
                                    <div class="item">
                                    <a class="search-anch" href="' . get_the_permalink(get_the_ID()) . '"></a>
                                        <h6 class="item-title fn">
                                           ' . esc_html(get_the_title(get_the_ID())) . '
                                        </h6>
                                        <div class="item-meta mt-2">' . wp_kses_post($course->get_course_price_html()) . '</div>
                                    </div>
                                </div>
                            </li>';

                    endwhile;
                    wp_reset_postdata();
                    $data .= '<br>';
                endif;
            }
        }

        //page search
        if (in_array('page', $list_search_item_array)) {
            $image_url_page = '';
            $page_args = array(
                's' => $search,
                'post_type' => 'page',
                'posts_per_page' => $data_count,
                'post_status' => 'publish',
            );

            query_posts($page_args);
            if (have_posts()) :

                $data .= '<h6 class="socialv-header-title">' . esc_html__('Page', 'socialv') . '</h6>';
                while (have_posts()) :
                    the_post();
                    $page_discription = get_the_excerpt();
                    $page_discription = strlen($page_discription) > 125 ? substr($page_discription, 0, 125) . '...' : $page_discription;

                    if (has_post_thumbnail()) :
                        $image_url_page = '<div class="item-avatar"><a href="' . get_the_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), array('thumbnail', '50', ' rounded avatar-70')) . '</a></div>';
                    endif;

                    $data .= '<li>
                                <div class="socialv-author-heading">' . $image_url_page . '
                                    <div class="item">
                                  
                                        <h6 class="item-title fn">
                                            ' . get_the_title() . '
                                        </h6>
                                        <a class="search-anch" href="' . get_the_permalink() . '"></a>
                                        <div class="item-meta mt-2">' . get_the_date() . '</div>
                                        <div class="search-desc">' . $page_discription . '</div>  
                                   
                                    </div>
                                </div>  
                        </li>';
                endwhile;
                wp_reset_postdata();
                $data .= '<br>';
            endif;
        }

        //forum search
        if (in_array('forum', $list_search_item_array)) {
            if (class_exists('bbPress')) {
                $forums_args = array(
                    's' => $search,
                    'post_type' => 'forum',
                    'posts_per_page' => $data_count,
                );

                query_posts($forums_args);

                if (have_posts()) {
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Forums', 'socialv') . '</h6>';

                    while (have_posts()) :
                        the_post();
                        $forum_id = get_the_ID();
                        $forum_content = bbp_get_topic_content($forum_id);
                        $forum_content = strlen($forum_content) > 125 ? substr($forum_content, 0, 125) . '...' : $forum_content;


                        $data .= '<li>
                            <div class="socialv-author-heading">
                                <div class="item">
                                
                                    <h6 class="item-title fn">
                                        ' . bbp_get_topic_title($forum_id) . '
                                    </h6>
                                    <a class="search-anch" href="' . esc_url(bbp_get_topic_permalink($forum_id)) . '"></a>
                                    <div class="search-desc">' . $forum_content . '</div>
                                    <div class="item-meta mt-2">' . bbp_get_forum_topic_count($forum_id) . ' ' . esc_html__('Topics', 'socialv') . '<span class="design_dott"></span> ' . bbp_get_forum_reply_count($forum_id) . ' ' . esc_html__('Replies', 'socialv') . '<span class="design_dott"></span> ' . esc_html__('Last Activity', 'socialv') . ' ' . bbp_get_topic_last_active_time($forum_id) . ' </div>
                               
                                </div>
                            </div>  
                     </li>';
                    endwhile;
                    wp_reset_postdata();
                    $data .= '<br>';
                }
            }

            //topic search
            if (in_array('topic', $list_search_item_array)) {
                $topics_args = array(
                    's' => $search,
                    'post_type' => 'topic',
                    'posts_per_page' => $data_count,
                );
                query_posts($topics_args);
                $topic_author_image = '';
                if (have_posts()) {
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Topics', 'socialv') . '</h6>';
                    while (have_posts()) :
                        the_post();

                        $topic_id = get_the_ID();
                        $topic_author_id = get_the_author_meta('ID');
                        $topic_author_image = get_avatar($topic_author_id, 50);
                        $topic_author_image = str_replace('<img', '<img class="avatar user-' . $topic_author_id . '-avatar avatar-50 photo"', $topic_author_image);

                        $topic_content = get_the_content();
                        $topic_content = strip_tags($topic_content);
                        $topic_content = strlen($topic_content) > 125 ? substr($topic_content, 0, 125) . '...' : $topic_content;


                        $data .= '<li>
                                <div class="socialv-author-heading"><div class="item-avatar"><a href="' . get_author_posts_url($topic_author_id) . '">' . $topic_author_image . '</a>
                                </div>
                                    <div class="item">
                                    
                                        <h6 class="item-title fn">
                                            ' . get_the_title() . '
                                        </h6>
                                        <a class="search-anch" href="' . esc_url(get_permalink($topic_id)) . '"></a>
                                        <div class="search-desc">' . $topic_content . '</div>      
                                       
                                        <div class="item-meta mt-2">' . esc_html__('By', 'socialv') . '  ' . bbp_get_topic_author_link(array('post_id' => $topic_id, 'type' => 'name')) . '<span class="design_dott"></span> ' . bbp_get_topic_reply_count($topic_id) . ' ' . esc_html__('Replies', 'socialv') . '</div>
                                   
                                    </div>
                                </div>  
                        </li>';

                    endwhile;

                    wp_reset_postdata();
                    $data .= '<br>';
                }
            }


            //Replies search
            if (in_array('reply', $list_search_item_array)) {
                $replies_args = array(
                    's' => $search,
                    'post_type' => 'reply',
                    'posts_per_page' => $data_count,
                );
                query_posts($replies_args);

                if (have_posts()) {
                    $data .= '<h6 class="socialv-header-title">' . esc_html__('Replies', 'socialv') . '</h6>';
                    while (have_posts()) :
                        the_post();
                        $reply_id = get_the_ID();
                        $reply_content = get_the_content(); // Get the content of the reply
                        $reply_content = strip_tags($reply_content);
                        $reply_content = strlen($reply_content) > 125 ? substr($reply_content, 0, 125) . '...' : $reply_content;

                        $reply_author_id = get_the_author_meta('ID');
                        $reply_author_image = get_avatar($reply_author_id, 50);
                        $reply_author_image = str_replace('<img', '<img class="avatar user-' . $reply_author_id . '-avatar avatar-50 photo"', $reply_author_image);


                        $data .= '<li>
                    <div class="socialv-author-heading"><div class="item-avatar">
                    <a href="' . bbp_get_reply_author_url($reply_id) . '">' . $reply_author_image . '</a>
                     </div>
                        <div class="item">

                            <div class="socialv-activity-item item-title fn"><p>
                                ' . bbp_get_reply_author_link(array('post_id' => $reply_id, 'type' => 'name')) . '  ' . esc_html__('replied to a discussion', 'socialv') . '
                            </p></div>
                            <a class="search-anch" href="' . esc_url(bbp_get_reply_url($reply_id)) . '"> </a>
                            <div class="search-desc">' . $reply_content . '</div>
                            <div class="item-meta mt-2">' . bbp_get_reply_post_date($reply_id, true, false) . '</div>
                        </div>
                    </div>  
            </li>';

                    endwhile;

                    wp_reset_postdata();
                    $data .= '<br>';
                }
            }
        }


        return $data;
    }


    //notification - mark all notification as read
    function socialv_read_all_notification()
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $table_name = $wpdb->prefix . 'bp_notifications';
        $update_data = array('is_new' => 0);
        $where = array('user_id' => $user_id, 'is_new' => 1);
        $format = array('%d');
        $wpdb->update($table_name, $update_data, $where, $format);
        wp_send_json_success('Marked all notifications as read');
        wp_die();
    }

    //pop activity
    function socialv_get_popup_activity()
    {
        $activity_id = isset($_POST['activity_id']) ? absint($_POST['activity_id']) : 0;

        // Disable legacy activity query to use the AJAX query
        add_filter('bp_use_legacy_activity_query', function ($value, $method, $args) {
            if ($method == "BP_Activity_Activity::get_activity_comments") {
                return false;
            }
        }, 10, 3);

        $args = array(
            'in' => $activity_id,
            'show_hidden' => 1,
            
        );
        // Perform BuddyPress activity query
        if ((bp_has_activities($args))) {
            ob_start(); // Start output buffering to capture HTML output

            // Check if it's the initial request, then open the activity stream
            if (empty($_POST['page'])) {
                echo '<ul id="activity-stream" class="activity-list socialv-list-post">';
            }

            while (bp_activities()) {
                bp_the_activity();
                bp_get_template_part('activity/entry');
            }

            // Check if it's the initial request, then close the activity stream
            if (empty($_POST['page'])) {
                echo '</ul>';
            }

            $activity_html = ob_get_clean(); // Get the buffered HTML output

            // Send the activity HTML as a JSON response
            wp_send_json_success(array(
                'activity_id' => $activity_id,
                'activity_html' => $activity_html
            ));
        } else {
            // No activities found, send empty response
            wp_send_json_success(array(
                'activity_id' => $activity_id,
                'activity_html' => ''
            ));
        }
    }


    //on the click of share on activity option in share open a popup box with the post and add content text
    function socialv_share_on_activity_click()
    {
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

        $args = array(
            'in' => $post_id,
            'show_hidden' => 1,
            'display_comments' => false
        );

        if ((bp_has_activities($args))) {
            ob_start(); // Start output buffering to capture HTML output

            // Check if it's the initial request, then open the activity stream
            if (empty($_POST['page'])) {
                echo '<ul id="activity-stream" class="activity-list socialv-list-post">';
            }

            do_action("socialv_before_activity_loop");

            while (bp_activities()) {
                bp_the_activity();
                bp_get_template_part('activity/entry');
            }

            // Check if it's the initial request, then close the activity stream
            if (empty($_POST['page'])) {
                echo '</ul>';
            }

            $activity_html = ob_get_clean(); // Get the buffered HTML output

            // Send the activity HTML as a JSON response
            wp_send_json_success(array(
                'post_id' => $post_id,
                'activity_html' => $activity_html
            ));
        } else {
            // No activities found, send empty response
            wp_send_json_success(array(
                'activity_id' => $post_id,
                'activity_html' => ''
            ));
        }
    }

}
