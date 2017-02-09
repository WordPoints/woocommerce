<?php

/**
 * Delete order hook action class.
 *
 * @package WordPoints_WooCommerce
 * @since   1.1.0
 */

/**
 * Represents an action that fires when an order is deleted.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Hook_Action_Order_Delete
	extends WordPoints_Hook_Action {

	/**
	 * @since 1.1.0
	 */
	public function should_fire() {

		$order = wc_get_order( $this->get_arg_value( 'woocommerce_order' ) );

		if ( ! $order ) {
			return false;
		}

		return parent::should_fire();
	}
}

// EOF
