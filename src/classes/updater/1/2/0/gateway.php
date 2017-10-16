<?php

/**
 * Gateway 1.2.0 updater class.
 *
 * @package WordPoints_WooCommerce
 * @since   1.2.1
 */

/**
 * Updates the gateway settings for 1.2.0.
 *
 * @since 1.2.1
 */
class WordPoints_WooCommerce_Updater_1_2_0_Gateway implements WordPoints_RoutineI {

	/**
	 * @since 1.2.1
	 */
	public function run() {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();

		$settings = $gateway->settings;

		if ( ! isset( $settings['points_type'], $settings['conversion_rate'] ) ) {
			return;
		}

		$settings[ "conversion_rate-{$settings['points_type']}" ] = $settings['conversion_rate'];

		unset( $settings['points_type'], $settings['conversion_rate'] );

		update_option( $gateway->get_option_key(), $settings );
	}
}

// EOF
