<?php

/**
 * Tests using a points coupon at checkout.
 *
 * @package WordPoints_WooCommerce
 * @since   1.3.0
 */

wc_empty_cart();
WC()->cart->empty_cart();

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Use a points coupon at checkout.' );

$product_id = $I->hadCreatedAProduct();

$I->hadCreatedAPointsType();
$I->hadCreatedAPointsType( array( 'name' => 'Test' ) );

$I->hadCreatedAPointsCoupon( 'test' );

$I->hadSetPointsConversionRate();
$I->hadSetPointsConversionRate( 'test', 50 );

$user_id = $I->amLoggedInAsCustomer();

wordpoints_set_points( $user_id, 100, 'test', 'test' );
wordpoints_set_points( $user_id, 10000, 'points', 'test' );

$I->amOnPage( str_replace( home_url(), '', get_permalink( $product_id ) ) );
$I->click( 'Add to cart' );
$I->amOnPage( str_replace( home_url(), '', wc_get_page_permalink( 'cart' ) ) );
$I->fillField( 'Coupon:', 'fake-coupon' );
$I->click( 'Apply coupon' );
$I->waitForJqueryAjax();
$I->click( 'Proceed to checkout' );
$I->waitForElementVisible( '[name=wordpoints_points-points-type]' );
$I->selectOption( 'wordpoints_points-points-type', 'Points' );
$I->click( 'Place order' );
$I->waitForElementNotVisible( '.blockOverlay' );
$I->see( 'Thank you. Your order has been received.' );

PHPUnit_Framework_Assert::assertSame( 90, wordpoints_get_points( $user_id, 'test' ) );

// EOF
