<?php

/**
 * Test case for the Product Publish hook event.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests the Product Publish hook event.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Hook_Event_Product_Publish
 */
class WordPoints_WooCommerce_Hook_Event_Product_Publish_Test
	extends WordPoints_PHPUnit_TestCase_Hook_Event_Dynamic {

	/**
	 * @since 1.1.0
	 */
	protected $event_class = 'WordPoints_WooCommerce_Hook_Event_Product_Publish';

	/**
	 * @since 1.1.0
	 */
	protected $event_slug = 'post_publish\\';

	/**
	 * @since 1.1.0
	 */
	protected $expected_targets = array(
		array( 'post\\', 'author', 'user' ),
	);

	/**
	 * @since 1.1.0
	 */
	protected $dynamic_slug = 'product';

	/**
	 * @since 1.1.0
	 */
	protected function fire_event( $arg, $reactor_slug ) {

		$post_id = $this->create_product( array( 'post_status' => 'draft' ) );

		$this->factory->post->update_object(
			$post_id
			, array( 'post_status' => 'publish' )
		);

		// Update the post again. See #550.
		$this->factory->post->update_object(
			$post_id
			, array( 'post_status' => 'publish' )
		);

		return array(
			$post_id,
			$this->create_product(),
		);
	}

	/**
	 * @since 1.1.0
	 */
	protected function reverse_event( $arg_id, $index ) {

		switch ( $index ) {

			case 0:
				wp_delete_post( $arg_id, true );
			break;

			case 1:
				wp_update_post(
					array( 'ID' => $arg_id, 'post_status' => 'draft' )
				);
			break;
		}
	}

	/**
	 * Create a product.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Args for the product.
	 *
	 * @return int The product ID.
	 */
	protected function create_product( array $args = array() ) {

		$user_id = $this->factory->user->create();

		wp_set_current_user( $user_id );

		$product = new WC_Product_Simple();
		$product->set_name( 'Test Product' );
		$product->set_status( isset( $args['post_status'] ) ? $args['post_status'] : 'publish' );
		$product->set_short_description( 'Test product short description.' );
		$product->set_description( 'Test product long description.' );

		// Attempts to create the new product.
		$product->save();

		return $product->get_id();
	}
}

// EOF
