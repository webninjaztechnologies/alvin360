<?php

/**
 * User profile template if current user blocked displayed user.
 *
 * Override to change template
 * 
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) return;

$user_id = !empty($args['displayed_user_id']) ? $args['displayed_user_id'] : get_current_user_id();
$default_avatar_img = apply_filters("blocked_user_default_avatar_image", IQONIC_MODERATION_TOOL_URL . "includes/assets/images/default-avatar.jpg");
$default_cover_img = apply_filters("blocked_user_default_cover_image", "");
$style = !empty($default_cover_img) ? "background-image:url($default_cover_img);" : "background-image:url()";
?>
<div id="item-header" role="complementary">

    <div id="cover-image-container">
        <div class="header-cover-image ">
            <div id="header-cover-image" class="header-cover-img" style="<?php echo $style; ?>"></div>
        </div>
    </div>

</div><!-- #item-header -->
<div class="card-main socialv-profile-box">
    <div class="card-inner">
        <div class="item-header-cover-image-wrapper">
            <div id="item-header-cover-image">

                <div id="item-header-content" class="row align-items-center">
                    <div class="col-lg-4"></div>

                    <div class="socialv-profile-center col-lg-4">
                        <div class="header-avatar">

                            <img loading="lazy" src="<?php echo esc_url($default_avatar_img); ?>" class="rounded user-69-avatar avatar-150 photo" alt="Profile Photo" width="150" height="150">

                        </div><!-- #item-header-avatar -->
                        <h5 class="profile-user-nicename"><?php echo bp_get_displayed_user_fullname(); ?></h5>
                        <div class="socialv-userinfo">

                            <div class="info-meta"><i class="iconly-Location icli"></i><span class="socialv-profile-member-location"><?php echo esc_html("Location", IQONIC_MODERATION_TEXT_DOMAIN); ?></span></div>

                            <div class="info-meta"><i class="icon-web"></i><span class="socialv-profile-member-website"><a href="#"><?php echo esc_html("Website", IQONIC_MODERATION_TEXT_DOMAIN); ?></a></span></div>

                        </div>

                        <div class="socialv-profile-tab-button" id="members-dir-list">
                            <!--  Message Button -->
                            <?php do_action('imt_block_member_header_actions'); ?>
                            <?php imt_get_template_part("templates/block/block-unblock", "button", ["member-id" => $user_id, "classes" => "btn btn-sm imt-btn-success socialv-btn-success"]); ?>

                        </div>
                    </div>

                    <div class="socialv-profile-right col-lg-4">
                        <ul class="socialv-user-meta list-inline">

                            <li>
                                <h5><?php _e(0, IQONIC_MODERATION_TEXT_DOMAIN);  ?></h5>
                                <?php _e('Posts', IQONIC_MODERATION_TEXT_DOMAIN);
                                ?>
                            </li>

                            <li>
                                <h5><?php _e(0, IQONIC_MODERATION_TEXT_DOMAIN);  ?></h5>
                                <?php _e('Comments', IQONIC_MODERATION_TEXT_DOMAIN); ?>
                            </li>

                            <li>
                                <h5><?php _e(0, IQONIC_MODERATION_TEXT_DOMAIN);  ?></h5>
                                <?php _e('Views', IQONIC_MODERATION_TEXT_DOMAIN); ?>
                            </li>

                        </ul>
                    </div>

                </div><!-- #item-header-content -->

            </div><!-- #item-header-cover-image -->
        </div><!-- .item-header-cover-image-wrapper -->
    </div>
</div>