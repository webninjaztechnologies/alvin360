<?php

/**
 * Current user block list
 *
 * Override to change in button
 * 
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) return;

if (empty($args["member-id"])) return;

$member_id = $args["member-id"];

$blocked_users = imt_get_blocked_members_ids($member_id);

wp_cache_set("block-list-dependent-script", true, "block-list-imt-depedent-scripts");
?>
<div class="card-head card-header-border d-flex align-items-center justify-content-between">
    <div class="head-title">
        <h4 class="card-title"><?php _e("Blocked List", IQONIC_MODERATION_TEXT_DOMAIN) ?></h4>
    </div>
</div>
<?php if ($blocked_users) : ?>
    <div class="card-inner">
        <div id="members-dir-list" class="members dir-list  clearfix">
            <div id="members-list" class="imt-members-lists imt-members-lists imt-bp-main-box imt-bp-main-box row">
                <?php foreach ($blocked_users as $user_id) : ?>
                    <?php
                    $user_link = bp_members_get_user_url($user_id);
                    ?>
                    <div class="item-entry col-12">
                        <div class="imt-member-info">
                            <div class="imt-member-main">
                                <div class="imt-member-left item-avatar">
                                    <a href="<?php echo $user_link; ?>">
                                        <?php echo  bp_core_fetch_avatar(array('item_id' => $user_id, 'type'    => 'full', 'width' => 50, 'height' => 50, 'class' => 'rounded-circle')); ?>
                                    </a>
                                </div>
                                <div class="imt-member-center item-block">
                                    <div class="member-name">
                                        <h6 class="title">
                                            <a href="<?php echo $user_link; ?>">
                                                <?php echo bp_core_get_user_displayname($user_id); ?>
                                            </a>
                                        </h6>
                                    </div>
                                    <div class="imt-member-info-top">
                                        <i class="iconly-Calendar icli me-1"></i>
                                        <span class="imt-e-last-activity me-4" data-livestamp="<?php bp_core_iso8601_date(bp_get_last_activity($user_id)); ?>">
                                            <?php bp_last_activity($user_id); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="imt-member-right">
                                <div class="friendship-button generic-button">
                                    <?php imt_get_template_part("templates/block/block-unblock", "button", ["member-id" => $user_id, "classes" => "btn btn-sm"]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="no-user-blocked p-5">
        <?php esc_html_e("No blocked user found !", IQONIC_MODERATION_TEXT_DOMAIN); ?>
    </div>
<?php endif; ?>