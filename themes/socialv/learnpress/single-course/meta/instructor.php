<?php

/**
 * Template for displaying course instructor in primary-meta section.
 *
 * @version 4.0.1
 * @author  ThimPress
 * @package LearnPress/Templates
 */

defined('ABSPATH') || exit;

$course = learn_press_get_course();
if (!$course) {
	return;
}
$instructor = $course->get_instructor();
$user_id = $instructor->get_id();
?>

<div class="meta-item meta-item-instructor">
	<div class="meta-item__image">
		<?php echo wp_kses_post($course->get_instructor()->get_profile_picture()); ?>
	</div>
	<div class="meta-item__value">
		<label><?php esc_html_e('Created By', 'socialv'); ?></label>
		<div><?php echo get_the_author_meta('display_name', $user_id); ?></div>
	</div>
</div>