<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

$settings = $this->get_settings_for_display();
$settings = $this->get_settings();

/* Swiper dynamic id */
$nav_next = $nav_prev = $pagination = $id = '';
$id = rand(10, 100);

$this->add_render_attribute('slider', 'data-loop', $settings['sw_loop']);
$this->add_render_attribute('slider', 'data-spacebtslide', $settings['sw_space_slide']);
$this->add_render_attribute('slider', 'data-navnext', $nav_next);
$this->add_render_attribute('slider', 'data-navprev', $nav_prev);
$this->add_render_attribute('slider', 'data-pagination', $pagination);
?>

<div class="socialv-testimonial" <?php echo $this->get_render_attribute_string('slider') ?>>
    <div class="testimonial-slider">
        <div class="swiper-container content-slider">
            <div class="swiper-wrapper">
                <?php
                if ($settings['testi_list']) {
                    foreach ($settings['testi_list'] as $item) { ?>
                        <div class="swiper-slide">
                            <?php if (!empty($item['description'])) { ?>
                                <div class="socialv-testimonial-user testimonial-message">
                                    <?php echo wp_kses_post($item['description']); ?>
                                </div>
                            <?php } ?>
                        </div>
                <?php
                    }
                } ?>
            </div>
        </div>
    </div>
    <div class="swiper-container user-thumbs">
        <div class="swiper-wrapper">
            <?php
            if ($settings['testi_list']) {
                foreach ($settings['testi_list'] as $item) { ?>
                    <div class="swiper-slide">
                        <div class="author-details">
                            <div class="testimonial-slider-img">
                                <?php echo sprintf('<img src="%1$s"  alt="' . esc_attr__('iqonic-user', IQONIC_EXTENSION_TEXT_DOMAIN) . '"/>', esc_url($item['testi_image']['url'])); ?>
                            </div>
                            <div class="socialv-lead">
                                <!-- TITLE START -->
                                <<?php echo esc_attr($settings['title_tag']);  ?> class="socialv-testi-title">
                                    <?php echo esc_html(sprintf(_x('%s', 'testi_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['testi_title']));  ?>
                                </<?php echo esc_attr($settings['title_tag']); ?>>
                                <!-- TITLE END -->
                                <!-- DESIGNATION START -->
                                <?php if ($item['testi_designation']) { ?>
                                    <span class="socialv-testi-designation"><?php echo esc_html(sprintf(_x('%s', 'testi_designation', IQONIC_EXTENSION_TEXT_DOMAIN), $item['testi_designation'])); ?></span>
                                <?php  } ?>
                                <?php if ($item['testi_company']) { ?>
                                    <span class="socialv-testi-company"><?php echo esc_html(sprintf(_x('%s', 'testi_company', IQONIC_EXTENSION_TEXT_DOMAIN), $item['testi_company'])); ?></span>
                                <?php  } ?>
                                <!-- DESIGNATION END -->
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <?php if ($settings['want_scrollbar'] == 'true') {  ?>
        <div class="swiper-scrollbar"></div>
    <?php } ?>

    <?php if ($settings['want_pagination'] == 'true') {  ?>
        <div class="swiper-pagination"></div>
    <?php } ?>

</div>

<!-- Navigation start -->
<?php if ($settings['want_nav'] == "true") { ?>
    <div class="iqonic-navigation">
        <div class="swiper-button-prev">
            <span class="text-btn">
                <i class="iconly-Arrow-Left-2 icli"></i>

            </span>
        </div>
        <div class="swiper-button-next">
            <span class="text-btn">
                <i class="iconly-Arrow-Right-2 icli"></i>
            </span>
        </div>
    </div>
<?php } ?>
<!-- Navigation end -->