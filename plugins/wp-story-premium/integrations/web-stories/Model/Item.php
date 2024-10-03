<?php

namespace Wpstory\Web_Stories\Model;

class Item {
	protected array $item = [];
	protected string $media_type = '';
	protected int $media_id = 0;
	protected string $media_url = '';
	protected array $media_sizes = [];
	protected array $media_metadata = [];
	protected string $button_url = '';
	protected string $button_text = '';
	protected int $duration = 0;
	protected string $title = '';

	/**
	 * @param array $item_array
	 *
	 * @return void
	 */
	public function __construct( $item_array ) {
		$this->item = $item_array;

		$this->set_media_id();
		$this->set_media_metadata();
		$this->set_media_type();
		$this->set_media_url();
		$this->set_media_sizes();
		$this->set_button_url();
		$this->set_button_text();
		$this->set_duration();
		$this->set_title();
	}

	protected function set_media_id() {
		if ( ! empty( $this->item['image']['id'] ) ) {
			$this->media_id = (int) $this->item['image']['id'];
		}
	}

	public function get_media_id() {
		return $this->media_id;
	}

	protected function set_media_type() {
		$type = 'image';

		if ( wp_attachment_is( 'video', $this->media_id ) ) {
			$type = 'video';
		}

		$this->media_type = $type;
	}

	public function get_media_type() {
		return $this->media_type;
	}

	protected function set_media_url() {
		if ( empty( $this->item['image']['url'] ) ) {
			return;
		}

		$this->media_url = $this->item['image']['url'];
	}

	public function get_media_url() {
		return $this->media_url;
	}

	protected function set_media_sizes() {
		if ( empty( $this->media_id ) ) {
			return;
		}

		$metadata = $this->media_metadata;
		$width    = $metadata['width'];
		$height   = $metadata['height'];

		$this->media_sizes = [ $width, $height ];
	}

	public function get_media_sizes() {
		return $this->media_sizes;
	}

	protected function set_button_url() {
		if ( empty( $this->item['link'] ) ) {
			return;
		}

		$this->button_url = $this->item['link'];
	}

	public function get_button_url() {
		return $this->button_url;
	}

	protected function set_button_text() {
		if ( empty( $this->item['text'] ) ) {
			return;
		}

		$this->button_text = $this->item['text'];
	}

	public function get_button_text() {
		return $this->button_text;
	}

	public function get_mixed_button_text() {
		return $this->button_text ?? esc_url( $this->button_url );
	}

	protected function set_duration() {
		if ( empty( $this->item['duration'] ) ) {
			return;
		}

		$this->duration = (int) $this->item['duration'];
	}

	public function get_duration() {
		if ( 'image' === $this->get_media_type() ) {
			return $this->duration;
		}

		$video_meta  = $this->media_metadata;
		$time_length = $video_meta['length_formatted'] ?? null;

		if ( empty( $time_length ) ) {
			return 0;
		}

		return WPSTORY()->time_to_sec( $time_length );
	}

	protected function set_media_metadata() {
		$this->media_metadata = wp_get_attachment_metadata( $this->media_id );
	}

	protected function set_title() {
		if ( empty( $this->item['title'] ) ) {
			return;
		}

		$this->title = $this->item['title'];
	}

	public function get_title() {
		return $this->title;
	}
}