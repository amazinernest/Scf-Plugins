<?php
/**
 * Plugin Name: Simple CPT & Fields
 * Description: A lightweight plugin to create Custom Post Types and Fields with Elementor support.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: simple-cpt-fields
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SCF_PATH', plugin_dir_path( __FILE__ ) );
define( 'SCF_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class Simple_CPT_Fields {

	/**
	 * Instance
	 *
	 * @var Simple_CPT_Fields The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Simple_CPT_Fields The instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files
	 */
	private function includes() {
		require_once SCF_PATH . 'includes/functions.php';
		require_once SCF_PATH . 'includes/class-admin-ui.php';
		require_once SCF_PATH . 'includes/class-cpt-manager.php';
		require_once SCF_PATH . 'includes/class-taxonomy-manager.php';
		require_once SCF_PATH . 'includes/class-field-manager.php';
		require_once SCF_PATH . 'includes/class-elementor-integration.php';
		require_once SCF_PATH . 'includes/class-shortcode.php';
	}

	/**
	 * Initialize Hooks
	 */
	private function init_hooks() {
		// Initialize the components
		new SCF_Admin_UI();
		new SCF_CPT_Manager();
		new SCF_Taxonomy_Manager();
		new SCF_Field_Manager();
		new SCF_Shortcode();
		
		// Initialize Elementor integration
		add_action( 'elementor/init', [ 'SCF_Elementor_Integration', 'init' ] );
	}

	/**
	 * Activate Plugin
	 */
	public static function activate() {
		require_once SCF_PATH . 'includes/class-cpt-manager.php';
		require_once SCF_PATH . 'includes/class-taxonomy-manager.php';
		
		$cpt_manager = new SCF_CPT_Manager();
		$cpt_manager->register_post_types();
		
		$tax_manager = new SCF_Taxonomy_Manager();
		$tax_manager->register_taxonomies();
		
		flush_rewrite_rules();
	}

	/**
	 * Deactivate Plugin
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}

register_activation_hook( __FILE__, [ 'Simple_CPT_Fields', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'Simple_CPT_Fields', 'deactivate' ] );

Simple_CPT_Fields::instance();
