<?php
/**
 * Field Link Manager
 *
 * Adds meta boxes for custom fields and handles saving.
 */

class SCF_Field_Manager {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_meta_box_data' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'scf-admin-js', SCF_URL . 'assets/js/admin.js', [ 'jquery' ], '1.0.0', true );
	}

	/**
	 * Add Meta Boxes
	 */
	public function add_meta_boxes() {
		$fields = get_option( 'scf_fields', [] );

		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return;
		}

		// Group fields by post type so we can create one metabox for all fields on a post type
		$fields_by_post_type = [];

		foreach ( $fields as $key => $field ) {
			$post_type = isset( $field['post_type'] ) ? $field['post_type'] : 'post';
			if ( ! isset( $fields_by_post_type[ $post_type ] ) ) {
				$fields_by_post_type[ $post_type ] = [];
			}
			$fields_by_post_type[ $post_type ][ $key ] = $field;
		}

		foreach ( $fields_by_post_type as $post_type => $grouped_fields ) {
			add_meta_box(
				'scf_custom_fields_metabox',
				__( 'Custom Fields', 'simple-cpt-fields' ),
				[ $this, 'render_meta_box' ],
				$post_type,
				'normal',
				'high',
				[ 'fields' => $grouped_fields ]
			);
		}
	}

	/**
	 * Render Meta Box Content
	 *
	 * @param WP_Post $post
	 * @param array $callback_args
	 */
	public function render_meta_box( $post, $callback_args ) {
		wp_nonce_field( 'scf_save_meta_box_data', 'scf_meta_box_nonce' );

		$fields = $callback_args['args']['fields'];

		echo '<div class="scf-meta-box-container">';
		
		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			$label = isset( $field['label'] ) ? $field['label'] : $key;
			$type = isset( $field['type'] ) ? $field['type'] : 'text';
			$options_str = isset( $field['options'] ) ? $field['options'] : '';
			
			// Parse options
			$options = [];
			if ( ! empty( $options_str ) ) {
				$lines = explode( "\n", $options_str );
				foreach ( $lines as $line ) {
					$parts = explode( ':', $line );
					$opt_val = trim( $parts[0] );
					$opt_label = isset( $parts[1] ) ? trim( $parts[1] ) : $opt_val;
					if ( ! empty( $opt_val ) ) {
						$options[ $opt_val ] = $opt_label;
					}
				}
			}

			echo '<p class="scf-field-row">';
			echo '<label for="scf_field_' . esc_attr( $key ) . '" style="display:block; font-weight:bold; margin-bottom:5px;">' . esc_html( $label ) . '</label>';
			
			switch ( $type ) {
				case 'textarea':
					echo '<textarea id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" style="width:100%;" rows="5">' . esc_textarea( $value ) . '</textarea>';
					break;
				case 'wysiwyg':
					wp_editor( $value, 'scf_field_' . $key, [ 
						'textarea_name' => $key,
						'textarea_rows' => 10,
						'media_buttons' => true,
						'teeny' => true
					] );
					break;
				case 'number':
					echo '<input type="number" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;" />';
					break;
				case 'email':
					echo '<input type="email" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;" />';
					break;
				case 'url':
					echo '<input type="url" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;" />';
					break;
				case 'date':
					echo '<input type="date" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;" />';
					break;
				case 'color':
					echo '<input type="color" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="height:40px;" />';
					break;
				case 'image':
					$image_url = '';
					if ( $value ) {
						$image_url = wp_get_attachment_image_url( $value, 'medium' );
					}
					echo '<div class="scf-image-uploader">';
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="scf-image-id" />';
					echo '<div class="scf-image-preview" style="margin-bottom:10px;">';
					if ( $image_url ) {
						echo '<img src="' . esc_url( $image_url ) . '" style="max-width:150px; height:auto;" />';
					}
					echo '</div>';
					echo '<button type="button" class="button scf-upload-image">Select Image</button>';
					echo '<button type="button" class="button scf-remove-image" style="' . ( $value ? '' : 'display:none;' ) . '">Remove Image</button>';
					echo '</div>';
					break;
				case 'file':
					$file_url = '';
					if ( $value ) {
						$file_url = wp_get_attachment_url( $value );
					}
					echo '<div class="scf-file-uploader">';
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="scf-file-id" />';
					echo '<div class="scf-file-preview" style="margin-bottom:10px;">';
					if ( $file_url ) {
						echo '<a href="' . esc_url( $file_url ) . '" target="_blank">' . basename( $file_url ) . '</a>';
					}
					echo '</div>';
					echo '<button type="button" class="button scf-upload-file">Select File</button>';
					echo '<button type="button" class="button scf-remove-file" style="' . ( $value ? '' : 'display:none;' ) . '">Remove File</button>';
					echo '</div>';
					break;
				case 'select':
					echo '<select name="' . esc_attr( $key ) . '" style="width:100%;">';
					echo '<option value="">Select...</option>';
					foreach ( $options as $opt_val => $opt_label ) {
						echo '<option value="' . esc_attr( $opt_val ) . '" ' . selected( $value, $opt_val, false ) . '>' . esc_html( $opt_label ) . '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ( $options as $opt_val => $opt_label ) {
						echo '<label style="margin-right:15px;"><input type="radio" name="' . esc_attr( $key ) . '" value="' . esc_attr( $opt_val ) . '" ' . checked( $value, $opt_val, false ) . '> ' . esc_html( $opt_label ) . '</label>';
					}
					break;
				case 'checkbox':
					// value might be an array if multiple checkboxes, but for single choice checkbox usually it's boolean.
					// Let's assume this is a "Multi Checkbox" if options are provided, or a "Single Checkbox" if options are NOT provided?
					// Simplicity: If options provided, it's a multi-checkbox (array). If no options or 1 option, well... 
					// Let's stick to: options provided = multiple choice checkboxes.
					
					$saved_values = is_array( $value ) ? $value : (array) $value;
					
					if ( ! empty( $options ) ) {
						foreach ( $options as $opt_val => $opt_label ) {
							echo '<label style="margin-right:15px; display:inline-block;">';
							echo '<input type="checkbox" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $opt_val ) . '" ' . ( in_array( $opt_val, $saved_values ) ? 'checked' : '' ) . ' /> ';
							echo esc_html( $opt_label );
							echo '</label><br>';
						}
					} else {
						// Single checkbox (boolean)
						echo '<label>';
						echo '<input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . ' /> ';
						echo 'Yes';
						echo '</label>';
					}
					break;
				default:
					echo '<input type="text" id="scf_field_' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;" />';
					break;
			}
			
			echo '</p>';
		}
		
		echo '</div>';
	}

	/**
	 * Save Meta Box Data
	 *
	 * @param int $post_id
	 */
	public function save_meta_box_data( $post_id ) {
		// specific nonce check
		if ( ! isset( $_POST['scf_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['scf_meta_box_nonce'], 'scf_save_meta_box_data' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = get_option( 'scf_fields', [] );
		
		foreach ( $fields as $key => $field ) {
			if ( isset( $_POST[ $key ] ) ) {
				$value = $_POST[ $key ];
				// Basic sanitization based on type could go here, but sanitize_text_field covers most simple inputs safely.
				// For textarea we might want to allow some HTML, but for "simple" safe use, we'll strip tags or use wp_kses_post.
				// Let's use wp_kses_post for textarea and sanitize_text_field for others.
				
				$type = isset( $field['type'] ) ? $field['type'] : 'text';
				
				if ( $type === 'textarea' || $type === 'wysiwyg' ) {
					update_post_meta( $post_id, $key, wp_kses_post( $value ) );
				} else if ( $type === 'url' ) {
					update_post_meta( $post_id, $key, esc_url_raw( $value ) );
				} else if ( $type === 'email' ) {
					update_post_meta( $post_id, $key, sanitize_email( $value ) );
				} else if ( $type === 'checkbox' && is_array( $value ) ) {
					// Multiple checkboxes
					$clean_values = array_map( 'sanitize_text_field', $value );
					update_post_meta( $post_id, $key, $clean_values );
				} else {
					// Text, Number, Date, Color, Select, Radio, Image (ID), File (ID)
					update_post_meta( $post_id, $key, sanitize_text_field( $value ) );
				}
			} else {
				// Handle unchecked checkboxes (if not present in POST, it means unchecked)
				$type = isset( $field['type'] ) ? $field['type'] : 'text';
				if ( $type === 'checkbox' ) {
					delete_post_meta( $post_id, $key );
				}
			}
		}
	}
}
