<?php
/**
 * Offerwall On-Scroll Ad Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$ow_enabled       = ( 'true' === get_option( 'adxbyms_offerwall_onscroll_enabled', 'false' ) );
$ow_network_code  = get_option( 'adxbyms_offerwall_onscroll_network_code', '' );
$ow_logo_url      = get_option( 'adxbyms_offerwall_onscroll_logo_url', '' );
$ow_trigger       = absint( get_option( 'adxbyms_offerwall_onscroll_trigger', 60 ) );
if ( $ow_trigger < 10 || $ow_trigger > 100 ) {
	$ow_trigger = 60;
}
?>

<div id="tab-offerwall" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Offerwall Ad (On-Scroll)', 'adx-ad-inserter' ); ?></h2>

	<!-- Master Enable Toggle -->
	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight:700; font-size:1.05rem; cursor:pointer; display:flex; align-items:center; gap:8px;">
			<input type="hidden" name="adxbyms_offerwall_onscroll_enabled" value="false" />
			<input type="checkbox" id="adxbyms_offerwall_onscroll_enabled" name="adxbyms_offerwall_onscroll_enabled" value="true" <?php checked( $ow_enabled, true ); ?> />
			<?php esc_html_e( 'Enable Offerwall Rewarded Ad', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left:24px;"><?php esc_html_e( 'Displays a consent bar when the visitor scrolls past the trigger depth. Visitor must engage with a rewarded ad to continue.', 'adx-ad-inserter' ); ?></span>
	</div>

	<div style="border:1px solid var(--adx-border); border-radius:8px; padding:24px; background:#f8fafc;">

		<!-- Ad Slot Path -->
		<div style="margin-bottom:20px;">
			<label for="adxbyms_offerwall_onscroll_network_code" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'GAM Rewarded Ad Slot Path', 'adx-ad-inserter' ); ?>
				<span class="dashicons dashicons-info" style="font-size:15px; width:15px; height:15px; vertical-align:middle; margin-left:5px; color:var(--adx-primary); cursor:help;" title="<?php esc_attr_e( 'The full GAM out-of-page rewarded slot path, e.g. /12345678/MyRewardedSlot', 'adx-ad-inserter' ); ?>"></span>
			</label>
			<input type="text" name="adxbyms_offerwall_onscroll_network_code" id="adxbyms_offerwall_onscroll_network_code" value="<?php echo esc_attr( $ow_network_code ); ?>" placeholder="/22859853152/MS_024JOBS_Scroll_Offerwall">
			<span class="help-text"><?php esc_html_e( 'Enter the full GAM out-of-page rewarded slot path.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Publisher Logo URL -->
		<div style="margin-bottom:20px;">
			<label for="adxbyms_offerwall_onscroll_logo_url" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Publisher Logo URL (Optional)', 'adx-ad-inserter' ); ?>
			</label>
			<input type="text" name="adxbyms_offerwall_onscroll_logo_url" id="adxbyms_offerwall_onscroll_logo_url" value="<?php echo esc_attr( $ow_logo_url ); ?>" placeholder="https://yoursite.com/logo.png">
			<span class="help-text"><?php esc_html_e( 'Your site logo shown inside the offerwall bar. Leave empty to use the default Monetiscope logo.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Scroll Trigger Depth -->
		<div style="margin-bottom:20px;">
			<label for="adxbyms_offerwall_onscroll_trigger" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Scroll Trigger Depth (%)', 'adx-ad-inserter' ); ?>
			</label>
			<input type="number" name="adxbyms_offerwall_onscroll_trigger" id="adxbyms_offerwall_onscroll_trigger" value="<?php echo esc_attr( $ow_trigger ); ?>" min="10" max="100" style="max-width:120px;">
			<span class="help-text"><?php esc_html_e( 'Percentage of the page scrolled before the offerwall consent bar appears. Default: 60%.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Info Box -->
		<div style="background:#ecfdf5; border:1px solid rgba(16,185,129,0.2); border-radius:8px; padding:14px 18px; margin-top:10px;">
			<p style="margin:0; font-size:0.88rem; color:#065f46;">
				<span class="dashicons dashicons-info" style="vertical-align:middle; margin-right:6px;"></span>
				<?php esc_html_e( 'The offerwall bar slides up when the visitor scrolls past the trigger depth. A close/skip button lets them temporarily dismiss it for the session. The rewarded ad is loaded via GAM Out-of-Page rewarded format.', 'adx-ad-inserter' ); ?>
			</p>
		</div>
	</div>
</div>