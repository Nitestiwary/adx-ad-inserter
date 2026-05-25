<?php
/**
 * Admin Area Sub-Controller
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Admin
 */

defined( 'ABSPATH' ) || exit;

class Adx_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}


	/**
	 * Register the plugin settings.
	 */
	public function register_settings() {
		// Central settings
		$central_settings = array(
			'adxbyms_enabled',
			'adxbyms_popup_enabled',
			'adxbyms_popup_network_code',
			'adxbyms_popup_option',
			'adxbyms_popup_scroll_trigger',
			'adxbyms_ad2_enabled',
			'adxbyms_ad2_network_code',
			'adxbyms_ad2_keywords',
			'adxbyms_anchor_enabled',
			'adxbyms_anchor_network_code',
			'adxbyms_anchor_position',
			'adxbyms_offerwall_onscroll_enabled',
			'adxbyms_offerwall_onscroll_network_code',
			'adxbyms_offerwall_onscroll_logo_url',
			'adxbyms_interstitial_enabled',
			'adxbyms_interstitial_network_code',
			'adxbyms_custom_enabled',
			'adxbyms_ads_txt_enabled',
			'adxbyms_slot_enabled',
			
			// New features central toggles
			'adxbyms_custom_adsense_enabled',
			'adxbyms_responsive_ads_enabled',
			'adxbyms_flying_carpet_enabled',
			'adxbyms_side_rail_enabled',
			'adxbyms_exclude_links',
			
			// Central performance
			'adxbyms_lazy_load',
			
			// Popup specific settings updates
			'adxbyms_popup_frequency',
			'adxbyms_popup_pages',
			'adxbyms_popup_devices',

			// Side rail specifics
			'adxbyms_side_rail_network_code',
			'adxbyms_side_rail_left_network_code',
			'adxbyms_side_rail_right_network_code',
			'adxbyms_side_rail_refresh_enabled',
			'adxbyms_side_rail_refresh_interval',

			// Offerwall trigger depth
			'adxbyms_offerwall_onscroll_trigger',
		);

		foreach ( $central_settings as $opt ) {
			if ( 'adxbyms_exclude_links' === $opt ) {
				register_setting( 'adxbyms_settings', $opt, array( 'sanitize_callback' => 'sanitize_textarea_field' ) );
			} else {
				register_setting( 'adxbyms_settings', $opt, array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			}
		}

		// Header/Footer textareas custom sanitization
		register_setting( 'adxbyms_settings', 'adxbyms_header_code', array( 'sanitize_callback' => array( $this, 'sanitize_raw_code' ) ) );
		register_setting( 'adxbyms_settings', 'adxbyms_footer_code', array( 'sanitize_callback' => array( $this, 'sanitize_raw_code' ) ) );
		register_setting( 'adxbyms_settings', 'adxbyms_ads_txt_code', array( 'sanitize_callback' => 'sanitize_textarea_field' ) );

		// Register 10 standard display slots
		for ( $i = 1; $i <= 10; $i++ ) {
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_enabled", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_network_code", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_sizes", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_pages", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_insertion", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_alignment", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_text", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_offset", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_slot_{$i}_devices", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
		}

		// Register 10 custom adsense blocks
		for ( $i = 1; $i <= 10; $i++ ) {
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_enabled", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_code", array( 'sanitize_callback' => array( $this, 'sanitize_raw_code' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_insertion", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_offset", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_alignment", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_pages", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_custom_adsense_block_{$i}_devices", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
		}

		// Register 5 responsive ad blocks
		for ( $i = 1; $i <= 5; $i++ ) {
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_enabled", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_network_code", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_insertion", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_offset", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_alignment", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_pages", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_responsive_block_{$i}_devices", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
		}

		// Register 5 flying carpet ad blocks
		for ( $i = 1; $i <= 5; $i++ ) {
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_enabled", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_network_code", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_insertion", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_offset", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_alignment", array( 'sanitize_callback' => 'sanitize_text_field' ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_pages", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
			register_setting( 'adxbyms_settings', "adxbyms_flying_carpet_block_{$i}_devices", array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) );
		}
	}

	/**
	 * Safe default settings values helper.
	 */
	public function sanitize_option( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}
		if ( 'true' === $value || 'false' === $value ) {
			return $value;
		}
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize raw custom HTML/JS ad codes safely.
	 */
	public function sanitize_raw_code( $value ) {
		if ( current_user_can( 'unfiltered_html' ) ) {
			return $value; // Administrators with unrestricted HTML can paste raw codes directly
		}

		// Strict security sandbox: strip unsafe nodes, allow only essential elements
		$allowed = array(
			'div'      => array(
				'id'     => true,
				'class'  => true,
				'style'  => true,
				'data-*' => true,
			),
			'span'     => array(
				'id'     => true,
				'class'  => true,
				'style'  => true,
				'data-*' => true,
			),
			'ins'      => array(
				'class'  => true,
				'style'  => true,
				'data-*' => true,
				'id'     => true,
			),
			'noscript' => array(),
		);

		return wp_kses( $value, $allowed );
	}

	/**
	 * Add Settings Page to WordPress Admin.
	 */
	public function add_settings_menu() {
		add_menu_page(
			__( 'AdX Ad Inserter', 'adx-ad-inserter' ),
			__( 'AdX Ad Inserter', 'adx-ad-inserter' ),
			'manage_options',
			'adx-ad-inserter',
			array( $this, 'render_settings_page' ),
			'dashicons-megaphone',
			59
		);
	}

	/**
	 * Render the settings template page.
	 */
	public function render_settings_page() {
		// Double check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'adx-ad-inserter' ) );
		}

		require_once ADXBYMS_PATH . 'templates/settings-page.php';
	}

	/**
	 * Enqueue Admin Scripts and CSS Assets.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_adx-ad-inserter' !== $hook && 'adx-ad-inserter' !== $hook ) {
			if ( ! isset( $_GET['page'] ) || 'adx-ad-inserter' !== $_GET['page'] ) {
				return;
			}
		}

		$css_version = ADXBYMS_VERSION . '.' . ( file_exists( ADXBYMS_PATH . 'assets/css/admin.css' ) ? filemtime( ADXBYMS_PATH . 'assets/css/admin.css' ) : time() );
		$js_version  = ADXBYMS_VERSION . '.' . ( file_exists( ADXBYMS_PATH . 'assets/js/admin.js' ) ? filemtime( ADXBYMS_PATH . 'assets/js/admin.js' ) : time() );

		wp_enqueue_style(
			'adxbyms-admin-css',
			ADXBYMS_URL_MODULAR . 'assets/css/admin.css',
			array(),
			$css_version
		);

		wp_enqueue_script(
			'adxbyms-admin-js',
			ADXBYMS_URL_MODULAR . 'assets/js/admin.js',
			array( 'jquery' ),
			$js_version,
			true
		);

		wp_localize_script(
			'adxbyms-admin-js',
			'adxbyms_strings',
			array(
				'pluginActive'   => __( 'Plugin Active', 'adx-ad-inserter' ),
				'pluginInactive' => __( 'Plugin Inactive', 'adx-ad-inserter' ),
			)
		);
	}
}

