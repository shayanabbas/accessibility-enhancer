<?php
/**
 * Accessibility Toolbar
 *
 * Handles the rendering of the accessibility toolbar on the frontend.
 *
 * @package AccessibilityEnhancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Accessibility_Toolbar
 *
 * Manages the accessibility toolbar rendering.
 */
class Accessibility_Toolbar {
	/**
	 * Constructor.
	 *
	 * Registers the action to render the toolbar on the frontend.
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'render_frontend_toolbar' ) );
	}

	/**
	 * Renders the toolbar on the frontend.
	 *
	 * This method is hooked into the WordPress footer to display the toolbar.
	 *
	 * @return void
	 */
	public function render_frontend_toolbar() {
		$this->output_toolbar();
	}

	/**
	 * Outputs the toolbar HTML.
	 *
	 * Includes the template file that contains the toolbar markup.
	 *
	 * @return void
	 */
	private function output_toolbar() {
		include plugin_dir_path( __FILE__ ) . '../templates/toolbar.php';
	}
}

// Initialize the toolbar class.
new Accessibility_Toolbar();
