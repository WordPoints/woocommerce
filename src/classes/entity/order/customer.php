<?php

/**
 * Order customer entity relationship class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.1.0
 */

/**
 * Represents the relationship between an Order and the user who placed it.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Order_Customer
	extends WordPoints_Entity_Relationship
	implements WordPoints_Entityish_StoredI {

	/**
	 * @since 1.1.0
	 */
	protected $primary_entity_slug = 'woocommerce_order';

	/**
	 * @since 1.1.0
	 */
	protected $related_entity_slug = 'user';

	/**
	 * @since 1.1.0
	 */
	protected function get_related_entity_ids( WordPoints_Entity $entity ) {

		$entity_id = $entity->get_the_id();

		if ( ! $entity_id ) {
			return null;
		}

		$order = wc_get_order( $entity_id );

		if ( ! $order ) {
			return null;
		}

		return $order->get_customer_id();
	}

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return _x( 'Customer', 'order entity', 'wordpoints-woocommerce' );
	}

	/**
	 * @since 1.1.0
	 */
	public function get_storage_info() {
		return array(
			'type' => 'db',
			'info' => array(
				'type'             => 'table',
				'table_name'       => $GLOBALS['wpdb']->postmeta,
				'primary_id_field' => 'post_id',
				'related_id_field' => 'meta_value',
				'conditions'       => array(
					array(
						'field' => 'meta_key',
						'value' => '_customer_user',
					),
				),
			),
		);
	}
}

// EOF
