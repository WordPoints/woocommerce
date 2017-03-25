<?php

/**
 * Order completed date entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the completion date for an order.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Date_Completed
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'unix_timestamp';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'date_completed';

	/**
	 * @since 1.1.0
	 */
	protected $meta_key = '_completed_date';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Date Completed', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
