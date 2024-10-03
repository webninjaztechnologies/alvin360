<?php

function Iqonic_author_info()
{
    register_widget('Iqonic_author');
}
add_action('widgets_init', 'Iqonic_author_info');

/*-------------------------------------------
		Iqonic Contact Information widget 
--------------------------------------------*/
class Iqonic_Author extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'Iqonic_author',

            // Widget name will appear in UI
            esc_html__('Iqonic Author', IQONIC_EXTENSION_TEXT_DOMAIN),

            // Widget description
            array('description' => esc_html('Iqonic Author. ', IQONIC_EXTENSION_TEXT_DOMAIN),)
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : false;

        // /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $social_handle = [
            "name_facebook" => esc_html__("FB", IQONIC_EXTENSION_TEXT_DOMAIN),
            "name_twitter"  => esc_html__("TW", IQONIC_EXTENSION_TEXT_DOMAIN),
            "name_linkedin" => esc_html__("LN", IQONIC_EXTENSION_TEXT_DOMAIN),
            "name_behance"  => esc_html__("BE", IQONIC_EXTENSION_TEXT_DOMAIN)
        ];
        /* here add extra display item  */

        echo '<div class="blog_widget widget socialv-widget-author">
            <div class="author-info-details">';
        if ($title) {
            echo '<h5 class="widget-title">' . esc_html($title) . '</h5>';
        }
        echo '</div>
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-5">';

        // Author Image Start
        $author_avtar_url = get_avatar_url(get_the_author_meta('ID'));
        echo  '<img src=" ' . esc_url($author_avtar_url) . '" alt="' . esc_attr__('Author', IQONIC_EXTENSION_TEXT_DOMAIN) . '"';
        // <!-- Author Image End -->

        echo '</div>
        <div class="col-sm-7 mt-sm-0 mt-4">';

        // Author Title start
        echo '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '" class="socialv-user">
                 <h5 class="socialv-admin">
                      ' . esc_html(get_the_author()) . '
                 </h5>
            </a>';
        //  Author Title End 


        //  Description start 
        echo '<p class="mb-0">' . get_the_author_meta('description') . '</p>';
        //  Description End 

        // Social Media Start
        echo '<div class="socialv-author-social">
                        <ul class="info-share d-flex">';
        foreach ($social_handle as $key => $label) {
            $social = get_the_author_meta($key);
            if (!empty($social)) {

                echo '<li><a target="_blank" href="' . esc_url($social) . '">' . esc_html($label) . ' </a></li>';
            }
        }
        echo '</ul></div></div></div></div>';
        // fb ln tw 
    }

    // Widget Backend 
    public function form($instance)
    {
        $title     = isset($instance['title']) ? esc_attr($instance['title']) : '';
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
/*---------------------------------------
		Class wpb_widget ends here
----------------------------------------*/
