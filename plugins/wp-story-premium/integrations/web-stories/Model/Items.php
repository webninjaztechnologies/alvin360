<?php

namespace Wpstory\Web_Stories\Model;

class Items {
	protected object $story;
	protected array $items = [];
	protected int $story_id = 0;

	/**
	 * @param Story $story Story class object.
	 *
	 * @return void
	 */
	public function __construct( $story ) {
		$this->story = $story;

		$this->set_items();
	}

	protected function set_items() {
		$meta_items = get_post_meta( $this->story->get_id(), 'wp_story_items', true );

		if ( ! empty( $meta_items ) ) {
			$this->items = $meta_items;
		}
	}

	public function get_items() {
		return $this->items;
	}
}