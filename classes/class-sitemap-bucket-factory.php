<?php

class WP_Sitemap_Bucket_Factory implements WP_Sitemap_Bucket_Factory_Interface {

	const BUCKET_POST_TYPE = 'wp_sitemap_bucket';
	const INDEX_META_KEY = 'index';
	const ITEM_COUNT_META_KEY = 'number_of_items';

	/** @var WP_Sitemap_Control */
	protected $sitemap_control;

	/**
	 * WP_Sitemap_Bucket_Factory constructor.
	 *
	 * @param WP_Sitemap_Control $sitemap_control
	 */
	public function __construct( WP_Sitemap_Control $sitemap_control ) {
		$this->sitemap_control = $sitemap_control;

		add_action( 'init', array( $this, 'create_custom_post_type' ), 0 );
	}

	/**
	 * @param int $post_id
	 */
	public function remove_bucket( $post_id ) {
		// Remove the bucket when no more items remain.
		do_action( 'sitemap_pre_bucket_remove', $post_id );
		wp_delete_post( $post_id );
		do_action( 'sitemap_post_bucket_remove', $post_id );
	}

	/**
	 * Create holder for container meta data
	 */
	public function create_custom_post_type() {
		$labels = array(
			'name'          => 'Sitemap Buckets',
			'singular_name' => 'Sitemap Bucket',
		);

		$args = array(
			'label'               => 'Sitemap Bucket',
			'description'         => 'Bucket to hold sitemap items',
			'labels'              => $labels,
			'supports'            => array(),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
		);

		register_post_type( 'post_type', $args );
	}

	/**
	 * @param int $post_id
	 *
	 * @return WP_Sitemap_Bucket_Proxy
	 */
	public function get_bucket( $post_id ) {
		static $checked_identifiers = array();

		if ( ! isset( $checked_identifiers[ $post_id ] ) ) {
			// Get post belonging to this identifier

			$find_query = new WP_Query(
				array(
					'post_type' => self::BUCKET_POST_TYPE,
					'p'         => $post_id
				)
			);

			$checked_identifiers[ $post_id ] = $find_query->have_posts();
		}

		if ( false !== $checked_identifiers[ $post_id ] ) {
			return new WP_Sitemap_Bucket_Proxy( $post_id );
		}

		return null;
	}

	/**
	 * @return WP_Sitemap_Bucket_Proxy|null
	 */
	public function get_active_bucket() {

		$find_query = new WP_Query(
			array(
				'post_type'      => self::BUCKET_POST_TYPE,
				'meta_key'       => self::INDEX_META_KEY,
				'orderby'        => 'meta_value_num',
				'order'          => 'desc',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		if ( ! $find_query->have_posts() ) {
			$bucket_id = $this->create_bucket();
		}

		if ( ! isset( $bucket_id ) ) {
			// Because of the fields:ids this will be the post_id instead of a post object.
			/** @var int $bucket_id */
			$bucket_id = $find_query->next_post();

			$bucket_meta = get_post_meta( $bucket_id );

			// When the bucket contains the maximum items, create a new bucket.
			if ( intval( $bucket_meta[ self::ITEM_COUNT_META_KEY ] ) >= $this->sitemap_control->get_settings()->get_items_per_bucket() ) {
				$bucket_id = $this->create_bucket( $bucket_meta[ self::INDEX_META_KEY ] );
			}
		}

		return $this->get_bucket( $bucket_id );
	}

	/**
	 * @param int $last_index
	 *
	 * @return int|WP_Error
	 */
	protected function create_bucket( $last_index = 0 ) {

		$post_id = wp_insert_post(
			array(
				'post_type' => self::BUCKET_POST_TYPE,
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, self::INDEX_META_KEY, $last_index + 1 );

		return $post_id;
	}
}
