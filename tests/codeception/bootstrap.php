<?php

/**
 * This is global bootstrap for autoloading for the codeception tests.
 *
 * @package WordPoints_WooCommerce\Codeception
 * @since 1.2.1
 */

/**
 * The dev-lib's main Codeception bootstrap.
 *
 * @since 1.2.1
 */
require_once __DIR__ . '/../../dev-lib/wpcept/bootstrap.php';

$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
$loader->add_plugin( 'woocommerce/woocommerce.php' );
$loader->add_action(
	'after_load_wordpress'
	, function () {

		// Disable the set-up wizard.
		WC_Admin_Notices::remove_notice( 'install' );
		delete_transient( '_wc_activation_redirect' );

		// But still create all of the store pages.
		WC_Install::create_pages();
	}
);

// EOF
