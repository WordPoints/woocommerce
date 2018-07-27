<?php

/**
 * Test case for the entity classes.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests the entity classes.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Entity_Order
 * @covers WordPoints_WooCommerce_Entity_Order_Attr
 * @covers WordPoints_WooCommerce_Entity_Order_Created_Via
 * @covers WordPoints_WooCommerce_Entity_Order_Customer
 * @covers WordPoints_WooCommerce_Entity_Order_Customer_Note
 * @covers WordPoints_WooCommerce_Entity_Order_Date_Completed
 * @covers WordPoints_WooCommerce_Entity_Order_Date_Created
 * @covers WordPoints_WooCommerce_Entity_Order_Date_Paid
 * @covers WordPoints_WooCommerce_Entity_Order_Tax_Cart
 * @covers WordPoints_WooCommerce_Entity_Order_Tax_Discount
 * @covers WordPoints_WooCommerce_Entity_Order_Tax_Shipping
 * @covers WordPoints_WooCommerce_Entity_Order_Total_Discount
 * @covers WordPoints_WooCommerce_Entity_Order_Total_Grand
 * @covers WordPoints_WooCommerce_Entity_Order_Total_Shipping
 */
class WordPoints_All_Entities_Test extends WordPoints_PHPUnit_TestCase_Entities {

	/**
	 * @since 1.1.0
	 */
	public function data_provider_entities() {

		global $wpdb;

		$this->factory             = new WP_UnitTest_Factory();
		$this->factory->wordpoints = WordPoints_PHPUnit_Factory::$factory;

		$entities = array(
			'woocommerce_order' => array(
				array(
					'class'        => 'WordPoints_WooCommerce_Entity_Order',
					'slug'         => 'woocommerce_order',
					'get_id'       => array( $this, 'get_order_id' ),
					'get_human_id' => array( $this, 'get_order_number' ),
					'context'      => 'site',
					'storage_info' => array(
						'type' => 'db',
						'info' => array(
							'type'       => 'table',
							'table_name' => $wpdb->posts,
							'conditions' => array(
								array(
									'field' => 'post_type',
									'value' => 'shop_order',
								),
							),
						),
					),
					'the_context'  => array( 'site' => 1, 'network' => 1 ),
					'create_func'  => array( $this, 'create_order' ),
					'delete_func'  => 'WC_Helper_Order::delete_order',
					'children'     => array(
						'cart_tax' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Tax_Cart',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_order_tax',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'created_via' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Created_Via',
							'data_type'    => 'text',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_created_via',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'customer' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Customer',
							'primary'      => 'woocommerce_order',
							'related'      => 'user',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'table',
									'table_name'       => $wpdb->postmeta,
									'primary_id_field' => 'post_id',
									'related_id_field' => 'meta_value',
									'conditions'       => array(
										array(
											'field' => 'meta_key',
											'value' => '_customer_user',
										),
									),
								),
							),
						),
						'customer_note' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Customer_Note',
							'data_type'    => 'text',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'  => 'field',
									'field' => 'post_excerpt',
								),
							),
						),
						'date_completed' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Date_Completed',
							'data_type'    => 'unix_timestamp',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_completed_date',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'date_created' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Date_Created',
							'data_type'    => 'mysql_datetime',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'  => 'field',
									'field' => 'post_date_gmt',
								),
							),
						),
						'date_paid' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Date_Paid',
							'data_type'    => 'unix_timestamp',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_paid_date',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'discount_total' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Total_Discount',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_cart_discount',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'discount_tax' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Tax_Discount',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_cart_discount_tax',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'grand_total' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Total_Grand',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_order_total',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'shipping_tax' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Tax_Shipping',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_order_shipping_tax',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
						'shipping_total' => array(
							'class'        => 'WordPoints_WooCommerce_Entity_Order_Total_Shipping',
							'data_type'    => 'decimal_number',
							'storage_info' => array(
								'type' => 'db',
								'info' => array(
									'type'             => 'meta_table',
									'table_name'       => $wpdb->postmeta,
									'meta_key'         => '_order_shipping',
									'meta_key_field'   => 'meta_key',
									'meta_value_field' => 'meta_value',
									'entity_id_field'  => 'post_id',
								),
							),
						),
					),
				),
			),
		);

		return $entities;
	}

	/**
	 * Creates an order.
	 *
	 * @since 1.3.1
	 *
	 * @return WC_Order The order.
	 */
	public function create_order() {

		$order = WC_Helper_Order::create_order();
		$order->update_status( 'completed' );
		$order->set_date_paid( time() );
		$order->save();

		return $order;
	}

	/**
	 * Gets the ID from an order.
	 *
	 * @since 1.1.0
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @return int The ID of the order.
	 */
	public function get_order_id( $order ) {
		return $order->get_id();
	}

	/**
	 * Gets the order number from an order.
	 *
	 * @since 1.1.0
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @return string The order number.
	 */
	public function get_order_number( $order ) {
		return $order->get_order_number();
	}
}

// EOF
