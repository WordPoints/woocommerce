<?php

/**
 * Order entity class.
 *
 * @package WordPoints_WooCommerce\Entities
 * @since 1.1.0
 */

/**
 * Represents an order.
 *
 * Orders are basically posts, by default, although in newer versions of WooCommerce
 * (3.0+) they can be stored almost anywhere in theory. We base our entity on the
 * default implementation; if a custom data store is being used, then a custom entity
 * may be needed, too.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order extends WordPoints_Entity_Post {

	/**
	 * @since 1.1.0
	 */
	protected $getter = 'wc_get_order';

	/**
	 * @since 1.1.0
	 */
	protected $human_id_field = 'ID';

	/**
	 * @since 1.2.1
	 */
	protected $post_type = 'shop_order';

	/**
	 * @since 1.1.0
	 */
	protected function get_attr_value( $entity, $attr ) {

		if (
			$entity instanceof WC_Order
			&& method_exists( $entity, "get_{$attr}" )
		) {
			return $entity->{"get_{$attr}"}();
		}

		return parent::get_attr_value( $entity, $attr );
	}

	/**
	 * @since 1.1.0
	 */
	protected function get_entity_human_id( $entity ) {

		if ( $entity instanceof WC_Order ) {
			return $entity->get_order_number();
		}

		return parent::get_entity_human_id( $entity );
	}
}

// EOF
