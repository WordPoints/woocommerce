<?php

/**
 * WooCommerce order complete points hook.
 *
 * @package WordPoints_WooCommerce\Points\Hooks
 * @since 1.0.0
 */

/**
 * Points hook to award points when a WooCommerce order is complete.
 *
 * @since 1.0.0
 */
class WordPoints_WC_Order_Complete_Points_Hook extends WordPoints_Points_Hook {

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::init(
			_x( 'WooCommerce Order Complete', 'points hook name', 'wordpoints-woocommerce' )
			, array( 'description' => _x( 'Completing an order.', 'points hook description', 'wordpoints-woocommerce' ) )
		);

		add_action( 'woocommerce_order_status_completed', array( $this, 'hook' ) );
		add_filter( 'wordpoints_points_log-woocommerce_order_complete', array( $this, 'logs' ), 10, 6 );
	}

	/**
	 * Award points when the hook is fired.
	 *
	 * @since 1.0.0
	 *
	 * @action woocommerce_order_status_completed Added by the constructor.
	 *
	 * @param int $order_id The ID of the order.
	 */
	public function hook( $order_id ) {

		$order = new WC_Order( $order_id );

		if ( ! $order->user_id ) {
			return;
		}

		$price = round(
			$order->get_total()
			- $order->get_total_tax()
			- $order->get_total_shipping()
		);

		if ( ! $price ) {
			return;
		}

		foreach ( $this->get_instances() as $number => $instance ) {

			wordpoints_add_points(
				$order->user_id
				, $price
				, $this->points_type( $number )
				, 'woocommerce_order_complete'
				, array( 'order_id' => $order_id )
			);
		}
	}

	/**
	 * Generate the log entry for a transaction.
	 *
	 * @since 1.0.0
	 *
	 * @action wordpoints_render_log-woocommerce_order_complete Added by the constructor.
	 *
	 * @param string $text        The text for the log entry.
	 * @param int    $points      The number of points.
	 * @param string $points_type The type of points for the transaction.
	 * @param int    $user_id     The affected user's ID.
	 * @param string $log_type    The type of transaction.
	 * @param array  $meta        Transaction meta data.
	 *
	 * @return string The log entry.
	 */
	public function logs( $text, $points, $points_type, $user_id, $log_type, $meta ) {

		if ( isset( $meta['order_id'] ) ) {

			$order = new WC_Order( $meta['order_id'] );

			if ( $order->id ) {
				return sprintf(
					_x( 'Order <a href="%s">%s</a>.', 'points log description', 'wordpoints' )
					, $order->get_view_order_url()
					, $order->get_order_number()
				);
			}
		}

		return _x( 'Completed order.', 'points log description', 'wordpoints' );
	}

	/**
	 * Display the settings update form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Instance settings. Not used.
	 *
	 * @return false No form.
	 */
	protected function form( $instance ) {

		return false;
	}

	/**
	 * Update a particular instance of this hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user
	 *                            via form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array|false Settings to save, or false to cancel saving.
	 */
	protected function update( $new_instance, $old_instance ) {

		return array_merge( $new_instance, $old_instance );
	}
}
WordPoints_Points_Hooks::register( 'WordPoints_WC_Order_Complete_Points_Hook' );

// EOF
