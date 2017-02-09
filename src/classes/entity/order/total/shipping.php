<?php

/**
 * Total order shipping entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the total shipping amount for an order.
 *
 * This excludes tax.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Total_Shipping
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'decimal_number';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'shipping_total';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_order_shipping';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Shipping', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
