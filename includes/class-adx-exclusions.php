<?php
/**
 * URL Exclusions Helper
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Includes
 */

defined( 'ABSPATH' ) || exit;

class Adx_Exclusions {

	/**
	 * Normalizes a URL/path to make it safe and consistent for comparison.
	 * Removes protocol, domain, trailing slash, query parameters, etc.
	 *
	 * @param string $url The URL or path to normalize.
	 * @return string The normalized path.
	 */
	public static function normalize_path( $url ) {
		if ( empty( $url ) ) {
			return '';
		}

		// Decode URL entities
		$url = rawurldecode( trim( $url ) );

		// Parse the URL
		$parsed = wp_parse_url( $url );

		// If a path exists, use it; otherwise use the original string if it is just a path
		$path = isset( $parsed['path'] ) ? $parsed['path'] : $url;

		// Strip query parameters for basic path matching (query-string-safe)
		// Unless the user explicitly included a query string to match, let's keep it clean
		if ( isset( $parsed['query'] ) && strpos( $url, '?' ) !== false ) {
			// We can choose to keep the query or strip it. Let's strip query string for primary path,
			// but we will do a separate comparison if query parameters are involved.
			$path = strtok( $path, '?' );
		}

		// Lowercase and trim slashes
		$path = strtolower( trim( $path, '/' ) );

		return $path;
	}

	/**
	 * Check if the current request should be excluded from showing ads.
	 *
	 * @return bool True if current page is excluded, false otherwise.
	 */
	public static function is_current_page_excluded() {
		// Only run on frontend
		if ( is_admin() ) {
			return false;
		}

		$exclude_option = get_option( 'adxbyms_exclude_links', '' );
		if ( empty( $exclude_option ) ) {
			return false;
		}

		// Get current requested URI
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$current_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$current_path = self::normalize_path( $current_uri );
		$current_full_url = strtolower( home_url( $_SERVER['REQUEST_URI'] ) );

		// Split user comma-separated links
		$excluded_items = explode( ',', $exclude_option );

		foreach ( $excluded_items as $item ) {
			$item = trim( $item );
			if ( empty( $item ) ) {
				continue;
			}

			// Check for full URL matching
			if ( strpos( $item, '://' ) !== false ) {
				$normalized_item = strtolower( strtok( $item, '?' ) );
				// Strip trailing slash for comparison
				if ( rtrim( $current_full_url, '/' ) === rtrim( $normalized_item, '/' ) ) {
					return true;
				}
			}

			// Check for path relative matching
			$norm_exclude_path = self::normalize_path( $item );
			if ( $current_path === $norm_exclude_path ) {
				return true;
			}

			// Sub-path prefix matching (e.g. /privacy-policy matches /privacy-policy/child/)
			if ( ! empty( $norm_exclude_path ) && strpos( $current_path, $norm_exclude_path . '/' ) === 0 ) {
				return true;
			}
		}

		return false;
	}
}
