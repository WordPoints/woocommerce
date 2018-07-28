<?php

/**
 * WordPoints WooCommerce integration extension.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2014-18  J.D. Grimes  (email : jdg@codesymphony.co)
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_WooCommerce
 * @version 1.3.1
 * @author  J.D. Grimes <jdg@codesymphony.co>
 */

wordpoints_register_extension(
	'
		Extension Name: WooCommerce
		Extension URI:  https://wordpoints.org/extensions/woocommerce/
		Author:         J.D. Grimes
		Author URI:     https://codesymphony.co/
		Version:        1.3.1
		Description:    Let your users pay with points.
		Text Domain:    wordpoints-woocommerce
		Domain Path:    /languages
		ID:             445
		Server:         wordpoints.org
		Namespace:      WooCommerce
	'
	, __FILE__
);

WordPoints_Class_Autoloader::register_dir( dirname( __FILE__ ) . '/classes' );

/**
 * The extension's constants.
 *
 * @since 1.0.0
 */
require_once dirname( __FILE__ ) . '/includes/constants.php';

/**
 * The extension's general utility functions.
 *
 * @since 1.0.0
 */
require_once WORDPOINTS_WOOCOMMERCE_DIR . '/includes/functions.php';

/**
 * Hooks up the extension's action and filter hooks.
 *
 * @since 1.1.0
 */
require_once WORDPOINTS_WOOCOMMERCE_DIR . '/includes/actions.php';

if ( wordpoints_component_is_active( 'points' ) ) {

	/**
	 * Points-related code.
	 *
	 * @since 1.0.0
	 */
	require_once WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/points.php';
}

// EOF
