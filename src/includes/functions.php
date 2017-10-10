<?php

/**
 * General utility functions of the extension.
 *
 * @package WordPoints_WooCommerce
 * @since 1.0.0
 */

/**
 * Install the extension.
 *
 * @since 1.0.0
 * @deprecated 1.0.2 Use WordPoints_Installables::install( 'module', 'woocommerce' )
 *                   instead.
 */
function wordpoints_woocommerce_install() {

	_deprecated_function( __FUNCTION__, '1.0.2', 'WordPoints_Installables::install' );

	WordPoints_Installables::install( 'module', 'woocommerce' );
}

/**
 * Load the extension's text domain.
 *
 * No longer needed as this is done automatically by WordPoints_Modules::register().
 *
 * @since 1.0.0
 * @deprecated 1.0.2
 */
function wordpoints_woocommerce_load_textdomain() {

	_deprecated_function( __FUNCTION__, '1.0.2' );

	wordpoints_load_module_textdomain(
		'wordpoints-woocommerce'
		, wordpoints_module_basename( WORDPOINTS_WOOCOMMERCE_DIR ) . '/languages'
	);
}

//
// Entities.
//

/**
 * Register entities when the entities app is initialized.
 *
 * @since 1.1.0
 *
 * @WordPress\action wordpoints_init_app_registry-apps-entities
 *
 * @param WordPoints_App_Registry $entities The entities app.
 */
function wordpoints_woocommerce_entities_init( $entities ) {

	$children = $entities->get_sub_app( 'children' );

	$entities->register( 'woocommerce_order', 'WordPoints_WooCommerce_Entity_Order' );
	$children->register( 'woocommerce_order', 'cart_tax', 'WordPoints_WooCommerce_Entity_Order_Tax_Cart' );
	$children->register( 'woocommerce_order', 'created_via', 'WordPoints_WooCommerce_Entity_Order_Created_Via' );
	$children->register( 'woocommerce_order', 'customer', 'WordPoints_WooCommerce_Entity_Order_Customer' );
	$children->register( 'woocommerce_order', 'customer_note', 'WordPoints_WooCommerce_Entity_Order_Customer_Note' );
	$children->register( 'woocommerce_order', 'date_completed', 'WordPoints_WooCommerce_Entity_Order_Date_Completed' );
	$children->register( 'woocommerce_order', 'date_created', 'WordPoints_WooCommerce_Entity_Order_Date_Created' );
	$children->register( 'woocommerce_order', 'date_paid', 'WordPoints_WooCommerce_Entity_Order_Date_Paid' );
	$children->register( 'woocommerce_order', 'discount_tax', 'WordPoints_WooCommerce_Entity_Order_Tax_Discount' );
	$children->register( 'woocommerce_order', 'discount_total', 'WordPoints_WooCommerce_Entity_Order_Total_Discount' );
	$children->register( 'woocommerce_order', 'grand_total', 'WordPoints_WooCommerce_Entity_Order_Total_Grand' );
	$children->register( 'woocommerce_order', 'shipping_tax', 'WordPoints_WooCommerce_Entity_Order_Tax_Shipping' );
	$children->register( 'woocommerce_order', 'shipping_total', 'WordPoints_WooCommerce_Entity_Order_Total_Shipping' );

	// Register our own handler for product comments, to give them a custom title.
	$entities->register( 'comment\\product', 'WordPoints_WooCommerce_Entity_Product_Review' );

	// Deregister the comment parent, because reviews don't support replies.
	$children->deregister( 'comment\\product', 'parent' );
}

/**
 * Register entity restrictions when the "know" restrictions registry is initialized.
 *
 * These are entities that are totally restricted, so that when the restriction
 * applies, the user is not even allowed to know that such an object exists.
 *
 * @since 1.1.0
 *
 * @WordPress\action wordpoints_init_app_registry-entities-restrictions-know
 *
 * @param WordPoints_Class_Registry_Deep_Multilevel $restrictions The restrictions
 *                                                                registry.
 */
function wordpoints_woocommerce_entity_restrictions_know_init( $restrictions ) {

	$restrictions->register(
		'nonpublic'
		, array( 'woocommerce_order' )
		, 'WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic'
	);

}

//
// Hooks.
//

/**
 * Register hook actions when the action registry is initialized.
 *
 * @since 1.1.0
 *
 * @WordPress\action wordpoints_init_app_registry-hooks-actions
 *
 * @param WordPoints_Hook_Actions $actions The action registry.
 */
function wordpoints_woocommerce_hook_actions_init( $actions ) {

	$actions->register(
		'woocommerce_order_complete'
		, 'WordPoints_Hook_Action'
		, array(
			'action' => 'woocommerce_order_status_completed',
			'data'   => array(
				'arg_index' => array( 'woocommerce_order' => 0 ),
			),
		)
	);

	$actions->register(
		'woocommerce_order_decomplete'
		, 'WordPoints_Hook_Action'
		, array(
			'action' => 'woocommerce_order_status_changed',
			'data'   => array(
				'arg_index'    => array( 'woocommerce_order' => 0 ),
				'requirements' => array( 1 => 'completed' ),
			),
		)
	);

	$actions->register(
		'woocommerce_order_delete\\shop_order'
		, 'WordPoints_WooCommerce_Hook_Action_Order_Delete'
		, array(
			'action' => 'delete_post',
			'data'   => array(
				'arg_index' => array( 'woocommerce_order' => 0 ),
			),
		)
	);
}

/**
 * Register hook events when the event registry is initialized.
 *
 * @since 1.1.0
 *
 * @WordPress\action wordpoints_init_app_registry-hooks-events
 *
 * @param WordPoints_Hook_Events $events The event registry.
 */
function wordpoints_woocommerce_hook_events_init( $events ) {

	// Update the Publish Product event class so as to use our translation strings.
	$events->register(
		'post_publish\\product'
		, 'WordPoints_WooCommerce_Hook_Event_Product_Publish'
	);

	$events->register(
		'comment_leave\\product'
		, 'WordPoints_WooCommerce_Hook_Event_Product_Review_Leave'
	);

	$events->register(
		'woocommerce_order_complete'
		, 'WordPoints_WooCommerce_Hook_Event_Order_Complete'
		, array(
			'actions' => array(
				'toggle_on'  => 'woocommerce_order_complete',
				'toggle_off' => array(
					'woocommerce_order_decomplete',
					'woocommerce_order_delete\\shop_order',
				),
			),
			'args' => array(
				'woocommerce_order' => 'WordPoints_Hook_Arg',
			),
		)
	);
}

// EOF
