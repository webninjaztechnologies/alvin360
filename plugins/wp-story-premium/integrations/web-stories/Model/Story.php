<?php

namespace Wpstory\Web_Stories\Model;

use WP_Post;

class Story {
	protected int $id = 0;
	protected string $title = '';
	protected string $excerpt = '';
	protected string $url = '';
	protected string $publisher_name = '';
	protected string $publisher_logo = '';
	protected array $publisher_logo_size = [];
	protected string $poster_sizes = '';
	protected string $poster_portrait = '';
	protected string $poster_srcset = '';
	protected array $poster_portrait_size = [];

	public function load_from_post( $post ) {
		$this->publisher_name = get_bloginfo( 'name' );

		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		$this->id      = $post->ID;
		$this->title   = get_the_title( $post );
		$this->excerpt = $post->post_excerpt;
		$this->url     = (string) get_permalink( $post );

		$thumbnail_id = (int) get_post_thumbnail_id( $post );

		if ( 0 !== $thumbnail_id ) {
			$poster_src = wp_get_attachment_image_src( $thumbnail_id, 'medium' );

			if ( $poster_src ) {
				[ $poster_url, $width, $height ] = $poster_src;
				$this->poster_portrait      = $poster_url;
				$this->poster_portrait_size = [ (int) $width, (int) $height ];

				$image_meta = wp_get_attachment_metadata( $thumbnail_id );
				if ( $image_meta ) {
					$size_array          = [ $image_meta['width'], $image_meta['height'] ];
					$this->poster_sizes  = (string) wp_calculate_image_sizes( $size_array, $poster_url, $image_meta, $thumbnail_id );
					$this->poster_srcset = (string) wp_calculate_image_srcset( $size_array, $poster_url, $image_meta, $thumbnail_id );
				}
			}
		}

		$publisher_logo    = get_post_meta( $this->id, 'wpstory_publisher_logo', true );
		$publisher_logo_id = $publisher_logo['id'] ?? null;

		if ( ! empty( $publisher_logo_id ) ) {
			$img_src = wp_get_attachment_image_src( (int) $publisher_logo_id, 'medium' );

			if ( $img_src ) {
				[ $src, $width, $height ] = $img_src;
				$this->publisher_logo_size = [ $width, $height ];
				$this->publisher_logo      = $src;
			}
		}
	}

	public function get_id(): int {
		return $this->id;
	}

	public function get_publisher_name() {
		return $this->publisher_name;
	}

	public function get_publisher_logo_url() {
		return $this->publisher_logo;
	}

	public function get_publisher_logo_size() {
		return $this->publisher_logo_size;
	}

	public function get_poster_portrait() {
		return $this->poster_portrait;
	}

	public function get_poster_portrait_size() {
		return $this->poster_portrait_size;
	}

	public function get_url() {
		return $this->url;
	}

	public function get_title() {
		return $this->title;
	}
}
