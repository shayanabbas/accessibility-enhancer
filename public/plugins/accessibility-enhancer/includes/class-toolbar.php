<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Accessibility_Toolbar {
    public function __construct() {
        // Register shortcode for rendering the toolbar
        add_shortcode('accessibility_toolbar', [$this, 'render_toolbar_shortcode']);
    }

    // Render the toolbar using a shortcode
    public function render_toolbar_shortcode() {
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/toolbar.php';
        return ob_get_clean();
    }
}

// Initialize the toolbar class
new Accessibility_Toolbar();
