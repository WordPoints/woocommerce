<?php

/**
 * Order grand total entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the total amount for an order, including taxes, fees, shipping, etc.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Total_Grand
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'decimal_number';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'total';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_order_total';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Grand Total', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
