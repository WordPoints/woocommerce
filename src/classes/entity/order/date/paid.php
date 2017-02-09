<?php

/**
 * Order paid date entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the date that an order was paid for.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Date_Paid
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'unix_timestamp';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'date_paid';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_paid_date';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Date Paid', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
