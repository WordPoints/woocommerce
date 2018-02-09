<?php

/**
 * Bootstrap file for the acceptance tests.
 *
 * @package WordPoints_WooCommerce
 * @since   1.2.0
 */

/**
 * The product factory.
 *
 * @since 1.2.0
 */
require_once __DIR__ . '/../../phpunit/includes/factories/product.php';

/**
 * The product factory.
 *
 * @since 1.2.0
 */
require_once __DIR__ . '/../../phpunit/includes/factories/order.php';

/**
 * Load the WooCommerce tests bootstrap.
 *
 * @since 1.3.0
 */
require_once WP_PLUGIN_DIR . '/woocommerce/tests/bootstrap.php';

// EOF
