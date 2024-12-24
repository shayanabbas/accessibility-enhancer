<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Reports {
    public static function generate_report($content) {
        $issues = [];

        // Check for missing alt attributes
        $issues = array_merge($issues, WCAG_Checker::check_missing_alt_tags($content));

        return $issues;
    }

    public static function save_report($post_id) {
        $content = get_post_field('post_content', $post_id);
        $issues = self::generate_report($content);

        // Save the issues as post meta
        update_post_meta($post_id, '_accessibility_issues', $issues);

        return $issues;
    }
}

// Hook into the 'save_post' action to generate accessibility reports on save
add_action('save_post', function ($post_id) {
    // Prevent saving during autosave or if it's a post revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Generate and save the report
    Reports::save_report($post_id);
});
