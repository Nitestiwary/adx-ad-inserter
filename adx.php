<?php
/**
 * Plugin Name: AdX Ad Inserter
 * Plugin URI: https://monetiscope.com/adx-ad-inserter-plugin/
 * Description: Insert Google AdX, Ad Manager, popup, rewarded, sticky, and in-content ads with precise placement controls. Built-in ads.txt editor.
 * Author: Monetiscope
 * Version: 1.3.2
 * Author URI: https://monetiscope.com
 * Requires at least: 5.0
 * Tested up to: 7.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: adx-ad-inserter
 * Domain Path: /languages
 *
 * @package AdX-Ad-Inserter
 */

defined( 'ABSPATH' ) || exit;

// Define core baseline file path constants
define( 'ADXBYMS_FILE', __FILE__ );
define( 'ADXBYMS_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADXBYMS_URL', plugin_dir_url( __FILE__ ) );

// Keep global rewrite definition for backward compatibility and activation requirements
function adxbyms_register_ads_txt_rewrite() {
	add_rewrite_rule( '^ads\.txt$', 'index.php?adxbyms_ads_txt=1', 'top' );
}
add_action( 'init', 'adxbyms_register_ads_txt_rewrite' );

// Load Core controller
require_once ADXBYMS_DIR . 'includes/class-adx-ad-inserter-core.php';

// Instantiate core bootstrap subsystem
new Adx_Ad_Inserter_Core();

// Activation and Deactivation hook bindings
register_activation_hook( __FILE__, array( 'Adx_Ad_Inserter_Core', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Adx_Ad_Inserter_Core', 'deactivate' ) );

/**
 * Add settings action link to Plugins screen
 *
 * @param array $links The existing links.
 * @return array The modified links.
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'adxbyms_add_settings_action_link' );
function adxbyms_add_settings_action_link( $links ) {
	$url   = admin_url( 'admin.php?page=adx-ad-inserter' );
	$label = __( 'Settings', 'adx-ad-inserter' );
	array_unshift(
		$links,
		'<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>'
	);
	return $links;
}
