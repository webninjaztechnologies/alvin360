<?php

/**
 * SocialV\Utility\Custom_Helper\Helpers\Members class
 *
 * @package socialv
 */

namespace SocialV\Utility\Custom_Helper\Helpers;

use BP_Activity_Activity;
use BP_Activity_Component;
use SocialV\Utility\Custom_Helper\Component;
use function add_action;

class PinActivity extends Component
{

    public function __construct()
    {

        // pin-activity        
        add_action("socialv_before_activity_loop", [$this, "socialv_add_pinned_activity"]);
        add_filter('bp_ajax_querystring', [$this, 'socialv_exclude_activity'], 999);
    }

    function socialv_add_pinned_activity()
    {
        if (isset($_POST['page']) && $_POST['page'] > 1) {
            return;
        }

        // Get Sticky Posts ID's.
        $posts_ids = $this->get_socialv_pinned_activity();

        if (empty($posts_ids)) {
            return;
        }

        global $activities_template;
        $old_activities_template = $activities_template;
        $args = array(
            'in' => $posts_ids,
            'per_page' => count(explode(',', $posts_ids)),
            'show_hidden' => 1,
            'display_comments' => 'threaded',
        );


        // Modify the bp_has_activities call to exclude the hidden activity IDs
        $hidden_activity = $this->get_socialv_hidden_activity();
        if (!empty($hidden_activity) && is_array($hidden_activity)) {
            $hidden_activity = implode(',', $hidden_activity);
            $args['exclude'] =  $hidden_activity;
        }
        $count = 0;
        if (bp_has_activities($args)) {
            while (bp_activities()) : bp_the_activity();
                $count++;
                bp_get_template_part('activity/entry');
            endwhile;
        }
        $activities_template = $old_activities_template;
    }

    function socialv_exclude_activity($query)
    {
        $hidden_activity = $this->get_socialv_hidden_activity();
        $pinned_activity = $this->get_socialv_pinned_activity();
        if (!empty($pinned_activity)) {
            if (!empty($query)) {
                $query .= '&';
            }
            if (!empty($args['exclude'])) {
                $query .= 'exclude=' . $args['exclude'] . ',' . $pinned_activity;
            } else {
                $query .= 'exclude=' . $pinned_activity;
            }
        }

        //  Merge excluded values with existing query.
        if (!empty($hidden_activity) && is_array($hidden_activity)) {
            $hidden_activity_string = implode(',', $hidden_activity);
            $hidden_activity_string .= $pinned_activity;
            $query .= '&exclude=' . $hidden_activity_string;
        }
        return $query;
    }

    function get_socialv_pinned_activity()
    {
        $result_id = '';
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $bp_current_page = bp_current_component();
        switch ($bp_current_page) {
            case 'activity':
                $key =  Activity::socialv_current_component();
                $result_id = get_user_meta($user_id, $key, true);
                break;
            case 'groups':
                $key =  Activity::socialv_current_component();
                $group_id  = bp_get_current_group_id();
                if ($group_id) {
                    $result_id = groups_get_groupmeta($group_id, $key, true);   
                    if (!empty($result_id)) {
                        $array_id = array_values($result_id);
                        if ($array_id) {
                            $result_id = implode(',', array_merge(...array_values($result_id)));
                        }
                    }
                }
                break;
        }
       
        $result_id = rtrim($result_id, ',');
        return $result_id;
    }


    function get_socialv_hidden_activity()
    {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $hidden_activity = get_user_meta($user_id, '_socialv_activity_hiden_by_user', true);
        $hidden_activity = maybe_unserialize($hidden_activity);
        return $hidden_activity;
    }
}
