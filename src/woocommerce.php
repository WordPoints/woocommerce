<?php

/**
 * Module Name: WooCommerce
 * Author:      J.D. Grimes
 * Author URI:  http://codesymphony.co/
 * Version:     1.0.1
 * Description: Let your users pay with points.
 * Text Domain: wordpoints-woocommerce
 * Domain Path: /languages
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2014-15  J.D. Grimes  (email : jdg@codesymphony.co)
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_WooCommerce
 * @version 1.0.1
 * @author  J.D. Grimes <jdg@codesymphony.co>
 */

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

if ( wordpoints_component_is_active( 'points' ) ) {

	/**
	 * Points-related code.
	 *
	 * @since 1.0.0
	 */
	include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/points.php' );
}

// EOF
