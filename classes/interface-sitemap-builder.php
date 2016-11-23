<?php

interface WP_Sitemap_Builder_Interface {
	/**
	 * @param WP_Sitemap_Bucket_Interface $bucket
	 *
	 * @return void
	 */
	public function build( WP_Sitemap_Bucket_Interface $bucket );
}
