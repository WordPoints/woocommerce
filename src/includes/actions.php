<?php

/**
 * Hooks up the actions and filters used by the extension.
 *
 * @package WordPoints_WooCommerce
 * @since   1.1.0
 */

// Don't hook up the actions if WooCommerce isn't active.
if ( ! function_exists( 'wc_get_order' ) ) {
	return;
}

add_action( 'wordpoints_init_app_registry-apps-entities', 'wordpoints_woocommerce_entities_init' );
add_action( 'wordpoints_init_app_registry-entities-restrictions-know', 'wordpoints_woocommerce_entity_restrictions_know_init' );

add_action( 'wordpoints_init_app_registry-hooks-events', 'wordpoints_woocommerce_hook_events_init' );
add_action( 'wordpoints_init_app_registry-hooks-actions', 'wordpoints_woocommerce_hook_actions_init' );

// EOF
