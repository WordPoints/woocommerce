<?php

/**
 * A test case parent for points tests.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * Parent points test case.
 *
 * @since 1.0.0
 */
class WordPoints_WooCommerce_Points_UnitTestCase extends WordPoints_PHPUnit_TestCase_Points {

	/**
	 * Add the custom factories on setup.
	 *
	 * @since 1.0.0
	 */
	public function setUp() {

		parent::setUp();

		$this->factory->order   = new WordPoints_WooCommerce_UnitTest_Factory_For_Order;
		$this->factory->product = new WordPoints_WooCommerce_UnitTest_Factory_For_Product;
	}
}

// EOF
