<?php
/**
 * Accessibility Enhancer Plugin Loader
 *
 * Initializes the plugin by loading dependencies, registering hooks, and setting up admin pages.
 *
 * @package AccessibilityEnhancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AEnhancer_Plugin_Loader
 *
 * Manages the initialization and setup of the Accessibility Enhancer plugin.
 */
class AEnhancer_Plugin_Loader {
	/**
	 * Runs the plugin by loading dependencies and registering hooks.
	 *
	 * @return void
	 */
	public function run() {
		$this->load_dependencies();
		$this->register_hooks();
	}

	/**
	 * Loads required dependencies for the plugin.
	 *
	 * @return void
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-aenhancer-rest-api.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-aenhancer-accessibility-toolbar.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-aenhancer-reports.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-aenhancer-wcag-checker.php';
	}

	/**
	 * Registers WordPress hooks for the plugin.
	 *
	 * @return void
	 */
	private function register_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_admin_meta_box' ) );
	}

	/**
	 * Enqueues admin scripts and styles for the plugin.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Ensure scripts load only on the plugin's admin page.
		if ( 'toplevel_page_accessibility-enhancer' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'accessibility-admin-script',
			plugin_dir_url( __FILE__ ) . '../dist/js/toolbar.js',
			array( 'wp-element' ), // React dependency.
			'1.0',
			true
		);

		wp_localize_script(
			'accessibility-admin-script',
			'accessibilityEnhancer',
			array(
				'restUrl' => esc_url_raw( rest_url( 'accessibility/v1/' ) ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_enqueue_style(
			'accessibility-admin-style',
			plugin_dir_url( __FILE__ ) . '../dist/css/style-admin.css',
			array(),
			'1.0'
		);
	}

	/**
	 * Enqueues frontend scripts and styles for the plugin.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'accessibility-toolbar',
			plugin_dir_url( __FILE__ ) . '../dist/js/toolbar.js',
			array( 'wp-element' ),
			'1.0',
			true
		);

		wp_localize_script(
			'accessibility-toolbar',
			'accessibilityEnhancer',
			array(
				'restUrl' => esc_url_raw( rest_url( 'accessibility/v1/' ) ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_enqueue_style(
			'accessibility-toolbar',
			plugin_dir_url( __FILE__ ) . '../dist/css/style.css',
			array(),
			'1.0'
		);

		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Adds the plugin's admin menu to the WordPress dashboard.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Accessibility Enhancer', 'accessibility-enhancer' ),
			__( 'Accessibility', 'accessibility-enhancer' ),
			'manage_options',
			'accessibility-enhancer',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Adds a meta box for accessibility reports on post and page editors.
	 *
	 * @return void
	 */
	public function add_admin_meta_box() {
		add_meta_box(
			'accessibility_report',
			__( 'Accessibility Report', 'accessibility-enhancer' ),
			array( $this, 'render_accessibility_report_box' ),
			array( 'post', 'page' ),
			'side',
			'high'
		);
	}

	/**
	 * Renders the admin dashboard page for the plugin.
	 *
	 * @return void
	 */
	public function render_admin_page() {
		include plugin_dir_path( __FILE__ ) . '../templates/admin-dashboard.php';
	}

	/**
	 * Renders the accessibility report meta box.
	 *
	 * @return void
	 */
	public function render_accessibility_report_box() {
		include plugin_dir_path( __FILE__ ) . '../templates/admin-generate-button-report.php';
	}
}
