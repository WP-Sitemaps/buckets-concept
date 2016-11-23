<?php

interface WP_Sitemap_Bucket_Factory_Interface {

	/**
	 * WP_Sitemap_Bucket_Factory constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_Control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_Control );

	/**
	 * @param string $identifier
	 *
	 * @return WP_Sitemap_Bucket_Proxy
	 */
	public function get_bucket( $identifier );

	/**
	 * @return WP_Sitemap_Bucket_Proxy
	 */
	public function get_active_bucket();

}
