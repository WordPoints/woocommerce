<?php

/**
 * Class to un/install the module.
 *
 * @package WordPoints_WooCommrce
 * @since 1.0.2
 */

/**
 * Un/install the module.
 *
 * @since 1.0.2
 */
class WordPoints_WooCommerce_Un_Installer extends WordPoints_Un_Installer_Base {

	/**
	 * @since 1.0.2
	 */
	protected $type = 'module';

	/**
	 * @since 1.2.0
	 */
	protected $updates = array(
		'1.2.0' => array( 'single' => true, 'site' => true ),
	);

	/**
	 * Updates a site on the network to 1.2.0.
	 *
	 * @since 1.2.0
	 */
	protected function update_site_to_1_2_0() {
		$this->update_points_gateway_settings_to_1_2_0();
	}

	/**
	 * Updates a single site install to 1.2.0.
	 *
	 * @since 1.2.0
	 */
	protected function update_single_to_1_2_0() {
		$this->update_points_gateway_settings_to_1_2_0();
	}

	/**
	 * Updates the points gateway settings for 1.2.0.
	 *
	 * @since 1.2.0
	 */
	protected function update_points_gateway_settings_to_1_2_0() {

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->init_settings();

		$settings = $gateway->settings;

		if ( ! isset( $settings['points_type'], $settings['conversion_rate'] ) ) {
			return;
		}

		$settings[ "conversion_rate-{$settings['points_type']}" ] = $settings['conversion_rate'];

		unset( $settings['points_type'], $settings['conversion_rate'] );

		update_option( $gateway->get_option_key(), $settings );
	}
}

return 'WordPoints_WooCommerce_Un_Installer';

// EOF
