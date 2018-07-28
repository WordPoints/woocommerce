<?php

/**
 * Order entity attribute class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents an entity attribute for an order.
 *
 * @since 1.1.0
 */
abstract class WordPoints_WooCommerce_Entity_Order_Attr
	extends WordPoints_Entity_Attr_Stored_DB_Table_Meta {

	/**
	 * @since 1.1.0
	 */
	protected $meta_type = 'post';

	/**
	 * The key used to retrieve this order from the order object.
	 *
	 * This is sometimes different than the meta key. At other times, it is just the
	 * meta key with an underscore prepended. In that case, you can omit to define
	 * the meta key and it will be set automatically.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $attr_key;

	/**
	 * @since 1.1.0
	 */
	public function __construct( $slug ) {

		if ( ! isset( $this->meta_key ) ) {
			$this->meta_key = '_' . $this->attr_key;
		}

		parent::__construct( $slug );
	}

	/**
	 * @since 1.1.0
	 */
	protected function get_attr_value_from_entity( WordPoints_Entity $entity ) {

		$entity_id = $entity->get_the_id();

		if ( ! $entity_id ) {
			return null;
		}

		$order = wc_get_order( $entity_id );

		if ( ! $order ) {
			return null;
		}

		$method = "get_{$this->attr_key}";

		if ( method_exists( $order, $method ) ) {
			return $order->{$method}();
		}

		return $order->get_meta( $this->attr_key );
	}
}

// EOF
