<?php
/**
 * The template for the panel footer area.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author        Redux Framework
 * @package       ReduxFramework/Templates
 * @version:      4.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

echo '<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>
<div class="redux-footer-sticky-wrap">
    <div id="redux-footer-sticky">
        <div id="redux-footer">';
if (isset($this->parent->args['share_icons'])) {
    $skip_icons = false;

    if (!$this->parent->args['dev_mode'] && $this->parent->args_class->omit_icons) {
        $skip_icons = true;
    }
    echo '<div id="redux-share">';
    foreach ($this->parent->args['share_icons'] as $links) {
        if ($skip_icons) {
            continue;
        }
        // SHIM, use URL now.
        if (isset($links['link']) && !empty($links['link'])) {
            $links['url'] = $links['link'];
            unset($links['link']);
        }
        if (isset($links['icon']) && !empty($links['icon'])) {
            if (strpos($links['icon'], 'el-icon') !== false && strpos($links['icon'], 'el ') === false) {
                $links['icon'] = 'el ' . $links['icon'];
            }
        }
        echo '<a href="' . esc_url($links['url']) . '" title="' . esc_attr($links['title']) . '" target="_blank">';
        if (isset($links['icon']) && !empty($links['icon'])) {
            echo '<i class="' . esc_attr($links['icon']) . '"></i>';
        } else {
            echo '<img src="' . esc_url($links['img']) . '"/>';
        }
        echo '</a>';
    }
    echo '</div>';
}
echo '<div class="redux-action_bar">
        <span class="spinner"></span>';
if (false === $this->parent->args['hide_save']) {
    submit_button(esc_html__('Save Changes', IQONIC_REACTION_TEXT_DOMAIN), 'primary', 'redux_save', false, array('id' => 'redux_bottom_save'));
}
if (false === $this->parent->args['hide_reset']) {
    submit_button(esc_html__('Reset Section', IQONIC_REACTION_TEXT_DOMAIN), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array('id' => 'redux-defaults-section-bottom'));
    submit_button(esc_html__('Reset All', IQONIC_REACTION_TEXT_DOMAIN), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array('id' => 'redux-defaults-bottom'));
}
echo '</div>
        <div class="redux-ajax-loading" alt="' . esc_attr__('Working...', IQONIC_REACTION_TEXT_DOMAIN) . '">&nbsp;</div>
        <div class="clear"></div>
    </div>
</div>';
