<?php

namespace Iqonic\Acf;

class MetaBox
{
    public function __construct()
    {
        add_action('wp_ajax_socialv_ajax_menu_get_image', [$this, 'iqonic_ajax_menu_get_image']);
        add_action('wp_nav_menu_item_custom_fields', [$this, 'iqonic_menu_icon'], 10, 2);
        add_action('wp_update_nav_menu_item', [$this, 'iqonic_menu_icon_update'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'iqonic_media_library_enque']);
        add_filter('nav_menu_item_args', [$this, 'iqonic_nav_menu_link_atts'], 10, 3);
    }

    // Menu Icon
    public function iqonic_ajax_menu_get_image() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
        if (!$id) {
            wp_send_json_error();
        }
    
        $image = wp_get_attachment_image($id, 'medium', false, array(
            'id' => 'socialv-preview-image',
            'style' => 'padding:5px;float:right;height:25px;width:25px;'
        ));
    
        $data = array(
            'image' => $image,
        );
    
        wp_send_json_success($data);
    }
    


    public function iqonic_menu_icon($item_id, $item)
    {
        $menu_icon = get_post_meta($item_id, '_select_icon', true);

        $image_id = get_option('socialv_image_id');
        $image = null;
        if (intval($image_id) > 0) {
            $image = wp_get_attachment_image($image_id, 'medium', false, array('id' => 'socialv-preview-image'));
        } else {
            $img_url = wp_get_attachment_image_url($menu_icon);
            if ($img_url != null) {
                $image = '<img id="socialv-preview-image" src="' . esc_url($img_url) . '" style="padding:5px;float:right;height:25px;width:25px;"/>';
            }
        }
        echo wp_kses_post($image); ?>
        <input type="hidden" name="menu_icon_id[<?php echo esc_html($item_id) ?>]" id="socialv_image_id" value="<?php echo esc_attr($menu_icon); ?>" class="regular-text menu_icon_id" />
        <input type='button' class="button-primary socialv_media_manager" name="menu_icon[<?php echo esc_html($item_id)  ?>]" value="<?php esc_attr_e('Select Icon', IQONIC_EXTENSION_TEXT_DOMAIN); ?> " id="menu-icon-[<?php echo esc_attr($item_id) ?>]" />
<?php
    }


    public function iqonic_menu_icon_update($menu_id, $menu_item_db_id)
    {
        if (isset($_POST['menu_icon_id'])) {
            $sanitized_data = sanitize_text_field($_POST['menu_icon_id'][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, '_select_icon', $sanitized_data);
        } else {
            delete_post_meta($menu_item_db_id, '_select_icon');
        }
    }

    public function iqonic_media_library_enque()
    {
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }
        wp_enqueue_script('socialv-media-script',  IQONIC_EXTENSION_PLUGIN_URL . 'includes/assets/js/media-script.js', array('jquery'), IQONIC_EXTENSION_VERSION, true);
    }


    public function iqonic_nav_menu_link_atts($atts, $item, $args)
    {
        if ($atts->theme_location == 'primary') {
            $menu_icon = get_post_meta($item->ID, '_select_icon', true);
            
            if (!empty($menu_icon)) {
                $img = wp_get_attachment_image_url($menu_icon);
                $img_alt = get_post_meta($menu_icon, '_wp_attachment_image_alt', true);
                
                if (pathinfo($img, PATHINFO_EXTENSION) == 'svg') {
                    $icon = '<i class="icon">' . file_get_contents($img) . '</i>';
                } elseif (in_array(pathinfo($img, PATHINFO_EXTENSION), ['png', 'jpg', 'jpeg'])) {
                    $icon = '<i class="icon"><img src="' . esc_url($img) . '" alt="' . esc_attr($img_alt) . '"></i>';
                }
            } else {
                $icon = '';
            }
            
            $item->title = $icon . '<span class="menu-title">' . $item->title . '</span>';
        }
        
        return $item;
    }
    
}
