<?php

/**
 * Test case for the Order Complete hook event.
 *
 * @package WordPoints\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests the Order Complete hook event.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Hook_Event_Order_Complete
 */
class WordPoints_WooCommerce_Hook_Event_Order_Complete_Test
	extends WordPoints_PHPUnit_TestCase_Hook_Event {

	/**
	 * @since 1.1.0
	 */
	protected $event_class = 'WordPoints_WooCommerce_Hook_Event_Order_Complete';

	/**
	 * @since 1.1.0
	 */
	protected $event_slug = 'woocommerce_order_complete';

	/**
	 * @since 1.1.0
	 */
	protected $expected_targets = array(
		array( 'woocommerce_order', 'customer', 'user' ),
	);

	/**
	 * @since 1.1.0
	 */
	protected function fire_event( $arg, $reactor_slug ) {

		$order_id = $this->create_order();

		$order = wc_get_order( $order_id );
		$order->update_status( 'processing' );
		$order->update_status( 'completed' );

		return array( $order_id, $this->create_order() );
	}

	/**
	 * @since 1.1.0
	 */
	protected function reverse_event( $arg_id, $index ) {

		if ( 0 === $index ) {
			$this->refund_order( $arg_id );
		} else {
			WC_Helper_Order::delete_order( $arg_id );
		}
	}

	/**
	 * Create and complete an order.
	 *
	 * @since 1.1.0
	 *
	 * @return int The order ID.
	 */
	protected function create_order() {

		$order = WC_Helper_Order::create_order( $this->factory->user->create() );

		$order->payment_complete();

		$order->update_status( 'completed' );

		return $order->get_id();
	}

	/**
	 * Simulate an Ajax request to refund an order.
	 *
	 * @since 1.1.0
	 *
	 * @param int $order_id The ID of the order to refund.
	 */
	protected function refund_order( $order_id ) {

		$order = wc_get_order( $order_id );

		$this->give_current_user_caps( 'edit_shop_orders' );

		$_REQUEST['security'] = wp_create_nonce( 'order-item' );

		$_POST['order_id']               = $order->get_id();
		$_POST['refund_amount']          = $order->get_total();
		$_POST['refunded_amount']        = '0.00';
		$_POST['refund_reason']          = 'Testing.';
		$_POST['line_item_qtys']         = '[]';
		$_POST['line_item_totals']       = '[]';
		$_POST['line_item_tax_totals']   = '[]';
		$_POST['api_refund']             = 'false';
		$_POST['restock_refunded_items'] = 'false';

		add_action( 'wp_doing_ajax', '__return_true' );
		add_action( 'wp_die_ajax_handler', array( $this, 'throw_exception' ) );

		// Prevent WooCommerce from deleting the refund and aborting the request
		// when our exception gets thrown from wp_die().
		add_action( 'pre_delete_post', array( $this, 'throw_exception' ) );

		try {

			WC_AJAX::refund_line_items();

		} catch ( WordPoints_PHPUnit_Exception $e ) {
			unset( $e );
		}

		ob_end_clean();

		remove_action( 'pre_delete_post', array( $this, 'throw_exception' ) );
	}
}

// EOF
