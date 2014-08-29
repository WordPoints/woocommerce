<?php

/**
 * A factory for WooCommerce products.
 *
 * @package WordPoints_WooCommerce\Tests
 * @since 1.0.0
 */

/**
 * WooCommerce product factory.
 *
 * @since 1.0.0
 */
class WordPoints_WooCommerce_UnitTest_Factory_For_Product extends WP_UnitTest_Factory_For_Post {

	/**
	 * Custom fields for orders.
	 *
	 * @since 1.0.0
	 *
	 * @type array $custom_fields
	 */
	public $custom_fields = array( '_sku', '_price', '_virtual', '_customer_user', '_downloadable' );

	/**
	 * Set up the default generation definitions on constructions.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_UnitTest_Factory $factory Optional factory.
	 */
	public function __construct( $factory = null ) {

		parent::__construct( $factory );

		$this->default_generation_definitions = array(
			'post_status'   => 'publish',
			'post_title'    => new WP_UnitTest_Generator_Sequence( 'Product title %s' ),
			'post_content'  => new WP_UnitTest_Generator_Sequence( 'Product content %s' ),
			'post_excerpt'  => new WP_UnitTest_Generator_Sequence( 'Product excerpt %s' ),
			'post_type'     => 'product',
			'_sku'          => new WP_UnitTest_Generator_Sequence( 'SKU%s' ),
			'_price'        => '25',
			'_virtual'      => 'no',
			'_downloadable' => 'no',
		);
	}

	/**
	 * Create a product.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The product data.
	 *
	 * @return int|WP_Error The product ID, or a WP_Error or 0 on error.
	 */
	public function create_object( $args ) {

		$post_id = wp_insert_post( $args );

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return $post_id;
		}

		foreach ( $this->custom_fields as $field ) {
			if ( isset( $args[ $field ] ) ) {
				add_post_meta( $post_id, $field, $args[ $field ] );
			}
		}

		return $post_id;
	}

	/**
	 * Update the product.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $post_id The ID of the product.
	 * @param array $fields  The product fields.
	 *
	 * @return int|WP_Error The ID of the post, or a WP_Error on failure.
	 */
	public function update_object( $post_id, $fields ) {

		$fields['ID'] = $post_id;

		$result = wp_update_post( $fields );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		foreach ( $this->custom_field as $field ) {
			if ( isset( $args[ $field ] ) ) {
				update_post_meta( $post_id, $field, $args[ $field ] );
			}
		}

		return $result;
	}
}

// EOF
