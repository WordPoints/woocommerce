<?php

/**
 * A test case for the Order Complete points hook.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * Test the Order Complete points hook.
 *
 * @since 1.0.0
 */
class WordPoints_WooCommerce_Order_Complete_Points_Hook_Test extends WordPoints_Points_UnitTestCase {

	/**
	 * Add the custom factories on setup.
	 *
	 * @since 1.0.0
	 */
	public function setUp() {

		parent::setUp();

		$this->factory->order   = new WordPoints_WooCommerce_UnitTest_Factory_For_Order;
		$this->factory->product = new WordPoints_WooCommerce_UnitTest_Factory_For_Product;
	}

	/**
	 * Test that points are awarded when an order is completed.
	 *
	 * @since 1.0.0
	 */
	public function test_points_awarded() {

		wordpointstests_add_points_hook( 'wordpoints_woocommerce_order_complete_points_hook' );

		// Create an order for a $50 product.
		$user_id    = $this->factory->user->create();
		$product_id = $this->factory->product->create(
			array(
				'_price'        => 50,
				'_virtual'      => 'yes',
				'_downloadable' => 'yes'
			)
		);
		$order      = $this->factory->order->create_and_get(
			array(
				'products'    => array( $product_id => 1 ),
				'customer_id' => $user_id,
			)
		);

		// Pretend we recieved payment (no processing necessary for virtual product).
		$order->payment_complete();

		// The user should have recieved 50 points.
		$this->assertEquals( 50, wordpoints_get_points( $user_id, 'points' ) );
	}
}

// EOF
