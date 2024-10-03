<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;
$image_html = "";
$settings = $this->get_settings();

if ($settings['media_style'] == 'image') {
    if (!empty($settings['image']['url'])) {
        $this->add_render_attribute('image', 'src', $settings['image']['url']);
        $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['image']));
        $this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['image']));
        $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image');
    }
}

if ($settings['icon_button_action'] == 'yes') {
    if ($settings['icon_link_type'] == 'dynamic') {
        $url = get_permalink(get_page_by_path($settings['icon_dynamic_link']));
        $this->add_render_attribute('socialv_class', 'href', esc_url($url));
        if ($settings['icon_use_new_window'] == 'yes') {
            $this->add_render_attribute('socialv_class', 'target', '_blank');
        }
    } else {
        if ($settings['icon_link']['url']) {
            $url = $settings['icon_link']['url'];
            $this->add_render_attribute('socialv_class', 'href', esc_url($url));

            if ($settings['icon_link']['is_external']) {
                $this->add_render_attribute('socialv_class', 'target', '_blank');
            }

            if ($settings['icon_link']['nofollow']) {
                $this->add_render_attribute('socialv_class', 'rel', 'nofollow');
            }
        }
    }

    $url = '';
}

?>
<div class="socialv-icon-box socialv-<?php echo esc_attr($settings['design_style']);
                                            echo ($settings['mainbox_hover'] == 'yes') ? ' on-hover' : ''; ?>">
    <?php if ($settings['icon_button_action'] == 'yes') { ?><a <?php echo $this->get_render_attribute_string('socialv_class') ?>><?php } ?>
        <div class="socialv-icon-image">
            <?php
            if ($settings['media_style'] == 'image') {
                echo $image_html;
            } elseif ($settings['media_style'] == 'icon') {
                Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
            } 
            ?>
        </div>
        <?php if ($settings['icon_button_action'] == 'yes') { ?>
        </a> <?php } ?>

    <div class="socialv-iconbox-info">
        <div class="socialv-title">
            <!-- title start -->
            <?php if ($settings['title']) { ?>
                <?php if ($settings['icon_button_action'] == 'yes') { ?><a <?php echo $this->get_render_attribute_string('socialv_class') ?>><?php } ?>
                    <<?php echo $settings['title_tag'] ?> class="socialv-heading-title">
                        <?php echo sprintf('%1$s', esc_html($settings['title'])); ?>
                    </<?php echo $settings['title_tag'] ?>>
                    <?php if ($settings['icon_button_action'] == 'yes') { ?>
                    </a> <?php } ?>
            <?php } ?>
            <!-- title end -->
        </div>

        <!-- description start -->
        <?php if (!empty($settings['description'])) {  ?>
            <div class="socialv-description">
                <p class="socialv-iconbox-description"><?php echo wp_kses($settings['description'], 'post'); ?></p>
            </div>
        <?php  }  ?>
        <!-- description end -->
        <?php
        if (!empty($settings['button_text']) && $settings['show_button'] == 'yes') {
            require IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Elements/Button/render.php';
        } ?>
    </div>
</div>