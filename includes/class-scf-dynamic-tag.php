<?php
/**
 * Elementor Dynamic Tag: SCF Field
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Dynamic_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'scf-field';
	}

	public function get_title() {
		return __( 'SCF Field', 'simple-cpt-fields' );
	}

	public function get_group() {
		return 'post'; // Group under 'Post' tags
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function _register_controls() {
		$fields = get_option( 'scf_fields', [] );
		$options = [];

		if ( ! empty( $fields ) && is_array( $fields ) ) {
			foreach ( $fields as $key => $field ) {
				$label = isset( $field['label'] ) ? $field['label'] : $key;
				$options[ $key ] = $label;
			}
		}

		$this->add_control(
			'key',
			[
				'label'   => __( 'Key', 'simple-cpt-fields' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => '', 
			]
		);
	}

	public function render() {
		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return;
		}

		$value = get_post_meta( get_the_ID(), $key, true );
		
		if ( is_array( $value ) ) {
			echo implode( ', ', $value );
		} else {
			// If it is an image ID, maybe we want to show the URL?
			// For now, let's just output the raw value. 
			// If the user uses this tag in an Image Widget -> Dynamic Tag -> Site Logo (example), it expects specific data.
			// But since we registered this as a TEXT_CATEGORY tag, it is mostly used in Text widgets.
			echo wp_kses_post( $value );
		}
	}
}
