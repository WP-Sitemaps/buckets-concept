<?php

class WP_Sitemap_Bucket implements WP_Sitemap_Bucket_Interface {

	/** @var string */
	private $identifier;

	/**
	 * WP_Sitemap_Bucket constructor.
	 *
	 * @param string $identifier
	 */
	public function __construct( $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function add_item( WP_Sitemap_Item_Interface $sitemap_item ) {

		$post_id = $sitemap_item->get_id();
		// If we don't have an ID it's a custom entry.
		if ( is_null( $post_id ) ) {
			do_action( 'sitemap_add_bucket_item', $this->identifier, $sitemap_item );

			return;
		}

		// Otherwise it's a 'post' based element.
		update_post_meta( $post_id, 'sitemap_bucket', $this->identifier );
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function remove_item( WP_Sitemap_Item_Interface $sitemap_item ) {

		$post_id = $sitemap_item->get_id();
		// If we don't have an ID it's a custom entry.
		if ( is_null( $post_id ) ) {
			do_action( 'sitemap_remove_bucket_item', $this->identifier, $sitemap_item );

			return;
		}

		// Otherwise it's a 'post' based element.
		delete_post_meta( $post_id, 'sitemap_bucket' );
	}

	/**
	 * @param int $offset
	 * @param int $number_of_items
	 *
	 * @return array
	 */
	public function get_items( $offset = 0, $number_of_items = - 1 ) {

		$item_query = new WP_Query(
			array(
				'meta_key'       => 'sitemap_bucket',
				'meta_value'     => $this->identifier,
				'posts_per_page' => $number_of_items,
				'paged'          => $offset + 1
			)
		);

		$items = $item_query->posts;

		// @todo add filter.

		return $items;

	}

	/**
	 * @param bool $gmt
	 *
	 * @return null|string
	 */
	public function get_last_modified_date( $gmt = true ) {
		$item_query = new WP_Query(
			array(
				'meta_key'       => 'sitemap_bucket',
				'meta_value'     => $this->identifier,
				'posts_per_page' => 1,
				'paged'          => 1,
				'orderby'        => 'modified',
				'order'          => 'DESC',
				'fields'         => 'ids'
			)
		);

		$last_modified = null;

		if ( $item_query->have_posts() ) {
			$last_modified = get_post_modified_time( 'c', $gmt, $item_query->posts[0] );
		}

		// @todo add filter.

		return $last_modified;
	}

}
