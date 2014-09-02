<?php

/**
 * Module Name: WooCommerce
 * Author:      J.D. Grimes
 * Author URI:  http://codesymphony.co/
 * Version:     1.0.0
 * License:     GPLv2+
 * Description: Integrates WordPoints with WooCommerce to let you award points for user's purchases.
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

/** The module's constants. */
include_once( dirname( __FILE__ ) . '/includes/constants.php' );

/** The module's general utility functions. */
include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/includes/functions.php' );

if ( wordpoints_component_is_active( 'points' ) ) {

	/** Points-related code. */
	include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/points.php' );
}

// EOF
