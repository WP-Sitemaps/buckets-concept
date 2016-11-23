<?php

class WP_Sitemap_Bucket_Finder implements WP_Sitemap_Bucket_Finder_Interface {

	/** @var WP_Sitemap_Control */
	protected $sitemap_control;

	/**
	 * WP_Sitemap_Bucket_Finder constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_control ) {
		$this->sitemap_control = $sitemap_control;
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 *
	 * @return null|WP_Sitemap_Bucket_Proxy
	 */
	public function get_bucket( WP_Sitemap_Item_Interface $sitemap_item ) {

		$bucket = apply_filters( 'sitemap_item_bucket', null, $sitemap_item->get_url() );

		if ( is_null( $bucket ) ) {
			$bucket = $this->get_bucket_from_meta( $sitemap_item );
		}

		if ( is_null( $bucket ) ) {
			return null;
		}

		return $this->sitemap_control->get_bucket_factory()->get_bucket( $bucket );
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 *
	 * @return null|int Bucket ID
	 */
	protected function get_bucket_from_meta( WP_Sitemap_Item_Interface $sitemap_item ) {

		$post_id = $sitemap_item->get_id();
		$bucket  = get_post_meta( $post_id, 'sitemap_bucket', true );

		// '', null or 0 are all invalid.
		if ( empty( $bucket ) ) {
			return null;
		}

		return $bucket;

	}
}
