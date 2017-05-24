<?php

/**
 * Tests saving the gateway settings.
 *
 * @package WordPoints_WooCommerce
 * @since   1.2.0
 */

activate_plugin( 'woocommerce/woocommerce.php' );

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Save points gateway settings.' );
$I->hadCreatedAPointsType();
$I->amLoggedInAsAdminOnPage(
	'/wp-admin/admin.php?page=wc-settings&tab=checkout&section=wordpoints_points'
);
$I->see( 'Conversion Rate For Points' );
$I->fillField( '#woocommerce_wordpoints_points_conversion_rate-points', '100' );
$I->click( 'Save changes' );
$I->seeInField( '#woocommerce_wordpoints_points_conversion_rate-points', '100' );

// EOF
