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
 */
function wordpoints_woocommerce_install() {

	$wordpoints_data = wordpoints_get_network_option( 'wordpoints_data' );

	if ( ! isset( $wordpoints_data['modules']['woocommerce']['version'] ) ) {
		$wordpoints_data['modules']['woocommerce']['version'] = WORDPOINTS_WOOCOMMERCE_VERSION;
		wordpoints_update_network_option( 'wordpoints_data', $wordpoints_data );
	}
}
wordpoints_register_module_activation_hook(
	WORDPOINTS_WOOCOMMERCE_DIR . '/wordpoints-woocommerce.php'
	, 'wordpoints_woocommerce_install'
);

/**
 * Load the module's text domain.
 *
 * @since 1.0.0
 */
function wordpoints_woocommerce_load_textdomain() {

	wordpoints_load_module_textdomain(
		'wordpoints-woocommerce'
		, wordpoints_module_basename( WORDPOINTS_WOOCOMMERCE_DIR ) . '/languages'
	);
}
add_action( 'wordpoints_modules_loaded', 'wordpoints_woocommerce_load_textdomain' );

// EOF
