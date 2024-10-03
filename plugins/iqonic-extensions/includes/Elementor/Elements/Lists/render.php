<?php
namespace Elementor;

if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();

$tabs = $this->get_settings_for_display('tabs');
$align = ' iq-' . $settings['list_column'] . '-column';
?>
<div class="iq-list <?php echo esc_attr($align); ?>">
    <?php

    if ($settings['list_style'] == 'unorder') {

    ?>
        <ul class="iq-unoreder-list">
            <?php
            foreach ($tabs as $index => $item) {
            ?>
                <li>
                    <?php echo esc_html(sprintf(_x('%s', 'tab_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['tab_title'])); ?>
                </li>

            <?php  }
            ?>
        </ul>

    <?php }
    if ($settings['list_style'] == 'order') {
    ?>
        <ol class="iq-order-list">
            <?php
            foreach ($tabs as $index => $item) {
                ?>
                <li>
                    <?php echo esc_html(sprintf(_x('%s', 'tab_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['tab_title'])); ?>
                </li>

            <?php  }
            ?>
        </ol>

    <?php }
    if ($settings['list_style'] == 'icon') {
          ?>
        <ul class="iq-list-with-icon">
            <?php
            foreach ($tabs as $index => $item) {
                  ?>
                <li>
                    <span class="list-icon"> <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?></span>
                    <span class="list-text"> <?php echo esc_html(sprintf(_x('%s', 'tab_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['tab_title'])); ?></span>
                </li>
                  <?php  
            }
               ?>
        </ul>
        <?php
    }
    if ($settings['list_style'] == 'image') {
        ?>
        <ul class="iq-list-with-img">
            <?php
            foreach ($tabs as $index => $item) {
            ?>
                <li>
                    <img src="<?php echo esc_url($settings['image']['url']); ?>">
                    <?php echo esc_html(sprintf(_x('%s', 'tab_title', IQONIC_EXTENSION_TEXT_DOMAIN), $item['tab_title'])); ?>
                </li>

            <?php  }
            ?>
        </ul>

    <?php } ?>
</div>