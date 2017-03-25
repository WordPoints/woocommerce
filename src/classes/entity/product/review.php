<?php

/**
 * Product review entity class.
 *
 * @package WordPoints_WooCommerce\Entities
 * @since 1.1.0
 */

/**
 * Represents a Product Review as an entity.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Product_Review
	extends WordPoints_Entity_Comment {

	/**
	 * @since 1.1.0
	 */
	public function get_title() {
		return __( 'Review', 'wordpoints-woocommerce' );
	}
}

// EOF
