<?php

interface WP_Sitemap_Bucket_Finder_Interface {
	/**
	 * WP_Sitemap_Bucket_Finder constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_control );

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 *
	 * @return null|WP_Sitemap_Bucket_Proxy
	 */
	public function get_bucket( WP_Sitemap_Item_Interface $sitemap_item );
}
