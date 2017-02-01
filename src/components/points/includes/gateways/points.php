<?php

/**
 * WordPoints Points payment gateway class.
 *
 * @package WordPoints_WooCommerce
 * @since 1.0.0
 */

/**
 * WordPoints Points Payment Gateway.
 *
 * @since 1.0.0
 */
class WordPoints_WooCommerce_Gateway_Points extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->id                   = 'wordpoints_points';
		$this->icon                 = '';
		$this->has_fields           = false;
		$this->method_title         = _x( 'WordPoints', 'gateway title', 'wordpoints-woocommerce' );
		/* translators: gateway description. */
		$this->method_description   = __( 'WordPoints works by letting the user pay with points.', 'wordpoints-woocommerce' );
		$this->supports 			= array( 'products', 'refunds' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		// Actions
		add_action(
			"woocommerce_update_options_payment_gateways_{$this->id}"
			, array( $this, 'process_admin_options' )
		);

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = false;
		}
	}

	/**
	 * Check if this gateway is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the gateway is enabled.
	 */
	public function is_valid_for_use() {

		if ( ! wordpoints_get_points_types() ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 1.0.0
	 */
	public function admin_options() {

		if ( $this->is_valid_for_use() ) {

			parent::admin_options();

		} else {

			?>
			<div class="inline error">
				<p>
					<strong><?php esc_html_e( 'Gateway Disabled:', 'wordpoints-woocommerce' ); ?></strong>
					<?php esc_html_e( 'You need to create one or more types of points.', 'wordpoints-woocommerce' ); ?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 *
	 * @since 1.0.0
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'wordpoints-woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable WordPoints points', 'wordpoints-woocommerce' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'       => _x( 'Title', 'form label', 'wordpoints-woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => __( 'This controls the title which the user sees during checkout.', 'wordpoints-woocommerce' ),
				'default'     => _x( 'Points', 'gateway title', 'wordpoints-woocommerce' ),
			),
			'description' => array(
				'title'       => _x( 'Description', 'form label', 'wordpoints-woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => __( 'This controls the description which the user sees during checkout.', 'wordpoints-woocommerce' ),
				'default'     => __( 'Pay with points.', 'wordpoints-woocommerce' ),
			),
			'points_type' => array(
				'title'       => _x( 'Points Type', 'form label', 'wordpoints-woocommerce' ),
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => __( 'Select which points type is used to pay.', 'wordpoints-woocommerce' ),
				'default'     => wordpoints_get_default_points_type(),
				'options'     => wp_list_pluck( wordpoints_get_points_types(), 'name' ),
			),
			'conversion_rate' => array(
				'title'       => __( 'Conversion Rate', 'wordpoints-woocommerce' ),
				'type'        => 'number',
				'default'     => '100',
				'desc_tip'    => true,
				'description' => sprintf(
					// translators: The formatted price (i.e., "$1.00").
					__( 'How many points should be counted as worth one monetary unit? For example, enter 100 if each 100 points should equal %s.', 'wordpoints-woocommerce' )
					, wc_price( 1 )
				),
			),
		);
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @since 1.0.0
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );
		$total = round( $order->get_total() * $this->settings['conversion_rate'] );

		// Back-compat for pre-WC 2.7.0.
		if ( ! method_exists( $order, 'get_user_id' ) ) {
			$user_id = $order->user_id;
		} else {
			$user_id = $order->get_user_id();
		}

		$user_points = wordpoints_get_points(
			$user_id
			, $this->settings['points_type']
		);

		if ( $user_points < $total ) {

			wc_add_notice(
				__( 'Payment error:', 'wordpoints-woocommerce' ) . ' '
				. __(
					'You have insufficient points to make this purchase.'
					, 'wordpoints-woocommerce'
				)
				, 'error'
			);

			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}

		$result = wordpoints_subtract_points(
			$user_id
			, $total
			, $this->settings['points_type']
			, 'woocommerce_points_gateway'
			, array( 'order_id' => $order_id )
		);

		if ( ! $result ) {

			wc_add_notice(
				__( 'Payment error:', 'wordpoints-woocommerce' ) . ' '
				. __(
					'Unable to subtract the points from your account.'
					, 'wordpoints-woocommerce'
				)
				, 'error'
			);

			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}

		$order->payment_complete();

		WC()->cart->empty_cart();

		return array(
			'result' 	=> 'success',
			'redirect'	=> $this->get_return_url( $order ),
		);
	}

	/**
	 * Process a refund if supported.
	 *
	 * @since 1.0.0
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}

		$refund = round( $amount * $this->settings['conversion_rate'] );

		// Back-compat for pre-WC 2.7.0.
		if ( ! method_exists( $order, 'get_user_id' ) ) {
			$user_id = $order->user_id;
		} else {
			$user_id = $order->get_user_id();
		}

		$result = wordpoints_add_points(
			$user_id
			, $refund
			, $this->settings['points_type']
			, 'woocommerce_points_gateway_refund'
			, array( 'order_id' => $order_id )
		);

		if ( ! $result ) {
			return false;
		}

		$order->add_order_note(
			// translators: The number of points refunded.
			sprintf( __( 'Refunded %s points.', 'wordpoints-woocommerce' ), $refund )
		);

		return true;
	}

	/**
	 * Register the gateway.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $methods The available gateways.
	 *
	 * @return string[] The available gateways with this one added.
	 */
	public static function add_gateway_class( $methods ) {

		$methods[] = __CLASS__;
		return $methods;
	}
}
add_filter(
	'woocommerce_payment_gateways'
	, 'WordPoints_WooCommerce_Gateway_Points::add_gateway_class'
);

// EOF
