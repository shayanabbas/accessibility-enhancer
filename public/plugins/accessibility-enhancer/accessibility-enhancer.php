<?php
/**
 * Plugin Name: Accessibility Enhancer
 * Description: A plugin to enhance accessibility features on your WordPress site.
 * Version: 1.0.0
 * Author: Shayan Abbas
 * Author URI: https://www.linkedin.com/in/shayanabbas/
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: accessibility-enhancer
 * Domain Path: /languages
 *
 * @package AccessibilityEnhancer
 */

// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit;

/**
 * Autoload dependencies.
 *
 * Loads the required classes and files for the plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-aenhancer-plugin-loader.php';

/**
 * Initializes the Accessibility Enhancer plugin.
 *
 * Creates an instance of the AEnhancer_Plugin_Loader class and starts the plugin.
 *
 * @return void
 */
function aenhancer_init() {
	$plugin = new AEnhancer_Plugin_Loader();
	$plugin->run();
}

// Hook the initialization function to the 'plugins_loaded' action.
add_action( 'plugins_loaded', 'aenhancer_init' );
