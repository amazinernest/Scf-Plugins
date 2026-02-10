<?php
/**
 * Shortcode Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Shortcode {

	public function __construct() {
		add_shortcode( 'scf_field', [ $this, 'render_shortcode' ] );
	}

	public function render_shortcode( $atts ) {
		$atts = shortcode_atts( [
			'key' => '',
			'post_id' => '',
		], $atts, 'scf_field' );

		if ( empty( $atts['key'] ) ) {
			return '';
		}

		$post_id = ! empty( $atts['post_id'] ) ? $atts['post_id'] : get_the_ID();
		
		if ( ! $post_id ) {
			return '';
		}

		$value = get_post_meta( $post_id, $atts['key'], true );

		return wp_kses_post( $value );
	}
}
