<?php

/**
 * Order created date entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents an order's creation date.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Date_Created
	extends WordPoints_Entity_Attr_Field {

	/**
	 * @since 1.1.0
	 */
	protected $storage_type = 'db';

	/**
	 * @since 1.1.0
	 */
	protected $data_type = 'mysql_datetime';

	/**
	 * @since 1.1.0
	 */
	protected $field = 'post_date';

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Date Created', 'order entity', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	protected function get_attr_value_from_entity( WordPoints_Entity $entity ) {

		$timestamp = $entity->get_the_attr_value( 'date_created' );

		if ( ! $timestamp ) {
			return null;
		}

		return date( 'Y-m-d H:i:s', $timestamp );
	}
}

// EOF
