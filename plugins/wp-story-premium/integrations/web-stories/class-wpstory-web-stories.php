<?php
/**
 * Web stories integration.
 *
 * @package WP Story Premium
 */

use Wpstory\Web_Stories\Model\Story;

/**
 * Class Wpstory_Web_Stories
 */
class Wpstory_Web_Stories {
	private string $post_type = '';
	private string $post_type_label = '';

	public function __construct() {
		$this->post_type       = 'wpstory-web-story';
		$this->post_type_label = esc_html__( 'Web Stories', 'wp-story-premium' );

		// Override template.
		add_filter( 'template_include', array( $this, 'override_single' ) );

		// wpstory hooks.
		add_action( 'wpstory_web_story_head', array( $this, 'print_document_title' ) );
		add_action( 'wpstory_web_story_head', array( $this, 'print_metadata' ) );
		add_action( 'wpstory_web_story_head', array( $this, 'print_schemaorg_metadata' ) );
		add_action( 'wpstory_web_story_head', array( $this, 'print_twitter_metadata' ) );
		add_action( 'wpstory_web_story_head', array( $this, 'print_feed_link' ) );

		// WordPress core hooks.
		add_action( 'wpstory_web_story_head', 'rest_output_link_wp_head', 10, 0 );
		add_action( 'wpstory_web_story_head', 'wp_resource_hints', 2 );
		add_action( 'wpstory_web_story_head', 'feed_links', 2 );
		add_action( 'wpstory_web_story_head', 'feed_links_extra', 3 );
		add_action( 'wpstory_web_story_head', 'rsd_link' );
		add_action( 'wpstory_web_story_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		add_action( 'wpstory_web_story_head', 'wp_generator' );
		add_action( 'wpstory_web_story_head', 'rel_canonical' );
		add_action( 'wpstory_web_story_head', 'wp_shortlink_wp_head', 10, 0 );
		add_action( 'wpstory_web_story_head', 'wp_site_icon', 99 );
		add_action( 'wpstory_web_story_head', 'wp_oembed_add_discovery_links' );
		add_action( 'wpstory_web_story_head', 'wp_robots', 1 );
	}

	public function override_single( $template ) {
		if ( is_singular( 'wpstory-web-story' ) ) {
			$template = WPSTORY_PATH . 'integrations/web-stories/templates/single.php';
		}

		return $template;
	}

	public function print_document_title() {
		?>
		<title><?php echo esc_html( wp_get_document_title() ); ?></title>
		<?php
	}

	public function print_metadata() {
		?>
		<meta name="description" content="<?php echo esc_attr( wp_strip_all_tags( get_the_excerpt() ) ); ?>" />
		<?php
	}

	public function print_schemaorg_metadata() {
		$metadata = $this->get_schemaorg_metadata();
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( $metadata, JSON_UNESCAPED_UNICODE ); ?></script>
		<?php
	}

	public function print_twitter_metadata(): void {
		$metadata = $this->get_twitter_metadata();

		foreach ( $metadata as $name => $value ) {
			printf( '<meta name="%s" content="%s" />', esc_attr( $name ), esc_attr( $value ) );
		}
	}

	public function print_feed_link(): void {
		$enable_print_feed_link = current_theme_supports( 'automatic-feed-links' ) && ! is_post_type_archive( $this->post_type );

		$name = $this->post_type_label;

		if ( ! $name ) {
			return;
		}

		$feed = get_post_type_archive_feed_link( $this->post_type );

		if ( ! $feed ) {
			return;
		}

		$separator       = _x( '&raquo;', 'feed link', 'wp-story-premium' );
		$post_type_title = esc_html__( '%1$s %2$s %3$s Feed', 'wp-story-premium' );

		$title = sprintf( $post_type_title, get_bloginfo( 'name' ), $separator, $name );

		printf(
			'<link rel="alternate" type="%s" title="%s" href="%s">',
			esc_attr( feed_content_type() ),
			esc_attr( $title ),
			esc_url( $feed )
		);
	}

	protected function get_schemaorg_metadata() {
		$post = get_queried_object();

		$story = new Story();
		$story->load_from_post( $post );

		$metadata = [
			'@context'  => 'http://schema.org',
			'publisher' => [
				'@type' => 'Organization',
				'name'  => $story->get_publisher_name(),
			],
		];

		if ( $post instanceof WP_Post ) {
			$url  = $story->get_publisher_logo_url();
			$size = $story->get_publisher_logo_size();
			if ( ! empty( $url ) && ! empty( $size ) ) {
				$metadata['publisher']['logo'] = [
					'@type'  => 'ImageObject',
					'url'    => $url,
					'width'  => $size[0],
					'height' => $size[1],
				];
			}

			$poster      = $story->get_poster_portrait();
			$poster_size = $story->get_poster_portrait_size();
			if ( $poster && $poster_size ) {
				$metadata['image'] = [
					'@type'  => 'ImageObject',
					'url'    => $poster,
					'width'  => $poster_size[0],
					'height' => $poster_size[1],
				];

			}

			$metadata = array_merge(
				$metadata,
				[
					'@type'            => 'Article',
					'mainEntityOfPage' => $story->get_url(),
					'headline'         => $story->get_title(),
					'datePublished'    => mysql2date( 'c', $post->post_date_gmt, false ),
					'dateModified'     => mysql2date( 'c', $post->post_modified_gmt, false ),
				]
			);

			$post_author = get_userdata( (int) $post->post_author );

			if ( $post_author ) {
				$metadata['author'] = [
					'@type' => 'Person',
					'name'  => html_entity_decode( $post_author->display_name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
				];
			}
		}

		return $metadata;
	}

	protected function get_twitter_metadata() {
		$metadata = [
			'twitter:card' => 'summary_large_image',
		];

		$post = get_queried_object();

		if ( $post instanceof WP_Post ) {
			$story = new Story();
			$story->load_from_post( $post );
			$poster = $story->get_poster_portrait();
			if ( $poster ) {
				$metadata['twitter:image']     = esc_url( $poster );
				$metadata['twitter:image:alt'] = $story->get_title();
			}
		}

		return $metadata;
	}

	protected function get_open_graph_metadata() {
		$metadata = [
			'og:locale'    => get_bloginfo( 'language' ),
			'og:site_name' => get_bloginfo( 'name' ),
		];

		$post = get_queried_object();

		if ( $post instanceof WP_Post ) {
			$story = new Story();
			$story->load_from_post( $post );

			$metadata['og:type']                = 'article';
			$metadata['og:title']               = $story->get_title();
			$metadata['og:url']                 = $story->get_url();
			$metadata['og:description']         = wp_strip_all_tags( get_the_excerpt( $post ) );
			$metadata['article:published_time'] = (string) get_the_date( 'c', $post );
			$metadata['article:modified_time']  = (string) get_the_modified_date( 'c', $post );

			$poster_url   = $story->get_poster_portrait();
			$poster_sizes = $story->get_poster_portrait_size();

			if ( $poster_url && $poster_sizes ) {
				$metadata['og:image']        = esc_url( $poster_url );
				$metadata['og:image:width']  = $poster_sizes[0];
				$metadata['og:image:height'] = $poster_sizes[1];
			}
		}

		return $metadata;
	}
}

new Wpstory_Web_Stories();
