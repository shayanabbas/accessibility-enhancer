<?php
/**
 * Accessibility Enhancer REST API
 *
 * Handles custom REST API endpoints for accessibility reports and settings.
 *
 * @package AccessibilityEnhancer
 */

/**
 * Class Rest_API
 *
 * Manages the custom REST API endpoints for the Accessibility Enhancer plugin.
 */
class Rest_API {
	/**
	 * Constructor.
	 *
	 * Registers the custom REST API routes.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Registers the REST API routes.
	 *
	 * Defines the routes for fetching accessibility reports and saving settings.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			'accessibility/v1',
			'/reports',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_reports' ),
				'args'     => array(
					'slug' => array(
						'description' => 'The slug of the post to fetch the report for.',
						'type'        => 'string',
						'required'    => false,
					),
				),
			)
		);
	}

	/**
	 * Fetches accessibility reports.
	 *
	 * Retrieves reports based on the specified slug or all posts with accessibility issues if no slug is provided.
	 *
	 * @param WP_REST_Request $request The REST API request object.
	 * @return WP_REST_Response The REST API response containing the reports data.
	 */
	public function get_reports( $request ) {
		$slug = $request->get_param( 'slug' );

		// Build the arguments for WP_Query.
		$query_args = array(
			'post_type'      => array( 'post', 'page' ),
			'meta_query'     => array(
				array(
					'key'     => '_accessibility_issues',
					'compare' => 'EXISTS',
				),
			),
			'posts_per_page' => 50, // Limit results for performance.
		);

		// If a slug is provided, add it to the query arguments.
		if ( $slug ) {
			$query_args['name'] = $slug;
		}

		// Perform the query.
		$query = new WP_Query( $query_args );

		// Process the results.
		$meta_results = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$issues         = get_post_meta( get_the_ID(), '_accessibility_issues', true );
			$meta_results[] = array(
				'post_id'    => get_the_ID(),
				'post_title' => get_the_title(),
				'status'     => empty( $issues ) ? 'Fixed' : 'Issues Found',
				'issues'     => $issues,
			);
		}
		wp_reset_postdata();

		// Cache the results using transient cache.
		$cache_key = 'accessibility_reports_' . ( $slug ? $slug : 'all' );
		set_transient( $cache_key, $meta_results, 12 * HOUR_IN_SECONDS );

		return new WP_REST_Response( array( 'data' => $meta_results ), 200 );
	}
}

// Initialize the REST API class.
new Rest_API();
