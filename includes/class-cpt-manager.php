<?php
/**
 * Post Type Manager
 *
 * Registers custom post types based on the settings.
 */

class SCF_CPT_Manager {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_types' ] );
	}

	/**
	 * Register Custom Post Types
	 */
	public function register_post_types() {
		$post_types = get_option( 'scf_cpts', [] );

		if ( empty( $post_types ) || ! is_array( $post_types ) ) {
			return;
		}

		foreach ( $post_types as $slug => $args ) {

			$labels = array(
				'name'               => _x( $args['plural'], 'post type general name', 'simple-cpt-fields' ),
				'singular_name'      => _x( $args['singular'], 'post type singular name', 'simple-cpt-fields' ),
				'menu_name'          => _x( $args['plural'], 'admin menu', 'simple-cpt-fields' ),
				'name_admin_bar'     => _x( $args['singular'], 'add new on admin bar', 'simple-cpt-fields' ),
				'add_new'            => _x( 'Add New', 'book', 'simple-cpt-fields' ),
				'add_new_item'       => __( 'Add New ' . $args['singular'], 'simple-cpt-fields' ),
				'new_item'           => __( 'New ' . $args['singular'], 'simple-cpt-fields' ),
				'edit_item'          => __( 'Edit ' . $args['singular'], 'simple-cpt-fields' ),
				'view_item'          => __( 'View ' . $args['singular'], 'simple-cpt-fields' ),
				'all_items'          => __( 'All ' . $args['plural'], 'simple-cpt-fields' ),
				'search_items'       => __( 'Search ' . $args['plural'], 'simple-cpt-fields' ),
				'parent_item_colon'  => __( 'Parent ' . $args['plural'] . ':', 'simple-cpt-fields' ),
				'not_found'          => __( 'No ' . strtolower( $args['plural'] ) . ' found.', 'simple-cpt-fields' ),
				'not_found_in_trash' => __( 'No ' . strtolower( $args['plural'] ) . ' found in Trash.', 'simple-cpt-fields' )
			);

			$register_args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'simple-cpt-fields' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $slug ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'show_in_rest'       => true, // Enable Block Editor
			);

			register_post_type( $slug, $register_args );
		}

		// Ensure Elementor is enabled for these CPTs
		if ( class_exists( 'SCF_Elementor_Integration' ) ) {
			SCF_Elementor_Integration::enable_elementor_support();
		}
	}
}
