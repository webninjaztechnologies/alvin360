<?php

namespace IR\Admin\Classes;
use BP_Notifications_Notification;

class IR_Database
{
    private $wpdb;
    // Define table names as constants
    const REACTION_LIST_TABLE = 'iq_reaction_list';
    const REACTION_ACTIVITY_TABLE = 'iq_reaction_activity';
    const COMMENT_REACTION_TABLE = 'iq_comment_reaction';
    const WP_ACTIVITY_META_TABLE_NAME = 'bp_activity_meta';

    public $iq_reaction_list; // stores reaction lists
    public $iq_reaction_activity; // stores reaction meta details
    public $iq_comment_reaction; // stores comment meta details
    public $wp_bp_activity_meta_table;
    public $globalReactionsList; //store list of reaction

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        // Set table names using constants
        $this->iq_reaction_list = $wpdb->prefix . self::REACTION_LIST_TABLE;
        $this->iq_reaction_activity = $wpdb->prefix . self::REACTION_ACTIVITY_TABLE;
        $this->iq_comment_reaction = $wpdb->prefix . self::COMMENT_REACTION_TABLE;
        $this->wp_bp_activity_meta_table = $wpdb->prefix . self::WP_ACTIVITY_META_TABLE_NAME;
    }

    //============================CREATE TABLES=====================================
    public function createReactionListTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->iq_reaction_list (
            id int NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            image_url varchar(500) NOT NULL,
            PRIMARY KEY (id)
          ) {$this->wpdb->get_charset_collate()};";

        return $this->wpdb->query($sql);
    }

    public function createReactionActivityTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->iq_reaction_activity (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        activity_id bigint(20) NOT NULL,
        user_id bigint(20) unsigned NOT NULL,
        reaction_id int NOT NULL,
        PRIMARY KEY (id, activity_id, user_id)
        ) {$this->wpdb->get_charset_collate()};";

        return $this->wpdb->query($sql);
    }

    public function createCommentReactionTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->iq_comment_reaction (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        comment_id bigint(20) NOT NULL,
        activity_id bigint(20) NOT NULL,
        user_id bigint(20) unsigned NOT NULL,
        reaction_id int NOT NULL,
        PRIMARY KEY (id, comment_id, activity_id, user_id)
        ) {$this->wpdb->get_charset_collate()};";

        return $this->wpdb->query($sql);
    }

    //==============SAVES DEFAULT REACTION IN REACTION LIST TABLE AND SYNCS REDUX REACTION OPTIONS==============
    public function insertDefaultReaction()
    {
        global $flag;
        $flag = 1;

        $format = ['%d', '%s', '%s', '%s'];

        $default_reactions = default_reactions();
        $options = get_option('ir_options');

        foreach ($default_reactions as $key => $value) {
            $options['reactions_field']['redux_repeater_data'][$value['id'] - 1]['title'] = " ";
            $options['reaction_name'][$value['id'] - 1] = $value['name'];
            $options['reaction_image'][$value['id'] - 1]['url'] = $value['image_url'];
            $options['reaction_image'][$value['id'] - 1]['thumbnail'] = $value['image_url'];

            $args = [
                'id' => $value['id'],
                'name' => $value['name'],
                'image_url' => $value['image_url'],
            ];
        }
        update_option('ir_options', $options);
        return $options;
    }
    function insertDefaultReactionsList()
    {
        global $wpdb;

        $reaction_table_name = $wpdb->prefix . 'iq_reaction_list';
            
        $default_reactions = default_reactions();

        foreach ($default_reactions as $reaction) {
            $wpdb->insert(
                $reaction_table_name,
                array(
                    'id' => $reaction['id'],
                    'name' => $reaction['name'],
                    'image_url' => $reaction['image_url']
                ),
                array(
                    '%d',
                    '%s',
                    '%s'
                )
            );
        }        
    }

    //=====================================INSERT QUERIES================================
    public function insertReactionActivity($args, $where) //insert into reaction activity table
    {
        $format = ['%d', '%d', '%d'];
        $res = $this->wpdb->update($this->iq_reaction_activity, $args, $where);
        if ($res == 0) {
            $res = $this->wpdb->replace($this->iq_reaction_activity, $args, $format);
        }

        return $res;
    }

    public function insertCommentReactionActivity($args, $where) //insert into comment reaction activity table
    {
        $format = ['%d', '%d', '%d', '%d'];
        $res = $this->wpdb->update($this->iq_comment_reaction, $args, $where);
        if ($res == 0) {
            $res = $this->wpdb->replace($this->iq_comment_reaction, $args, $format);
        }
        return $res;
    }

    //=====================================DELETE QUERIES================================
    public function deleteUserReactions($args)
    {
        $format = ['%d', '%d'];
        $result = $this->wpdb->delete($this->iq_reaction_activity, $args, $format);
        return $result;
    }

    public function deleteCommentReactionActivity($args)
    {
        $format = ['%d', '%d', '%d'];
        $result = $this->wpdb->delete($this->iq_comment_reaction, $args, $format);
        return $result;
    }

    //=============================GETS ALL REACTIONS WHERE===========================
    public function getAllReactionsList()
    {
        $sql = "SELECT * FROM $this->iq_reaction_list
                ORDER BY id ASC";

        return $this->wpdb->get_results($sql);
    }

    //=================================IQ_REACTION_ACTIVITY FUNCTIONS==========================
    public function getGroupedReaction($activity_id)
    {
        $sql = "SELECT name,image_url,reaction_id, GROUP_CONCAT(reaction_id separator ',' ) AS reactions, COUNT(reaction_id) AS reaction_count
                FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id = $this->iq_reaction_list.id
                WHERE activity_id = %d
                GROUP BY reaction_id
                ORDER BY reaction_count DESC";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id]));
    }

    public function getReactionByActivityId($activity_id)
    {
        $sql = "SELECT * FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id = $this->iq_reaction_list.id
                WHERE activity_id = %d
                GROUP BY reaction_id
                LIMIT 0,15";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id]));
    }

    public function getReactionByReactionId($activity_id, $reaction_id, $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'page'      => 0,
                'per_page'  => -1
            ]
        );

        $per_page = $args['per_page'];
        $page = $args['page'];

        $sql = "SELECT user_id, name, image_url, GROUP_CONCAT(reaction_id separator ',' ) AS reactions
                FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id = $this->iq_reaction_list.id
                WHERE reaction_id = %d AND activity_id=%d
                GROUP BY user_id, name, image_url
                ORDER BY user_id, name, image_url";

        if ($per_page > 0) {
            if ($page == 0)
                $start = 0;
            else
                $start = ($page - 1) * $per_page;

            $sql .= ' LIMIT ' . $start . ',' . $per_page;
        }

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$reaction_id, $activity_id]));
    }

    public function getReactions($activity_id, $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'page'          => 0,
                'per_page'      => -1,
                'counts_only'   => false
            ]
        );
        $per_page = $args['per_page'];
        $page = $args['page'];

        $sql = "SELECT $this->iq_reaction_list.id, name, image_url,user_id, COUNT($this->iq_reaction_list.id) AS reaction_count
                FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id=$this->iq_reaction_list.id
                WHERE activity_id=%d
                GROUP BY user_id
                ORDER BY $this->iq_reaction_activity.id DESC";

        if ($per_page > 0) {
            if ($page == 0)
                $start = 0;
            else
                $start = ($page - 1) * $per_page;

            $sql .= ' LIMIT ' . $start . ',' . $per_page;
        }

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, [$activity_id])
        );
    }

    public function execute_query($sql)
    {
        return $this->wpdb->get_results($this->wpdb->prepare($sql));
    }

    public function getUserReaction($activity_id, $user_id)
    {
        $sql = "SELECT $this->iq_reaction_list.id, $this->iq_reaction_activity.id as table_id,  name, image_url, COUNT($this->iq_reaction_list.id) AS reaction_count FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id=$this->iq_reaction_list.id
                WHERE activity_id=%d AND user_id=%d
                GROUP BY $this->iq_reaction_list.id
                ORDER BY reaction_count ASC";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, [$activity_id, $user_id])
        );
    }

    public function get_default_reaction($reaction_name)
    {
        $sql = "SELECT * FROM $this->iq_reaction_list
                WHERE name=%s";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, [$reaction_name])
        );
    }

    public function getUsersByActivityReaction($activity_id, $reaction_id)
    {
        $sql = "SELECT user_id FROM $this->iq_reaction_activity
                INNER JOIN $this->iq_reaction_list ON $this->iq_reaction_activity.reaction_id=$this->iq_reaction_list.id
                WHERE activity_id=%d AND reaction_id=%d";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, [$activity_id, $reaction_id])
        );
    }

    //=====================COMMENT REACTION FUNCTIONS=======================
    public function getCommentReaction($activity_id, $user_id, $comment_id)
    {
        $sql = "SELECT $this->iq_reaction_list.id, $this->iq_comment_reaction.id as table_id,  name, image_url, COUNT($this->iq_reaction_list.id) AS reaction_count
                FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id=$this->iq_reaction_list.id
                WHERE activity_id=%d AND user_id=%d AND comment_id=%d
                GROUP BY $this->iq_reaction_list.id
                ORDER BY reaction_count DESC";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, [$activity_id, $user_id, $comment_id])
        );
    }

    public function getGroupedCommentReaction($activity_id, $comment_id)
    {
        $sql = "SELECT name,image_url,reaction_id,user_id, GROUP_CONCAT(reaction_id separator ',' ) AS reactions, COUNT(reaction_id) AS reaction_count
                FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id = $this->iq_reaction_list.id
                WHERE activity_id = %d AND comment_id=%d
                GROUP BY reaction_id
                ORDER BY user_id ASC";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id, $comment_id]));
    }

    public function getCommentsReactionList($activity_id, $comment_id, $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'page'      => 0,
                'per_page'  => -1
            ]
        );

        $per_page = $args['per_page'];
        $page = $args['page'];

        $sql = "SELECT $this->iq_reaction_list.id, name, image_url, reaction_id, user_id, COUNT($this->iq_reaction_list.id) AS reaction_count
                FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id=$this->iq_reaction_list.id
                WHERE activity_id = %d AND comment_id=%d
                GROUP BY user_id
                ORDER BY $this->iq_comment_reaction.id DESC";

        if ($per_page > 0) {
            if ($page == 0)
                $start = 0;
            else
                $start = ($page - 1) * $per_page;

            $sql .= ' LIMIT ' . $start . ',' . $per_page;
        }


        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id, $comment_id]));
    }

    public function getReactionByCommentId($activity_id, $comment_id)
    {
        $sql = "SELECT * FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id = $this->iq_reaction_list.id
                WHERE activity_id = %d AND comment_id=%d
                GROUP BY reaction_id
                LIMIT 0,15";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id, $comment_id]));
    }

    public function getCommentReactionByCommentId($activity_id, $comment_id)
    {
        $sql = "SELECT * FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id = $this->iq_reaction_list.id
                WHERE activity_id = %d AND comment_id=%d
                LIMIT 0,15";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$activity_id, $comment_id]));
    }

    public function getCommentReactionByReactionId($activity_id, $reaction_id, $comment_id, $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'page'      => 0,
                'per_page'  => -1
            ]
        );

        $per_page = $args['per_page'];
        $page = $args['page'];

        $sql = "SELECT user_id, name, image_url, GROUP_CONCAT(reaction_id separator ',' ) AS reactions
                FROM $this->iq_comment_reaction
                INNER JOIN $this->iq_reaction_list ON $this->iq_comment_reaction.reaction_id = $this->iq_reaction_list.id
                WHERE reaction_id = %d AND activity_id=%d AND Comment_id=%d
                GROUP BY user_id, name, image_url
                ORDER BY user_id, name, image_url";

        if ($per_page > 0) {
            if ($page == 0)
                $start = 0;
            else
                $start = ($page - 1) * $per_page;

            $sql .= ' LIMIT ' . $start . ',' . $per_page;
        }


        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$reaction_id, $activity_id, $comment_id]));
    }

    public function convert_bp_likes($options)
    {
        $sql = "SELECT * FROM $this->wp_bp_activity_meta_table
                WHERE meta_key='_socialv_activity_liked_users' AND meta_value NOT LIKE ''
                ORDER BY activity_id";

        $result = $this->wpdb->get_results($sql);

        $columns = ['activity_id', 'reaction_id', 'user_id'];
        $columns     = implode(', ', $columns);

        $values = array();
        foreach ($result as $key => $value) {
            $args = [
                'activity_id' => $value->activity_id,
                'reaction_id' => '1'
            ];

            $values = [
                $value->activity_id,
                1,
            ];

            $search = explode(",", $value->meta_value);

            if (count($search) > 1) { // when multiple users have liked the same posts, this portion will run
                foreach ($search as $user_id) {
                    $activity_id = $value->activity_id;
                    $reaction_id = $args['reaction_id'];

                    $sql = "INSERT INTO $this->iq_reaction_activity ($columns)
                    SELECT $activity_id, $reaction_id ,$user_id 
                    WHERE NOT EXISTS (SELECT * FROM $this->iq_reaction_activity WHERE activity_id = %d and user_id = %d)";

                    $this->wpdb->query($this->wpdb->prepare($sql, [$activity_id, $user_id]));
                }
            } else {
                $values[] = $search[0];
                $all_values[] = $values;
            }
        }

        foreach ($all_values as $key => $value) { // when single user have liked the posts, this portion will run

            $activity_id = $value[0];
            $reaction_id = $value[1];
            $user_id = $value[2];

            $sql = "INSERT INTO $this->iq_reaction_activity ($columns)
                SELECT $activity_id, $user_id, $reaction_id
                WHERE NOT EXISTS (SELECT * FROM $this->iq_reaction_activity WHERE activity_id = %d and user_id = %d)";

            $this->wpdb->query($this->wpdb->prepare($sql, [$activity_id, $user_id]));
        }

        $options['convert_bp_likes_into_reaction'] = 0; //sets redux option to no after conversion
        update_option('ir_options', $options);
    }

    //redux reaction list
    public function update_reaction_list($reactions_name, $reactions_image)
    {
        $options  = get_option('ir_options');
        $reaction_id = $this->getAllReactionsList();
        $redux_reaction_name = $options['reaction_name'];

        $redux_reaction = array_map(
            function ($redux_reaction_name) {
                return array_combine(
                    ['name'],
                    [$redux_reaction_name]
                );
            },
            $redux_reaction_name
        );

        foreach ($redux_reaction as $key => $value) {
            $id = (int)$reaction_id[$key]->id;
            $reaction_name = $reactions_name[$key];
            $reaction_emoji = $reactions_image[$key];

            if (!empty($reaction_id[$key]->id)) {
                $sql = "UPDATE $this->iq_reaction_list
                        SET name = %s, image_url = %s
                        WHERE id=%d";

                $a = $this->wpdb->update($this->iq_reaction_list, ['name' => $reaction_name, 'image_url' => $reaction_emoji], ['id' => $id]);
                if (!$a) {
                    $this->wpdb->show_errors();
                }
            } elseif (isset($reaction_name) && !empty($reaction_name)) {
                $sql = "INSERT INTO $this->iq_reaction_list (name, image_url)
                        VALUES (%s, %s)";

                $this->wpdb->query($this->wpdb->prepare($sql, [$reaction_name, $reaction_emoji]));
            }
        }
    }

    //====================DROP TABLES====================
    public function dropTable() //drops reaction activity table
    {
        $sql = "DROP TABLE IF EXISTS $this->iq_reaction_activity";
        return $this->wpdb->query($sql);
    }

    //====================DROP TABLES====================
    public function truncateReactionListTable() //empty the reaction list table
    {
        $sql = "TRUNCATE TABLE $this->iq_reaction_list";
        $this->wpdb->query($sql);
    }

    //====================DELETE REACTION DATA FROM DATABASE OF A PARTICULAR REACTION====================
    //delete the activity reaction data for single reaction name
    public function deleteActivityReactionByName($reaction_name)
    {
        $sql = "DELETE $this->iq_reaction_activity FROM $this->iq_reaction_activity
                LEFT JOIN $this->iq_reaction_list ON $this->iq_reaction_list.id = $this->iq_reaction_activity.reaction_id	
                WHERE $this->iq_reaction_list.name = %s";

        return $this->wpdb->query($this->wpdb->prepare($sql, [$reaction_name]));
    }

    //delete the comment reaction data for single reaction name
    public function deleteCommentReactionByName($reaction_name)
    {
        $sql = "DELETE $this->iq_comment_reaction FROM $this->iq_comment_reaction
                LEFT JOIN $this->iq_reaction_list ON $this->iq_reaction_list.id = $this->iq_comment_reaction.reaction_id	
                WHERE $this->iq_reaction_list.name = %s";

        return $this->wpdb->query($this->wpdb->prepare($sql, [$reaction_name]));
    }

    //delete reaction from the reaction list
    public function deleteReactionFromList($reaction_name)
    {
        $sql = "DELETE FROM $this->iq_reaction_list
                WHERE $this->iq_reaction_list.name = %s";

        return $this->wpdb->query($this->wpdb->prepare($sql, [$reaction_name]));
    }

    public function getActivityReactionFromList($reaction_name)
    {
        $sql = "SELECT * FROM $this->iq_reaction_activity
                LEFT JOIN $this->iq_reaction_list ON $this->iq_reaction_list.id = $this->iq_reaction_activity.reaction_id	
                WHERE $this->iq_reaction_list.name = %s";

        echo $this->wpdb->prepare($sql, [$reaction_name]);

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$reaction_name]));
    }

    public function getCommentReactionFromList($reaction_name)
    {
        $sql = "SELECT * FROM $this->iq_comment_reaction
                LEFT JOIN $this->iq_reaction_list ON $this->iq_reaction_list.id = $this->iq_comment_reaction.reaction_id	
                WHERE $this->iq_reaction_list.name = %s";

        return $this->wpdb->get_results($this->wpdb->prepare($sql, [$reaction_name]));
    }

    //====================DELETE REACTION NOTIFICATION====================
    public function delete_activity_reaction_notification($reaction_name)
    {
        $reaction_data = $this->getActivityReactionFromList($reaction_name);

        foreach ($reaction_data as $reaction) {
            $activity_id =  $reaction->activity_id;

            if (!empty($activity_id) && $activity_id == "2638") {
                $notification_args = [
                    'item_id'           => $activity_id,
                    'component_name'    => 'iqonic_activity_reaction_notification',
                    'component_action'  => 'action_activity_reacted',
                ];

                $existing = BP_Notifications_Notification::get($notification_args);
                if (!empty($existing)) {
                    foreach ($existing as $notification) {
                        BP_Notifications_Notification::delete(array('id' => $notification->id));
                    }
                }
            }
        }
    }

    public function delete_comment_reaction_notification($reaction_name)
    {
        $reaction_data = $this->getCommentReactionFromList($reaction_name);

        foreach ($reaction_data as $reaction) {
            $activity_id =  $reaction->activity_id;

            if (!empty($activity_id)) {

                $notification_args = [
                    'item_id'           => $activity_id,
                    'component_name'    => 'iqonic_comment_activity_reaction_notification',
                    'component_action'  => 'action_comment_activity_reacted',
                ];

                $existing = BP_Notifications_Notification::get($notification_args);
                if (!empty($existing)) {
                    foreach ($existing as $notification) {
                        if (!empty($notification->id)) {
                            BP_Notifications_Notification::delete(array('id' => $notification->id));
                        }
                    }
                }
            }
        }
    }
}
