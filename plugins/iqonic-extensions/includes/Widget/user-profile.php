<?php

use function SocialV\Utility\socialv;

function Iqonic_User_Profile_Info()
{
    register_widget('Iqonic_User_Profile');
}
add_action('widgets_init', 'Iqonic_User_Profile_Info');

/*-------------------------------------------
		Iqonic User Profile widget 
--------------------------------------------*/
class Iqonic_User_Profile extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'Iqonic_User_Profile',

            // Widget name will appear in UI
            esc_html__('Iqonic User Profile', IQONIC_EXTENSION_TEXT_DOMAIN),

            // Widget description
            array('description' => esc_html('Display User Profile Infomation.', IQONIC_EXTENSION_TEXT_DOMAIN),)
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

        // Get 'Show Button' Option Value
        $show_buttons = (isset($instance['show_buttons']) && $instance['show_buttons']) ? 'on' : 'off';
        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        if (is_user_logged_in()) {
            $loggedin_user = wp_get_current_user();
            if (($loggedin_user instanceof WP_User)) {
                $user_link = function_exists('bp_members_get_user_url') ? bp_members_get_user_url(get_current_user_id()) : '#';
?>
                <div class="user-menu-head">
                    <div class="d-flex align-items-center">
                        <a class="user-link" href="<?php echo esc_url($user_link); ?>">
                            <?php echo bp_core_fetch_avatar(array(
                                'item_id' => get_current_user_id(),
                                'type'    => 'thumb',
                                'size'  => 40,
                                'class' => 'rounded-circle',
                                'email' => $loggedin_user->user_email
                            )); ?>
                        </a>
                        <div class="item-detail-data">
                            <a href="<?php echo esc_url($user_link); ?>" class="item-title"><?php echo esc_html($loggedin_user->display_name); ?>
                            <?php if (class_exists("BP_Verified_Member"))
                                   echo socialv()->socialv_get_verified_badge($loggedin_user->ID);
                            ?></a>
                            <p class="m-0 item-desc">@<?php echo esc_html($loggedin_user->user_login); ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            if ($show_buttons  == 'on') {  ?>
                <div class="user-menu-head">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo esc_url(BP_AVATAR_DEFAULT); ?>" loading="lazy" class="rounded-circle avatar-50 photo" alt="<?php esc_attr_e('image', IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
                        <div class="item-detail-data mt-auto mb-auto">
                            <a href="#" class="item-title"><?php esc_html_e('UserName', IQONIC_EXTENSION_TEXT_DOMAIN); ?></a>
                            <p class="m-0 item-desc">@<?php esc_html_e('username', IQONIC_EXTENSION_TEXT_DOMAIN); ?></p>
                        </div>
                    </div>
                </div>
        <?php
            }
        }

        echo $args['after_widget'];
        wp_reset_postdata();
    }

    // Widget Backend 
    public function form($instance)
    {
        // Get Widget Data.
        $title = strip_tags($instance['title']);

        ?>
        <p>
            <label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
        </p>
        <!-- Display Buttons -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_buttons'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_buttons')); ?>" name="<?php echo esc_attr($this->get_field_name('show_buttons')); ?>">
            <label for="<?php echo $this->get_field_id('show_buttons'); ?>"><?php esc_html_e('Show Without Login ?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
        </p>

<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['show_buttons'] = $new_instance['show_buttons'];

        return $instance;
    }
}
