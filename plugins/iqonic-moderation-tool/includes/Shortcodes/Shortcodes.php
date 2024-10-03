<?php

namespace IMT\Shortcodes;

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag\Em;
use IMT\Admin\Classes\IMT_Settings;

class Shortcodes
{
    protected $dependent_scripts, $types;

    public function init()
    {
        add_shortcode('imt_block_button', [$this, 'imt_get_block_button']);
        if (IMT_Settings::is_report_enable())
            add_shortcode('imt_report_button', [$this, 'imt_get_report_button']);

        $this->types = self::get_types();
        $this->dependent_scripts = self::get_dependancy();
    }

    public function imt_get_block_button($atts)
    {
        if (user_can($atts['member-id'], 'manage_options')) return;

        if (empty($atts) || !IMT_Settings::is_block_unblock_enable()) return;

        if (!isset($atts['member-id']) || empty($atts['member-id'])) return;

        if (get_current_user_id() == $atts['member-id']) return;

        $default = array(
            'member-id' => 0,
            'classes'   => ''
        );

        $args = shortcode_atts($default, $atts);

        return imt_get_block_button_template($args);
    }

    public function imt_get_report_button($atts)
    {
        if (empty($atts) || !IMT_Settings::is_report_enable() || !IMT_Settings::is_activity_report_enable()) return;

        if (!isset($atts['id']) || empty($atts['id'])) return;

        if (!isset($atts['type']) || empty($atts['type'])) return;

        if ($atts['type'] == "activity" && user_can(bp_get_activity_user_id($atts['id']), 'manage_options')) return;

        if ($atts['type'] == "activity" && get_current_user_id() == bp_get_activity_user_id($atts['id'])) return;

        $default = array(
            'id'        => 0,
            'page_id'   => '',
            'type'      => '',
            'classes'   => '',
            'name'  => 'Report',
            'content' => ''
        );

        $args = shortcode_atts($default, $atts);

        return imt_get_report_button_template($args);
    }

    public function imt_dependent_scripts()
    {
        global $wp_object_cache;
        $version = IQONIC_MODERATION_TOOL_VERSION;

        $dependent_scripts = isset($wp_object_cache->cache["imt-depedent-scripts"]) ? $wp_object_cache->cache["imt-depedent-scripts"] : false;
        $ajax_data = [];
        if ($dependent_scripts) {
            foreach ($this->dependent_scripts as $key => $script) {
                if (isset($dependent_scripts[$key])) {
                    if (isset($script["js"]) && !empty($script["js"]))
                        wp_enqueue_script($script["name"], IQONIC_MODERATION_TOOL_URL . 'includes/assets/js/' . $script["js"], array(), $version);
                    if (isset($script["css"]) && !empty($script["css"]))
                        wp_enqueue_style($script["name"], IQONIC_MODERATION_TOOL_URL . 'includes/assets/css/' . $script["css"], array(), $version);
                    if (isset($script["ajax_data"]) && !empty($script["ajax_data"])) {
                        $ajax_data[$script["ajax_obj"]] = $script["ajax_data"];
                    }
                }
            }
        }
        if (!empty($ajax_data)) {
            wp_register_script('imt_front_ajax_url', false);
            wp_localize_script('imt_front_ajax_url', 'imt_front_ajax_params', array(
                'ajaxUrl'           => admin_url('admin-ajax.php'), // WordPress AJAX
                'ajaxData'          => $ajax_data
            ));
            wp_enqueue_script('imt_front_ajax_url');
        }

        if (wp_cache_get("report-dependent-script", "imt-depedent-scripts")) {
            wp_enqueue_script("imt-modal", IQONIC_MODERATION_TOOL_URL . 'includes/assets/js/modal.js', array(), $version);
            wp_enqueue_style("imt-modal", IQONIC_MODERATION_TOOL_URL . 'includes/assets/css/modal.css', array(), $version);

            self::member_report_modal();

            echo '<div id="imt-modal-overlay"></div>';
        }
    }
    public function member_report_modal()
    {
?>
        <div class="imt-modal" id="imt-report-modal" style="display:none;">
            <div class="imt-modal-centered">
                <div class="card-main">
                    <div class="card-inner">
                        <div class="imt-modal-head">
                            <h4 id="imt-modal-title"><?php _e("Report Member", IQONIC_MODERATION_TEXT_DOMAIN); ?></h4>
                            <div class="imt-close-modal icon-close-2"></div>
                        </div>
                        <div class="imt-modal-body">
                            <?php echo imt_get_report_form_template(); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
<?php
    }
    public function get_dependancy()
    {
        return [
            "block-dependent-script"    => [
                "name"      => "imt-block-member",
                "css"       => "block-user.css",
                "js"        => "block-member.js",
                "ajax_obj"  => "blockAction",
                "ajax_data" => [
                    "action"        => "imt_block_unblock_member",
                    "blockLabel"    => esc_html__("Block", IQONIC_MODERATION_TEXT_DOMAIN),
                    "unblockLabel"  => esc_html__("Unblock", IQONIC_MODERATION_TEXT_DOMAIN)
                ]
            ],
            "report-dependent-script"   => [
                "name"      => "imt-report-front",
                "js"        => "report.js",
                "css"       => "",
                "ajax_obj"  => "reportAction",
                "ajax_data" => [
                    "action"        => "imt_report_form",
                    "reportLabel"   => esc_html__("Report", IQONIC_MODERATION_TEXT_DOMAIN),
                    "modalData"     => $this->types
                ]
            ]
        ];
    }
    public function get_types()
    {
        return [
            "member"    => esc_html__("Report Member", IQONIC_MODERATION_TEXT_DOMAIN),
            "activity"  => esc_html__("Report Activity", IQONIC_MODERATION_TEXT_DOMAIN),
            "group"     => esc_html__("Report Group", IQONIC_MODERATION_TEXT_DOMAIN)
        ];
    }
}
