<?php

/**
 * Order cart tax entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the tax that must be payed on the cart amount for an order.
 *
 * This is the tax on the items in the cart. It excludes shipping.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Tax_Cart
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'decimal_number';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'cart_tax';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_order_tax';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Cart Tax', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
