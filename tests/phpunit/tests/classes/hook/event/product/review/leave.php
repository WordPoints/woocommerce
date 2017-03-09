<?php

/**
 * Test case for the Reviewing a Product hook event.
 *
 * @package WordPoints_WooCommerce\PHPUnit\Tests
 * @since 1.1.0
 */

/**
 * Tests the Reviewing a Product hook event.
 *
 * @since 1.1.0
 *
 * @covers WordPoints_WooCommerce_Hook_Event_Product_Review_Leave
 */
class WordPoints_WooCommerce_Hook_Event_Product_Review_Leave_Test
	extends WordPoints_PHPUnit_TestCase_Hook_Event_Dynamic {

	/**
	 * @since 1.1.0
	 */
	protected $event_class = 'WordPoints_WooCommerce_Hook_Event_Product_Review_Leave';

	/**
	 * @since 1.1.0
	 */
	protected $event_slug = 'comment_leave\\';

	/**
	 * @since 1.1.0
	 */
	protected $dynamic_slug = 'product';

	/**
	 * @since 1.1.0
	 */
	protected $expected_targets = array(
		array( 'comment\\', 'author', 'user' ),
		array( 'comment\\', 'parent', 'comment\\', 'author', 'user' ),
		array( 'comment\\', 'parent', 'comment\\', 'post\\', 'post\\', 'author', 'user' ),
		array( 'comment\\', 'post\\', 'post\\', 'author', 'user' ),
	);

	/**
	 * @since 1.1.0
	 */
	protected function fire_event( $arg, $reactor_slug ) {

		$comment_id = $this->factory->comment->create(
			array(
				'comment_approved' => 0,
				'user_id'          => $this->factory->user->create(),
				'comment_parent'  => $this->factory->comment->create(
					array(
						'user_id'         => $this->factory->user->create(),
						'comment_post_ID' => $this->factory->post->create(
							array(
								'post_author' => $this->factory->user->create(),
								'post_type'   => $this->dynamic_slug,
							)
						),
					)
				),
				'comment_post_ID'  => $this->factory->post->create(
					array(
						'post_author' => $this->factory->user->create(),
						'post_type'   => $this->dynamic_slug,
						'post_parent' => $this->factory->post->create(
							array(
								'post_author' => $this->factory->user->create(),
								'post_type'   => $this->dynamic_slug,
							)
						),
					)
				),
			)
		);

		wp_update_comment(
			array( 'comment_ID' => $comment_id, 'comment_approved' => 1 )
		);

		return array(
			$this->factory->comment->create(
				array(
					'user_id'         => $this->factory->user->create(),
					'comment_parent'  => $this->factory->comment->create(
						array(
							'user_id'         => $this->factory->user->create(),
							'comment_post_ID' => $this->factory->post->create(
								array(
									'post_author' => $this->factory->user->create(),
									'post_type'   => $this->dynamic_slug,
								)
							),
						)
					),
					'comment_post_ID' => $this->factory->post->create(
						array(
							'post_author' => $this->factory->user->create(),
							'post_type'   => $this->dynamic_slug,
							'post_parent' => $this->factory->post->create(
								array(
									'post_author' => $this->factory->user->create(),
									'post_type'   => $this->dynamic_slug,
								)
							),
						)
					),
				)
			),
			$comment_id,
		);
	}

	/**
	 * @since 1.1.0
	 */
	protected function reverse_event( $arg_id, $index ) {

		switch ( $index ) {

			case 0:
				wp_delete_comment( $arg_id, true );
			break;

			case 1:
				wp_update_comment(
					array( 'comment_ID' => $arg_id, 'comment_approved' => 0 )
				);
			break;
		}
	}
}

// EOF
