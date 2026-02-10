<?php
/**
 * Elementor Widget: SCF Field Display
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SCF_Field_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scf-field-widget';
	}

	public function get_title() {
		return __( 'SCF Field Display', 'simple-cpt-fields' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'simple-cpt-fields' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

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
				'label'   => __( 'Select Field', 'simple-cpt-fields' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => '', 
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$key = $this->get_settings_for_display( 'key' );
		if ( empty( $key ) ) {
			return;
		}

		$value = get_post_meta( get_the_ID(), $key, true );
		
		if ( ! empty( $value ) ) {
			echo '<div class="scf-field-value">' . wp_kses_post( $value ) . '</div>';
		}
	}
}
