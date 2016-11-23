<?php

interface WP_Sitemap_Item_Interface {

	/**
	 * WP_Sitemap_Item_Interface constructor.
	 *
	 * @param string          $url
	 * @param null|int|string $id
	 */
	public function __construct( $url, $id = null );

	/**
	 * @return string
	 */
	public function get_url();

	/**
	 * @return string|null
	 */
	public function get_modified_date();

	/**
	 * @return int|string|null
	 */
	public function get_id();
}
