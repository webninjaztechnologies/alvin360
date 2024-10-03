<?php

/**
 * Template part for displaying the page content when a 404 error has occurred
 *
 * @package socialv
 */

namespace SocialV\Utility;

$socialv_options = get_option('socialv-options');
?>

<div class="<?php echo apply_filters('content_container_class', 'container'); ?>">
	<div class="content-area">
		<main class="site-main">
			<div class="error-404 not-found">
				<div class="page-content">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php
							$bgurl = (!empty($socialv_options['404_banner_image']['url'])) ? $socialv_options['404_banner_image']['url'] : (get_template_directory_uri() . '/assets/images/redux/404.png');
							?>
							<div class="fourzero-image mb-5">
								<img src="<?php echo esc_url($bgurl); ?>" loading="lazy" alt="<?php esc_attr_e('404', 'socialv'); ?>" />
							</div>

							<?php
							$four_title = (!empty($socialv_options['404_title'])) ? esc_html($socialv_options['404_title']) : esc_html__('Page Not Found.', 'socialv');
							echo '<h2>' . $four_title . '</h2>';

							$four_des = (!empty($socialv_options['404_description'])) ? esc_html($socialv_options['404_description']) : esc_html__('The requested page does not exist.', 'socialv');
							echo '<p class="mb-5">' . $four_des . '</p>';
							?>

							<div class="d-block">
								<?php
								$btn_text  = (!empty($socialv_options['404_backtohome_title'])) ? esc_html($socialv_options['404_backtohome_title']) : esc_html__('Back to Home', 'socialv');
								socialv()->socialv_get_blog_readmore(home_url(), $btn_text); ?>
							</div>
						</div>
					</div>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .container -->