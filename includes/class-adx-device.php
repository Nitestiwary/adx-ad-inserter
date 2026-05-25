<?php
/**
 * Device Targeting Helper
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Includes
 */

defined( 'ABSPATH' ) || exit;

class Adx_Device {

	/**
	 * Detect if the current device is a tablet.
	 *
	 * @return bool
	 */
	public static function is_tablet() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}

		$ua = strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) );
		
		// Common tablet indicators
		if ( strpos( $ua, 'ipad' ) !== false || strpos( $ua, 'tablet' ) !== false || strpos( $ua, 'playbook' ) !== false || strpos( $ua, 'kindle' ) !== false || strpos( $ua, 'silk' ) !== false ) {
			return true;
		}

		// Android tablet: contains 'android' but NOT 'mobile'
		if ( strpos( $ua, 'android' ) !== false && strpos( $ua, 'mobile' ) === false ) {
			return true;
		}

		return false;
	}

	/**
	 * Detect if the current device is a mobile phone (non-tablet mobile).
	 *
	 * @return bool
	 */
	public static function is_mobile_phone() {
		return wp_is_mobile() && ! self::is_tablet();
	}

	/**
	 * Detect if the current device is a desktop.
	 *
	 * @return bool
	 */
	public static function is_desktop() {
		return ! wp_is_mobile();
	}

	/**
	 * Check if the current device matches the targeted devices.
	 *
	 * @param array|string $targeted_devices Array or string of targeted devices.
	 * @return bool True if matches target, false otherwise.
	 */
	public static function matches( $targeted_devices ) {
		if ( empty( $targeted_devices ) ) {
			return true; // If none specified, allow everywhere
		}

		if ( is_string( $targeted_devices ) ) {
			$targeted_devices = array( $targeted_devices );
		}

		$targeted_devices = array_map( 'strtolower', array_map( 'trim', (array) $targeted_devices ) );

		// 'all' or empty means all devices
		if ( in_array( 'all', $targeted_devices, true ) || in_array( 'all_devices', $targeted_devices, true ) ) {
			return true;
		}

		$is_desktop = self::is_desktop();
		$is_tablet  = self::is_tablet();
		$is_mobile  = self::is_mobile_phone();

		foreach ( $targeted_devices as $device ) {
			if ( 'desktop' === $device && $is_desktop ) {
				return true;
			}
			if ( 'tablet' === $device && $is_tablet ) {
				return true;
			}
			if ( 'mobile' === $device && $is_mobile ) {
				return true;
			}
			// Backward compatibility: check "mobile/tablet" combined
			if ( 'mobile/tablet' === $device || 'mobile_tablet' === $device ) {
				if ( $is_mobile || $is_tablet ) {
					return true;
				}
			}
		}

		return false;
	}
}
