<?php

class WP_Sitemap_Bucket_Proxy implements WP_Sitemap_Bucket_Interface {

	/** @var int */
	protected $post_id;

	/** @var WP_Sitemap_Bucket */
	private $bucket;

	/** @var bool */
	private $cache;

	/**
	 * WP_Sitemap_Bucket_Proxy constructor.
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->bucket  = new WP_Sitemap_Bucket( $post_id );

		$this->reset_cache();

		add_action( 'sitemap_pre_bucket_update', array( $this, 'reset_cache' ) );
	}

	/**
	 * @return string
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function add_item( WP_Sitemap_Item_Interface $sitemap_item ) {
		do_action( 'sitemap_pre_bucket_item_add', $this->post_id, $sitemap_item, $this );
		do_action( 'sitemap_pre_bucket_update', $this->post_id, $sitemap_item, $this );
		$this->bucket->add_item( $sitemap_item );
		do_action( 'sitemap_post_bucket_update', $this->post_id, $sitemap_item, $this );
		do_action( 'sitemap_post_bucket_item_add', $this->post_id, $sitemap_item, $this );
	}

	/**
	 * @param WP_Sitemap_Item_Interface $sitemap_item
	 */
	public function remove_item( WP_Sitemap_Item_Interface $sitemap_item ) {
		do_action( 'sitemap_pre_bucket_item_remove', $this->post_id, $sitemap_item, $this );
		do_action( 'sitemap_pre_bucket_update', $this->post_id, $sitemap_item, $this );
		$this->bucket->remove_item( $sitemap_item );
		do_action( 'sitemap_post_bucket_update', $this->post_id, $sitemap_item, $this );
		do_action( 'sitemap_post_bucket_item_remove', $this->post_id, $sitemap_item, $this );
	}

	/**
	 * @param int $offset
	 * @param int $number_of_items
	 *
	 * @return array
	 */
	public function get_items( $offset = 0, $number_of_items = - 1 ) {
		if ( empty( $this->cache['items'][ $offset ][ $number_of_items ] ) ) {
			$this->cache['items'][ $offset ][ $number_of_items ] = $this->bucket->get_items( $offset, $number_of_items );
		}

		return $this->cache['items'][ $offset ][ $number_of_items ];
	}

	/**
	 * @return string
	 */
	public function get_last_modified_date() {
		if ( empty( $this->cache['last_modified_date'] ) ) {
			$this->cache['last_modified_date'] = $this->bucket->get_last_modified_date();
		}

		return $this->cache['last_modified_date'];
	}

	/**
	 * Reset cache
	 */
	public function reset_cache() {
		$this->cache = array( 'items' => false, 'last_modified_date' => false );
	}
}
