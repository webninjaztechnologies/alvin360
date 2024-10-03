<?php

/**
 * BuddyPress - Activity Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the start of the activity loop.
 *
 * @since 1.2.0
 */

do_action('bp_before_activity_loop');

?>

<?php if (bp_has_activities(bp_ajax_querystring('activity'))) :

	if (empty($_POST['page'])) : ?>
		<ul id="activity-stream" class="activity-list  socialv-list-post">

			<li class="modal activitypopup fade" id="activityPopup" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
					<div class="modal-content">
						<div class="modal-header ">
							<div class="modal-user-name"></div>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="icon-close-2"></i></button>
						</div>
						<div class="modal-body "></div>
						<div class="modal-footer "></div>
					</div>
				</div>
			</li>


			<!-- on the click of share on activity option in share open a popup box with the post and add content text -->
			<li class="modal shareactivitypopup fade" id="shareactivitypopup" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
					<div class="modal-content">
						<div class="modal-header">
							<div class="modal-user-name"></div>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="icon-close-2"></i></button>
						</div>
						<div class="modal-body">
							<div class="share_activity-content"></div>
						</div>
						<div class="modal-footer">
							<div class="d-flex align-items-center gap-2 m-0">
								<div class="comment-textfield flex-grow-1">
									<input type="text" id="comment-input" placeholder="<?php esc_html_e('Add a comment' , 'socialv')?>">
								</div>
								<button id="share-btn" class="btn socialv-btn-primary repost-share-btn socialv-reshare-post"><?php esc_html_e('post' , 'socialv'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</li>	

		<?php endif;

	do_action("socialv_before_activity_loop");

	while (bp_activities()) : bp_the_activity();

		bp_get_template_part('activity/entry');

	endwhile;
	if (bp_activity_has_more_items()) : ?>

			<li class="load-more">
				<a class="socialv-loader" href="<?php bp_activity_load_more_link() ?>"></a>
			</li>

		<?php endif;

	if (empty($_POST['page'])) : ?>

		</ul>

	<?php endif;

else : ?>

	<div id="message" class="info">
		<p><?php esc_html_e('Sorry, there was no activity found. Please try a different filter.', 'socialv'); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the finish of the activity loop.
 *
 * @since 1.2.0
 */
do_action('bp_after_activity_loop');

if (empty($_POST['page'])) : ?>

	<form name="activity-loop-form" id="activity-loop-form" method="post">
		<?php wp_nonce_field('activity_filter', '_wpnonce_activity_filter'); ?>
	</form>

<?php endif;
