<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

$html = '';
$icon = '';

$tabs = $this->get_settings_for_display('tabs');
$settings = $this->get_settings();

$this->add_render_attribute('socialv_class', 'class', 'socialv-accordion');
$this->add_render_attribute('socialv_class', 'class', 'socialv-accordion-square');


if ($settings['iqonic_has_box_shadow'] == 'yes') {

    $this->add_render_attribute('socialv_class', 'class', 'socialv-box-shadow');
}

if ($settings['title_back_active_color'] != $settings['content_back_color']) {
    $this->add_render_attribute('socialv_class', 'class', 'socialv-accordion-classic');
}

?>
<div <?php echo $this->get_render_attribute_string('socialv_class'); ?>>
    <?php
    $has_active = '';
    foreach ($tabs as $index => $item) {
        if ($item['has_active'] == 'yes') {
            $show = "show";
            $active = "socialv-active";
        } else {
            $show = "";
            $active = "";
        }
    ?>
        <div class="socialv-accordion-block <?php echo esc_attr($active);  ?>">
            <div class="socialv-accordion-title">
                <div class="socialv-accordion-title-info">
                    <?php if (!empty($item['tab_title'])) { ?>
                        <<?php echo $settings['title_tag']; ?> class="mb-0 accordion-title socialv-heading-title">
                            <?php echo esc_html(sprintf(_x('%s', 'tab_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['tab_title'])); ?>
                        </<?php echo $settings['title_tag']; ?>>
                    <?php } ?>
                </div>
                <?php if ($settings['has_icon'] == 'yes') { ?>
                    <div class="socialv-icon-style socialv-icon-right">
                        <span class="active">
                            <?php echo ($settings['active_icon']) ? get_render_icon($settings['active_icon']) : ''; ?>
                        </span><span class="inactive">
                            <?php echo ($settings['inactive_icon']) ? get_render_icon($settings['inactive_icon']) : ''; ?>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <div class="socialv-accordion-details">
                <p class="socialv-content-text"> <?php echo $this->parse_text_editor($item['tab_content']); ?> </p>
            </div>
        </div>
    <?php 
    } ?>

</div>