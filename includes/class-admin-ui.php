<?php
/**
 * Admin UI Manager
 */

class SCF_Admin_UI {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_init', [ $this, 'process_form_submission' ] );
	}

	public function add_menu_page() {
		add_menu_page(
			'Simple CPT & Fields',
			'Simple CPT & Fields',
			'manage_options',
			'simple-cpt-fields',
			[ $this, 'render_admin_page' ],
			'dashicons-layout',
			100
		);
	}

	public function render_admin_page() {
		?>
		<div class="wrap">
			<h1>Simple CPT & Fields</h1>
			<p>Manage your custom post types and fields here. Settings are saved automatically.</p>
			
			<div style="display:flex; gap:20px;">
				<!-- CPT Column -->
				<div style="flex:1; background:#fff; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
					<h2>Custom Post Types</h2>
					<form method="post" action="">
						<?php wp_nonce_field( 'scf_add_cpt', 'scf_cpt_nonce' ); ?>
						<p>
							<label>Slug (e.g. movie)</label><br>
							<input type="text" name="cpt_slug" required style="width:100%">
						</p>
						<p>
							<label>Plural Name (e.g. Movies)</label><br>
							<input type="text" name="cpt_plural" required style="width:100%">
						</p>
						<p>
							<label>Singular Name (e.g. Movie)</label><br>
							<input type="text" name="cpt_singular" required style="width:100%">
						</p>
						<p>
							<input type="submit" name="scf_submit_cpt" class="button button-primary" value="Add Post Type">
						</p>
					</form>
					
					<hr>
					
					<h3>Registered Types</h3>
					<ul>
						<?php
						$cpts = get_option( 'scf_cpts', [] );
						if ( ! empty( $cpts ) ) {
							foreach ( $cpts as $slug => $cpt ) {
								echo '<li><strong>' . esc_html( $cpt['plural'] ) . '</strong> (' . esc_html( $slug ) . ') ';
								echo '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=simple-cpt-fields&action=delete_cpt&slug=' . $slug ), 'scf_delete_cpt_' . $slug ) . '" style="color:red;">Delete</a></li>';
							}
						} else {
							echo '<li>No Custom Post Types registered.</li>';
						}
						?>
					</ul>

					<hr>

					<h2>Custom Taxonomies</h2>
					<form method="post" action="">
						<?php wp_nonce_field( 'scf_add_tax', 'scf_tax_nonce' ); ?>
						<p>
							<label>Slug (e.g. genre)</label><br>
							<input type="text" name="tax_slug" required style="width:100%">
						</p>
						<p>
							<label>Plural Name (e.g. Genres)</label><br>
							<input type="text" name="tax_plural" required style="width:100%">
						</p>
						<p>
							<label>Singular Name (e.g. Genre)</label><br>
							<input type="text" name="tax_singular" required style="width:100%">
						</p>
						<p>
							<label>Attach to Post Type</label><br>
							<select name="tax_post_type" style="width:100%">
								<option value="post">Post</option>
								<option value="page">Page</option>
								<?php
								$cpts = get_option( 'scf_cpts', [] );
								foreach ( $cpts as $slug => $cpt ) {
									echo '<option value="' . esc_attr( $slug ) . '">' . esc_html( $cpt['singular'] ) . '</option>';
								}
								?>
							</select>
						</p>
						<p>
							<input type="submit" name="scf_submit_tax" class="button button-primary" value="Add Taxonomy">
						</p>
					</form>
					
					<h3>Registered Taxonomies</h3>
					<ul>
						<?php
						$taxes = get_option( 'scf_taxonomies', [] );
						if ( ! empty( $taxes ) ) {
							foreach ( $taxes as $slug => $tax ) {
								$pt = is_array($tax['post_types']) ? implode(', ', $tax['post_types']) : $tax['post_types'];
								echo '<li><strong>' . esc_html( $tax['plural'] ) . '</strong> (' . esc_html( $slug ) . ') on <em>' . esc_html( $pt ) . '</em> ';
								echo '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=simple-cpt-fields&action=delete_tax&slug=' . $slug ), 'scf_delete_tax_' . $slug ) . '" style="color:red;">Delete</a></li>';
							}
						} else {
							echo '<li>No Custom Taxonomies registered.</li>';
						}
						?>
					</ul>
				</div>

				<!-- Fields Column -->
				<div style="flex:1; background:#fff; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
					<h2>Custom Fields</h2>
					<form method="post" action="">
						<?php wp_nonce_field( 'scf_add_field', 'scf_field_nonce' ); ?>
						<p>
							<label>Field Key (e.g. movie_director)</label><br>
							<input type="text" name="field_key" required style="width:100%">
						</p>
						<p>
							<label>Field Type</label><br>
							<select name="field_type" style="width:100%">
								<option value="text">Text</option>
								<option value="textarea">Text Area</option>
								<option value="wysiwyg">WYSIWYG Editor</option>
								<option value="number">Number</option>
								<option value="email">Email</option>
								<option value="url">URL</option>
								<option value="date">Date</option>
								<option value="color">Color</option>
								<option value="image">Image</option>
								<option value="file">File</option>
								<option value="select">Select (Dropdown)</option>
								<option value="radio">Radio Buttons</option>
								<option value="checkbox">Checkbox</option>
							</select>
						</p>
						<p>
							<label>Options (for Select, Radio, Checkbox only)</label><br>
							<i style="font-size:12px; color:#666;">One per line, e.g. value : Label</i><br>
							<textarea name="field_options" rows="3" style="width:100%"></textarea>
						</p>
						<p>
							<label>Label (e.g. Director)</label><br>
							<input type="text" name="field_label" required style="width:100%">
						</p>
						<p>
							<label>Post Type (Registered)</label><br>
							<select name="field_post_type" style="width:100%">
								<option value="post">Post</option>
								<option value="page">Page</option>
								<?php
								$cpts = get_option( 'scf_cpts', [] );
								foreach ( $cpts as $slug => $cpt ) {
									echo '<option value="' . esc_attr( $slug ) . '">' . esc_html( $cpt['singular'] ) . '</option>';
								}
								?>
							</select>
						</p>
						<p>
							<input type="submit" name="scf_submit_field" class="button button-primary" value="Add Field">
						</p>
					</form>
					
					<hr>
					
					<h3>Registered Fields</h3>
					<ul>
						<?php
						$fields = get_option( 'scf_fields', [] );
						if ( ! empty( $fields ) ) {
							foreach ( $fields as $key => $field ) {
								$type = isset( $field['type'] ) ? $field['type'] : 'text';
								echo '<li><strong>' . esc_html( $field['label'] ) . '</strong> (' . esc_html( $key ) . ') - <em>' . esc_html( $type ) . '</em> on <em>' . esc_html( $field['post_type'] ) . '</em> ';
								echo '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=simple-cpt-fields&action=delete_field&key=' . $key ), 'scf_delete_field_' . $key ) . '" style="color:red;">Delete</a></li>';
							}
						} else {
							echo '<li>No Custom Fields registered.</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}

	public function process_form_submission() {
		// Add CPT
		if ( isset( $_POST['scf_submit_cpt'] ) && check_admin_referer( 'scf_add_cpt', 'scf_cpt_nonce' ) ) {
			$slug = sanitize_key( $_POST['cpt_slug'] );
			$plural = sanitize_text_field( $_POST['cpt_plural'] );
			$singular = sanitize_text_field( $_POST['cpt_singular'] );
			
			$cpts = get_option( 'scf_cpts', [] );
			$cpts[ $slug ] = [
				'plural' => $plural,
				'singular' => $singular,
			];
			
			update_option( 'scf_cpts', $cpts );
			flush_rewrite_rules();
			
			wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=cpt_added' ) );
			exit;
		}
		
		// Add Taxonomy
		if ( isset( $_POST['scf_submit_tax'] ) && check_admin_referer( 'scf_add_tax', 'scf_tax_nonce' ) ) {
			$slug = sanitize_key( $_POST['tax_slug'] );
			$plural = sanitize_text_field( $_POST['tax_plural'] );
			$singular = sanitize_text_field( $_POST['tax_singular'] );
			$post_type = sanitize_text_field( $_POST['tax_post_type'] );
			
			$taxes = get_option( 'scf_taxonomies', [] );
			$taxes[ $slug ] = [
				'plural' => $plural,
				'singular' => $singular,
				'post_types' => array( $post_type )
			];
			
			update_option( 'scf_taxonomies', $taxes );
			flush_rewrite_rules();
			
			wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=tax_added' ) );
			exit;
		}

		// Add Field
		if ( isset( $_POST['scf_submit_field'] ) && check_admin_referer( 'scf_add_field', 'scf_field_nonce' ) ) {
			$key = sanitize_key( $_POST['field_key'] );
			$label = sanitize_text_field( $_POST['field_label'] );
			$post_type = sanitize_text_field( $_POST['field_post_type'] );
			$type = sanitize_text_field( $_POST['field_type'] );
			$options = sanitize_textarea_field( $_POST['field_options'] );
			
			$fields = get_option( 'scf_fields', [] );
			$fields[ $key ] = [
				'label' => $label,
				'post_type' => $post_type,
				'type' => $type,
				'options' => $options
			];
			
			update_option( 'scf_fields', $fields );
			
			wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=field_added' ) );
			exit;
		}

		// Delete CPT
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_cpt' && isset( $_GET['slug'] ) ) {
			$slug = sanitize_key( $_GET['slug'] );
			if ( check_admin_referer( 'scf_delete_cpt_' . $slug ) ) {
				$cpts = get_option( 'scf_cpts', [] );
				unset( $cpts[ $slug ] );
				update_option( 'scf_cpts', $cpts );
				flush_rewrite_rules();
				wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=cpt_deleted' ) );
				exit;
			}
		}

		// Delete Taxonomy
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_tax' && isset( $_GET['slug'] ) ) {
			$slug = sanitize_key( $_GET['slug'] );
			if ( check_admin_referer( 'scf_delete_tax_' . $slug ) ) {
				$taxes = get_option( 'scf_taxonomies', [] );
				unset( $taxes[ $slug ] );
				update_option( 'scf_taxonomies', $taxes );
				flush_rewrite_rules();
				wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=tax_deleted' ) );
				exit;
			}
		}

		// Delete Field
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_field' && isset( $_GET['key'] ) ) {
			$key = sanitize_key( $_GET['key'] );
			if ( check_admin_referer( 'scf_delete_field_' . $key ) ) {
				$fields = get_option( 'scf_fields', [] );
				unset( $fields[ $key ] );
				update_option( 'scf_fields', $fields );
				wp_redirect( admin_url( 'admin.php?page=simple-cpt-fields&message=field_deleted' ) );
				exit;
			}
		}
	}
}
