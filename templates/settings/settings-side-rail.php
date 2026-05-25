<?php
/**
 * Side Rail Ads Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$enabled          = ( 'true' === get_option( 'adxbyms_side_rail_enabled', 'false' ) );
$network_code     = get_option( 'adxbyms_side_rail_network_code', '' );
$refresh_enabled  = ( 'true' === get_option( 'adxbyms_side_rail_refresh_enabled', 'false' ) );
$refresh_interval = absint( get_option( 'adxbyms_side_rail_refresh_interval', 30 ) );
if ( $refresh_interval < 30 ) {
	$refresh_interval = 30; // Minimum 30s
}
?>

<div id="tab-side-rail" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Side Rail Ads (Desktop)', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight: 700; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
			<input type="hidden" name="adxbyms_side_rail_enabled" value="false" />
			<input type="checkbox" id="adxbyms_side_rail_enabled" name="adxbyms_side_rail_enabled" value="true" <?php checked( $enabled, true ); ?> />
			<?php esc_html_e( 'Enable Side Rail Ads', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'Desktop-only rails. Automatically creates matching left and right sticky columns on screens wider than 1200px.', 'adx-ad-inserter' ); ?></span>
	</div>

	<div style="border: 1px solid var(--adx-border); border-radius: 8px; padding: 24px; background: #f8fafc;">
		
		<!-- Ad Slot Path -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_side_rail_network_code" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Google Ad Manager Side Rail Slot Path', 'adx-ad-inserter' ); ?>
			</label>
			<input type="text" name="adxbyms_side_rail_network_code" id="adxbyms_side_rail_network_code" value="<?php echo esc_attr( $network_code ); ?>" placeholder="/23118073583/MS_Steppa_SideRails">
			<span class="help-text"><?php esc_html_e( 'Enter a single slot path. The plugin automatically generates corresponding left and right target containers and divides sizes correctly.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Refresh toggle -->
		<div style="margin-bottom: 20px;">
			<label style="font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-bottom:6px;">
				<input type="hidden" name="adxbyms_side_rail_refresh_enabled" value="false" />
				<input type="checkbox" id="adxbyms_side_rail_refresh_enabled" name="adxbyms_side_rail_refresh_enabled" value="true" <?php checked( $refresh_enabled, true ); ?> />
				<?php esc_html_e( 'Enable Viewability-Based Auto-Refresh', 'adx-ad-inserter' ); ?>
			</label>
			<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'If enabled, side rail slots will automatically refresh dynamically only when currently active in the visitor\'s viewport.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Refresh Interval -->
		<div style="margin-bottom: 20px; <?php echo $refresh_enabled ? '' : 'display:none;'; ?>" id="side-rail-interval-wrapper">
			<label for="adxbyms_side_rail_refresh_interval" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Refresh Interval (Minimum 30 seconds)', 'adx-ad-inserter' ); ?>
			</label>
			<input type="number" name="adxbyms_side_rail_refresh_interval" id="adxbyms_side_rail_refresh_interval" value="<?php echo esc_attr( $refresh_interval ); ?>" min="30" style="max-width: 150px;">
			<span class="help-text"><?php esc_html_e( 'Auto-refresh rate in seconds. Must be at least 30s in compliance with Google AdX / GAM policies.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Shortcode Display -->
		<div style="margin-top:20px; background:#fff; padding:12px; border-radius:6px; border:1px solid var(--adx-border);">
			<span style="font-weight: 600; font-size: 0.85rem; color: var(--adx-text-muted);">
				<?php esc_html_e( 'Shortcode placement code:', 'adx-ad-inserter' ); ?>
				<code style="background:#f1f5f9; padding:3px 6px; border-radius:4px; color:#c084fc; font-family:monospace; margin-left:8px;">[ms_side_rail]</code>
			</span>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Inline helper for side rail settings refresh fields
	document.addEventListener('DOMContentLoaded', function() {
		const refreshCheckbox = document.getElementById('adxbyms_side_rail_refresh_enabled');
		const intervalWrapper = document.getElementById('side-rail-interval-wrapper');
		if(refreshCheckbox && intervalWrapper) {
			refreshCheckbox.addEventListener('change', function() {
				intervalWrapper.style.display = this.checked ? 'block' : 'none';
			});
		}
	});
</script>
