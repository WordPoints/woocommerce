<?php

/**
 * Uninstall the module.
 *
 * @package WordPoints_Woocommerce
 * @since 1.0.0
 */

require_once WORDPOINTS_DIR . 'components/points/includes/class-wordpoints-points-hooks.php';

WordPoints_Points_Hooks::uninstall_hook_types(
	'wordpoints_wc_order_complete_points_hook'
);

$wordpoints_data = wordpoints_get_network_option( 'wordpoints_data' );
unset( $wordpoints_data['modules']['woocommerce'] );
wordpoints_update_network_option( 'wordpoints_data', $wordpoints_data );

// EOF
