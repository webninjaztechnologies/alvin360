<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;
$settings = $this->get_settings_for_display();
$fallback_defaults = [
    'icon-facebook',
    'icon-twitter-2',
    'fa fa-google-plus',
];

$class_animation = $social = $align = '';

if (!empty($settings['hover_animation'])) {
    $class_animation = ' elementor-animation-' . $settings['hover_animation'];
}
$align = 'socialv-social-share';
if ($settings['layout'] == 'names') {
    $align .= ' socialv-social-names';
} else if ($settings['layout'] == 'icons_names') {
    $align .= ' socialv-social-icons-names';
} else {
    $align .= ' socialv-social-icons';
}
$migration_allowed = Icons_Manager::is_migration_allowed();

?>
<div class="<?php echo esc_attr($align); ?>">
    <?php
    foreach ($settings['social_icon_list'] as $index => $item) {
        $migrated = isset($item['__fa4_migrated']['social_icon']);
        $is_new = empty($item['social']) && $migration_allowed;

        // add old default
        if (empty($item['social']) && !$migration_allowed) {
            $item['social'] = isset($fallback_defaults[$index]) ? $fallback_defaults[$index] : 'fa fa-wordpress';
        }

        if (!empty($item['social'])) {
            $social = str_replace('fa fa-', '', $item['social']);
        }

        if (($is_new || $migrated) && 'svg' !== $item['social_icon']['library']) {
            $social = explode(' ', $item['social_icon']['value'], 2);
            if (empty($social[1])) {
                $social = '';
            } else {
                $social = str_replace('fa-', '', $social[1]);
            }
        }
        if ('svg' === $item['social_icon']['library']) {
            $social = get_post_meta($item['social_icon']['value']['id'], '_wp_attachment_image_alt', true);
        }

        $link_key = 'link_' . $index;

        $this->add_render_attribute($link_key, 'class', [
            'socialv-share',
            'elementor-social-icon-' . $social . $class_animation,
        ]);

        $this->add_link_attributes($link_key, $item['link']);
    ?>
        <div class="socialv-social-item">
            <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                <?php
                if ($settings['layout'] != 'names') {
                    if ($is_new || $migrated) {
                        Icons_Manager::render_icon($item['social_icon']);
                    } else { ?>
                        <i class="<?php echo esc_attr($item['social']); ?>"></i>
                <?php }
                } ?>
                <?php if ($settings['layout'] != 'default') { ?>
                    <?php echo esc_html(sprintf(_x('%s', 'social_text', IQONIC_EXTENSION_TEXT_DOMAIN), $item['social_text'])); ?>
                <?php }  ?>
            </a>
        </div>
    <?php } ?>
</div>