<?php

/**
 * Test case for the 1.2.0 update.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.2.0
 */

/**
 * Tests updating to 1.2.0.
 *
 * @since 1.2.0
 *
 * @covers WordPoints_WooCommerce_Un_Installer::update_single_to_1_2_0()
 * @covers WordPoints_WooCommerce_Un_Installer::update_site_to_1_2_0()
 * @covers WordPoints_WooCommerce_Un_Installer::update_points_gateway_settings_to_1_2_0()
 */
class WordPoints_WooCommerce_Update_1_2_0_Test extends WordPoints_PHPUnit_TestCase {

	/**
	 * @since 1.2.0
	 */
	protected $previous_version = '1.1.0';

	/**
	 * @since 1.2.0
	 */
	protected $wordpoints_module = 'woocommerce';

	/**
	 * Tests updating to 1.2.0.
	 *
	 * @since 1.2.0
	 */
	public function test_update_points_gateway_settings() {

		$this->create_points_type();

		$gateway = new WordPoints_WooCommerce_Gateway_Points();
		$gateway->init_settings();

		$settings = $gateway->settings;
		$settings['points_type'] = 'points';
		$settings['conversion_rate'] = '100';

		update_option( $gateway->get_option_key(), $settings );

		$this->update_module();

		$gateway = new WordPoints_WooCommerce_Gateway_Points();

		$this->assertArrayHasKey( 'conversion_rate-points', $gateway->settings );
		$this->assertSame( '100', $gateway->settings['conversion_rate-points'] );

		$this->assertArrayNotHasKey( 'points_type', $gateway->settings );
		$this->assertArrayNotHasKey( 'conversion_rate', $gateway->settings );
	}
}

// EOF
