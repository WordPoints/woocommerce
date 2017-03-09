<?php

/**
 * Leave product review hook event class.
 *
 * @package WordPoints_WooCommerce\Hooks
 * @since 1.1.0
 */

/**
 * Represents a hook event that occurs when a product review is left.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Hook_Event_Product_Review_Leave
	extends WordPoints_Hook_Event_Comment_Leave {

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return __( 'Reviewing a Product', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_description() {
		return __( 'When a user leaves a review for a Product sold through the WooCommerce-powered store.', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_reversal_text() {
		return __( 'Review removed.', 'wordpoints-woocommerce' );
	}
}

// EOF
