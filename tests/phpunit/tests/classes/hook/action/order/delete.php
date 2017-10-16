<?php

/**
 * Test case for WordPoints_WooCommerce_Hook_Action_Order_Delete.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests WordPoints_WooCommerce_Hook_Action_Order_Delete.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Hook_Action_Order_Delete
 */
class WordPoints_WooCommerce_Hook_Action_Order_Delete_Test
	extends WordPoints_PHPUnit_TestCase_Hooks {

	/**
	 * Test checking if an action should fire.
	 *
	 * @since 1.1.0
	 */
	public function test_should_fire_correct_post_type() {

		$order = WC_Helper_Order::create_order();

		$action = new WordPoints_WooCommerce_Hook_Action_Order_Delete(
			'test'
			, array( $order->get_id() )
			, array( 'arg_index' => array( 'woocommerce_order' => 0 ) )
		);

		$this->assertTrue( $action->should_fire() );
	}

	/**
	 * Test checking if an action should fire when the ID is for the wrong post type.
	 *
	 * @since 1.1.0
	 */
	public function test_should_fire_incorrect_post_type() {

		$post_id = $this->factory->post->create();

		$action = new WordPoints_WooCommerce_Hook_Action_Order_Delete(
			'test'
			, array( $post_id )
			, array( 'arg_index' => array( 'woocommerce_order' => 0 ) )
		);

		$this->assertFalse( $action->should_fire() );
	}

	/**
	 * Test checking if the action should fire when there is no post.
	 *
	 * @since 1.1.0
	 */
	public function test_should_fire_no_post() {

		$action = new WordPoints_WooCommerce_Hook_Action_Order_Delete(
			'test'
			, array( 'a' )
		);

		$this->assertFalse( $action->should_fire() );
	}

	/**
	 * Test checking if an action should fire when the requirements are met.
	 *
	 * @since 1.1.0
	 */
	public function test_should_fire_other_requirements_met() {

		$order = WC_Helper_Order::create_order();

		$action = new WordPoints_WooCommerce_Hook_Action_Order_Delete(
			'test'
			, array( $order->get_id(), 'a' )
			, array(
				'arg_index'    => array( 'woocommerce_order' => 0 ),
				'requirements' => array( 1 => 'a' ),
			)
		);

		$this->assertTrue( $action->should_fire() );
	}

	/**
	 * Test checking if an action should fire when the requirements aren't met.
	 *
	 * @since 1.1.0
	 */
	public function test_should_fire_other_requirements_not_met() {

		$order = WC_Helper_Order::create_order();

		$action = new WordPoints_WooCommerce_Hook_Action_Order_Delete(
			'test'
			, array( $order->get_id(), 'b' )
			, array(
				'arg_index'    => array( 'woocommerce_order' => 0 ),
				'requirements' => array( 1 => 'a' ),
			)
		);

		$this->assertFalse( $action->should_fire() );
	}
}

// EOF
