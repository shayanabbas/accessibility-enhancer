<?php

class Rest_API {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('accessibility/v1', '/reports', [
            'methods' => 'GET',
            'callback' => [$this, 'get_reports']
        ]);

        register_rest_route('accessibility/v1', '/settings', [
            'methods' => 'POST',
            'callback' => [$this, 'save_settings']
        ]);
    }

    public function get_reports() {
        // Fetch reports from database
        return new WP_REST_Response(['data' => 'Sample Report Data'], 200);
    }

    public function save_settings($request) {
        // Save settings to the database
        return new WP_REST_Response(['success' => true], 200);
    }
}
