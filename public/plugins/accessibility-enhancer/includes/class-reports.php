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
		$css_rules     = array();
		$element_count = array(); // Track occurrences of each element type.

		preg_match_all( '/<([a-z]+)([^>]*)style=["\'](.*?)["\']/i', $content, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$tag        = $match[1]; // The HTML tag (e.g., div, span).
			$attributes = $match[2]; // Additional attributes of the element.
			$styles     = explode( ';', $match[3] );

			// Extract class and ID from attributes.
			preg_match( '/class=["\'](.*?)["\']/', $attributes, $class_match );
			preg_match( '/id=["\'](.*?)["\']/', $attributes, $id_match );

			$class = isset( $class_match[1] ) ? '.' . str_replace( ' ', '.', $class_match[1] ) : '';
			$id    = isset( $id_match[1] ) ? '#' . $id_match[1] : '';

			// Initialize or increment the count for this tag.
			if ( ! isset( $element_count[ $tag ] ) ) {
				$element_count[ $tag ] = 1;
			} else {
				++$element_count[ $tag ];
			}

			// Add a numeric postfix to distinguish duplicates.
			$postfix  = $element_count[ $tag ];
			$selector = "{$tag}{$id}{$class}:nth-of-type({$postfix})";

			$rule_set = array();
			foreach ( $styles as $style ) {
				if ( strpos( $style, ':' ) !== false ) {
					list( $property, $value )      = explode( ':', $style );
					$rule_set[ trim( $property ) ] = trim( $value );
				}
			}

			$css_rules[ $selector ] = $rule_set;
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
