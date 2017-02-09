<?php

/**
 * Order created via entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the field holding the method that this order was created via.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Created_Via
	extends WordPoints_WooCommerce_Entity_Order_Attr {

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'text';

	/**
	 * @since 1.1.0
	 */
	protected $attr_key = 'created_via';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Created Via', 'order entity', 'wordpoints-woocommerce' );
	}
}

// EOF
