<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Ensure $post is available and a valid post object.
global $post;
if (!isset($post) || !is_a($post, 'WP_Post')) {
    echo '<p>Error: Unable to generate the report. Invalid post context.</p>';
    return;
}
?>

<div>
    <button id="generate-report-button" class="button button-primary" aria-label="Generate Accessibility Report">
        Generate Report
    </button>

    <div id="report-output" class="report-output" aria-live="polite" aria-atomic="true"></div>
</div>
