<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

$html = $align = $icon_position = $icon = '';

$settings = $this->get_settings_for_display();
$settings = $this->get_settings();

if (empty($settings['align_button'])) {
    $align = 'left';
}

$align = $settings['align_button'];

$this->add_render_attribute('socialv_container', 'class', 'socialv-button-container');

if ($settings['button_style'] === "style-one") {
    $this->add_render_attribute('socialv_class', 'class', 'socialv-button socialv-button-link');
} else {
    if ($settings['button_size'] == 'normal') {
        $this->add_render_attribute('socialv_class', 'class', 'btn');
    } elseif ($settings['button_size'] == 'small') {
        $this->add_render_attribute('socialv_class', 'class', 'btn-sm');
    }
    $this->add_render_attribute('socialv_class', 'class', $settings['button_types']);
}

$html .= '' . esc_html(sprintf(_x('%s', 'button_text', IQONIC_EXTENSION_TEXT_DOMAIN), $settings['button_text'])) . '';

if ($settings['button_action'] == 'link') {
    if ($settings['link_type'] == 'dynamic') {
        $url = get_permalink(get_page_by_path($settings['dynamic_link']));
        $this->add_render_attribute('socialv_class', 'href', esc_url($url));
        if ($settings['use_new_window'] == 'yes') {
            $this->add_render_attribute('socialv_class', 'target', '_blank');
        }
    } else {
        if ($settings['link']['url']) {
            $url = $settings['link']['url'];
            $this->add_render_attribute('socialv_class', 'href', esc_url($url));

            if ($settings['link']['is_external']) {
                $this->add_render_attribute('socialv_class', 'target', '_blank');
            }

            if ($settings['link']['nofollow']) {
                $this->add_render_attribute('socialv_class', 'rel', 'nofollow');
            }
        }
    }

    $url = '';
}

$modalid = '';
if ($settings['button_action'] == 'popup') {
    $modalid = 'mymodal' . rand(10, 1000);

    $this->add_render_attribute('socialv_class', 'data-bs-toggle', 'modal');
    $this->add_render_attribute('socialv_class', 'data-bs-target', '#' . $modalid);
    $this->add_render_attribute('socialv_class', 'href', '#' . $modalid);
} ?>

<div <?php echo $this->get_render_attribute_string('socialv_container') ?>>
    <a <?php echo $this->get_render_attribute_string('socialv_class') ?>>
        <?php echo $html; ?>
    </a>
</div>
<?php
if ($settings['button_action'] == 'popup') {
    $icon = sprintf('<i aria-hidden="true" class="%1$s"></i>', esc_attr($settings['model_selected_icon']['value']));

?>
    <div class="socialv-modal">
        <div class="modal fade" id="<?php echo esc_attr($modalid); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">
                            <?php echo esc_html(sprintf(_x('%s', 'model_title', IQONIC_EXTENSION_TEXT_DOMAIN), $settings['model_title'])); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><?php echo $icon; ?></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $this->parse_text_editor($settings['model_body']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
