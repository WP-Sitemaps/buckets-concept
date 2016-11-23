<?php

$path = dirname( __FILE__ );

if ( ! file_exists( $path . '/vendor/autoload_52.php' ) ) {
	throw new Exception( 'Please run `composer install` in ' . $path );
}

require_once $path . 'vendor/autoload_52.php';

$sitemap_control = new WP_Sitemap_Control();
$sitemap_control->load();
