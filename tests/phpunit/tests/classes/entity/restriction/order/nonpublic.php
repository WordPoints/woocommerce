<?php

/**
 * Test case for WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic
 */
class WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic_Test
	extends WordPoints_PHPUnit_TestCase {

	/**
	 * Test that it applies when the order is not public (which is always).
	 *
	 * @since 1.1.0
	 */
	public function test_applies_not_public() {

		$restriction = new WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic(
			WC_Helper_Order::create_order()->get_id()
			, array( 'test' )
		);

		$this->assertTrue( $restriction->applies() );
	}

	/**
	 * Test that it applies when the order is nonexistent.
	 *
	 * @since 1.1.0
	 */
	public function test_applies_nonexistent() {

		$restriction = new WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic(
			0
			, array( 'test' )
		);

		$this->assertTrue( $restriction->applies() );
	}

	/**
	 * Test that the user can't when the order is not public (which is always).
	 *
	 * @since 1.1.0
	 */
	public function test_user_can_not_public() {

		$restriction = new WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic(
			WC_Helper_Order::create_order()->get_id()
			, array( 'test' )
		);

		$this->assertFalse(
			$restriction->user_can( $this->factory->user->create() )
		);
	}

	/**
	 * Test that the user can't when the order is nonexistent.
	 *
	 * @since 1.1.0
	 */
	public function test_user_can_nonexistent() {

		$restriction = new WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic(
			0
			, array( 'test' )
		);

		$this->assertFalse(
			$restriction->user_can( $this->factory->user->create() )
		);
	}

	/**
	 * Test that the user can when the order is not public if they are the customer.
	 *
	 * @since 1.1.0
	 */
	public function test_user_can_not_public_is_customer() {

		$user_id = $this->factory->user->create();

		$restriction = new WordPoints_WooCommerce_Entity_Restriction_Order_Nonpublic(
			WC_Helper_Order::create_order( $user_id )->get_id()
			, array( 'test' )
		);

		$this->assertTrue( $restriction->user_can( $user_id ) );
	}
}

// EOF
