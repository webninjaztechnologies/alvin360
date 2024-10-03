<?php

/**
 * Report activity or gorup or members.
 *
 * @param   int     $reported      Id of the reported activity or gorup or members.
 * @param   int     $reporter      Id of the member who reporting.
 * @return  true    if user reported, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_report($args)
{
    do_action("imt_brfore_report_member", $args);

    $reported = $args["reported"];
    $reporter = $args["reporter"];

    if (empty($reporter)) $reporter = get_current_user_id();

    if (user_can($reported, 'manage_options')) return false;

    $reported_member = get_user_by("ID", $reported);
    $activity_type = $args["activity_type"];
    $item_id = in_array($activity_type, ["member", "group"]) ? $reported : $args['item_id'];
    $details = $args["details"];
    $report_type = $args["report_type"];

    $report_count = imt_item_reports_count($item_id, $reporter);
    if ($report_count == 5) return false;

    if ($activity_type == "activity") {
        $title = $reported_member->display_name . "'s activity has been reported";
        $link   = bp_get_activity_directory_permalink() . "p/" . $item_id;
    } elseif ($activity_type == "group") {
        $group = groups_get_group($reported);
        if ($group) {
            $title = $group->name . " Group has been reported";
            $link = bp_get_group_url($group);
        }
    } else {
        $title = $reported_member->display_name . "'s account has been reported.";
        $link = bp_members_get_user_url($reported);
    }

    $post_args = array(
        'post_title'   => $title,
        'post_content' => $details,
        'post_author'  => $reporter,
        'meta_input'   => array(
            'imt_member_reported'   => $reported,
            'imt_reported_by'       => $reporter,
            'imt_link'              => $link,
            'imt_item_id'           => $item_id,
            'imt_activity_type'     => $activity_type,
            'imt_user_report'       => true,
            'is_upheld'             => 1,
            'is_read'               => 0,
        ),
        'post_status'  => 'publish',
        'post_type'    => 'imt_reports',
    );
    $post_args = apply_filters("imt_report_member_post_args", $post_args);

    $post_id = wp_insert_post($post_args);
    wp_set_object_terms($post_id, $report_type, 'report-types');

    do_action("imt_after_report_member", $post_id);

    return $post_id ? true : false;
}

function imt_item_reports_count($item_id, $author = false)
{
    if (!isset($item_id) || $item_id == '') {
        return;
    }

    $args = array(
        'post_type'   => 'imt_reports',
        'numberposts' => -1,
        'meta_query'  => [
            [
                'key'   => 'imt_item_id',
                'value' => $item_id,
            ]
        ],
    );

    if ($author)
        $args['author'] = $author;

    $query = get_posts($args);
    $count = $query ? count($query) : 0;

    return $count;
}

/**
 * Get report types.
 *
 * @param array $args      Id of the reported member.
 * @return array if report types found, Otherwise false.
 * 
 * @since 1.0.0
 *
 */
function imt_get_report_types($args = [])
{
    $args = wp_parse_args($args, [
        "taxonomy"      => "report-types",
        "hide_empty"    => 0,
    ]);

    $types = get_terms($args);
    if ($types) {
        $report_types = [];
        foreach ($types as $type) {
            if ($type->slug != "other")
                $report_types[$type->slug] = $type->name;
        }
    }
    $report_types['other'] = esc_html__("Other", IQONIC_MODERATION_TEXT_DOMAIN);

    return apply_filters("imt_report_types", $report_types);
}

function imt_ordinal($number)
{
    $ends = array(
        'th',
        'st',
        'nd',
        'rd',
        'th',
        'th',
        'th',
        'th',
        'th',
        'th'
    );

    if ($number % 100 >= 11 && $number % 100 <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function imt_get_report_button_template($args = [])
{
    imt_get_template_part("templates/report/report", "button", $args);
}
function imt_get_report_form_template($args = [])
{
    imt_get_template_part("templates/report/report", "form", $args);
}

add_action('wp_ajax_imt_report_form', 'imt_report_form_ajax');
add_action('wp_ajax_nopriv_imt_report_form', 'imt_report_form_ajax');
function imt_report_form_ajax()
{

    if (!is_user_logged_in()) wp_send_json(["status" => false]);

    if (!check_ajax_referer("verify_imt_submit_report_nonce", "imt_submit_report_nonce")) wp_send_json(["status" => false]);

    $id = $_POST['id'];
    $reporter = get_current_user_id();
    $reported = $id;
    $activity_type = $_POST['activity_type'];
    $report_type = $_POST['report_type'];
    $details = $_POST['report_details'];

    if ($activity_type == "activity") {
        $item_id = $id;
        $activity = bp_activity_get(["in" => $id]);
        if ($activity) {
            $activity = reset($activity['activities']);
            $reported = $activity->user_id;
        }
    }

    $report_count = imt_item_reports_count($item_id, $reporter);
    if ($report_count == 5) wp_send_json(["status" => false]);

    $args = [
        "reported"      => $reported,
        "reporter"      => $reporter,
        "item_id"       => $item_id,
        "details"       => $details,
        "report_type"   => $report_type,
        "activity_type" => $activity_type
    ];

    $is_repoted = imt_report($args);

    wp_send_json(["status" => $is_repoted]);
}

function imt_can_report($id, $type)
{
    if (!is_user_logged_in()) return false;

    $current_user_id = get_current_user_id();
    if ("member" == $type) {
        return $id != $current_user_id;
    } elseif ("activity" == $type) {
        $activity = bp_activity_get(["in" => $id]);
        if ($activity) {
            $activity = reset($activity["activities"]);
            return ($activity) ? $current_user_id != $activity->user_id : false;
        }
    } elseif ("group") {
        return !groups_is_user_admin($current_user_id, $id);
    }

    return false;
}
