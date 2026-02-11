<?php
/**
 * Elementor Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Elementor_Integration {

	public static function init() {
		add_action( 'elementor/dynamic_tags/register_tags', [ __CLASS__, 'register_dynamic_tags' ] );
		add_action( 'elementor/widgets/register', [ __CLASS__, 'register_widgets' ] );
	}

	public static function register_dynamic_tags( $dynamic_tags ) {
		// Load our tag classes
		require_once SCF_PATH . 'includes/class-scf-dynamic-tag.php';
		require_once SCF_PATH . 'includes/class-scf-image-tag.php';
		
		// Register the tags
		$dynamic_tags->register_tag( 'SCF_Dynamic_Tag' );
		$dynamic_tags->register_tag( 'SCF_Image_Tag' );
	}

	public static function register_widgets( $widgets_manager ) {
		require_once SCF_PATH . 'includes/class-scf-widget.php';
		$widgets_manager->register( new SCF_Field_Widget() );
	}

	/**
	 * Automatically enable Elementor for our registered CPTs
	 */
	public static function enable_elementor_support() {
		$cpts = get_option( 'scf_cpts', [] );
		if ( empty( $cpts ) ) {
			return;
		}

		$cpt_slugs = array_keys( $cpts );
		$supported_types = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );

		if ( ! is_array( $supported_types ) ) {
			$supported_types = [ 'page', 'post' ];
		}

		$needs_update = false;
		foreach ( $cpt_slugs as $slug ) {
			if ( ! in_array( $slug, $supported_types ) ) {
				$supported_types[] = $slug;
				$needs_update = true;
			}
		}

		if ( $needs_update ) {
			update_option( 'elementor_cpt_support', $supported_types );
		}
	}
}
