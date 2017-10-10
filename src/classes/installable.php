<?php

/**
 * Installable class.
 *
 * @package WordPoints_WooCommerce
 * @since   1.2.1
 */

/**
 * Installable object for the extension.
 *
 * @since 1.2.1
 */
class WordPoints_WooCommerce_Installable extends WordPoints_Installable_Extension {

	/**
	 * @since 1.2.1
	 */
	public function get_update_routine_factories() {

		$factories = parent::get_update_routine_factories();

		// v1.2.0.
		$factories[] = new WordPoints_Updater_Factory(
			'1.2.0'
			, array(
				'local' => array( 'WordPoints_WooCommerce_Updater_1_2_0_Gateway' ),
			)
		);

		return $factories;
	}
}

// EOF
