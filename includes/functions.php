<?php
/**
 * Plugin Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get field value helper function
 *
 * @param string $key
 * @param int $post_id
 * @return mixed
 */
function scf_get_field( $key, $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, $key, true );
}
