<?php

class Plugin_Loader {
    public function run() {
        $this->load_dependencies();
        $this->register_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(__FILE__) . 'class-rest-api.php';
    }

    private function register_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function enqueue_scripts() {
        
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Accessibility Enhancer', 'accessibility-enhancer'),
            __('Accessibility', 'accessibility-enhancer'),
            'manage_options',
            'accessibility-enhancer',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page() {
        include plugin_dir_path(__FILE__) . '../templates/admin-dashboard.php';
    }
}
