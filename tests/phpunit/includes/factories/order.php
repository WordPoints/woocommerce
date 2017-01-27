<?php

/**
 * A factory for WooCommerce orders.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * WooCommerce order factory.
 *
 * @since 1.0.0
 */
class WordPoints_WooCommerce_UnitTest_Factory_For_Order extends WP_UnitTest_Factory_For_Post {

	/**
	 * The default address to use.
	 *
	 * @since 1.0.0
	 *
	 * @type array $default_adress
	 */
	protected $default_adress = array(
		'first_name' => 'Joe',
		'last_name'  => 'Tester',
		'company'    => '',
		'email'      => 'test@example.com',
		'phone'      => '777-777-777-777',
		'address_1'  => '12 Bukle Mishue',
		'address_2'  => '',
		'city'       => 'Hebron',
		'state'      => 'MD',
		'postcode'   => '12345',
		'country'    => 'US',
	);

	/**
	 * Set up the default generation definitions on constructions.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_UnitTest_Factory $factory Optional factory.
	 */
	public function __construct( $factory = null ) {

		parent::__construct( $factory );

		$this->default_generation_definitions = array(
			'post_status'  => 'publish',
			'post_title'   => new WP_UnitTest_Generator_Sequence( 'Order title %s' ),
			'post_content' => new WP_UnitTest_Generator_Sequence( 'Order content %s' ),
			'post_excerpt' => new WP_UnitTest_Generator_Sequence( 'Order excerpt %s' ),
			'post_type'    => 'order',
		);
	}

	/**
	 * Get the order by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id The order ID.
	 *
	 * @return WC_Order The order.
	 */
	public function get_object_by_id( $order_id ) {

		return new WC_Order( $order_id );
	}

	/**
	 * Create an order.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The order data.
	 *
	 * @return int|WP_Error The order ID, or a WP_Error on error.
	 */
	public function create_object( $args ) {

		$order = wc_create_order( $args );

		if ( is_wp_error( $order ) ) {
			return $order;
		}

		$default_args = array(
			'billing_address'  => $this->default_adress,
			'shipping_address' => $this->default_adress,
		);

		$args = array_merge( $args, $default_args );

		if ( empty( $args['products'] ) ) {
			$args['products'] = array( $this->factory->product->create() => 1 );
		}

		foreach ( $args['products'] as $product_id => $number ) {
			$order->add_product( wc_get_product( $product_id ), $number );
		}

		$order->set_address( $args['billing_address'], 'billing' );
		$order->set_address( $args['shipping_address'], 'shipping' );

		$order->calculate_totals();

		// Back-compat for pre-WC 2.7.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		return $order_id;
	}

	/**
	 * Update an order.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $order_id The ID of the order.
	 * @param array $fields   The order fields.
	 *
	 * @return WC_Order|WP_Error The ID of the post, or a WP_Error on failure.
	 */
	public function update_object( $order_id, $fields ) {

		$fields['order_id'] = $order_id;
		return wc_update_order( $fields );
	}
}

// EOF
