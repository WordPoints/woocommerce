<?php

/**
 * WordPoints WooCommerce integration module.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2014-17  J.D. Grimes  (email : jdg@codesymphony.co)
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_WooCommerce
 * @version 1.1.0
 * @author  J.D. Grimes <jdg@codesymphony.co>
 */

WordPoints_Modules::register(
	'
		Module Name: WooCommerce
		Module URI:  https://wordpoints.org/modules/woocommerce/
		Author:      J.D. Grimes
		Author URI:  https://codesymphony.co/
		Version:     1.1.0
		Description: Let your users pay with points.
		Text Domain: wordpoints-woocommerce
		Domain Path: /languages
		ID:          445
		Channel:     wordpoints.org
		Namespace:   WooCommerce
	'
	, __FILE__
);

WordPoints_Class_Autoloader::register_dir( dirname( __FILE__ ) . '/classes' );

/**
 * The module's constants.
 *
 * @since 1.0.0
 */
include_once( dirname( __FILE__ ) . '/includes/constants.php' );

/**
 * The module's general utility functions.
 *
 * @since 1.0.0
 */
include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/includes/functions.php' );

/**
 * Hooks up the module's action and filter hooks.
 *
 * @since 1.1.0
 */
include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/includes/actions.php' );

if ( wordpoints_component_is_active( 'points' ) ) {

	/**
	 * Points-related code.
	 *
	 * @since 1.0.0
	 */
	include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/points.php' );
}

// EOF
