<?php

/**
 * The file that renders the content of the Report Custom Post Type metabox.
 *
 * @link       https://iqonic.design
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}

/**
 * Are we on the add new post screen?
 *
 * @since 2.0
 */
function imt_is_add_screen()
{
    global  $pagenow;
    if ('post-new.php' === $pagenow) {
        return true;
    }
}

function imt_get_username($id)
{
    $user_info = get_userdata($id);
    $user_name = $user_info->display_name;
    return $user_name;
}

update_post_meta(get_the_id(), 'is_read', 1);

echo '<div class="metabox-panel-wrap"><div id="" class="panel imt-metabox imt-no-tabs">';
echo '<fieldset class="field-wrap imt_member_reported_field">';
echo '<div class="field-label">' . ($post->imt_activity_type == "group" ? __('Group Reported', IQONIC_MODERATION_TEXT_DOMAIN) : __('Member Reported', IQONIC_MODERATION_TEXT_DOMAIN)) . '</div>';
if (imt_is_add_screen() || empty($post->imt_member_reported)) {
    echo '<div>' . esc_html("Enter ID of user/group to report:", IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '<input style="width: 75px;" id="imt_member_reported" name="imt_member_reported" type="text" value="">';
} else {
    echo '<div class="userid-input-label">';
    if ($post->imt_activity_type == "group") {
        $group = groups_get_group($post->imt_member_reported);
        echo bp_get_group_avatar('type=full&width=100&height=100', $group);
        echo '<div class="userid-input-label-username">';
        echo '<a href="' . esc_url(bp_get_group_url($group)) . '">';
        echo $group->name . ' <span class="dashicons dashicons-external"></span></a></div>';
    } else {
        echo bp_core_fetch_avatar(['item_id' => $post->imt_member_reported, 'type' => 'full', 'width' => 100, 'height' => 100]);
        echo '<div class="userid-input-label-username">';
        echo '<a href="' . esc_url(get_edit_user_link($post->imt_member_reported)) . '">';
        echo imt_get_username($post->imt_member_reported) . ' <span class="dashicons dashicons-external"></span></a></div>';
    }
    echo '</div>';
}
echo '</fieldset>';

// imt_reported_by_field
echo '<fieldset class="field-wrap imt_reported_by_field">';
echo '<div class="field-label">' . esc_html('Reported By', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
if (imt_is_add_screen() || empty($post->imt_reported_by)) {
    $user = wp_get_current_user();
    echo '<div class="userid-input-label">';
    echo bp_core_fetch_avatar(['item_id' => $user->ID, 'width' => 100, 'height' => 100]);
    echo '<div class="userid-input-label-username">';
    echo $user->display_name . '</div></div>';
    echo '<input name="imt_reported_by" type="hidden" value="' . get_current_user_id() . '">';
} else {
    echo '<div class="userid-input-label">';
    echo bp_core_fetch_avatar(['item_id' => $post->imt_reported_by, 'type' => 'full', 'width' => 100, 'height' => 100]);
    echo '<div class="userid-input-label-username">';
    echo '<a href="' . esc_url(get_edit_user_link($post->imt_reported_by)) . '">';
    echo imt_get_username($post->imt_reported_by).' <span class="dashicons dashicons-external"></span></a></div>';
    echo '</div>';
   
    
}
echo '</fieldset>';

// imt_link_field
if (!imt_is_add_screen() && $post->imt_link) {
    echo '<fieldset class="field-wrap imt_link_field">';
    echo '<div class="field-label">' . esc_html('Link to Reported Content', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '<div class="field-discription-wrap">';
    $link = $post->imt_link ? esc_url($post->imt_link) : '';
    echo '<a href="' . $link . '" target="_blank">';
    echo $post->imt_link . '<span class="dashicons dashicons-external"></span></a>';
    echo '<div class="field-description">' . esc_html('Click the link to be taken to the content that has been reported.', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '</div>';
    echo '</fieldset>';
}

// imt_meta_field
if (!imt_is_add_screen() && $post->imt_meta) {
    echo '<fieldset class="field-wrap imt_meta_field">';
    echo '<div class="field-label">' . esc_html('Reported Content', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    if ($post->imt_activity_type == 'mediapress') {
        echo '<img src="' . esc_url($post->imt_meta) . '">';
    } else {
        echo '<div>' . ($post->imt_meta ? esc_html($post->imt_meta) : '') . '</div>';
    }
    echo '<div class="field-description">' . esc_html('The content that this report relates to.', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '</fieldset>';
}

// imt_activity_type_field
echo '<fieldset class="field-wrap imt_activity_type_field">';
echo '<div class="field-label">' . esc_html('Content Type', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
if (imt_is_add_screen()) {
    echo '<div class="userid-input-label">';
    echo '<div class="userid-input-label-username">' . esc_html("Admin Created Report", IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '</div>';
    echo '<input name="imt_activity_type" type="hidden" value="member">';
    echo '<input name="imt_admin_created" type="hidden" value="1">';
} elseif ($post->imt_admin_created == 1) {
    echo '<div class="userid-input-label">';
    echo '<div class="userid-input-label-username">' . esc_html("Admin Created Report", IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
    echo '</div>';
    echo '<div class="field-description">' . esc_html('This report was created by an administrator, without a specific item.', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
} else {
    switch ($post->imt_activity_type) {
        case 'member':
            $type = "Member";
            break;
        case 'comment':
            $type = "Comment";
            break;
        case 'activity':
            $type = "Activity";
            break;
        case 'activity-comment':
            $type = "Activity Comment";
            break;
        case 'group':
            $type = "Group";
            break;
        case 'message':
            $type = "Private Message";
            break;
        case 'forum-topic':
            $type = "Forum Topic";
            break;
        case 'forum-reply':
            $type = "Forum Reply";
            break;
        case 'rtmedia':
            $type = "Media Upload";
            break;
        default:
            $type = "Member";
            break;
    }
    echo '<div class="userid-input-label">';
    echo '<div class="userid-input-label-username">' . $type . '</div>';
    echo '</div>';
}
echo '</fieldset>';

// imt_report_comments_field
echo '<fieldset class="field-wrap imt_report_comments_field">';
echo '<div class="field-label">' . esc_html('Details of Report', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
echo '<div class="field-discription-wrap"><textarea class="imt-textarea" id="post_content" rows="10" value="" name="post_content">' . ($post->post_content ? esc_textarea($post->post_content) : '') . '</textarea>';
if (!isset($post->imt_admin_created)) {
    echo '<div class="field-description">' . esc_html('Members can explain why they made the report.', IQONIC_MODERATION_TEXT_DOMAIN) . '</div>';
}
echo '</div>';
echo '</fieldset>';

echo '</div></div>'; // Close the final divs

