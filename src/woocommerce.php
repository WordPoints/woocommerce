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
 * Copyright 2014  J.D. Grimes  (email : jdg@codesymphony.co)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or later, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_WooCommerce
 * @version 1.0.0
 * @author  J.D. Grimes <jdg@codesymphony.co>
 * @license GPLv2+
 */

/** The module's constants. */
include_once( dirname( __FILE__ ) . '/includes/constants.php' );

if ( wordpoints_component_is_active( 'points' ) ) {

	/** Points-related code. */
	include_once( WORDPOINTS_WOOCOMMERCE_DIR . '/components/points/points.php' );
}

// EOF
