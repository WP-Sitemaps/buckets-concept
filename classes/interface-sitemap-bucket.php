<?php

interface WP_Sitemap_Bucket_Interface {

	/**
	 * WP_Sitemap_Bucket_Proxy_Interface constructor.
	 *
	 * @param string $identifier
	 */
	public function __construct( $identifier );

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function add_item( WP_Sitemap_Item_Interface $sitemap_item );

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function remove_item( WP_Sitemap_Item_Interface $sitemap_item );

	/**
	 * @param int $offset
	 * @param int $number_of_items
	 *
	 * @return array
	 */
	public function get_items( $offset = 0, $number_of_items = -1 );

	/**
	 * @return string
	 */
	public function get_last_modified_date();

}