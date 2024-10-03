<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;
$settings = $this->get_settings_for_display();
if ($settings['img_list']) {
    $nav_next  =  $nav_prev =  $pagination  = '';
    $id = rand(10, 100);
    if ($settings['want_nav'] == "true" || $settings['want_pagination'] == "true") {
        $nav_next   = 'navnext_' . $id;
        $nav_prev   = 'navprev_' . $id;
        $pagination = 'pagination_' . $id;
    }
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
    if ($settings['select_style'] == 'style2') {
        if (!empty($settings['center_image']['url'])) { ?>
            <div class="image-box-bg">
                <img src="<?php echo esc_url($settings['center_image']['url']); ?>" alt="<?php esc_attr_e('Image', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" loading="lazy" />
            </div>
        <?php } ?>
        <div class="socialv-image-box-slider">
        <?php } ?>
        <div class="socialv-imagebox socialv-<?php echo esc_attr($settings['select_style']); ?>">
            <div class="socialv-image-box socialv-widget-swiper" <?php echo $this->get_render_attribute_string('slider') ?>>
                <ul class="list-inline m-0 swiper-wrapper">
                    <?php foreach ($settings['img_list'] as $item) {
                        if ($item['button_action'] == 'link') {
                            if ($item['link_type'] == 'dynamic') {
                                $url = get_permalink(get_page_by_path($item['dynamic_link']));
                                $this->add_render_attribute('socialv_class', 'href', esc_url($url));
                                if ($item['use_new_window'] == 'yes') {
                                    $this->add_render_attribute('socialv_class', 'target', '_blank');
                                }
                            } else {
                                if ($item['link']['url']) {
                                    $url = $item['link']['url'];
                                    $this->add_render_attribute('socialv_class', 'href', esc_url($url));

                                    if ($item['link']['is_external']) {
                                        $this->add_render_attribute('socialv_class', 'target', '_blank');
                                    }

                                    if ($item['link']['nofollow']) {
                                        $this->add_render_attribute('socialv_class', 'rel', 'nofollow');
                                    }
                                }
                            }

                            $url = '';
                        }
                    ?>
                        <li class="swiper-slide">
                            <div class="<?php if ($settings['select_style'] == 'style2') {
                                            echo esc_attr('scroll-img', IQONIC_EXTENSION_TEXT_DOMAIN);
                                        } ?> socialv-image-box-data">
                                <?php if (!empty($item['image']['url'])) : ?>
                                    <div class="image-box">
                                        <?php if ($item['button_action'] == 'link') { ?><a <?php echo $this->get_render_attribute_string('socialv_class') ?>><?php } ?>
                                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php esc_attr_e('Image', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" loading="lazy" />
                                            <?php if ($item['button_action'] == 'link') { ?> </a> <?php } ?>
                                    </div>
                                <?php endif; ?>
                                <div class="images-data-info">
                                    <?php if ($item['button_action'] == 'link') { ?> <a <?php echo $this->get_render_attribute_string('socialv_class') ?>><?php } ?><?php if (!empty($item['title_text'])) : ?><<?php echo wp_kses($settings['title_size'], 'post') ?> class="title"><?php echo esc_html($item['title_text']); ?> </<?php echo $settings['title_size'] ?>><?php endif; ?><?php if ($item['button_action'] == 'link') { ?></a><?php } ?>
                                    <?php if (!empty($item['description_text'])) : ?><p class="desc"><?php echo esc_html(sprintf(_x('%s', 'description_text', IQONIC_EXTENSION_TEXT_DOMAIN), $item['description_text'])); ?></p><?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
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
            } ?>
        </div>
        <?php if ($settings['select_style'] == 'style2') { ?>
        </div>
<?php }
    }
