<?php
/**
 * Ad Content Insertion Engine
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Includes
 */

defined( 'ABSPATH' ) || exit;

class Adx_Content_Inserter {

	/**
	 * Insert ad HTML into the content based on placement rules.
	 *
	 * @param string $content    The post content.
	 * @param string $ad_html    The ad HTML code.
	 * @param string $insertion  The insertion target (e.g. before_content, after_paragraph).
	 * @param int    $offset     The paragraph/image/heading index (1-based).
	 * @return string The modified content.
	 */
	public static function insert( $content, $ad_html, $insertion, $offset = 1 ) {
		if ( empty( $content ) || empty( $ad_html ) ) {
			return $content;
		}

		$offset = max( 1, intval( $offset ) );

		switch ( $insertion ) {
			case 'before_content':
				return $ad_html . $content;

			case 'after_content':
				return $content . $ad_html;

			case 'before_paragraph':
				return self::insert_at_tag_position( $content, $ad_html, 'p', $offset, 'before' );

			case 'after_paragraph':
				return self::insert_at_tag_position( $content, $ad_html, 'p', $offset, 'after' );

			case 'before_image':
				return self::insert_at_tag_position( $content, $ad_html, 'img', $offset, 'before' );

			case 'after_image':
				return self::insert_at_tag_position( $content, $ad_html, 'img', $offset, 'after' );

			case 'before_heading':
				return self::insert_at_heading_position( $content, $ad_html, $offset );

			case 'between_content':
				// Compute total paragraphs and split in the middle
				$p_count = preg_match_all( '/<p\b[^>]*>/i', $content );
				if ( $p_count > 0 ) {
					$middle = max( 1, intval( ceil( $p_count / 2 ) ) );
					return self::insert_at_tag_position( $content, $ad_html, 'p', $middle, 'after' );
				}
				// Fallback to start
				return $ad_html . $content;

			default:
				return $content;
		}
	}

	/**
	 * Insert ad before/after the Xth instance of a tag.
	 *
	 * @param string $content  The HTML content.
	 * @param string $ad_html  The ad HTML.
	 * @param string $tag      The tag to target (e.g., 'p', 'img').
	 * @param int    $instance The 1-based instance number.
	 * @param string $position 'before' or 'after'.
	 * @return string The modified content.
	 */
	private static function insert_at_tag_position( $content, $ad_html, $tag, $instance, $position ) {
		// Use regex to locate opening tags while ignoring inline scripts or comments
		$pattern = '/<' . preg_quote( $tag, '/' ) . '\b[^>]*>/i';
		
		if ( ! preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE ) ) {
			return $content; // Tag not found
		}

		$match_index = $instance - 1;
		if ( ! isset( $matches[0][$match_index] ) ) {
			// If tag count is less than requested instance, append/prepend as fallback
			return 'after' === $position ? $content . $ad_html : $ad_html . $content;
		}

		$match = $matches[0][$match_index];
		$tag_start_pos = $match[1];
		$tag_string = $match[0];

		if ( 'before' === $position ) {
			return substr( $content, 0, $tag_start_pos ) . $ad_html . substr( $content, $tag_start_pos );
		} else {
			// Find where this element ends to place the ad 'after' it
			if ( 'p' === $tag ) {
				// Find closing </p> after the opening tag
				$close_pos = stripos( $content, '</p>', $tag_start_pos );
				if ( false !== $close_pos ) {
					$insert_pos = $close_pos + 4; // After </p>
					return substr( $content, 0, $insert_pos ) . $ad_html . substr( $content, $insert_pos );
				}
			}
			
			// For images or self-closing/unknown tags, place right after the opening tag closing '>'
			$insert_pos = $tag_start_pos + strlen( $tag_string );
			return substr( $content, 0, $insert_pos ) . $ad_html . substr( $content, $insert_pos );
		}
	}

	/**
	 * Insert ad before the Xth heading tag (h1, h2, h3, h4, h5, h6).
	 *
	 * @param string $content  The HTML content.
	 * @param string $ad_html  The ad HTML.
	 * @param int    $instance The 1-based heading index.
	 * @return string The modified content.
	 */
	private static function insert_at_heading_position( $content, $ad_html, $instance ) {
		// Match any h1-h6 tag
		$pattern = '/<h[1-6]\b[^>]*>/i';

		if ( ! preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE ) ) {
			return $content; // Heading not found
		}

		$match_index = $instance - 1;
		if ( ! isset( $matches[0][$match_index] ) ) {
			// Fallback: prepend if headings don't exist in requested quantity
			return $ad_html . $content;
		}

		$tag_start_pos = $matches[0][$match_index][1];
		return substr( $content, 0, $tag_start_pos ) . $ad_html . substr( $content, $tag_start_pos );
	}
}
