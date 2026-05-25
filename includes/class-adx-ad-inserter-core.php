<?php
/**
 * Main Core Plugin Controller
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Includes
 */

defined( 'ABSPATH' ) || exit;

class Adx_Ad_Inserter_Core {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init();
	}

	/**
	 * Define plugin-wide constants.
	 */
	private function define_constants() {
		if ( ! defined( 'ADXBYMS_VERSION' ) ) {
			define( 'ADXBYMS_VERSION', '1.4.1' );
		}
		if ( ! defined( 'ADXBYMS_PATH' ) ) {
			define( 'ADXBYMS_PATH', plugin_dir_path( ADXBYMS_FILE ) );
		}
		if ( ! defined( 'ADXBYMS_URL_MODULAR' ) ) {
			define( 'ADXBYMS_URL_MODULAR', plugin_dir_url( ADXBYMS_FILE ) );
		}
	}

	/**
	 * Load required modular files.
	 */
	private function includes() {
		// Includes / Helper classes
		require_once ADXBYMS_PATH . 'includes/class-adx-gpt-manager.php';
		require_once ADXBYMS_PATH . 'includes/class-adx-device.php';
		require_once ADXBYMS_PATH . 'includes/class-adx-exclusions.php';
		require_once ADXBYMS_PATH . 'includes/class-adx-content-inserter.php';

		// Subsystem controllers
		if ( is_admin() ) {
			require_once ADXBYMS_PATH . 'admin/class-adx-admin.php';
		}
		require_once ADXBYMS_PATH . 'public/class-adx-public.php';
	}

	/**
	 * Initialize administrators and public hooks.
	 */
	private function init() {
		// Initialize GPT Manager
		Adx_Gpt_Manager::get_instance();

		// Load Admin controller
		if ( is_admin() ) {
			new Adx_Admin();
		}

		// Load Public controller
		new Adx_Public();
	}

	/**
	 * Run on plugin activation.
	 */
	public static function activate() {
		// Initial default settings
		$defaults = array(
			'adxbyms_enabled'                    => 'false',
			'adxbyms_ads_txt_enabled'            => 'false',
			'adxbyms_slot_enabled'               => 'false',
			'adxbyms_custom_enabled'             => 'false',
			'adxbyms_popup_enabled'              => 'false',
			'adxbyms_popup_option'               => 'ONCE_PER_SESSION',
			'adxbyms_popup_scroll_trigger'       => '60',
			'adxbyms_ad2_enabled'                => 'false',
			'adxbyms_anchor_enabled'             => 'false',
			'adxbyms_anchor_position'            => 'TOP_ANCHOR',
			'adxbyms_offerwall_onscroll_enabled' => 'false',
			'adxbyms_interstitial_enabled'       => 'false',
			
			// New features defaults
			'adxbyms_custom_adsense_enabled'     => 'false',
			'adxbyms_responsive_ads_enabled'     => 'false',
			'adxbyms_flying_carpet_enabled'      => 'false',
			'adxbyms_side_rail_enabled'          => 'false',
			'adxbyms_exclude_links'              => '',
		);

		foreach ( $defaults as $opt => $val ) {
			if ( get_option( $opt ) === false ) {
				update_option( $opt, $val );
			}
		}

		// Force register and flush rewrite rules for ads.txt
		if ( function_exists( 'adxbyms_register_ads_txt_rewrite' ) ) {
			adxbyms_register_ads_txt_rewrite();
		}
		flush_rewrite_rules();
	}

	/**
	 * Run on plugin deactivation.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
