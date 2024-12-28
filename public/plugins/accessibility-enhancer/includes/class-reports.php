<?php
/**
 * Accessibility Enhancer Reports
 *
 * Handles generating and saving accessibility reports.
 *
 * @package AccessibilityEnhancer
 */

/**
 * Class Reports
 *
 * Manages accessibility reports for WordPress content.
 */
class Reports {
	/**
	 * Generates an accessibility report for the provided content.
	 *
	 * @param string $content The content to analyze.
	 * @return array The list of accessibility issues.
	 */
	public static function generate_report( $content ) {
		$issues = array();
		$issues = array_merge( $issues, WCAG_Checker::check_missing_alt_tags( $content ) );
		$issues = array_merge( $issues, WCAG_Checker::check_heading_structure( $content ) );
		$issues = array_merge( $issues, WCAG_Checker::check_aria_roles( $content ) );

		// Extract CSS rules from content.
		$css_rules = self::extract_inline_styles( $content );

		// Add color contrast issues.
		$contrast_issues = WCAG_Checker::check_color_contrast( $css_rules );
		$issues          = array_merge( $issues, $contrast_issues );

		return $issues;
	}

	/**
	 * Saves the accessibility report for a given post.
	 *
	 * @param int $post_id The ID of the post to analyze.
	 * @return array The saved accessibility issues.
	 */
	public static function save_report( $post_id ) {
		$content = get_post_field( 'post_content', $post_id );
		$issues  = self::generate_report( $content );

		// Extract inline styles for color contrast checks.
		$css_rules       = self::extract_inline_styles( $content );
		$contrast_issues = WCAG_Checker::check_color_contrast( $css_rules );
		$issues          = array_merge( $issues, $contrast_issues );

		// Save the issues as post meta.
		update_post_meta( $post_id, '_accessibility_issues', $issues );

		return $issues;
	}

	/**
	 * Extracts inline styles from the given content.
	 *
	 * @param string $content The content to analyze.
	 * @return array An array of CSS rules extracted from inline styles.
	 */
	private static function extract_inline_styles( $content ) {
		$css_rules = array();
		preg_match_all( '/style=["\'](.*?)["\']/', $content, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$styles   = explode( ';', $match[1] );
			$rule_set = array();
			foreach ( $styles as $style ) {
				if ( strpos( $style, ':' ) !== false ) {
					list($property, $value)        = explode( ':', $style );
					$rule_set[ trim( $property ) ] = trim( $value );
				}
			}
			$css_rules[] = $rule_set;
		}

		return $css_rules;
	}
}

// Hook into the 'save_post' action to generate accessibility reports on save.
add_action(
	'save_post',
	function ( $post_id ) {
		/**
		 * Prevent saving during autosave or if it's a post revision.
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Generate and save the report.
		Reports::save_report( $post_id );
	}
);
