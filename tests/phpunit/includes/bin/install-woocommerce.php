<?php

/**
 * Install WooCommerce remotely.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 * @deprecated 1.1.0 Use WPPPB instead.
 */

$config_file_path = $argv[1];
$is_multisite     = $argv[2];

require $config_file_path;

unset( $config_file_path );

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = WP_TESTS_DOMAIN;
// @codingStandardsIgnoreStart
// See https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/pull/720.
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';
// @codingStandardsIgnoreEnd

if ( $is_multisite ) {

	define( 'MULTISITE', true );
	define( 'SUBDOMAIN_INSTALL', false );
	define( 'DOMAIN_CURRENT_SITE', WP_TESTS_DOMAIN );
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );

	$GLOBALS['base'] = '/';
}

unset( $is_multisite );

define( 'WP_USE_THEMES', false );

require ABSPATH . '/wp-settings.php';

wp_register_plugin_realpath( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' );

require WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';

do_action( 'activate_woocommerce/woocommerce.php', false );

// EOF
