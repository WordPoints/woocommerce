<?php

/**
 * Simulate module usage.
 *
 * Used by the install/uninstall tests to provide a more full test of whether
 * everything is properly deleted on uninstall. Uninstalling a fresh install is
 * important, but cleaning up the little things is also important. Doing this little
 * dance here helps us to make sure we're doing that.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * Simulate module usage.
 *
 * Only available from the module uninstall usage simulator.
 *
 * @since 1.0.0
 */
function wordpointswctests_simulate_usage() {

	// Do nothing right now.
}

// Include the test functions so we can simulate adding points hooks and widgets.
//require_once dirname( __FILE__ ) . '/functions.php';

if ( is_multisite() ) {

	$blog_ids = get_sites( array( 'fields' => 'ids', 'number' => 0 ) );

	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $_blog_id ) {

		// We use $_blog_id instead of $blog_id, because this is in the global scope.
		switch_to_blog( $_blog_id );

		wordpointswctests_simulate_usage();
	}

	switch_to_blog( $original_blog_id );

	// See https://wordpress.stackexchange.com/a/89114/27757
	unset( $GLOBALS['_wp_switched_stack'] );
	$GLOBALS['switched'] = false;

} else {

	wordpointswctests_simulate_usage();
}

// EOF
