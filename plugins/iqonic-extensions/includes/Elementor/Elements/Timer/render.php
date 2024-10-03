<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;
$settings = $this->get_settings_for_display();
$settings = $this->get_settings();
$align = $settings['align'];

$this->add_render_attribute('render_attribute', 'data-date', esc_attr($settings['future_date']));

if ($settings['show_label']) {
    $label = "true";
} else {
    $label = "false";
}

$this->add_render_attribute('render_attribute', 'data-labels', esc_attr($label));
$this->add_render_attribute('render_attribute', 'data-format', esc_attr($settings['timer_format']));

//echo '---',$label;

?>

<div class="iq-count-down <?php echo esc_attr($align); ?>">
    <!-- TITLE START -->
    <?php if ($settings['timer_title']) { ?>
        <<?php echo esc_attr($settings['title_tag']); ?> class="socialv-title socialv-heading-title">
            <?php echo esc_html__($settings['timer_title']); ?>
        </<?php echo esc_attr($settings['title_tag']); ?>>
    <?php  } ?>
    <!-- TITLE END-->
    <span class="iq-data-countdown-timer" <?php echo $this->get_render_attribute_string('render_attribute'); ?>></span>
</div>