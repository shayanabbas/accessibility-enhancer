<?php
/**
 * WCAG Checker
 *
 * Provides methods to check accessibility issues in content.
 *
 * @package AccessibilityEnhancer
 */

/**
 * Class WCAG_Checker
 *
 * Contains methods to analyze WCAG compliance in HTML content.
 */
class WCAG_Checker {
	/**
	 * Checks for missing alt attributes in images.
	 *
	 * @param string $content The content to analyze.
	 * @return array A list of issues found.
	 */
	public static function check_missing_alt_tags( $content ) {
		preg_match_all( '/<img[^>]+>/i', $content, $images );
		$issues = array();

		foreach ( $images[0] as $img ) {
			if ( ! preg_match( '/alt=["\'].*?["\']/', $img ) ) {
				$issues[] = array(
					'issue' => 'Missing alt attribute',
					'html'  => esc_html( $img ),
				);
			}
		}

		return $issues;
	}

	/**
	 * Checks for insufficient color contrast between text and background.
	 *
	 * @param array $css_rules The CSS rules to analyze.
	 * @return array A list of color contrast issues found.
	 */
	public static function check_color_contrast( $css_rules ) {
		$issues = array();

		foreach ( $css_rules as $selector => $styles ) {
			if ( isset( $styles['color'], $styles['background-color'] ) ) {
				$contrast_ratio = self::calculate_contrast_ratio( $styles['color'], $styles['background-color'] );
				if ( $contrast_ratio < 4.5 ) {
					$issues[] = array(
						'issue'    => 'Insufficient color contrast',
						'selector' => $selector,
					);
				}
			}
		}

		return $issues;
	}

	/**
	 * Calculates the contrast ratio between two colors.
	 *
	 * @param string $color1 The text color in hex format.
	 * @param string $color2 The background color in hex format.
	 * @return float The contrast ratio.
	 */
	private static function calculate_contrast_ratio( $color1, $color2 ) {
		// Convert hex colors to RGB.
		$rgb1 = self::hex_to_rgb( $color1 );
		$rgb2 = self::hex_to_rgb( $color2 );

		// Calculate relative luminance.
		$luminance1 = self::relative_luminance( $rgb1 );
		$luminance2 = self::relative_luminance( $rgb2 );

		// Calculate contrast ratio.
		return ( $luminance1 > $luminance2 )
			? ( $luminance1 + 0.05 ) / ( $luminance2 + 0.05 )
			: ( $luminance2 + 0.05 ) / ( $luminance1 + 0.05 );
	}

	/**
	 * Converts a hex color code to an RGB array.
	 *
	 * @param string $hex The hex color code.
	 * @return array An array with RGB values.
	 */
	private static function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );

		if ( strlen( $hex ) === 3 ) {
			$hex = str_repeat( $hex[0], 2 ) . str_repeat( $hex[1], 2 ) . str_repeat( $hex[2], 2 );
		}

		return array(
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
		);
	}

	/**
	 * Calculates the relative luminance of an RGB color.
	 *
	 * @param array $rgb An array with RGB values.
	 * @return float The relative luminance.
	 */
	private static function relative_luminance( $rgb ) {
		$rgb = array_map(
			function ( $channel ) {
				$channel /= 255;
				return ( $channel <= 0.03928 )
				? $channel / 12.92
				: pow( ( $channel + 0.055 ) / 1.055, 2.4 );
			},
			$rgb
		);

		return 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2];
	}

	/**
	 * Checks for improper heading hierarchy in the content.
	 *
	 * @param string $content The content to analyze.
	 * @return array A list of heading hierarchy issues found.
	 */
	public static function check_heading_structure( $content ) {
		preg_match_all( '/<h[1-6][^>]*>/i', $content, $headings );
		$issues     = array();
		$last_level = 0;

		foreach ( $headings[0] as $heading ) {
			preg_match( '/<h([1-6])/', $heading, $matches );
			$current_level = intval( $matches[1] );

			if ( $current_level > $last_level + 1 ) {
				$issues[] = array(
					'issue' => 'Improper heading hierarchy',
					'html'  => esc_html( $heading ),
				);
			}

			$last_level = $current_level;
		}

		return $issues;
	}

	/**
	 * Checks for invalid ARIA roles in the content.
	 *
	 * @param string $content The content to analyze.
	 * @return array A list of invalid ARIA roles found.
	 */
	public static function check_aria_roles( $content ) {
		preg_match_all( '/role=["\'](.*?)["\']/i', $content, $roles );
		$issues      = array();
		$valid_roles = array( 'button', 'dialog', 'alert', 'menu', 'tablist', 'tabpanel' );

		foreach ( $roles[1] as $role ) {
			if ( ! in_array( $role, $valid_roles, true ) ) {
				$issues[] = array(
					'issue' => 'Invalid ARIA role',
					'role'  => $role,
				);
			}
		}

		return $issues;
	}
}
