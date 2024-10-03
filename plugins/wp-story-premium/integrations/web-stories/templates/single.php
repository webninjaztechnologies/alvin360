<?php
/**
 * AMP Web Stories single page template.
 *
 * @package WP Story Premium
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> amp>
<head>
	<meta name="amp-story-generator-name" content="WP Story" />
	<meta name="amp-story-generator-version" content="<?php echo esc_attr( WPSTORY_PREMIUM_VERSION ); ?>" />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<script
		async
		custom-element="amp-story"
		src="https://cdn.ampproject.org/v0/amp-story-1.0.js"
	></script>
	<script
		async
		custom-element="amp-video"
		src="https://cdn.ampproject.org/v0/amp-video-0.1.js"
	></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap"
		rel="stylesheet"
	>
	<style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
	</style>
	<style amp-custom>
        body {
            font-family: 'Roboto', sans-serif;
        }
	</style>
	<noscript>
		<style amp-boilerplate>
            body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }
		</style>
	</noscript>
	<?php do_action( 'wpstory_web_story_head' ); ?>
</head>
<body>
<?php
$post  = get_queried_object();
$story = new \Wpstory\Web_Stories\Model\Story();
$story->load_from_post( $post );

$items     = new \Wpstory\Web_Stories\Model\Items( $story );
$items_arr = $items->get_items();

$share_providers = [
	[
		'provider' => 'facebook',
	],
	[
		'provider' => 'twitter',
	],
	[
		'provider' => 'linkedin',
	],
	[
		'provider' => 'whatsapp',
	],
	[
		'provider' => 'pinterest',
	],
	[
		'provider' => 'email',
	],
	[
		'provider' => 'system',
	],
];

$share_config = [
	'shareProviders' => $share_providers,
];

$social_share = sprintf(
	'<amp-story-social-share layout="nodisplay"><script type="application/json">%s</script></amp-story-social-share>',
	wp_json_encode( $share_config )
);
?>
<amp-story
	standalone
	title="<?php echo esc_attr( $story->get_title() ); ?>"
	publisher="<?php echo esc_attr( $story->get_publisher_name() ); ?>"
	publisher-logo-src="<?php echo esc_attr( $story->get_publisher_logo_url() ); ?>"
	poster-portrait-src="<?php echo esc_attr( $story->get_poster_portrait() ); ?>"
>
	<?php
	echo $social_share;

	foreach ( $items_arr as $item ) :
		$item = new \Wpstory\Web_Stories\Model\Item( $item );

		if ( empty( $item->get_media_id() ) ) {
			continue;
		}

		$media_url = $item->get_media_url();
		$sizes     = $item->get_media_sizes();
		$duration  = $item->get_duration();
		?>
		<amp-story-page
			<?php echo ! empty( $duration ) ? 'auto-advance-after="' . $duration . 's"' : ''; ?>
			id="wpstory-web-story-<?php echo $item->get_media_id(); ?>"
		>
			<amp-story-grid-layer template="fill">
				<?php if ( 'image' === $item->get_media_type() ) : ?>
					<amp-img
						src="<?php echo esc_url( $media_url ); ?>"
						width="<?php echo esc_attr( $sizes[0] ); ?>"
						height="<?php echo esc_attr( $sizes[1] ); ?>"
						alt=""
					>
					</amp-img>
				<?php else : ?>
					<amp-video
						autoplay
						loop
						grid-area="middle-third"
						width="<?php echo esc_attr( $sizes[0] ); ?>"
						height="<?php echo esc_attr( $sizes[1] ); ?>"
						poster=""
						layout="responsive"
						alt=""
					>
						<source src="<?php echo esc_url( $media_url ); ?>" type="video/mp4">
					</amp-video>
				<?php endif; ?>
			</amp-story-grid-layer>
			<?php if ( ! empty( $item->get_title() ) ) : ?>
				<amp-story-grid-layer
					template="vertical"
					animate-in="fly-in-left"
					animate-in-duration="0.5s"
				>
					<h1 style="margin: 0;font-size: 1.5em;color: #000;">
						<span
							style="background-color: #fff;padding: 1.2% 2.4%;-webkit-box-decoration-break: clone;"
						><?php echo esc_attr( $item->get_title() ); ?></span>
					</h1>
				</amp-story-grid-layer>
			<?php endif; ?>
			<?php if ( ! empty( $item->get_button_url() ) ) : ?>
				<amp-story-page-outlink layout="nodisplay">
					<a
						href="<?php echo esc_url( $item->get_button_url() ); ?>"
					><?php echo esc_html( $item->get_mixed_button_text() ); ?></a>
				</amp-story-page-outlink>
			<?php endif; ?>
		</amp-story-page>
	<?php endforeach; ?>
</amp-story>
</body>
</html>
