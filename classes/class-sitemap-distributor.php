<?php

class WP_Sitemap_Distributor {

	/** @var WP_Sitemap_Control */
	private $sitemap_control;

	/**
	 * WP_Sitemap_Distributor constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_Control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_Control ) {
		$this->sitemap_control = $sitemap_Control;
	}

	/**
	 * @return WP_Sitemap_Bucket_Finder
	 */
	protected function get_bucket_finder() {
		return $this->sitemap_control->get_bucket_finder();
	}

	/**
	 * @return WP_Sitemap_Bucket_Factory
	 */
	protected function get_bucket_factory() {
		return $this->sitemap_control->get_bucket_factory();
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function distribute( WP_Sitemap_Item_Interface $sitemap_item ) {

		$current_bucket = $this->get_bucket( $sitemap_item );

		if ( $this->should_be_in_sitemap( $sitemap_item ) ) {
			if ( is_null( $current_bucket ) ) {
				$this->add_to_active_bucket( $sitemap_item );
			}

			return;
		}

		if ( $current_bucket instanceof WP_Sitemap_Bucket_Interface ) {
			$this->remove_from_bucket( $sitemap_item, $current_bucket );
		}
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 *
	 * @return bool
	 */
	protected function should_be_in_sitemap( WP_Sitemap_Item_Interface $sitemap_item ) {
		if ( $this->sitemap_control->get_settings()->is_excluded_from_sitemap( $sitemap_item->get_url() ) ) {
			return false;
		}

		$id   = $sitemap_item->get_id();
		$post = get_post( $id );

		if ( ! empty( $post->post_password ) ) {
			return false;
		}

		$post_status = get_post_status( $id );
		if ( 'publish' !== $post_status ) {

			// @todo filterable (core?)

			return false;
		}

		$post_type = get_post_type( $id );
		if ( ! is_post_type_viewable( get_post_type_object( $post_type ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 *
	 * @return null|WP_Sitemap_Bucket_Proxy
	 */
	protected function get_bucket( WP_Sitemap_Item_Interface $sitemap_item ) {
		return $this->get_bucket_finder()->get_bucket( $sitemap_item );
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	protected function add_to_active_bucket( WP_Sitemap_Item_Interface $sitemap_item ) {
		$active_bucket = $this->get_bucket_factory()->get_active_bucket();

		if ( $active_bucket instanceof WP_Sitemap_Bucket_Interface ) {
			$active_bucket->add_item( $sitemap_item );
		}
	}

	/**
	 * @param WP_Sitemap_Item_Interface   $sitemap_item
	 * @param WP_Sitemap_Bucket_Interface $sitemap_bucket
	 */
	protected function remove_from_bucket( WP_Sitemap_Item_Interface $sitemap_item, WP_Sitemap_Bucket_Interface $sitemap_bucket ) {
		$sitemap_bucket->remove_item( $sitemap_item );
	}

}
