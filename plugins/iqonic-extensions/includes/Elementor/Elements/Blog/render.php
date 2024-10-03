<?php

namespace Elementor;

use function SocialV\Utility\socialv;

if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();

$align = '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$cat = '';
if (isset($settings['blog_cat']) && !empty($settings['blog_cat'])) {
    $cat = implode(',', $settings['blog_cat']);
}

$args = array(
    'post_type'         => 'post',
    'post_status'       => array('private', 'publish'),
    'paged'             => $paged,
    'category_name'     => $cat,
    'order'             => $settings['order'],
    'suppress_filters'  => 0,
    'posts_per_page'    => $settings['posts_per_page']
);

$align = 'blog-widget ';
$align .= $settings['align'];
$align .= ' grid-default';

global $post;

$wp_query = new \WP_Query($args); ?>

<div class="<?php echo esc_attr(trim($align)); ?>">
    <?php

    echo '<div class="row">';
    $grid = [
        "1" => "col-lg-12",
        "2" => "col-lg-6 col-md-6",
        "3" => "col-lg-4 col-md-6",
        "4" => "col-lg-3 col-md-6",
    ];
    $col = (!empty($settings['blog_style'])) ? $grid[$settings['blog_style']] : $grid["3"];
    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {

            $wp_query->the_post();
            $thumb_id = get_post_thumbnail_id(get_the_ID());
            $srcset = wp_get_attachment_image_srcset($thumb_id);
    ?>
            <div class="<?php echo esc_attr($col); ?>">
                
                <div class="socialv-blog-box">

                    <!-- blog image start -->
                    <?php if (has_post_thumbnail() && $settings['display_thumbnail'] == 'yes') { ?>
                        <div class="socialv-blog-image">
                            <a class="socialv-blog-link" href="<?php echo sprintf("%s", esc_url(get_permalink($wp_query->ID))); ?>">
                                <?php $image_attributes = [
                                    'src' => '',  // Leave src empty or provide a placeholder image
                                    'data-src' => wp_get_attachment_image_url($thumb_id, $settings["image_size"]),
                                    'data-srcset' => $srcset,
                                ];
                                echo wp_get_attachment_image($thumb_id, $settings["image_size"], false, $image_attributes); ?>
                            </a>
                        </div>
                    <?php } ?>
                    <!-- blog image end -->

                    <!-- blog details main start -->
                    <div class="socialv-blog-details">

                        <div class="socialv-blog-meta">
                            <!-- category start -->
                            <ul class="list-inline">
                                <li class="posted-on">
                                    <!-- date is here -->
                                    <span class="list-inline-item blog-date">
                                        <?php echo sprintf("%s", iqonic_blog_time_link()); ?>
                                    </span>
                                    <!-- date -->
                                </li>
                                <?php
                                $postcat = get_the_category();
                                if ($postcat) {
                                    $category = "";
                                    foreach ($postcat as $cat) {
                                        $category .= '<li class="widget_categories">
                                                <a href="' . get_category_link($cat->cat_ID) . '">
                                                ' . $cat->name . '  </a>
                                            </li>';
                                    }
                                    echo wp_kses($category, ["li" => ["class" => true], "a" => ["class" => true, "href" => true]]);
                                }
                                ?>
                            </ul>
                            <!-- category end -->
                        </div>

                        <div class="blog-content">
                            <!-- Title Start -->
                            <a class="socialv-post-title" href="<?php echo sprintf("%s", esc_url(get_permalink($wp_query->ID))); ?>">
                                <<?php echo esc_attr($settings['title_tag']); ?> class="socialv-heading-title">
                                    <?php echo sprintf("%s", get_the_title($wp_query->ID)); ?>
                                </<?php echo esc_attr($settings['title_tag']); ?>>
                            </a>
                            <!-- Title End -->

                            <!-- excerpt start-->
                            <?php if ($settings['display_excerpt'] == "yes") { ?>
                                <p class="socialv-post-desc"><?php echo sprintf("%s", get_the_excerpt($wp_query->ID)); ?></p>
                            <?php } ?>
                            <!-- excerpt end -->
                        </div>

                        <div class="blog-author-wrapper">
                            <div class="author">
                                <?php esc_html_e('By:', IQONIC_EXTENSION_TEXT_DOMAIN); ?> <?php the_author_link(); ?>
                            </div>

                            <?php
                            global $wpdb;
                            $postid = get_the_id();
                            $table_name = $wpdb->prefix . 'postmeta';
                            $query = $wpdb->get_results("SELECT meta_value FROM `$table_name` WHERE ( meta_key = '_socialv_posts_liked_users' AND post_id = '" . get_the_id() . "' )");
                            $stack = array();

                            if ($query) {
                                foreach ($query as $word) {
                                    $meta = strip_tags($word->meta_value);
                                    $meta = explode(' ', $meta);
                                    if (!empty($meta[0])) {
                                        array_push($stack, $meta);
                                    }
                                }
                            }

                            if (isset($meta_key) && $meta_key == "_socialv_posts_liked_users" && update_post_meta($activity_id, $meta_key, implode(", ", $post_array), $currentvalue)) {
                                $args = array("has_activity" => $this->is_socialv_user_likes($user_id, $meta_key), "status" => false);
                            }

                            ?>

                            <div class="list">
                                <ul>
                                    <li>
                                        <span class="likes">
                                            <?php if (socialv()->is_socialv_user_likes(get_the_ID(), "_socialv_posts_liked_users")) : ?>
                                                <a href="javascript:void(0)" class="socialv-user-activity-btn has-socialv-post added" data-id="<?php echo get_the_ID(); ?>">
                                                    <?php echo apply_filters("socialv_undo_like_icon", '<i class="iconly-Heart icbo"></i>'); ?>
                                                    <span><?php echo socialv()->socialv_blog_total_user_likes(get_the_ID(), "_socialv_posts_liked_users"); ?></span>
                                                </a>
                                            <?php else : ?>
                                                <a href="javascript:void(0)" class="socialv-user-activity-btn has-socialv-post" data-id="<?php echo get_the_ID(); ?>">
                                                    <?php echo apply_filters("socialv_like_icon", '<i class="iconly-Heart icli"></i>'); ?>
                                                    <span><?php echo socialv()->socialv_blog_total_user_likes(get_the_ID(), "_socialv_posts_liked_users"); ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="comments">
                                            <i class="iconly-Chat icli"></i><span><?php echo get_comments_number($post->ID); ?></span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- blog details main end -->
                </div>
              
            </div>
    <?php
        }
    }
    wp_reset_postdata();
    echo '</div>';

    ?>
</div>
<?php
// pagination
if (isset($settings['show_pagination']) && $settings['show_pagination'] == 'yes') {

    $total_pages = $wp_query->max_num_pages;

    if ($total_pages > 1) {
        $current_page = max(1, get_query_var('paged'));
        echo paginate_links(array(
            'format'    => '/page/%#%',
            'current'   => $current_page,
            'total'     => $total_pages,
            'type'            => 'list',
            'prev_text'       => '<i class="iconly-Arrow-Left icli"></i>',
            'next_text'       => '<i class="iconly-Arrow-Right icli"></i>'
        ));
    }
}
