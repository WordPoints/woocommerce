<?php

/**
 * General utility functions of the module.
 *
 * @package WordPoints_WooCommerce
 * @since 1.0.0
 */

/**
 * Install the module.
 *
 * @since 1.0.0
 * @deprecated 1.0.2 Use WordPoints_Installables::install( 'module', 'woocommerce' )
 *                   instead.
 */
function wordpoints_woocommerce_install() {

	_deprecated_function( __FUNCTION__, '1.0.2', 'WordPoints_Installables::install' );

	WordPoints_Installables::install( 'module', 'woocommerce' );
}

/**
 * Load the module's text domain.
 *
 * No longer needed as this is done automatically by WordPoints_Modules::register().
 *
 * @since 1.0.0
 * @deprecated 1.0.2
 */
function wordpoints_woocommerce_load_textdomain() {

	_deprecated_function( __FUNCTION__, '1.0.2' );

	wordpoints_load_module_textdomain(
		'wordpoints-woocommerce'
		, wordpoints_module_basename( WORDPOINTS_WOOCOMMERCE_DIR ) . '/languages'
	);
}

// EOF
