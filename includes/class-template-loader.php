<?php
/**
 * Template Loader
 *
 * Checks if a specific template exists in the theme. If not, it falls back
 * to the plugin's default template for all registered Custom Post Types.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Template_Loader {

	public function __construct() {
		add_filter( 'template_include', [ $this, 'template_loader' ] );
	}

	public function template_loader( $template ) {
		// Only check if we are on a single post page
		if ( is_singular() ) {
			
			// Get the current post type
			$post_type = get_post_type();
			
			// Get our registered post types
			$cpts = get_option( 'scf_cpts', [] );
			
			// Check if the current post type is one of ours
			if ( array_key_exists( $post_type, $cpts ) ) {
				
				// Check if the theme has a specific template for this post type
				// e.g. single-movie.php
				$theme_template = locate_template( [ "single-{$post_type}.php" ] );
				
				// If a theme template is NOT found, use our plugin template
				if ( ! $theme_template ) {
					// Check if a generic 'single-scf.php' exists in the theme (optional override)
					$generic_override = locate_template( [ 'single-scf.php' ] );
					
					if ( $generic_override ) {
						return $generic_override;
					} else {
						// Return plugin default template
						return SCF_PATH . 'templates/single-scf.php';
					}
				}
			}
		}

		return $template;
	}
}
