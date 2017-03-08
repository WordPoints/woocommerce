<?php

/**
 * Order tax discount entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the tax that is saved based on the discount amount for an order.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Tax_Discount
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'decimal_number';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'discount_tax';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_cart_discount_tax';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Tax Discount', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
