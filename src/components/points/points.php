<?php

/**
 * Bootstrap for points component-related code.
 *
 * @package WordPoints_WooCommerce
 * @since 1.0.0
 */

/**
 * The general points-related functions.
 *
 * @since 1.0.0
 */
include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/includes/functions.php' );

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	/**
	 * The points payment gateway.
	 *
	 * @since 1.0.0
	 */
	include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/includes/gateways/points.php' );
}

// EOF
