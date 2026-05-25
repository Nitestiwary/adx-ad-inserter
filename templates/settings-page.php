<?php
/**
 * Main Settings Page Template Frame
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates
 */

defined( 'ABSPATH' ) || exit;

// Verify active settings link
$is_active = ( 'true' === get_option( 'adxbyms_enabled', 'false' ) );

// Registered Sidebar Tabs
$tabs = array(
	'tab-display-slot'     => __( 'Display Slots', 'adx-ad-inserter' ),
	'tab-custom-adsense'   => __( 'Adsense Ads / Custom', 'adx-ad-inserter' ),
	'tab-responsive-ads'   => __( 'Responsive Ads', 'adx-ad-inserter' ),
	'tab-popup-updated'    => __( 'Popup Ads', 'adx-ad-inserter' ),
	'tab-flying-carpet'    => __( 'Flying Carpet Ads', 'adx-ad-inserter' ),
	'tab-side-rail'        => __( 'Side Rail Ads', 'adx-ad-inserter' ),
	'tab-exclude-links'    => __( 'Exclude Links', 'adx-ad-inserter' ),
	'tab-anchor'           => __( 'Anchor Ad', 'adx-ad-inserter' ),
	'tab-button-rewarded'  => __( 'Button Rewarded Ad', 'adx-ad-inserter' ),
	'tab-offerwall'        => __( 'Offerwall Ad', 'adx-ad-inserter' ),
	'tab-interstitial'     => __( 'Interstitial Ad', 'adx-ad-inserter' ),
	'tab-header-footer'    => __( 'Header/Footer Scripts', 'adx-ad-inserter' ),
	'tab-ads-txt'          => __( 'Ads.Txt Manager', 'adx-ad-inserter' ),
);

// Map Tabs to Template Panels
$panels = array(
	'tab-display-slot'     => 'settings-display.php',
	'tab-custom-adsense'   => 'settings-custom-adsense.php',
	'tab-responsive-ads'   => 'settings-responsive.php',
	'tab-popup-updated'    => 'settings-popup.php',
	'tab-flying-carpet'    => 'settings-flying-carpet.php',
	'tab-side-rail'        => 'settings-side-rail.php',
	'tab-exclude-links'    => 'settings-exclude-links.php',
	'tab-anchor'           => 'settings-anchor.php',
	'tab-button-rewarded'  => 'settings-button-rewarded.php',
	'tab-offerwall'        => 'settings-offerwall-onscroll.php',
	'tab-interstitial'     => 'settings-interstitial.php',
	'tab-header-footer'    => 'settings-custom.php',
	'tab-ads-txt'          => 'settings-ads-txt.php',
);

// Slot Status Registry for Sidebar Indicators
$status_registry = array(
	'Display Slots'        => array( 'enabled' => 'adxbyms_slot_enabled', 'type' => 'display' ),
	'Adsense / Custom'     => array( 'enabled' => 'adxbyms_custom_adsense_enabled', 'type' => 'custom-adsense' ),
	'Responsive Ads'       => array( 'enabled' => 'adxbyms_responsive_ads_enabled', 'type' => 'responsive' ),
	'Popup Ads'            => array( 'enabled' => 'adxbyms_popup_enabled', 'code' => 'adxbyms_popup_network_code' ),
	'Flying Carpet'        => array( 'enabled' => 'adxbyms_flying_carpet_enabled', 'type' => 'flying-carpet' ),
	'Side Rail Ads'        => array( 'enabled' => 'adxbyms_side_rail_enabled', 'code' => 'adxbyms_side_rail_network_code' ),
	'Anchor Ads'           => array( 'enabled' => 'adxbyms_anchor_enabled', 'code' => 'adxbyms_anchor_network_code' ),
	'Button Rewarded'      => array( 'enabled' => 'adxbyms_ad2_enabled', 'code' => 'adxbyms_ad2_network_code' ),
	'Offerwall Ad'         => array( 'enabled' => 'adxbyms_offerwall_onscroll_enabled', 'code' => 'adxbyms_offerwall_onscroll_network_code' ),
	'Interstitial Ad'      => array( 'enabled' => 'adxbyms_interstitial_enabled', 'code' => 'adxbyms_interstitial_network_code' ),
);
?>

<div class="wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'adxbyms_settings' );
		do_settings_sections( 'adxbyms_settings' );
		?>

		<!-- Glassmorphism Dashboard Header -->
		<div class="form-header">
			<h1 class="settings-title"><?php esc_html_e( 'AdX Ad Inserter Premium', 'adx-ad-inserter' ); ?></h1>
			<div class="settings-separator"></div>
			<div class="form-actions">
				<div class="form-toggle">
					<h2 class="toggle-title">
						<?php echo esc_html( $is_active ? __( 'Plugin Active', 'adx-ad-inserter' ) : __( 'Plugin Inactive', 'adx-ad-inserter' ) ); ?>
					</h2>
					<label class="switch">
						<input type="hidden" name="adxbyms_enabled" value="false" />
						<input type="checkbox" id="adxbyms_enabled" name="adxbyms_enabled" value="true" <?php checked( $is_active, true ); ?>>
						<span class="slider round"></span>
					</label>
				</div>

				<div class="head-banner">
					<a href="https://monetiscope.com/contact/" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/banner2.jpg' ); ?>" alt="<?php esc_attr_e( 'Monetiscope Help Desk', 'adx-ad-inserter' ); ?>">
					</a>
				</div>

				<?php submit_button( __( 'Save Changes', 'adx-ad-inserter' ), 'primary', 'submit', false, array( 'id' => 'adx_save_top' ) ); ?>
			</div>
		</div>

		<!-- Container (faded out if plugin is inactive) -->
		<?php $container_style = $is_active ? '' : 'opacity:0.5; pointer-events:none; cursor:not-allowed;'; ?>
		<div class="settings-container" style="<?php echo esc_attr( $container_style ); ?>">
			
			<!-- Left side navigation + settings cards panels -->
			<div class="settings-left">
				<nav class="settings-nav">
					<ul>
						<?php foreach ( $tabs as $id => $label ) : ?>
							<li>
								<a href="#" class="nav-tab" data-target="<?php echo esc_attr( $id ); ?>">
									<?php echo esc_html( $label ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<div class="settings-main">
					<?php
					foreach ( $panels as $id => $panel_file ) {
						// Look in the new templates/settings/ folder
						$file_path = ADXBYMS_PATH . 'templates/settings/' . $panel_file;
						if ( file_exists( $file_path ) ) {
							require $file_path;
						} else {
							// Fallback to absolute plugin path templates
							$fallback_path = ADXBYMS_PATH . 'templates/' . $panel_file;
							if ( file_exists( $fallback_path ) ) {
								require $fallback_path;
							}
						}
					}
					?>
					
					<div class="bottom-save">
						<?php submit_button( __( 'Save Settings', 'adx-ad-inserter' ), 'primary' ); ?>
					</div>
				</div>
			</div>

			<!-- Right Sidebar: Active Status Board & Support -->
			<aside class="settings-sidebar">
				<h2><?php esc_html_e( 'Slot Status Board', 'adx-ad-inserter' ); ?></h2>
				<ul>
					<?php
					foreach ( $status_registry as $label => $spec ) {
						$enabled = ( 'true' === get_option( $spec['enabled'] ) );
						
						// Dynamic indicator class calculation
						$status_class = 'status-empty';
						
						if ( isset( $spec['type'] ) ) {
							$type = $spec['type'];
							$any_code_saved = false;
							$any_sub_enabled = false;
							
							if ( 'display' === $type ) {
								for ( $j = 1; $j <= 10; $j++ ) {
									if ( '' !== trim( (string) get_option( "adxbyms_slot_{$j}_network_code", '' ) ) ) {
										$any_code_saved = true;
										if ( 'true' === get_option( "adxbyms_slot_{$j}_enabled" ) ) {
											$any_sub_enabled = true;
										}
									}
								}
							} elseif ( 'custom-adsense' === $type ) {
								for ( $j = 1; $j <= 10; $j++ ) {
									if ( '' !== trim( (string) get_option( "adxbyms_custom_adsense_block_{$j}_code", '' ) ) ) {
										$any_code_saved = true;
										if ( 'true' === get_option( "adxbyms_custom_adsense_block_{$j}_enabled" ) ) {
											$any_sub_enabled = true;
										}
									}
								}
							} elseif ( 'responsive' === $type ) {
								for ( $j = 1; $j <= 5; $j++ ) {
									if ( '' !== trim( (string) get_option( "adxbyms_responsive_block_{$j}_network_code", '' ) ) ) {
										$any_code_saved = true;
										if ( 'true' === get_option( "adxbyms_responsive_block_{$j}_enabled" ) ) {
											$any_sub_enabled = true;
										}
									}
								}
							} elseif ( 'flying-carpet' === $type ) {
								for ( $j = 1; $j <= 5; $j++ ) {
									if ( '' !== trim( (string) get_option( "adxbyms_flying_carpet_block_{$j}_network_code", '' ) ) ) {
										$any_code_saved = true;
										if ( 'true' === get_option( "adxbyms_flying_carpet_block_{$j}_enabled" ) ) {
											$any_sub_enabled = true;
										}
									}
								}
							}
							
							if ( ! $any_code_saved ) {
								$status_class = 'status-empty';
							} elseif ( $enabled && $any_sub_enabled ) {
								$status_class = 'status-active';
							} else {
								$status_class = 'status-filled';
							}
							
						} else {
							$code = trim( (string) get_option( $spec['code'], '' ) );
							if ( empty( $code ) ) {
								$status_class = 'status-empty';
							} elseif ( $enabled ) {
								$status_class = 'status-active';
							} else {
								$status_class = 'status-filled';
							}
						}
						
						?>
						<li>
							<span class="status-indicator <?php echo esc_attr( $status_class ); ?>"></span>
							<span class="status-label"><?php echo esc_html( $label ); ?></span>
						</li>
						<?php
					}
					?>
				</ul>
				
				<hr style="border: 0; height: 1px; background: #e2e8f0; margin: 20px 0;">
				
				<div class="links">
					<h3><?php esc_html_e( 'Connect with Monetiscope', 'adx-ad-inserter' ); ?></h3>
					<div class="social-icons">
						<a href="https://monetiscope.com/" target="_blank" rel="noopener noreferrer" title="Website">
							<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/website2.png' ); ?>" alt="Website">
						</a>
						<a href="https://www.linkedin.com/company/monetiscope" target="_blank" rel="noopener noreferrer" title="LinkedIn">
							<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/linkedin2.png' ); ?>" alt="LinkedIn">
						</a>
						<a href="https://x.com/monetiscope" target="_blank" rel="noopener noreferrer" title="Twitter/X">
							<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/x2.png' ); ?>" alt="Twitter">
						</a>
						<a href="mailto:support@monetiscope.com" title="Support Email">
							<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/email2.png' ); ?>" alt="Email">
						</a>
					</div>
				</div>
			</aside>
		</div>
	</form>
</div>

<!-- Zapier Chatbot widget inside admin setting page -->
<zapier-interfaces-chatbot-embed is-popup='true' chatbot-id='cmc8tco1i00178eenqd9m351r'></zapier-interfaces-chatbot-embed>
