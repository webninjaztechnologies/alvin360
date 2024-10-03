<?php
defined("ABSPATH") || exit;

function irRecursiveSanitizeTextField($array)
{
    $filterParameters = [];
    foreach ($array as $key => $value) {

        if ($value === '') {
            $filterParameters[$key] = null;
        } else {
            if (is_array($value)) {
                $filterParameters[$key] = irRecursiveSanitizeTextField($value);
            } else {
                if (preg_match("/<[^<]+>/", $value, $m) !== 0) {
                    $filterParameters[$key] = $value;
                } else {
                    $filterParameters[$key] = sanitize_text_field($value);
                }
            }
        }
    }

    return $filterParameters;
}

function ir_comman_message_response($message, $status_code = 200)
{
    $response = new WP_REST_Response(
        array(
            "message" => $message
        )
    );
    $response->set_status($status_code);
    return $response;
}

function ir_comman_custom_response($res, $status_code = 200)
{
    $response = new WP_REST_Response($res);
    $response->set_status($status_code);
    return $response;
}

function ir_comman_list_response($data)
{
    $response = new WP_REST_Response(array(
        "data" => $data
    ));

    $response->set_status(200);
    return $response;
}
function rest_reaction_list($list_obj, $component)
{
    foreach ($list_obj as $key => $reaction_ibj) {
        $user = get_userdata($reaction_ibj->user_id);
        $reaction_ibj->icon = $reaction_ibj->image_url;
        $reaction_ibj->reaction = $reaction_ibj->name;

        if ($user) {
            $user_id = $user->ID;
            $user_obj = [
                "id"            => $user_id,
                "display_name"  => $user->display_name,
                "user_name"     => $user->user_login,
                "avatar"        => get_avatar_url($user_id, apply_filters("ir_rest_" . $component . "_reaction_user_avatr_args", []))
            ];
            $reaction_ibj->user = apply_filters("ir_rest_" . $component . "_reaction_user_data", $user_obj, $user);
        } else {
            unset($list_obj[$key]);
        }

        unset($reaction_ibj->user_id);
        unset($reaction_ibj->image_url);
        unset($reaction_ibj->name);
        unset($reaction_ibj->reaction_count);
    }

    $list_obj = array_values($list_obj);

    return apply_filters("ir_rest_" . $component . "_reaction_list", $list_obj, $component);
}

function rest_single_reaction_list($list_obj, $component)
{
    foreach ($list_obj as $key => $reaction_ibj) {
        $user = get_userdata($reaction_ibj->user_id);
        $reaction_ibj->id = $reaction_ibj->reactions;
        $reaction_ibj->icon = $reaction_ibj->image_url;
        $reaction_ibj->reaction = $reaction_ibj->name;

        if ($user) {
            $user_id = $user->ID;
            $user_obj = [
                "id"            => $user_id,
                "display_name"  => $user->display_name,
                "user_name"     => $user->user_login,
                "avatar"        => get_avatar_url($user_id, apply_filters("ir_rest_" . $component . "_reaction_user_avatr_args", []))
            ];
            $reaction_ibj->user = apply_filters("ir_rest_" . $component . "_reaction_user_data", $user_obj, $user);
        } else {
            unset($list_obj[$key]);
        }
        unset($reaction_ibj->user_id);
        unset($reaction_ibj->image_url);
        unset($reaction_ibj->name);
        unset($reaction_ibj->reactions);
    }

    return apply_filters("ir_rest_" . $component . "_reaction_list", array_values($list_obj), $component);
}
