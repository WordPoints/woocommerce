<?php

/**
 * Product publish hook event class.
 *
 * @package WordPoints_WooCommerce\Hooks
 * @since 1.1.0
 */

/**
 * Represents a hook event that occurs when a Product is published.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Hook_Event_Product_Publish
	extends WordPoints_Hook_Event_Post_Publish {

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return __( 'Publish Product', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_description() {
		return __( 'When a Product is published on the WooCommerce-powered store.', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_reversal_text() {
		return __( 'Product removed.', 'wordpoints-woocommerce' );
	}
}

// EOF
