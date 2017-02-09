<?php

/**
 * Order customer note entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents a note submitted by the customer along with their order.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Customer_Note
	extends WordPoints_Entity_Attr_Field {

	/**
	 * @since 1.1.0
	 */
	protected $storage_type = 'db';

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'text';

	/**
	 * @since 1.1.0
	 */
	protected $field = 'post_excerpt';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Customer Note', 'order entity', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	protected function get_attr_value_from_entity( WordPoints_Entity $entity ) {
		return $entity->get_the_attr_value( 'customer_note' );
	}
}

// EOF
