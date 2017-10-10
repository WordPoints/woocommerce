<?php

/**
 * Hooks up the points-related actions and filters used by the extension.
 *
 * @package WordPoints_WooCommerce\Points
 * @since   1.1.0
 */

add_filter(
	'woocommerce_payment_gateways'
	, 'WordPoints_WooCommerce_Gateway_Points::add_gateway_class'
);

// EOF
