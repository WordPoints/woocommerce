<?php

/**
 * Acceptance tester class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.2.0
 */

/**
 * Tester for use in the acceptance tests.
 *
 * @since 1.2.0
 */
class AcceptanceTester extends \WordPoints\Tests\Codeception\AcceptanceTester {

	/**
	 * Logs in as a customer.
	 *
	 * @since 1.3.0
	 *
	 * @return int The customer user ID.
	 */
	public function amLoggedInAsCustomer() {

		$user_id = wp_insert_user( array( 'user_login' => 'customer', 'user_pass' => 'password' ) );
		update_user_meta( $user_id, 'billing_country', 'US' );
		update_user_meta( $user_id, 'billing_first_name', 'Joe' );
		update_user_meta( $user_id, 'billing_last_name', 'Tester' );
		update_user_meta( $user_id, 'billing_address_1', '12 Bukle Mishue' );
		update_user_meta( $user_id, 'billing_city', 'Hebron' );
		update_user_meta( $user_id, 'billing_state', 'MD' );
		update_user_meta( $user_id, 'billing_postcode', '12345' );
		update_user_meta( $user_id, 'billing_email', 'test@example.com' );
		update_user_meta( $user_id, 'billing_phone', '777-777-777-777' );

		wp_set_current_user( $user_id );

		$I = $this;
		$I->loginAs( 'customer', 'password' );

		return $user_id;
	}

	/**
	 * Creates a product to use in the test.
	 *
	 * @since 1.3.0
	 *
	 * @return int The product ID.
	 */
	public function hadCreatedAProduct() {

		$factory = new WordPoints_WooCommerce_UnitTest_Factory_For_Product();

		return $factory->create();
	}

	/**
	 * Creates a points coupon to use in the tests.
	 *
	 * @since 1.3.0
	 *
	 * @param string $points_type The points type to create the coupon for.
	 * @param int    $amount      The number of points the coupon should cost.
	 *
	 * @return WC_Coupon The coupon.
	 */
	public function hadCreatedAPointsCoupon( $points_type = 'points', $amount = 10 ) {

		$coupon = WC_Helper_Coupon::create_coupon( 'fake-coupon' );
		$coupon->set_amount( 5 );
		$coupon->update_meta_data( 'wordpoints_points_type', $points_type );
		$coupon->update_meta_data( 'wordpoints_points_amount', $amount );
		$coupon->save();

		return $coupon;
	}

	/**
	 * Sets the conversion rate for the points gateway.
	 *
	 * @since 1.3.0
	 *
	 * @param string $points_type The points type to set the conversion rate for.
	 * @param int    $rate        The conversion rate to set.
	 */
	public function hadSetPointsConversionRate( $points_type = 'points', $rate = 100 ) {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->settings[ "conversion_rate-{$points_type}" ] = $rate;
		update_option( $gateway->get_option_key(), $gateway->settings );
	}
}

// EOF
