<?php

/**
 * Tests using the points gateway to checkout.
 *
 * @package WordPoints_WooCommerce
 * @since   1.2.0
 */

$factory    = new WordPoints_WooCommerce_UnitTest_Factory_For_Product();
$product_id = $factory->create();

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

$gateway = new WordPoints_WooCommerce_Gateway_Points();
$gateway->settings['conversion_rate-points'] = 100;
$gateway->settings['conversion_rate-test']   = 50;
update_option( $gateway->get_option_key(), $gateway->settings );

wc_empty_cart();
WC()->cart->empty_cart();

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Checkout with points gateway.' );

$I->hadCreatedAPointsType();
$I->hadCreatedAPointsType( array( 'name' => 'Test' ) );

wordpoints_set_points( $user_id, 10000, 'test', 'test' );

$I->loginAs( 'customer', 'password' );
$I->amOnPage( str_replace( home_url(), '', get_permalink( $product_id ) ) );
$I->click( 'Add to cart' );
$I->amOnPage( str_replace( home_url(), '', wc_get_page_permalink( 'checkout' ) ) );
$I->click( '#payment_method_wordpoints_points' );
$I->waitForElementVisible( '[name=wordpoints_points-points-type]' );
$I->selectOption( 'wordpoints_points-points-type', 'Test' );
$I->click( 'Place order' );
$I->waitForElementNotVisible( '.blockOverlay' );
$I->see( 'Thank you. Your order has been received.' );
PHPUnit_Framework_Assert::assertSame( 8750, wordpoints_get_points( $user_id, 'test' ) );

// EOF
