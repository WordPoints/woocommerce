<?php

/**
 * PHPUnit tests bootstrap for the module.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

if ( ! getenv( 'WP_TESTS_DIR' ) ) {
	exit( '$_ENV["WP_TESTS_DIR"] is not set.' . PHP_EOL );
} elseif ( ! getenv( 'WORDPOINTS_TESTS_DIR' ) ) {
	exit( '$_ENV["WORDPOINTS_TESTS_DIR"] is not set.' . PHP_EOL );
}

/**
 * The module's tests directory.
 *
 * @since 1.0.0
 *
 * @type string
 */
define( 'WORDPOINTS_WC_TESTS_DIR', dirname( dirname( __FILE__ ) ) );

/**
 * The WordPress tests functions.
 *
 * We are loading this so that we can add our tests filter to load the module and
 * WordPoints, using tests_add_filter().
 *
 * @since 1.0.0
 */
require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

/**
 * The module's utilitiy functions for the tests.
 *
 * @since 1.0.0
 */
require_once WORDPOINTS_WC_TESTS_DIR . '/includes/functions.php';

/** The plugin uninstall tester functions. */
require_once WORDPOINTS_WC_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/includes/functions.php';

/** The module uninstall tester functions. */
require_once WORDPOINTS_WC_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/includes/functions.php';

if ( ! running_wordpoints_module_uninstall_tests() ) {

	// Hook to load WooCommerce.
	tests_add_filter( 'muplugins_loaded', 'wordpoints_wc_tests_manually_load_woocommerce' );

	// Hook to load the module.
	tests_add_filter( 'wordpoints_modules_loaded', 'wordpoints_wc_tests_manually_load_module', 5 );
}

/**
 * We're running tests for a module.
 *
 * We need to tell WordPoints' tests bootstrap this so that it won't load it's plugin
 * uninstall tester.
 *
 * @since 1.0.0
 */
define( 'RUNNING_WORDPOINTS_MODULE_TESTS', true );

/**
 * The WordPoints tests bootstrap.
 *
 * @since 1.0.0
 */
require_once getenv( 'WORDPOINTS_TESTS_DIR' ) . '/includes/bootstrap.php';

/** The plugin uninstall tester functions. */
require_once WORDPOINTS_WC_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/bootstrap.php';

/** The module uninstall tester functions. */
require_once WORDPOINTS_WC_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/bootstrap.php';

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

// wc_create_order() expects this to be set.
if ( ! isset( $_SERVER['REMOTE_ADDR'] ) ) {
	 $_SERVER['REMOTE_ADDR'] = '';
}

// EOF
