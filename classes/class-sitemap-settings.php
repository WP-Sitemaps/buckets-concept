<?php

class WP_Sitemap_Settings {

	/**
	 * The number of items per bucket
	 *
	 * @return int
	 */
	public function get_items_per_bucket() {
		return 1000;
	}

	/**
	 * Test to see if the URL should be excluded from the sitemap
	 *
	 * @param string $url URL to test for exclusion
	 *
	 * @return bool
	 */
	public function is_excluded_from_sitemap( $url ) {
		$exclude = apply_filters( 'sitemap_exclude_url', false, $url );
		if ( is_null( $exclude ) || false === $exclude ) {
			return false;
		}

		return true;
	}

}