<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();
$align = $settings['align'];
if (empty($align)) {
	$align .= 'left';
}

$this->add_render_attribute('socialv-section', 'class', $align);
$this->add_render_attribute('socialv-section', 'class', 'socialv-title-box');  ?>
<?php
// title link static & dynamic
if ($settings['title_action'] == 'yes') {
	if ($settings['link_type'] == 'dynamic') {
		$url = get_permalink(get_page_by_path($settings['dynamic_link']));
		$this->add_render_attribute('socialv-image-link', 'href', esc_url($url));
	} else {
		if ($settings['link']['url']) {
			$url = $settings['link']['url'];
			$this->add_render_attribute('socialv-image-link', 'href', esc_url($url));

			if ($settings['link']['is_external']) {
				$this->add_render_attribute('socialv-image-link', 'target', '_blank');
			}

			if ($settings['link']['nofollow']) {
				$this->add_render_attribute('socialv-image-link', 'rel', 'nofollow');
			}
		}
	}
}
?>

<div <?php echo $this->get_render_attribute_string('socialv-section'); ?>>

	<!-- before subtitle start -->
	<?php if (!empty(trim($settings['sub_title'])) && $settings['sub_title_position'] == 'before') { ?>
		<div class="socialv-subtitle-wrap socialv-subtitle-before">
			<?php echo sprintf('<h6 class="socialv-subtitle">%1$s</h6>', esc_html($settings['sub_title'])); ?>
		</div>
	<?php } ?>
	<!-- before subtitle end -->

	<!-- title start -->
	<?php if (isset($url)) : ?>
		<a <?php echo $this->get_render_attribute_string('socialv-image-link'); ?>>
		<?php endif; ?>
		<?php if (!empty(trim($settings['section_title']))) { ?>
			<<?php echo $settings['title_tag'];  ?> class="socialv-title socialv-heading-title">
				<?php echo wp_kses($settings['section_title'], 'post'); ?>
			</<?php echo $settings['title_tag']; ?>>
		<?php } ?>
		<?php if (isset($url)) : ?>
		</a>
	<?php endif; ?>
	<!-- title end -->

	<!-- after subtitle start -->
	<?php if (!empty(trim($settings['sub_title'])) && $settings['sub_title_position'] == 'after') { ?>
		<div class="socialv-subtitle-wrap socialv-subtitle-after">
			<?php echo sprintf('<h6 class="socialv-subtitle">%1$s</h6>', esc_html($settings['sub_title'])); ?>
		</div>
	<?php  } ?>
	<!-- after subtitle end -->

	<!-- description start -->
	<?php
	if (!empty($settings['description']) && $settings['has_description'] == 'yes') {
		echo sprintf('<div class="socialv-title-desc">%1$s</div>', $this->parse_text_editor($settings['description']));
	} ?>
	<!-- description end -->
</div>