<?php

namespace IMT\Admin\Classes;

use WP_List_Table;

class IMT_Suspend extends WP_List_Table
{



    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct()
    {
        parent::__construct(array(
            'singular' => 'wp_list_text_link', //Singular label
            'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
            'ajax'   => false //We won't support Ajax for this table
        ));
    }
    function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'id' => __('ID', IQONIC_MODERATION_TEXT_DOMAIN),
            'display_name' => __('User', IQONIC_MODERATION_TEXT_DOMAIN),
            'actions' => __('Action', IQONIC_MODERATION_TEXT_DOMAIN),
        );
    }
    
    public function get_hidden_columns()
    {
        // Setup Hidden columns and return them
        return array("id");
    }
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'display_name':
                return "<strong>" . $item[$column_name] . "</strong>";
            case 'actions':
                return sprintf('<div class="button imt-member-suspended" id="admin-suspend-member" type="submit" data-suspended="1" data-id="%d">%s  </div>', $item['id'], __("Unsuspend Member", IQONIC_MODERATION_TEXT_DOMAIN));
            default:
                return print_r($item, true);
        }
    }
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'display_name' => array('display_name', true)
        );
        return $sortable_columns;
    }
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="user[]" value="%s" />', $item['id']);
    }
    function column_display_name($item) {
        $edit_url = admin_url("user-edit.php?user_id=".$item['id']."&action=edit");
        $actions = array(
            'edit' => sprintf('<a href="%s">Edit</a>', esc_url($edit_url)),
        );
    
        return sprintf('%1$s %2$s', $item['display_name'], $this->row_actions($actions));
    }
    
    
    function get_bulk_actions()
    {
        $actions = array(
            'unsuspend'    => 'Unsuspend'
        );
        return $actions;
    }
    public function process_bulk_action()
    {
        $user = isset($_GET['user']) ? $_GET['user'] : '';
        if ('unsuspend' === $this->current_action()) {
            if (!empty($user)) {
                if (is_array($user)) {
                    foreach ($user as $id) {
                        if (!empty($id)) {
                            update_user_meta($id, "imt_suspend_member", false);
                        }
                    }
                }
            }
        }
    }
    private function table_data()
    {
        global $wpdb;
        $user_table_name = $wpdb->prefix . 'users';
        $usermeta_table_name = $wpdb->prefix . 'usermeta';
        $data = array();

        $sql = "SELECT user.ID,user.display_name FROM $user_table_name user 
        LEFT JOIN $usermeta_table_name  usermeta ON user.ID = usermeta.user_id
        WHERE usermeta.meta_value= 1 AND usermeta.meta_key = 'imt_suspend_member'";
        if (isset($_GET['s'])) {
            $search = $_GET['s'];
            $search = trim($search);
            $sql .= "AND display_name LIKE '%$search%'";
        }
        $suspended_user = $wpdb->get_results($sql);

        $i = 0;
        foreach ($suspended_user as $suspended_users) {
            $data[] = array(
                'id'  => $suspended_users->ID,
                'display_name' =>   $suspended_users->display_name
            );
            $i++;
        }

        return $data;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $hidden = $this->get_hidden_columns();
        $this->process_bulk_action();
        $data = $this->table_data();

        $totalitems = count($data);
        $per_page = get_user_option('users_per_page');

        // Use consistent variable names
        $this->_column_headers = array($columns, $hidden, $sortable);
        if(empty($per_page = 10) && $per_page = 0)
        $per_page = 10;

        usort($data, [$this, 'usort_reorder']);
        $totalpages = ceil($totalitems / $per_page);
        $currentPage = $this->get_pagenum();

        $data = array_slice($data, (($currentPage - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $per_page,
        ));
        $this->items = $data;
    }

        
    function usort_reorder($a, $b)
    {
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
        $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
        return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
    }
}
