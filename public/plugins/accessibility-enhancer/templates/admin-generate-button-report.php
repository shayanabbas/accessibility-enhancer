<?php
if (!defined('ABSPATH')) {
    exit;
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
<script>
    /**
     * Adds a click event listener to the "Generate Report" button.
     * Fetches the accessibility report for the current post using the REST API.
     * The report data is then displayed in the "report-output" container.
     */
    document
        .getElementById('generate-report-button')
        .addEventListener('click', async function () {
            const reportOutput = document.getElementById('report-output');
            reportOutput.textContent = 'Generating report...'; // Notify the user

            try {
                const response = await fetch(
                    `/wp-json/accessibility/v1/reports?post_id=<?php echo esc_html($post->ID); ?>`
                );

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const result = await response.json();

                // Format the output
                if (result.data.length > 0) {
                    const formattedReport = result.data.map(item => {
                        return `<strong>${item.post_title}</strong>: ${item.status}<br/>` + 
                            (item.issues.length > 0 
                                ? `<ul>${item.issues.map(issue => `<li>${issue.issue}: ${issue.selector || issue.html}</li>`).join('')}</ul>`
                                : 'No issues found.');
                    }).join('<br/>');

                    reportOutput.innerHTML = formattedReport;
                } else {
                    reportOutput.textContent = 'No accessibility issues found.';
                }
            } catch (error) {
                console.error('Error fetching the accessibility report:', error);
                reportOutput.textContent = 'An error occurred while generating the report. Please try again.';
            }
        });
</script>


<style>
    .report-output {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
        white-space: pre-wrap;
        line-height: 1.5;
    }

    .report-output strong {
        color: #007cba; /* WordPress blue */
    }

    .report-output ul {
        margin: 10px 0;
        padding-left: 20px;
        list-style-type: disc;
    }

    .report-output li {
        margin-bottom: 5px;
    }
</style>
