<?php

/**
 * Utility functions used in PHPUnit testing.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
$loader->add_plugin( 'woocommerce/woocommerce.php' );

/**
 * Manually load the module.
 *
 * @since 1.0.0
 * @deprecated 1.1.0 Use WPPPB instead.
 */
function wordpoints_wc_tests_manually_load_module() {

	require WORDPOINTS_WC_TESTS_DIR . '/../../src/woocommerce.php';
}

/**
 * Manually load the WooCommerce plugin.
 *
 * @since 1.0.0
 * @deprecated 1.1.0 Use WPPPB instead.
 */
function wordpoints_wc_tests_manually_load_woocommerce() {

	// Remove all of WooCommerce's tables from the DB so we start with a clean slate.
	global $wpdb;

	$wpdb->query(
		$wpdb->prepare(
			'
				SELECT CONCAT( "DROP TABLE ", GROUP_CONCAT( CONCAT( table_schema, ".", table_name ) ), ";" )
					INTO @dropcmd
					FROM information_schema.tables
					WHERE table_schema = database()
						AND table_name LIKE %s
			'
			, $wpdb->esc_like( $wpdb->base_prefix . 'woocommerce_' ) . '%'
		)
	);

	if ( $wpdb->get_var( 'SELECT @dropcmd' ) ) {
		$wpdb->query( 'PREPARE s1 FROM @dropcmd' );
		$wpdb->query( 'EXECUTE s1' );
		$wpdb->query( 'DEALLOCATE PREPARE s1' );
	}

	$config_file_path = getenv( 'WP_TESTS_DIR' );

	if ( ! file_exists( $config_file_path . '/wp-tests-config.php' ) ) {

		// Support the config file from the root of the develop repository.
		if (
			basename( $config_file_path ) === 'phpunit'
			&& basename( dirname( $config_file_path ) ) === 'tests'
		) {
			$config_file_path = dirname( dirname( $config_file_path ) );
		}
	}

	$config_file_path .= '/wp-tests-config.php';

	if ( ! is_readable( $config_file_path ) ) {
		exit( 'Error: Unable to locate the wp-tests-config.php file.' );
	}

	// @codingStandardsIgnoreStart
	system(
		WP_PHP_BINARY
		. ' ' . escapeshellarg( dirname( __FILE__ ) . '/bin/install-woocommerce.php' )
		. ' ' . escapeshellarg( $config_file_path )
		. ' ' . (int) is_multisite()
	);
	// @codingStandardsIgnoreEnd

	require WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
}

// EOF
