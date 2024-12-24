<?php
/**
 * Plugin Name: Accessibility Enhancer
 * Description: A plugin to enhance accessibility features on your WordPress site.
 * Version: 1.0
 * Author: Shayan Abbas
 */

defined('ABSPATH') || exit;

// Autoload dependencies
require_once plugin_dir_path(__FILE__) . 'includes/class-plugin-loader.php';

function accessibility_enhancer_init() {
    $plugin = new Plugin_Loader();
    $plugin->run();
}
add_action('plugins_loaded', 'accessibility_enhancer_init');
