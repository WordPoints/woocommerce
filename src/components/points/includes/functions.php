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

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 2.7.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Payment for order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Payment for an order.', 'points log description', 'wordpoints-woocommerce' );
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

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 2.7.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Refunded payment for order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Refunded payment for an order.', 'points log description', 'wordpoints-woocommerce' );

}
add_filter(
	'wordpoints_points_log-woocommerce_points_gateway_refund'
	, 'wordpoints_points_logs_woocommerce_points_gateway_refund'
	, 10
	, 6
);

// EOF
