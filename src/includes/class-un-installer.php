<?php

/**
 * Class to un/install the extension.
 *
 * @package WordPoints_WooCommrce
 * @since 1.0.2
 * @deprecated 1.2.1
 */

_deprecated_file( __FILE__, '1.2.1' );

/**
 * Un/install the extension.
 *
 * @since 1.0.2
 * @deprecated 1.2.1
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
		$routine = new WordPoints_WooCommerce_Updater_1_2_0_Gateway();
		$routine->run();
	}
}

return 'WordPoints_WooCommerce_Un_Installer';

// EOF
