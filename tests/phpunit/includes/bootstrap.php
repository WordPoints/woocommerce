<?php

/**
 * PHPUnit tests bootstrap for the module.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * The module's tests directory.
 *
 * @since 1.0.0
 *
 * @type string
 */
define( 'WORDPOINTS_WC_TESTS_DIR', dirname( dirname( __FILE__ ) ) );

/** The points parent test case. */
require_once WORDPOINTS_WC_TESTS_DIR . '/includes/testcases/points.php';

/**
 * The product factory.
 *
 * @since 1.0.0
 */
require_once WORDPOINTS_WC_TESTS_DIR . '/includes/factories/product.php';

/**
 * The product factory.
 *
 * @since 1.0.0
 */
require_once WORDPOINTS_WC_TESTS_DIR . '/includes/factories/order.php';

if ( ! WordPoints_PHPUnit_Bootstrap_Loader::instance()->running_uninstall_tests() ) {
	/**
	 * Load the WooCommerce tests bootstrap.
	 *
	 * @since 1.1.0
	 */
	require_once WP_PLUGIN_DIR . '/woocommerce/tests/bootstrap.php';
}

// wc_create_order() expects this to be set.
if ( ! isset( $_SERVER['REMOTE_ADDR'] ) ) {
	 $_SERVER['REMOTE_ADDR'] = '';
}

// EOF
