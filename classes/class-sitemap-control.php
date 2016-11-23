<?php

class WP_Sitemap_Control {

	/** @var WP_Sitemap_Distributor */
	protected $distributor;

	/** @var WP_Sitemap_Bucket_Factory */
	protected $bucket_factory;

	/** @var WP_Sitemap_Settings */
	protected $settings;

	/** @var WP_Sitemap_Bucket_Update_Listener */
	protected $bucket_update_listener;

	/** @var WP_Sitemap_Bucket_Finder */
	protected $bucket_finder;

	/** @var array */
	protected $bucket_types = array();

	/** @var array */
	protected $builders = array();

	/**
	 * Setup classes and add hooks
	 */
	public function load() {
		add_action( 'wp_sitemap_register_bucket_type', array( $this, 'register_bucket_type' ), 10, 2 );
		add_action( 'wp_sitemap_register_bucket_builder', array( $this, 'register_bucket_builder' ), 10, 2 );

		$this->settings       = new WP_Sitemap_Settings();
		$this->bucket_factory = new WP_Sitemap_Bucket_Factory( $this );
		$this->bucket_finder  = new WP_Sitemap_Bucket_Finder( $this );
		$this->distributor    = new WP_Sitemap_Distributor( $this );

		$this->bucket_update_listener = new WP_Sitemap_Bucket_Update_Listener( $this );
		$this->bucket_update_listener->add_hooks();
	}

	/**
	 * @return WP_Sitemap_Bucket_Factory
	 */
	public function get_bucket_factory() {
		return $this->bucket_factory;
	}

	/**
	 * @return WP_Sitemap_Settings
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @return WP_Sitemap_Distributor
	 */
	public function get_distributor() {
		return $this->distributor;
	}

	/**
	 * @return WP_Sitemap_Bucket_Finder
	 */
	public function get_bucket_finder() {
		return $this->bucket_finder;
	}


	/**
	 * Add bucket type
	 *
	 * @param string $bucket_type
	 * @param string $bucket_class
	 */
	public function register_bucket_type( $bucket_type, $bucket_class ) {

		if ( ! class_exists( $bucket_class ) ) {
			return;
		}

		$this->bucket_types[ $bucket_type ] = $bucket_class;
	}

	/**
	 * Add bucket builder
	 *
	 * @param string $bucket_type
	 * @param string $builder_class
	 */
	public function add_builder( $bucket_type, $builder_class ) {

		if ( ! isset( $this->bucket_types[ $bucket_type ] ) ) {
			return;
		}

		$this->builders[ $bucket_type ][] = $builder_class;
	}

	/**
	 * @param $bucket_class
	 *
	 * @return WP_Sitemap_Builder_Interface[]|array
	 */
	public function get_builders( $bucket_class ) {
		$bucket_type = array_search( $bucket_class, $this->bucket_types );
		if ( false !== $bucket_type ) {
			return array();
		}

		return $this->builders[ $bucket_type ];

	}
}
