<?php

/**
 * A test case for uninstalling the module.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * Test that the module unintalls itself properly.
 *
 * @since 1.0.0
 *
 * @group uninstall
 */
class WordPoints_WooCommerce_Uninstall_Test
	extends WordPoints_Module_Uninstall_UnitTestCase {

	//
	// Protected properties.
	//

	/**
	 * The full path to the main module file.
	 *
	 * @since 1.0.0
	 *
	 * @type string $module_file
	 */
	protected $module_file;

	/**
	 * The module's install function.
	 *
	 * @since 1.0.0
	 *
	 * @type callable $install_function
	 */
	protected $install_function = 'wordpoints_woocommerce_install';

	/**
	 * Whether the tests are being run with the module is network-activated.
	 *
	 * @since 1.0.0
	 *
	 * @type bool $network_wide
	 */
	protected $network_wide = false;

	//
	// Public methods.
	//

	/**
	 * Set up for the tests.
	 *
	 * @since 1.0.0
	 */
	public function setUp() {

		$this->module_file = WORDPOINTS_WC_TESTS_DIR . '/../../src/woocommerce.php';
		$this->simulation_file = WORDPOINTS_WC_TESTS_DIR . '/includes/usage-simulator.php';

		parent::setUp();
	}

	/**
	 * Tear down after the tests.
	 *
	 * @since 1.0.0
	 *
	public function tearDown() {

		// We've just deleted the tables, so this will have a DB error.
		remove_action( 'delete_blog', 'wordpoints_delete_points_logs_for_blog' );

		parent::tearDown();
	}

	/**
	 * Test installation and uninstallation.
	 *
	 * @since 1.0.0
	 */
	public function test_uninstall() {

		global $wpdb;

		/*
		 * Install.
		 */

		// Check the the basic module data option was added.
		if ( $this->network_wide ) {
			$wordpoints_data = get_site_option( 'wordpoints_data' );
		} else {
			$wordpoints_data = get_option( 'wordpoints_data' );
		}

		$this->assertArrayHasKey( 'woocommerce', $wordpoints_data['modules'] );
		$this->assertInternalType( 'array', $wordpoints_data['modules']['woocommerce'] );
		$this->assertArrayHasKey( 'version', $wordpoints_data['modules']['woocommerce'] );

		/*
		 * Uninstall.
		 */

		$this->uninstall();

		$this->assertNoUserMetaWithPrefix( 'wordpoints_woocommerce' );

		if ( is_multisite() ) {

			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );

			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id ) {

				switch_to_blog( $blog_id );

				$this->assertNoUserOptionsWithPrefix( 'wordpoints_woocommerce' );
				$this->assertNoOptionsWithPrefix( 'wordpoints_woocommerce' );
				$this->assertNoOptionsWithPrefix( 'wordpoints_hook-wordpoints_wc' );
				$this->assertNoOptionsWithPrefix( 'widget_wordpoints_woocommerce' );
				$this->assertNoCommentMetaWithPrefix( 'wordpoints_woocommerce' );
			}

			switch_to_blog( $original_blog_id );

			// See http://wordpress.stackexchange.com/a/89114/27757
			unset( $GLOBALS['_wp_switched_stack'] );
			$GLOBALS['switched'] = false;

		} else {

			$this->assertNoOptionsWithPrefix( 'wordpoints_woocommerce' );
			$this->assertNoOptionsWithPrefix( 'wordpoints_hook-wordpoints_wc' );
			$this->assertNoOptionsWithPrefix( 'widget_wordpoints_woocommerce' );
			$this->assertNoCommentMetaWithPrefix( 'wordpoints_woocommerce' );
		}

	} // function test_uninstall()
}

// EOF
