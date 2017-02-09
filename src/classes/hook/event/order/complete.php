<?php

/**
 * Order complete hook event class.
 *
 * @package WordPoints\Hooks
 * @since 1.1.0
 */

/**
 * An event that fires when an order is completed.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Hook_Event_Order_Complete
	extends WordPoints_Hook_Event
	implements WordPoints_Hook_Event_ReversingI {

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return __( 'Order Complete', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_description() {
		return __( 'When an order placed through the WooCommerce store is completed.', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_reversal_text() {
		return __( 'Order status changed.', 'wordpoints-woocommerce' );
	}
}

// EOF
