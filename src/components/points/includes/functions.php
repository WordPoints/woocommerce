<?php

/**
 * Points component-related functions.
 *
 * @package WordPoints_WooCommerce\Points
 * @since 1.0.0
 */

/**
 * Render logs for an order through the points gateway.
 *
 * @since 1.0.0
 */
function wordpoints_points_logs_woocommerce_points_gateway(
		$text, $points, $points_type, $user_id, $log_type, $meta
	) {

	return sprintf(
		_x( 'Payment for order #%s', 'points log description', 'wordpoints-woocommerce' )
		, $meta['order_id']
	);
}
add_filter(
	'wordpoints_points_log-woocommerce_points_gateway'
	, 'wordpoints_points_logs_woocommerce_points_gateway'
	, 10
	, 6
);

/**
 * Render logs for a refund for an order through the points gateway.
 *
 * @since 1.0.0
 */
function wordpoints_points_logs_woocommerce_points_gateway_refund(
		$text, $points, $points_type, $user_id, $log_type, $meta
	) {

	return sprintf(
		_x( 'Refunded payment for order #%s', 'points log description', 'wordpoints-woocommerce' )
		, $meta['order_id']
	);
}
add_filter(
	'wordpoints_points_log-woocommerce_points_gateway_refund'
	, 'wordpoints_points_logs_woocommerce_points_gateway_refund'
	, 10
	, 6
);

// EOF
