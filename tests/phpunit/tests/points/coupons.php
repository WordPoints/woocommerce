<?php

/**
 * Test case for the coupons functions.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.3.0
 */

/**
 * Tests the coupons functions.
 *
 * @since 1.3.0
 */
class WordPoints_WooCommerce_Coupons_Functions_Test
	extends WordPoints_WooCommerce_Points_UnitTestCase {

	/**
	 * Tests checking if a coupon is valid for an order.
	 *
	 * @since 1.3.0
	 *
	 * @covers ::wordpoints_woocommerce_points_coupon_is_valid
	 */
	public function test_coupon_validate() {

		$user_id = $this->factory->user->create();

		wp_set_current_user( $user_id );

		// Give the user some points.
		wordpoints_set_points( $user_id, 5, 'points', 'test' );

		$this->assertSame( 5, wordpoints_get_points( $user_id, 'points' ) );

		// Create the order.
		$order = WC_Helper_Order::create_order( $user_id );

		// Create the coupon.
		$coupon = WC_Helper_Coupon::create_coupon( 'fake-coupon' );
		$coupon->set_amount( 10 );
		$coupon->update_meta_data( 'wordpoints_woocommerce_points_coupon_points_type', 'points' );
		$coupon->update_meta_data( 'wordpoints_woocommerce_points_coupon_cost', 10 );
		$coupon->save();

		// Apply the coupon.
		$result = $order->apply_coupon( $coupon );

		$this->assertWPError( $result );
		$this->assertSame(
			'This coupon costs $10pts., you have only $5pts..'
			, $result->get_error_message()
		);
	}

	/**
	 * Tests using a points coupon on an order.
	 *
	 * @since 1.3.0
	 *
	 * @covers ::wordpoints_woocommerce_points_coupons_apply
	 */
	public function test_coupon_apply() {

		$user_id = $this->factory->user->create();

		wp_set_current_user( $user_id );

		// Give the user some points.
		wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$this->assertSame( 100, wordpoints_get_points( $user_id, 'points' ) );

		// Create the order.
		$order = WC_Helper_Order::create_order( $user_id );

		$this->assertSame( '50.00', $order->get_total() );

		// Create the coupon.
		$coupon = WC_Helper_Coupon::create_coupon( 'fake-coupon' );
		$coupon->set_amount( 5 );
		$coupon->update_meta_data( 'wordpoints_woocommerce_points_coupon_points_type', 'points' );
		$coupon->update_meta_data( 'wordpoints_woocommerce_points_coupon_cost', 10 );
		$coupon->save();

		// Apply the coupon.
		$order->apply_coupon( $coupon );
		$order->save();

		// Check that the coupon is applied.
		$this->assertSame( '45.00', $order->get_total() );

		// Complete the order.
		$order->set_status( 'completed' );
		$order->save();

		// The points should have been deducted.
		$this->assertSame( 90, wordpoints_get_points( $user_id, 'points' ) );

		// Cancel the order.
		$order->set_status( 'cancelled' );
		$order->save();

		// The points should have been restored.
		$this->assertSame( 100, wordpoints_get_points( $user_id, 'points' ) );
	}
}

// EOF
