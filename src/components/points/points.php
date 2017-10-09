<?php

/**
 * Bootstrap for points component-related code.
 *
 * @package WordPoints_WooCommerce
 * @since 1.0.0
 */

WordPoints_Class_Autoloader::register_dir(
	WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/includes'
);

/**
 * The general points-related functions.
 *
 * @since 1.0.0
 */
require_once WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/includes/functions.php';

/**
 * Hooks up the points-related actions.
 *
 * @since 1.1.0
 */
require_once WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/includes/actions.php';

// EOF
