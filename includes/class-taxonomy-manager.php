<?php
/**
 * Taxonomy Manager
 *
 * Registers custom taxonomies.
 */

class SCF_Taxonomy_Manager {

	public function __construct() {
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	public function register_taxonomies() {
		$taxonomies = get_option( 'scf_taxonomies', [] );

		if ( empty( $taxonomies ) || ! is_array( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $slug => $args ) {
			// Ensure attached posts types is an array
			$post_types = isset( $args['post_types'] ) ? $args['post_types'] : [ 'post' ];
			if ( ! is_array( $post_types ) ) {
				$post_types = [ $post_types ];
			}

			$labels = array(
				'name'                       => _x( $args['plural'], 'taxonomy general name', 'simple-cpt-fields' ),
				'singular_name'              => _x( $args['singular'], 'taxonomy singular name', 'simple-cpt-fields' ),
				'search_items'               => __( 'Search ' . $args['plural'], 'simple-cpt-fields' ),
				'popular_items'              => __( 'Popular ' . $args['plural'], 'simple-cpt-fields' ),
				'all_items'                  => __( 'All ' . $args['plural'], 'simple-cpt-fields' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit ' . $args['singular'], 'simple-cpt-fields' ),
				'update_item'                => __( 'Update ' . $args['singular'], 'simple-cpt-fields' ),
				'add_new_item'               => __( 'Add New ' . $args['singular'], 'simple-cpt-fields' ),
				'new_item_name'              => __( 'New ' . $args['singular'] . ' Name', 'simple-cpt-fields' ),
				'separate_items_with_commas' => __( 'Separate ' . strtolower( $args['plural'] ) . ' with commas', 'simple-cpt-fields' ),
				'add_or_remove_items'        => __( 'Add or remove ' . strtolower( $args['plural'] ), 'simple-cpt-fields' ),
				'choose_from_most_used'      => __( 'Choose from the most used ' . strtolower( $args['plural'] ), 'simple-cpt-fields' ),
				'not_found'                  => __( 'No ' . strtolower( $args['plural'] ) . ' found.', 'simple-cpt-fields' ),
				'menu_name'                  => __( $args['plural'], 'simple-cpt-fields' ),
			);

			$register_args = array(
				'hierarchical'          => true, // Like categories
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => $slug ),
				'show_in_rest'          => true,
			);

			register_taxonomy( $slug, $post_types, $register_args );
		}
	}
}
