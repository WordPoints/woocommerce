<?php

/**
 * Points component-related functions.
 *
 * @package WordPoints_WooCommerce\Points
 * @since 1.0.0
 */

/**
 * Render logs for an order through the points gateway.
 *
 * @since 1.0.0
 */
function wordpoints_points_logs_woocommerce_points_gateway( // WPCS: prefix OK.
		$text, $points, $points_type, $user_id, $log_type, $meta
	) {

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Payment for order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Payment for an order.', 'points log description', 'wordpoints-woocommerce' );
}
add_filter(
	'wordpoints_points_log-woocommerce_points_gateway'
	, 'wordpoints_points_logs_woocommerce_points_gateway'
	, 10
	, 6
);

/**
 * Render logs for a refund for an order through the points gateway.
 *
 * @since 1.0.0
 */
function wordpoints_points_logs_woocommerce_points_gateway_refund( // WPCS: prefix OK.
		$text, $points, $points_type, $user_id, $log_type, $meta
	) {

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Refunded payment for order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Refunded payment for an order.', 'points log description', 'wordpoints-woocommerce' );

}
add_filter(
	'wordpoints_points_log-woocommerce_points_gateway_refund'
	, 'wordpoints_points_logs_woocommerce_points_gateway_refund'
	, 10
	, 6
);

/**
 * Filters the tabs for the coupon settings.
 *
 * Adds the points tab.
 *
 * @since 1.3.0
 *
 * @param array $tabs The coupon settings tabs.
 *
 * @return array The filtered coupon settings tabs.
 */
function wordpoints_woocommerce_points_coupon_tabs_filter( $tabs ) {

	$tabs['wordpoints_points'] = array(
		'label'  => __( 'Points', 'wordpoints-woocommerce' ),
		'target' => 'wordpoints_points_coupon_data',
		'class'  => '',
	);

	return $tabs;
}
add_filter( 'woocommerce_coupon_data_tabs', 'wordpoints_woocommerce_points_coupon_tabs_filter' );

/**
 * Displays the points coupon data panel.
 *
 * @since 1.3.0
 *
 * @param int       $coupon_id The ID of the coupon the settings are being shown for.
 * @param WC_Coupon $coupon    The coupon the settings are being shown for.
 */
function wordpoints_woocommerce_points_coupon_data_panel( $coupon_id, $coupon ) {

	?>

	<div id="wordpoints_points_coupon_data" class="panel woocommerce_options_panel">
		<?php

		// Points cost.
		woocommerce_wp_text_input(
			array(
				'id'          => 'wordpoints_woocommerce_points_coupon_cost',
				'label'       => __( 'Points cost per use', 'wordpoints-woocommerce' ),
				'description' => __( 'Number of points it costs to use this coupon. Leave blank to not charge points for this coupon.', 'wordpoints-woocommerce' ),
				'type'        => 'number',
				'desc_tip'    => true,
			)
		);

		// Points type.
		woocommerce_wp_select(
			array(
				'id'      => 'wordpoints_woocommerce_points_coupon_points_type',
				'label'   => __( 'Points type', 'wordpoints-woocommerce' ),
				'options' => wp_list_pluck( wordpoints_get_points_types(), 'name' ),
			)
		);

		/**
		 * Fires at the bottom of the points coupon settings panel.
		 *
		 * @since 1.3.0
		 *
		 * @param int       $coupon_id The ID of the coupon the settings are being shown for.
		 * @param WC_Coupon $coupon    The coupon the settings are being shown for.
		 */
		do_action( 'wordpoints_woocommerce_points_coupon_options', $coupon_id, $coupon );

		?>
	</div>

	<?php
}
add_action( 'woocommerce_coupon_data_panels', 'wordpoints_woocommerce_points_coupon_data_panel', 10, 2 );

/**
 * Saves the points coupon options.
 *
 * @since 1.3.0
 *
 * @param int       $coupon_id The ID of the coupon whose settings are being saved.
 * @param WC_Coupon $coupon    The coupon whose settings are being saved.
 */
function wordpoints_woocommerce_points_coupon_options_save( $coupon_id, $coupon ) {

	if (
		isset( $_POST['wordpoints_woocommerce_points_coupon_points_type'] ) // WPCS: CSRF OK.
		&& isset( $_POST['wordpoints_woocommerce_points_coupon_cost'] ) // WPCS: CSRF OK.
	) {

		$coupon->update_meta_data(
			'wordpoints_woocommerce_points_coupon_points_type',
			sanitize_key( $_POST['wordpoints_woocommerce_points_coupon_points_type'] ) // WPCS: CSRF OK.
		);

		$coupon->update_meta_data(
			'wordpoints_woocommerce_points_coupon_cost'
			, wordpoints_posint( $_POST['wordpoints_woocommerce_points_coupon_cost'] ) // WPCS: CSRF OK.
		);

		$coupon->save();
	}
}
add_action( 'woocommerce_coupon_options_save', 'wordpoints_woocommerce_points_coupon_options_save', 10, 2 );

/**
 * Validates a coupon based on the user's points.
 *
 * @since 1.3.0
 *
 * @throws Exception If the user has insufficient points to use the coupon.
 *
 * @param bool      $is_valid Whether the coupon is valid.
 * @param WC_Coupon $coupon   The coupon being validated.
 *
 * @return bool Whether the coupon is valid.
 */
function wordpoints_woocommerce_points_coupon_is_valid( $is_valid, $coupon ) {

	if ( $is_valid ) {

		$points = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_cost' );

		if ( wordpoints_posint( $points ) ) {

			$points_type = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_points_type' );

			$user_id = get_current_user_id();

			$user_points = wordpoints_get_points( $user_id, $points_type );

			if ( $user_points < $points ) {
				throw new Exception(
					sprintf(
						// translators: 1: Coupon price in points. 2: Number of points the user has.
						__( 'This coupon costs %1$s, you have only %2$s.', 'wordpoints-woocommerce' )
						, wordpoints_format_points(
							$points
							, $points_type
							, 'woocommerce_coupon_error_cost'
						)
						, wordpoints_format_points(
							$user_points
							, $points_type
							, 'woocommerce_coupon_error_balance'
						)
					)
					, 100172001 // Creative prefixing: 1 W0RDp01N72 001
				);
			}
		}
	}

	return $is_valid;
}
add_filter( 'woocommerce_coupon_is_valid', 'wordpoints_woocommerce_points_coupon_is_valid', 10, 2 );

/**
 * Filters the coupon amount shown on the checkout page.
 *
 * Appends the points cost of the coupon, if any.
 *
 * @since 1.3.0
 *
 * @param string    $html   The HTML of the coupon discount amount.
 * @param WC_Coupon $coupon The coupon.
 *
 * @return string The coupon amount HTML.
 */
function wordpoints_woocommerce_points_coupon_amount_html_filter( $html, $coupon ) {

	$points = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_cost' );

	if ( wordpoints_posint( $points ) ) {

		$points_type = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_points_type' );

		$cost = sprintf(
			// translators: Coupon price in points.
			__( '(Cost: %s)', 'wordpoints-woocommerce' )
			, wordpoints_format_points(
				$points
				, $points_type
				, 'woocommerce_coupon_cost'
			)
		);

		$html .= ' <span class="wordpoints-woocommerce-points-coupon-cost">' . $cost . '</span>';
	}

	return $html;
}
add_filter( 'woocommerce_coupon_discount_amount_html', 'wordpoints_woocommerce_points_coupon_amount_html_filter', 10, 2 );

/**
 * Update used coupon amount for each coupon within an order.
 *
 * @since 1.3.0
 *
 * @param int $order_id The oder ID.
 */
function wordpoints_woocommerce_points_coupons_apply( $order_id ) {

	$order = wc_get_order( $order_id );

	if ( ! $order ) {
		return;
	}

	$has_recorded = $order->get_data_store()->get_recorded_coupon_usage_counts( $order );

	if ( $order->has_status( 'cancelled' ) && $has_recorded ) {
		$action = 'refund';
	} elseif ( ! $order->has_status( 'cancelled' ) && ! $has_recorded ) {
		$action = 'charge';
	} else {
		return;
	}

	$user_id = $order->get_user_id();

	if ( ! $user_id ) {
		return;
	}

	foreach ( $order->get_used_coupons() as $code ) {

		if ( ! $code ) {
			continue;
		}

		$coupon = new WC_Coupon( $code );

		$points = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_cost' );

		if ( ! wordpoints_posint( $points ) ) {
			continue;
		}

		$points_type = $coupon->get_meta( 'wordpoints_woocommerce_points_coupon_points_type' );

		$points = 'refund' === $action ? $points : -$points;

		wordpoints_alter_points(
			$user_id
			, $points
			, $points_type
			, "woocommerce_coupon_{$action}"
			, array(
				'order_id'  => $order_id,
				'coupon_id' => $coupon->get_id(),
			)
		);
	}
}
add_action( 'woocommerce_order_status_pending', 'wordpoints_woocommerce_points_coupons_apply', 5 );
add_action( 'woocommerce_order_status_completed', 'wordpoints_woocommerce_points_coupons_apply', 5 );
add_action( 'woocommerce_order_status_processing', 'wordpoints_woocommerce_points_coupons_apply', 5 );
add_action( 'woocommerce_order_status_on-hold', 'wordpoints_woocommerce_points_coupons_apply', 5 );
add_action( 'woocommerce_order_status_cancelled', 'wordpoints_woocommerce_points_coupons_apply', 5 );

/**
 * Render logs for when a coupon was used on an order.
 *
 * @since 1.3.0
 */
function wordpoints_woocommerce_points_logs_coupon_charge(
	$text, $points, $points_type, $user_id, $log_type, $meta
) {

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Coupon used on order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Coupon used on an order.', 'points log description', 'wordpoints-woocommerce' );
}
add_filter(
	'wordpoints_points_log-woocommerce_coupon_charge'
	, 'wordpoints_woocommerce_points_logs_coupon_charge'
	, 10
	, 6
);

/**
 * Render logs for when a coupon used on an order is cancelled.
 *
 * @since 1.3.0
 */
function wordpoints_woocommerce_points_logs_coupon_refund(
	$text, $points, $points_type, $user_id, $log_type, $meta
) {

	if ( isset( $meta['order_id'] ) ) {

		$order = new WC_Order( $meta['order_id'] );

		// Back-compat for pre-WC 3.0.0.
		if ( ! method_exists( $order, 'get_id' ) ) {
			$order_id = $order->id;
		} else {
			$order_id = $order->get_id();
		}

		if ( $order_id ) {

			return sprintf(
				// translators: Order number.
				_x( 'Refunded coupon for order %s', 'points log description', 'wordpoints-woocommerce' )
				, $order->get_order_number()
			);
		}
	}

	return _x( 'Refunded coupon for an order.', 'points log description', 'wordpoints-woocommerce' );

}
add_filter(
	'wordpoints_points_log-woocommerce_coupon_refund'
	, 'wordpoints_woocommerce_points_logs_coupon_refund'
	, 10
	, 6
);

// EOF
