<?php

/**
 * Order shipping tax entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the tax that must be payed on the shipping amount for an order.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Tax_Shipping
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'decimal_number';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'shipping_tax';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_order_shipping_tax';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Shipping Tax', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
