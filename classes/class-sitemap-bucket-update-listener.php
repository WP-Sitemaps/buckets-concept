<?php

class WP_Sitemap_Bucket_Update_Listener {

	/** @var WP_Sitemap_Control */
	protected $sitemap_control;

	/**
	 * WP_Sitemap_Bucket_Update_Listener constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_control ) {
		$this->sitemap_control = $sitemap_control;
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'sitemap_post_bucket_item_add', array( $this, 'bump_bucket_item_count' ), 10, 1 );
		add_action( 'sitemap_post_bucket_update', array( $this, 'refresh_bucket_cache' ), 10, 3 );
		add_action( 'sitemap_post_bucket_item_remove', array( $this, 'remove_empty_bucket' ), 10, 3 );
	}

	/**
	 * Whenever a new item is added to the bucket, increment the item count
	 *
	 * @param int $post_id
	 */
	public function bump_bucket_item_count( $post_id ) {
		$current_items = (int) get_post_meta( $post_id, WP_Sitemap_Bucket_Factory::ITEM_COUNT_META_KEY, false );
		update_post_meta( $post_id, WP_Sitemap_Bucket_Factory::ITEM_COUNT_META_KEY, $current_items + 1 );
	}

	/**
	 * @param int                         $post_id
	 * @param WP_Sitemap_Item_Interface   $sitemap_item
	 * @param WP_Sitemap_Bucket_Interface $bucket
	 */
	public function remove_empty_bucket( $post_id, WP_Sitemap_Item_Interface $sitemap_item, WP_Sitemap_Bucket_Interface $bucket ) {
		if ( $bucket->get_items() === array() ) {
			$this->sitemap_control->get_bucket_factory()->remove_bucket( $post_id );
		}
	}

	/**
	 * @param int                         $post_id
	 * @param WP_Sitemap_Item_Interface   $sitemap_item
	 * @param WP_Sitemap_Bucket_Interface $bucket
	 */
	public function refresh_bucket_cache( $post_id, WP_Sitemap_Item_Interface $sitemap_item, WP_Sitemap_Bucket_Interface $bucket ) {

		$builders = $this->sitemap_control->get_builders( get_class( $bucket ) );

		if ( ! is_array( $builders ) || array() === $builders ) {
			return;
		}

		foreach ( $builders as $builder ) {
			$builder->build( $bucket );
		}
	}
}
