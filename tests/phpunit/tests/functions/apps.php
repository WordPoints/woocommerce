<?php

/**
 * Test case for the apps functions.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests the apps functions.
 *
 * @since 1.1.0
 */
class WordPoints_WooCommerce_Apps_Functions_Test
	extends WordPoints_PHPUnit_TestCase_Hooks {

	//
	// Entities.
	//

	/**
	 * Test the entity registration function.
	 *
	 * @since 1.1.0
	 *
	 * @covers ::wordpoints_woocommerce_entities_init
	 */
	public function test_entities() {

		$this->mock_apps();

		$entities = wordpoints_entities();

		wordpoints_woocommerce_entities_init( $entities );

		$children = $entities->get_sub_app( 'children' );

		$this->assertTrue( $entities->is_registered( 'woocommerce_order' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'cart_tax' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'created_via' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'customer' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'customer_note' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'date_completed' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'date_created' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'date_paid' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'discount_total' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'grand_total' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'shipping_tax' ) );
		$this->assertTrue( $children->is_registered( 'woocommerce_order', 'shipping_total' ) );
	}

	/**
	 * Test the "know" entity restriction registration function.
	 *
	 * @since 1.1.0
	 *
	 * @covers ::wordpoints_woocommerce_entity_restrictions_know_init
	 */
	public function test_entity_restrictions_know() {

		$restrictions = new WordPoints_Class_Registry_Deep_Multilevel();

		wordpoints_woocommerce_entity_restrictions_know_init( $restrictions );

		$this->assertTrue( $restrictions->is_registered( 'nonpublic', array( 'woocommerce_order' ) ) );
	}

	//
	// Hooks.
	//

	/**
	 * Test the action registration function.
	 *
	 * @since 1.1.0
	 *
	 * @covers ::wordpoints_woocommerce_hook_actions_init
	 */
	public function test_actions() {

		$this->mock_apps();

		$actions = wordpoints_hooks()->get_sub_app( 'actions' );

		wordpoints_woocommerce_hook_actions_init( $actions );

		$this->assertTrue( $actions->is_registered( 'woocommerce_order_complete' ) );
		$this->assertTrue( $actions->is_registered( 'woocommerce_order_decomplete' ) );
	}

	/**
	 * Test the events registration function.
	 *
	 * @since 1.1.0
	 *
	 * @covers ::wordpoints_woocommerce_hook_events_init
	 */
	public function test_events() {

		$this->mock_apps();

		$events = wordpoints_hooks()->get_sub_app( 'events' );

		wordpoints_woocommerce_hook_events_init( $events );

		$this->assertEventRegistered( 'post_publish\\product' );
		$this->assertEventRegistered( 'comment_leave\\product' );
		$this->assertEventRegistered( 'woocommerce_order_complete', 'woocommerce_order' );
	}
}

// EOF
