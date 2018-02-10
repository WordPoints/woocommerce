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

		$this->id           = 'wordpoints_points';
		$this->icon         = '';
		$this->has_fields   = count( $this->get_points_types_for_checkout() ) > 1;
		$this->supports     = array( 'products', 'refunds' );
		$this->method_title = _x( 'WordPoints', 'gateway title', 'wordpoints-woocommerce' );
		/* translators: gateway description. */
		$this->method_description = __( 'WordPoints works by letting the user pay with points.', 'wordpoints-woocommerce' );

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

		add_filter(
			'woocommerce_no_available_payment_methods_message'
			, array( $this, 'no_available_payment_methods_message' )
		);

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
		}
	}

	/**
	 * Gets the list of points types that users can use during checkout.
	 *
	 * @since 1.2.0
	 *
	 * @return array The list of points types that users can use to pay.
	 */
	public function get_points_types_for_checkout() {

		$for_checkout = array();
		$points_types = wordpoints_get_points_types();

		foreach ( $points_types as $slug => $data ) {
			if ( $this->get_option( "conversion_rate-{$slug}" ) ) {
				$for_checkout[ $slug ] = $data;
			}
		}

		return $for_checkout;
	}

	/**
	 * Check if this gateway is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the gateway is enabled.
	 */
	public function is_valid_for_use() {

		$points_types = $this->get_points_types_for_checkout();

		if ( empty( $points_types ) ) {
			return false;
		}

		if ( ! is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 1.0.0
	 */
	public function admin_options() {

		if ( wordpoints_get_points_types() ) {

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
		);

		foreach ( wordpoints_get_points_types() as $slug => $data ) {

			$description = sprintf(
				// translators: The formatted price (i.e., "$1.00").
				__(
					'How many points should be counted as worth one monetary unit? For example, enter 100 if each 100 points should equal %s.',
					'wordpoints-woocommerce'
				)
				, wc_price( 1 )
			);

			$description .= ' ' . __( 'Leave blank to disable checkout with this points type.', 'wordpoints-woocommerce' );

			$this->form_fields[ "conversion_rate-{$slug}" ] = array(
				// translators: Points type name.
				'title'       => sprintf( __( 'Conversion Rate for %s', 'wordpoints-woocommerce' ), $data['name'] ),
				'type'        => 'number',
				'default'     => '',
				'desc_tip'    => true,
				'description' => $description,
			);
		}
	}

	/**
	 * @since 1.2.0
	 */
	public function payment_fields() {

		parent::payment_fields();

		$this->points_type_field();
	}

	/**
	 * Displays the points type field during checkout.
	 *
	 * @since 1.2.0
	 */
	public function points_type_field() {

		$points_types = $this->get_points_types_for_checkout();

		if ( count( $points_types ) < 2 ) {
			return;
		}

		?>

		<p class="form-row form-row-first">
			<label for="<?php echo esc_attr( $this->id ); ?>-points-type"><?php echo esc_html__( 'Points Type', 'wordpoints-woocommerce' ); ?> <span class="required">*</span></label>
			<?php

			$dropdown = new WordPoints_Dropdown_Builder(
				wp_list_pluck( $points_types, 'name' )
				, array(
					'id'   => "{$this->id}-points-type",
					'name' => "{$this->id}-points-type",
				)
			);

			$dropdown->display();

			?>
		</p>

		<?php
	}

	/**
	 * Gets the points type that should be used to pay for the order.
	 *
	 * @since 1.2.0
	 *
	 * @return string|false The slug of the points type, or false.
	 */
	protected function get_points_type_to_pay_with() {

		$points_types = $this->get_points_types_for_checkout();

		if ( count( $points_types ) < 2 ) {
			return key( $points_types );
		}

		if (
			! isset(
				$_POST[ "{$this->id}-points-type" ], // WPCS: CSRF OK.
				$points_types[ $_POST[ "{$this->id}-points-type" ] ] // WPCS: CSRF OK.
			)
		) {

			wc_add_notice(
				__( 'Payment Error:', 'wordpoints-woocommerce' ) . ' '
				. __(
					'Please select a points type to pay with.'
					, 'wordpoints-woocommerce'
				)
				, 'error'
			);

			return false;
		}

		return sanitize_key( $_POST[ "{$this->id}-points-type" ] );
	}

	/**
	 * @since 1.2.0
	 */
	public function validate_fields() {

		if ( ! $this->get_points_type_to_pay_with() ) {
			return false;
		}

		return parent::validate_fields();
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @since 1.0.0
	 */
	public function process_payment( $order_id ) {

		$points_type = $this->get_points_type_to_pay_with();

		if ( ! $points_type ) {
			return array( 'result' => 'fail', 'redirect' => '' );
		}

		$conversion_rate = $this->settings[ "conversion_rate-{$points_type}" ];

		$order = wc_get_order( $order_id );
		$total = round( $order->get_total() * $conversion_rate );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_user_id' ) ) {
			$user_id = $order->user_id;
		} else {
			$user_id = $order->get_user_id();
		}

		$user_points = wordpoints_get_points( $user_id, $points_type );

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
			, $points_type
			, 'woocommerce_points_gateway'
			, array( 'order_id' => $order_id, 'conversion_rate' => $conversion_rate )
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
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
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

		$log = $this->get_points_log_for_order( $order_id );

		if ( ! $log ) {
			return false;
		}

		$conversion_rate = wordpoints_get_points_log_meta( $log->id, 'conversion_rate', true );

		if ( ! $conversion_rate ) {
			$conversion_rate = -$log->points / $order->get_total();
		}

		$refund = round( $amount * $conversion_rate );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_user_id' ) ) {
			$user_id = $order->user_id;
		} else {
			$user_id = $order->get_user_id();
		}

		$result = wordpoints_add_points(
			$user_id
			, $refund
			, $log->points_type
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
	 * Gets the points log for an order.
	 *
	 * @since 2.4.0
	 *
	 * @param int $order_id The ID of order.
	 *
	 * @return object|false The log or false.
	 */
	public function get_points_log_for_order( $order_id ) {

		$query = new WordPoints_Points_Logs_Query(
			array(
				'log_type'   => 'woocommerce_points_gateway',
				'meta_key'   => 'order_id',
				'meta_value' => $order_id,
			)
		);

		$log = $query->get( 'row' );

		if ( ! $log ) {
			return false;
		}

		return $log;
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

	/**
	 * Overrides the message displayed when no payment methods are available.
	 *
	 * @since 1.2.0
	 *
	 * @param string $message The message being displayed.
	 *
	 * @return string The message to display.
	 */
	public function no_available_payment_methods_message( $message ) {

		// If the gateway is enabled, but isn't usable because the user is logged out.
		if (
			'yes' === $this->get_option( 'enabled' )
			&& $this->get_points_types_for_checkout()
			&& ! is_user_logged_in()
		) {

			/** @var WC_Payment_Gateway[] $gateways */
			$gateways = WC()->payment_gateways()->payment_gateways();

			// Check if any other gateways are enabled.
			$others_enabled = false;

			foreach ( $gateways as $id => $gateway ) {

				if ( $id === $this->id ) {
					continue;
				}

				if ( 'yes' === $gateway->get_option( 'enabled' ) ) {
					$others_enabled = true;
					break;
				}
			}

			// And if not, show a customized message.
			if ( ! $others_enabled ) {
				return __( 'Sorry, you must be logged in and have sufficient points in order to check out.', 'wordpoints-woocommerce' );
			}
		}

		return $message;
	}
}

// EOF
