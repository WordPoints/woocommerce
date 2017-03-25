<?php

/**
 * Nonpublic order entity restriction class.
 *
 * @package WordPoints_WooCommerce
 * @since   1.1.0
 */

/**
 * Restriction rule for orders.
 *
 * All orders are nonpublic by default.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic
	implements WordPoints_Entity_RestrictionI {

	/**
	 * The ID of the order this restriction relates to.
	 *
	 * @since 1.1.0
	 *
	 * @var int
	 */
	protected $order_id;

	/**
	 * @since 1.1.0
	 */
	public function __construct( $entity_id, array $hierarchy ) {
		$this->order_id = $entity_id;
	}

	/**
	 * @since 1.1.0
	 */
	public function user_can( $user_id ) {
		return user_can( $user_id, 'view_order', $this->order_id );
	}

	/**
	 * @since 1.1.0
	 */
	public function applies() {
		return true;
	}
}

// EOF
