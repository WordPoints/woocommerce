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
 * @group gateways
 */
class WordPoints_WooCommerce_Points_Gateway_Test extends WordPoints_WooCommerce_Points_UnitTestCase {

	/**
	 * The ID of the original current user.
	 *
	 * This is overridden during the tests.
	 *
	 * @since 1.0.0
	 *
	 * @type int $original_user_id
	 */
	protected $original_user_id;

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

		$this->original_user_id = get_current_user_id();
		wp_set_current_user( $this->factory->user->create() );

		wordpoints_update_maybe_network_option(
			'wordpoints_default_points_type'
			, 'points'
		);

		add_filter( 'query', array( $this, 'no_commit_queries' ) );
	}

	/**
	 * Clean up after each test.
	 *
	 * @since 1.0.0
	 */
	public function tearDown() {

		wp_set_current_user( $this->original_user_id );

		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate'] = 1;

		WC()->cart->empty_cart();

		parent::tearDown();
	}

	/**
	 * Test that points are charged when a user checks out.
	 *
	 * @since 1.0.0
	 */
	public function test_processing_payment() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$this->simulate_checkout();

		$this->assertEquals( 75, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test that there is an error if the user has insufficient points.
	 *
	 * @since 1.0.0
	 */
	public function test_insufficient_points() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 10, 'points', 'test' );

		$this->simulate_checkout(
			array( 'expected_errors' => 'Payment error: You have insufficient points to make this purchase.' )
		);

		$this->assertEquals( 10, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test when the exchange rate is 100.
	 *
	 * @since 1.0.0
	 */
	public function test_100_exchange_rate() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		wordpoints_set_points( $user_id, 3000, 'points', 'test' );

		// Set the exchange rate.
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		$gateways['wordpoints_points']->settings['conversion_rate'] = 100;

		$this->simulate_checkout();

		$this->assertEquals( 500, wordpoints_get_points( $user_id, 'points' ) );
	}

	/**
	 * Test that points are refunded correctly.
	 *
	 * @since 1.0.0
	 */
	public function test_refund() {

		// Give the user points to make the purchase with.
		$user_id = get_current_user_id();
		$result = wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$this->simulate_checkout();

		$this->assertEquals( 75, wordpoints_get_points( $user_id, 'points' ) );

		// Refund the order.
		$order_id = $this->checkout_order_id;
		$refund_amount = 20;
		$refund_reason = 'Testing refunds.';

		$refund = wc_create_refund(
			array(
				'amount' => $refund_amount,
				'reason' => $refund_reason,
				'order_id' => $order_id,
			)
		);

		$payment_gateways = WC()->payment_gateways->payment_gateways();

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

		$this->assertEquals( 95, wordpoints_get_points( $user_id, 'points' ) );
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

		$_POST['_wpnonce']           = wp_create_nonce( 'woocommerce-process_checkout' );
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
			WC()->checkout->process_checkout();
			remove_action( 'woocommerce_new_order', array( $this, 'capture_order_id' ) );
		} catch ( Exception $e ) {
			$this->assertEquals(
				'WordPoints_WooCommerce_Points_Gateway_Test'
				, $e->getMessage()
			);
		}

		ob_start();
		wc_print_notices();
		$messages = ob_get_clean();

		$expected_errors = '';
		if ( ! empty( $args['expected_errors'] ) ) {

			$expected_errors = '			<li>'
				. implode( '', (array) $args['expected_errors'] )
				. '</li>' . "\n";

		} else {

			$expected_errors =
				'			<li>WordPoints_WooCommerce_Points_Gateway_Test</li>'
				. "\n";

		}

		$this->assertEquals(
			'<ul class="woocommerce-error">' . "\n" . $expected_errors . '	</ul>'
			, trim( $messages )
		);
	}

	/**
	 * Capture the ID of a new order when it's created by the chckout simulator.
	 *
	 * @since 1.0.0
	 */
	public function capture_order_id( $order_id ) {

		$this->checkout_order_id = $order_id;
	}
}

// EOF
