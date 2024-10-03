<?php

use SocialV\Utility\Nav_Walker;

function Iqonic_Navigation_Menu_Links()
{
    register_widget('Iqonic_Navigation_Menu');
}
add_action('widgets_init', 'Iqonic_Navigation_Menu_Links');

/*-------------------------------------------
		Iqonic Navigation Menu widget 
--------------------------------------------*/
class Iqonic_Navigation_Menu extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'Iqonic_Navigation_Menu',

            // Widget name will appear in UI
            esc_html__('Iqonic Navigation Menu', IQONIC_EXTENSION_TEXT_DOMAIN),

            // Widget description
            array('description' => esc_html('Display Navigation Menu Links Usign Toggle.', IQONIC_EXTENSION_TEXT_DOMAIN),)
        );
    }
    // Creating widget front-end
    public function widget($args, $instance)
    {

        $nav_menu = !empty($instance['menu']) ? wp_get_nav_menu_object($instance['menu']) : false;
        if (!$nav_menu) {
            return;
        }
        $class = '';
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $class = !empty($instance['class']) ? $instance['class'] : '';
        $show_title = isset($instance['show_title']) ? $instance['show_title'] : true;
        $title_class = ($show_title == true) ? '' :  'no-title';
        $classs = $class . ' widget-verticle ' . $title_class;
        $before_widget = $args['before_widget'];
        if (strpos($before_widget, 'class') === false) {
            $before_widget = str_replace('>', 'class="' . $classs . '">', $before_widget);
        } else {
            $before_widget = str_replace('class="', 'class="' . $classs . ' ', $before_widget);
        }
        /* Before widget */
        echo $before_widget;
        echo $args['before_title'] . $title . $args['after_title'];
        wp_nav_menu(
            array(
                'menu'           => $nav_menu,
                'fallback_cb'    => '',
                'container'      => false,
                'menu_class'     => 'navbar-nav iq-main-menu',
                'walker' => new Nav_Walker\Component(),
            )
        );
        
        
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance)
    {
        // Get Widget Data.
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => esc_html__(' Menu', IQONIC_EXTENSION_TEXT_DOMAIN),
                'show_title' => true,
            )
        );
        $title = strip_tags($instance['title']);
        $nav_menu = isset($instance['menu']) ? $instance['menu'] : '';
        if (!is_numeric($nav_menu)) {
            $menu_object = wp_get_nav_menu_object($nav_menu);
            $nav_menu = $menu_object->slug;
        }
        $class = isset($instance['class']) ? $instance['class'] : '';
        $show_title = (bool) $instance['show_title'];
        $menus = wp_get_nav_menus();
    ?>
        <p>
            <label for="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Title:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($title, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_html($this->get_field_id('menu', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Select Menu:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
            <select id="<?php echo $this->get_field_id('menu'); ?>" name="<?php echo $this->get_field_name('menu'); ?>" class="widefat">
                <option value="0"><?php _e('&mdash; Select &mdash;'); ?></option>
                <?php foreach ($menus as $menu) : ?>
                    <option value="<?php echo esc_attr($menu->slug); ?>" <?php selected($nav_menu, $menu->slug); ?>>
                        <?php echo esc_html($menu->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_title); ?> id="<?php echo esc_html($this->get_field_id('show_title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('show_title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" />
            <label for="<?php echo esc_html($this->get_field_id('show_title', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Display Menu Text?', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_html($this->get_field_id('class', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>"><?php esc_html_e('Additional CSS class:', IQONIC_EXTENSION_TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id('class', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" name="<?php echo esc_html($this->get_field_name('class', IQONIC_EXTENSION_TEXT_DOMAIN)); ?>" type="text" value="<?php echo esc_html($class, IQONIC_EXTENSION_TEXT_DOMAIN); ?>" />
        </p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['menu'] = $new_instance['menu'];
        $instance['class'] = $new_instance['class'];
        $instance['show_title'] = isset($new_instance['show_title']) ? (bool) $new_instance['show_title'] : false;
        return $instance;
    }
}
