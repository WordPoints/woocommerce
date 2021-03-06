<?php

/**
 * A test case for the points gateway.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * Test that the points gateway functions correctly.
 *
 * @since 1.0.0
 *
 * @covers WordPoints_WooCommerce_Gateway_Points
 */
class WordPoints_WooCommerce_Points_Gateway_Test
	extends WordPoints_WooCommerce_Points_UnitTestCase {

	/**
	 * The ID of the order created by the checkout simulator.
	 *
	 * @since 1.0.0
	 *
	 * @type int $checkout_order_id
	 */
	protected $checkout_order_id;

	/**
	 * Set up for each test.
	 *
	 * @since 1.0.0
	 */
	public function setUp() {

		parent::setUp();

		wp_set_current_user( $this->factory->user->create() );

		wordpoints_update_maybe_network_option(
			'wordpoints_default_points_type'
			, 'points'
		);

		WC()->payment_gateways()->init();

		// Set the exchange rate.
		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate-points'] = 100;

		add_filter( 'query', array( $this, 'no_commit_queries' ) );
	}

	/**
	 * Clean up after each test.
	 *
	 * @since 1.0.0
	 */
	public function tearDown() {

		WC()->cart->empty_cart();

		parent::tearDown();
	}

	/**
	 * Tests that it doesn't have checkout fields by default.
	 *
	 * @since 1.2.0
	 */
	public function test_has_fields() {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();

		$this->assertFalse( $gateway->has_fields() );
	}

	/**
	 * Tests that it doesn't have checkout fields when only one points type can be used.
	 *
	 * @since 1.2.0
	 */
	public function test_has_fields_one_points_type() {

		// A second points type may exist, but the conversion rate isn't set.
		$this->factory->wordpoints->points_type->create();

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points'] = 100;
		update_option( $gateway->get_option_key(), $gateway->settings );

		$gateway = new WordPoints_WooCommerce_Gateway_Points();

		$this->assertFalse( $gateway->has_fields() );
	}

	/**
	 * Tests that it does have checkout fields when there are multiple points types.
	 *
	 * @since 1.2.0
	 */
	public function test_has_fields_multiple_points_types() {

		$slug = $this->factory->wordpoints->points_type->create();

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points']    = 100;
		$gateway->settings[ "conversion_rate-{$slug}" ] = 50;
		update_option( $gateway->get_option_key(), $gateway->settings );

		$gateway = new WordPoints_WooCommerce_Gateway_Points();

		$this->assertTrue( $gateway->has_fields() );
	}

	/**
	 * Tests getting the points types that can be used to pay.
	 *
	 * @since 1.2.0
	 */
	public function test_get_points_types_for_checkout() {

		// A second points type may exist, but the conversion rate isn't set.
		$this->factory->wordpoints->points_type->create();

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points'] = 100;

		$this->assertSame(
			array(
				'points' => array(
					'name'   => 'Points',
					'prefix' => '$',
					'suffix' => 'pts.',
				),
			)
			, $gateway->get_points_types_for_checkout()
		);
	}

	/**
	 * Tests checking if the gateway is valid for use.
	 *
	 * @since 1.2.0
	 */
	public function test_is_valid_for_use() {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points'] = 100;

		$this->assertTrue( $gateway->is_valid_for_use() );
	}

	/**
	 * Tests checking if it is valid for use when no conversion rates are set.
	 *
	 * @since 1.2.0
	 */
	public function test_is_valid_for_use_no_conversion_rates_set() {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points'] = 0;

		$this->assertFalse( $gateway->is_valid_for_use() );
	}

	/**
	 * Tests checking if it is valid for use when the user is not logged in.
	 *
	 * @since 1.2.0
	 */
	public function test_is_valid_for_use_not_logged_in() {

		wp_set_current_user( 0 );

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings['conversion_rate-points'] = 100;

		$this->assertFalse( $gateway->is_valid_for_use() );
	}

	/**
	 * Test that points are charged when a user checks out.
	 *
	 * @since 1.0.0
	 */
	public function test_processing_payment() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10000, 'points', 'test' );

		$this->simulate_checkout();

		$this->assertSame( 7500, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test that points are charged when a user checks out.
	 *
	 * @since 1.2.0
	 */
	public function test_processing_payment_multiple_points_types() {

		$slug = $this->factory->wordpoints->points_type->create();

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10000, $slug, 'test' );

		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate-points']    = 100;
		$gateways['wordpoints_points']->settings[ "conversion_rate-{$slug}" ] = 50;

		$_POST[ "{$gateways['wordpoints_points']->id}-points-type" ] = $slug;

		$this->simulate_checkout();

		$this->assertSame( 8750, wordpoints_get_points( $user_id, $slug ) );
	}

	/**
	 * Test that there is an error if the user has insufficient points.
	 *
	 * @since 1.0.0
	 */
	public function test_insufficient_points() {

		// Give the user (not enough) points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10, 'points', 'test' );

		$this->simulate_checkout(
			array(
				'expected_errors' => 'Payment error: You have insufficient points to make this purchase.',
			)
		);

		$this->assertSame( 10, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test when the exchange rate is 1.
	 *
	 * @since 1.0.0
	 */
	public function test_1_exchange_rate() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 30, 'points', 'test' );

		// Set the exchange rate.
		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate-points'] = 1;

		$this->simulate_checkout();

		$this->assertSame( 5, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test that points are refunded correctly.
	 *
	 * @since 1.0.0
	 */
	public function test_refund() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10000, 'points', 'test' );

		$this->simulate_checkout();

		$this->assertSame( 7500, wordpoints_get_points( $user_id, 'points' ) );

		// Refund the order.
		$order_id      = $this->checkout_order_id;
		$refund_amount = 20;
		$refund_reason = 'Testing refunds.';

		wc_create_refund(
			array(
				'amount'   => $refund_amount,
				'reason'   => $refund_reason,
				'order_id' => $order_id,
			)
		);

		/** @var WC_Payment_Gateway[] $payment_gateways */
		$payment_gateways = WC()->payment_gateways()->payment_gateways();

		$this->assertArrayHasKey( 'wordpoints_points', $payment_gateways );
		$this->assertTrue(
			$payment_gateways['wordpoints_points']->supports( 'refunds' )
		);

		$result = $payment_gateways['wordpoints_points']->process_refund(
			$order_id
			, $refund_amount
			, $refund_reason
		);

		$this->assertTrue( $result );

		$this->assertSame( 9500, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test that points are refunded correctly.
	 *
	 * @since 1.2.0
	 */
	public function test_refund_multiple_points_types() {

		$slug = $this->factory->wordpoints->points_type->create();

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10000, $slug, 'test' );

		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate-points']    = 100;
		$gateways['wordpoints_points']->settings[ "conversion_rate-{$slug}" ] = 50;

		$_POST[ "{$gateways['wordpoints_points']->id}-points-type" ] = $slug;

		$this->simulate_checkout();

		$this->assertSame( 8750, wordpoints_get_points( $user_id, $slug ) );

		// Refund the order.
		$order_id      = $this->checkout_order_id;
		$refund_amount = 20;
		$refund_reason = 'Testing refunds.';

		wc_create_refund(
			array(
				'amount'   => $refund_amount,
				'reason'   => $refund_reason,
				'order_id' => $order_id,
			)
		);

		/** @var WC_Payment_Gateway[] $payment_gateways */
		$payment_gateways = WC()->payment_gateways()->payment_gateways();

		$this->assertArrayHasKey( 'wordpoints_points', $payment_gateways );
		$this->assertTrue(
			$payment_gateways['wordpoints_points']->supports( 'refunds' )
		);

		$result = $payment_gateways['wordpoints_points']->process_refund(
			$order_id
			, $refund_amount
			, $refund_reason
		);

		$this->assertTrue( $result );

		$this->assertSame( 9750, wordpoints_get_points( $user_id, $slug ) );
	}

	/**
	 * Test that points are refunded correctly when the conversion rate wasn't saved.
	 *
	 * @since 1.0.0
	 */
	public function test_refund_legacy() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10000, 'points', 'test' );

		$this->simulate_checkout();

		$this->assertSame( 7500, wordpoints_get_points( $user_id, 'points' ) );

		// Refund the order.
		$order_id      = $this->checkout_order_id;
		$refund_amount = 20;
		$refund_reason = 'Testing refunds.';

		wc_create_refund(
			array(
				'amount'   => $refund_amount,
				'reason'   => $refund_reason,
				'order_id' => $order_id,
			)
		);

		/** @var WC_Payment_Gateway[] $payment_gateways */
		$payment_gateways = WC()->payment_gateways()->payment_gateways();

		$this->assertArrayHasKey( 'wordpoints_points', $payment_gateways );

		/** @var WordPoints_WooCommerce_Gateway_Points $gateway */
		$gateway = $payment_gateways['wordpoints_points'];
		$gateway->settings['conversion_rate-points'] = 50;

		$log = $gateway->get_points_log_for_order( $order_id );
		wordpoints_delete_points_log_meta( $log->id, 'conversion_rate' );

		$result = $gateway->process_refund(
			$order_id
			, $refund_amount
			, $refund_reason
		);

		$this->assertTrue( $result );

		$this->assertSame( 9500, wordpoints_get_points( $user_id, 'points' ) );
	}

	//
	// Helpers.
	//

	/**
	 * Throw an exception.
	 *
	 * Used as part of a hack.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception An exception.
	 */
	public function throw_exception() {
		throw new Exception( __CLASS__ );
	}

	/**
	 * Don't allow the transaction to be committed.
	 *
	 * WC_Checkout->create_order() uses a transaction and either commits it or rolls
	 * it back, based on whether the order was created successfully.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query The query SQL.
	 *
	 * @return string The query SQL.
	 */
	public function no_commit_queries( $query ) {

		$bad_queries = array(
			'START TRANSACTION' => true,
			'COMMIT'            => true,
			'ROLLBACK'          => true,
		);

		if ( isset( $bad_queries[ $query ] ) ) {
			$query = "SELECT 'No COMMIT queries allowed'";
		}

		return $query;
	}

	/**
	 * Simulate the checkout process.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *        Arguments.
	 *
	 *        @type string|array $expected_errors Errors expected from the checkout process.
	 * }
	 */
	protected function simulate_checkout( array $args = array() ) {

		// Add items to the cart.
		WC()->cart->add_to_cart( $this->factory->product->create() );

		$_REQUEST['_wpnonce']        = wp_create_nonce( 'woocommerce-process_checkout' );
		$_POST['terms']              = 1;
		$_POST['payment_method']     = 'wordpoints_points';
		$_POST['billing_country']    = 'US';
		$_POST['billing_first_name'] = 'Joe';
		$_POST['billing_last_name']  = 'Tester';
		$_POST['billing_address_1']  = '12 Bukle Mishue';
		$_POST['billing_city']       = 'Hebron';
		$_POST['billing_state']      = 'MD';
		$_POST['billing_postcode']   = '12345';
		$_POST['billing_email']      = 'test@example.com';
		$_POST['billing_phone']      = '777-777-777-777';

		add_action( 'wp_redirect', array( $this, 'throw_exception' ) );

		try {
			add_action( 'woocommerce_new_order', array( $this, 'capture_order_id' ) );
			WC()->checkout()->process_checkout();
			remove_action( 'woocommerce_new_order', array( $this, 'capture_order_id' ) );
		} catch ( Exception $e ) {
			$this->assertSame(
				'WordPoints_WooCommerce_Points_Gateway_Test'
				, $e->getMessage()
			);
		}

		ob_start();
		wc_print_notices();
		$messages = ob_get_clean();

		if ( ! empty( $args['expected_errors'] ) ) {
			$expected_errors = $args['expected_errors'];
		} else {
			$expected_errors = 'WordPoints_WooCommerce_Points_Gateway_Test';
		}

		foreach ( (array) $expected_errors as $expected_error ) {
			$this->assertStringContains( $expected_error, $messages );
		}
	}

	/**
	 * Capture the ID of a new order when it's created by the checkout simulator.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id The order ID.
	 */
	public function capture_order_id( $order_id ) {

		$this->checkout_order_id = $order_id;
	}
}

// EOF
