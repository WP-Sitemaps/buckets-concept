<?php

class WP_Sitemap_Index implements WP_Sitemap_Item_Interface {

	private $url;
	private $id;

	/**
	 * WP_Sitemap_Index constructor.
	 *
	 * @param string          $url
	 * @param null|string|int $id
	 */
	public function __construct( $url, $id = null ) {
		$this->url = $url;
		$this->id  = $id;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * @return null|string
	 */
	public function get_modified_date() {

		if ( ! is_null( $this->id ) ) {
			return get_the_modified_date( $this->id );
		}

		return apply_filters( 'sitemap_post_modified_date', null, $this->get_url(), $this );

	}

	/**
	 * @return int|null|string
	 */
	public function get_id() {
		return $this->id;
	}
}