<?php

/**
 * Tests saving the coupon settings.
 *
 * @package WordPoints_WooCommerce
 * @since   1.3.0
 */

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Save coupon points settings.' );
$I->hadCreatedAPointsType();
$I->amLoggedInAsAdminOnPage(
	'/wp-admin/post-new.php?post_type=shop_coupon'
);
$I->fillField( 'coupon_amount', '10' );
$I->click( 'Points', '.coupon_data_tabs' );
$I->fillField( 'wordpoints_woocommerce_points_coupon_cost', '10' );
$I->click( 'Publish' );
$I->click( 'Points', '.coupon_data_tabs' );
$I->seeInField( 'wordpoints_woocommerce_points_coupon_cost', '10' );

// EOF
