<?php

/**
 * Module Name: WooCommerce
 * Author:      J.D. Grimes
 * Author URI:  http://codesymphony.co/
 * Version:     1.0.0
 * License:     GPLv2+
 * Description: Let your users pay with points, and/or reward your users with points whem they make a purchase.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2014  J.D. Grimes  (email : jdg@codesymphony.co)  All rights reserved.
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_WooCommerce
 * @version 1.0.0
 * @author  J.D. Grimes <jdg@codesymphony.co>
 * @license GPLv2+
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
