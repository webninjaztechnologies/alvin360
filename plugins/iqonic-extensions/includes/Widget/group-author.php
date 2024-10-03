<?php

use function SocialV\Utility\socialv;

function Iqonic_Group_Author_Info()
{
    register_widget('Iqonic_Group_Author');
}
add_action('widgets_init', 'Iqonic_Group_Author_Info');

/*-------------------------------------------
		Iqonic Group Author widget 
--------------------------------------------*/
class Iqonic_Group_Author extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'Iqonic_Group_Author',

            // Widget name will appear in UI
            esc_html__('Iqonic Group Author', IQONIC_EXTENSION_TEXT_DOMAIN),

            // Widget description
            array('description' => esc_html('Display Group Author Infomation.', IQONIC_EXTENSION_TEXT_DOMAIN),)
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];
        echo $args['before_title'] . $title . $args['after_title'];

        if (!function_exists('groups_get_group_admins')) {
            return;
        }
            $group_admins = groups_get_group_admins(bp_get_group_id());
            $admins_with_activity = array();
            foreach ($group_admins as $post) {
                $admin_id = $post->user_id;
                $last_activity = bp_get_user_last_activity($admin_id);
                $admins_with_activity[$admin_id] = $last_activity;
            }
            arsort($admins_with_activity);

          echo '<div class="socialv-group-admin-info">
                <ul class="list-inline m-0">';
                    foreach ($admins_with_activity as $admin_id => $last_activity) : 
                       echo '<li class="socialv-widget-image-content-wrap">
                            <div class="item-avatar">
                                <a href="'. bp_members_get_user_url($admin_id) .'" class="submit user-submit">'.bp_core_fetch_avatar(array('item_id' => $admin_id, 'type' => 'full', 'class' => 'rounded-circle', 'width' => '60', 'height' => '60')).'</a>
                            </div>
                            <div class="avtar-details">
                                <h6>'.bp_core_get_userlink($admin_id); 
                                 if (class_exists("BP_Verified_Member"))
                                    echo socialv()->socialv_get_verified_badge($admin_id);
                                                                
                              echo '</h6>
                                      <div class="socialv-nik-name">@' . bp_members_get_user_slug($admin_id) . '</div>
                               </div>
                            </li>';
                  endforeach;
              echo '</ul></div>';
        
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance)
    {
        // Get Widget Data.
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => esc_html__(' Group Administrators', IQONIC_EXTENSION_TEXT_DOMAIN),
            )
        );
        $title = strip_tags($instance['title']);
        ?>
        <p>
            <label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
        </p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}
