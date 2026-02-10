<?php
/**
 * Elementor Dynamic Tag: SCF Image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'scf-image-tag';
	}

	public function get_title() {
		return __( 'SCF Image', 'simple-cpt-fields' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	protected function _register_controls() {
		$fields = get_option( 'scf_fields', [] );
		$options = [];

		if ( ! empty( $fields ) && is_array( $fields ) ) {
			foreach ( $fields as $key => $field ) {
				if ( isset( $field['type'] ) && $field['type'] === 'image' ) {
					$label = isset( $field['label'] ) ? $field['label'] : $key;
					$options[ $key ] = $label;
				}
			}
		}

		$this->add_control(
			'key',
			[
				'label'   => __( 'Select Image Field', 'simple-cpt-fields' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => '', 
			]
		);
	}

	public function get_value( array $options = [] ) {
		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return [];
		}

		$image_id = get_post_meta( get_the_ID(), $key, true );
		if ( ! $image_id ) {
			return [];
		}

		$src = wp_get_attachment_image_src( $image_id, 'full' );
		if ( ! $src ) {
			return [];
		}
		
		return [
			'id' => $image_id,
			'url' => $src[0],
		];
	}
}
