<?php

namespace Elementor;

use function SocialV\Utility\socialv;

use Elementor\Plugin;

if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();
$align = '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$cat = $nav_next = $nav_prev = $pagination = $id = '';
if (isset($settings['blog_cat']) && !empty($settings['blog_cat'])) {
    $cat = implode(',', $settings['blog_cat']);
}

$args = array(
    'post_type'         => 'post',
    'post_status'       => array('private','publish'),
    'paged'             => $paged,
    'category_name'     => $cat,
    'order'             => $settings['order'],
    'suppress_filters'  => 0,
    'posts_per_page'    => $settings['posts_per_page']
);

$align = 'blog-widget ';
$align .= $settings['align'];

/* Random id Genrate For Swiper Slider */
$id = rand(10, 100);
$nav_next  =  $nav_prev =  $pagination  = '';
if ($settings['want_nav'] == "true" || $settings['want_pagination'] == "true") {
    $nav_next   = 'navnext_' . $id;
    $nav_prev   = 'navprev_' . $id;
    $pagination = 'pagination_' . $id;
}

global $post;

$wp_query = new \WP_Query($args); ?>

<div class="<?php echo esc_attr(trim($align)); ?>">
    <?php
    $this->add_render_attribute('slider', 'data-slide', $settings['sw_slide']);
    $this->add_render_attribute('slider', 'data-loop', $settings['sw_loop']);
    $this->add_render_attribute('slider', 'data-speed', $settings['sw_speed']);
    $this->add_render_attribute('slider', 'data-spacebtslide', $settings['sw_space_slide']);
    $this->add_render_attribute('slider', 'data-autoplay', $settings['sw_autoplay']);
    $this->add_render_attribute('slider', 'data-laptop', $settings['sw_laptop_no']);
    $this->add_render_attribute('slider', 'data-tab', $settings['sw_tab_no']);
    $this->add_render_attribute('slider', 'data-mobile', $settings['sw_mob_no']);
    $this->add_render_attribute('slider', 'data-navnext', $nav_next);
    $this->add_render_attribute('slider', 'data-navprev', $nav_prev);
    $this->add_render_attribute('slider', 'data-pagination', $pagination);
    ?>

    <div class="socialv-widget-swiper" <?php echo $this->get_render_attribute_string('slider'); ?>>
        <!-- slider start -->
        <div class="swiper-wrapper">
            <?php
            if ($wp_query->have_posts()) {
                while ($wp_query->have_posts()) {
                    $wp_query->the_post();
                    $thumb_id = get_post_thumbnail_id(get_the_ID());
                    $srcset = wp_get_attachment_image_srcset($thumb_id);
            ?>
                    <div class="swiper-slide socialv-blog-box">

                        <!-- blog image start -->
                        <?php if (has_post_thumbnail() && $settings['display_thumbnail'] == 'yes') { ?>
                            <div class="socialv-blog-image">
                                <a class="socialv-blog-link" href="<?php echo sprintf("%s", esc_url(get_permalink($wp_query->ID))); ?>">
                                    <?php echo wp_get_attachment_image($thumb_id, $settings["image_size"], false, ['srcset' => $srcset]); ?>
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
                                <div class="list">
                                    <ul>
                                        <li>
                                            <span class="likes">
                                                <?php if (socialv()->is_socialv_user_likes(get_the_ID(), "_socialv_posts_liked_users")) : ?>
                                                    <a href="javascript:void(0)" class="socialv-user-activity-btn has-socialv-post added" data-id="<?php echo get_the_ID(); ?>">
                                                        <?php echo apply_filters("socialv_undo_like_icon", '<i class="iconly-Heart icbo"></i>'); ?>
                                                    </a>
                                                    <span><?php echo socialv()->is_socialv_user_likes(get_the_ID(), "_socialv_posts_liked_users"); ?></span>
                                                <?php else : ?>
                                                    <a href="javascript:void(0)" class="socialv-user-activity-btn has-socialv-post" data-id="<?php echo get_the_ID(); ?>">
                                                        <?php echo apply_filters("socialv_like_icon", '<i class="iconly-Heart icli"></i>'); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="comments">
                                                <i class="iconly-Chat icli"></i>
                                                <span><?php echo get_comments_number($post->ID); ?></span>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <!-- blog details main end -->
                    </div>
            <?php
                }
            }
            wp_reset_postdata(); ?>
        </div>
        <!-- slider end -->
    </div>

    <!-- Navigation start -->
    <?php if ($settings['want_nav'] == "true") { ?>
        <div class="iqonic-navigation">
            <div class="swiper-button-prev" id="<?php echo esc_attr($nav_prev); ?>">
                <span class="text-btn">
                    <i class="iconly-Arrow-Left-2 icli"></i>
                </span>
            </div>
            <div class="swiper-button-next" id="<?php echo esc_attr($nav_next); ?>">
                <span class="text-btn">
                    <i class="iconly-Arrow-Right-2 icli"></i>
                </span>
            </div>
        </div>
    <?php
    }
    // -- Navigation end -->
    // -- Pagination start -->
    if ($settings['want_pagination'] == "true") {
    ?>
        <div class="swiper-pagination css-prefix-pagination-align" id="<?php echo esc_attr($pagination); ?>"></div>
    <?php
    }
    // -- Pagination end -->
    ?>
</div>