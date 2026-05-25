<?php
/**
 * Side Rail Ads Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$enabled              = ( 'true' === get_option( 'adxbyms_side_rail_enabled', 'false' ) );
$left_network_code    = get_option( 'adxbyms_side_rail_left_network_code', get_option( 'adxbyms_side_rail_network_code', '' ) );
$right_network_code   = get_option( 'adxbyms_side_rail_right_network_code', get_option( 'adxbyms_side_rail_network_code', '' ) );
$refresh_enabled      = ( 'true' === get_option( 'adxbyms_side_rail_refresh_enabled', 'false' ) );
$refresh_interval     = absint( get_option( 'adxbyms_side_rail_refresh_interval', 30 ) );
if ( $refresh_interval < 30 ) {
	$refresh_interval = 30;
}
?>

<div id="tab-side-rail" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Side Rail Ads (Desktop)', 'adx-ad-inserter' ); ?></h2>

	<!-- Master Enable Toggle -->
	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight:700; font-size:1.05rem; cursor:pointer; display:flex; align-items:center; gap:8px;">
			<input type="hidden" name="adxbyms_side_rail_enabled" value="false" />
			<input type="checkbox" id="adxbyms_side_rail_enabled" name="adxbyms_side_rail_enabled" value="true" <?php checked( $enabled, true ); ?> />
			<?php esc_html_e( 'Enable Side Rail Ads', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left:24px;"><?php esc_html_e( 'Desktop-only sticky rails. Automatically creates left and right sticky columns on screens wider than 1200px.', 'adx-ad-inserter' ); ?></span>
	</div>

	<div style="border:1px solid var(--adx-border); border-radius:8px; padding:24px; background:#f8fafc;">

		<!-- Left and Right Slot Paths side by side -->
		<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">

			<!-- Left Rail -->
			<div style="background:#fff; border:1px solid var(--adx-border); border-radius:8px; padding:20px;">
				<div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
					<span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; background:var(--adx-primary-light); border-radius:8px; color:var(--adx-primary-hover);">
						<span class="dashicons dashicons-arrow-left-alt" style="font-size:16px; width:16px; height:16px;"></span>
					</span>
					<strong style="font-size:1rem; color:var(--adx-text);"><?php esc_html_e( 'Left Side Rail', 'adx-ad-inserter' ); ?></strong>
				</div>
				<label for="adxbyms_side_rail_left_network_code" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.9rem;">
					<?php esc_html_e( 'Left Rail Ad Slot Path', 'adx-ad-inserter' ); ?>
					<span class="dashicons dashicons-info" style="font-size:14px; width:14px; height:14px; vertical-align:middle; margin-left:4px; color:var(--adx-primary); cursor:help;" title="<?php esc_attr_e( 'e.g. /12345678/SiteRail_Left', 'adx-ad-inserter' ); ?>"></span>
				</label>
				<input type="text" name="adxbyms_side_rail_left_network_code" id="adxbyms_side_rail_left_network_code" value="<?php echo esc_attr( $left_network_code ); ?>" placeholder="/23118073583/MS_SiteRails_Left">
				<span class="help-text" style="font-size:0.8rem;"><?php esc_html_e( 'Full GAM slot path for the left sticky column.', 'adx-ad-inserter' ); ?></span>
			</div>

			<!-- Right Rail -->
			<div style="background:#fff; border:1px solid var(--adx-border); border-radius:8px; padding:20px;">
				<div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
					<span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; background:var(--adx-primary-light); border-radius:8px; color:var(--adx-primary-hover);">
						<span class="dashicons dashicons-arrow-right-alt" style="font-size:16px; width:16px; height:16px;"></span>
					</span>
					<strong style="font-size:1rem; color:var(--adx-text);"><?php esc_html_e( 'Right Side Rail', 'adx-ad-inserter' ); ?></strong>
				</div>
				<label for="adxbyms_side_rail_right_network_code" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.9rem;">
					<?php esc_html_e( 'Right Rail Ad Slot Path', 'adx-ad-inserter' ); ?>
					<span class="dashicons dashicons-info" style="font-size:14px; width:14px; height:14px; vertical-align:middle; margin-left:4px; color:var(--adx-primary); cursor:help;" title="<?php esc_attr_e( 'e.g. /12345678/SiteRail_Right', 'adx-ad-inserter' ); ?>"></span>
				</label>
				<input type="text" name="adxbyms_side_rail_right_network_code" id="adxbyms_side_rail_right_network_code" value="<?php echo esc_attr( $right_network_code ); ?>" placeholder="/23118073583/MS_SiteRails_Right">
				<span class="help-text" style="font-size:0.8rem;"><?php esc_html_e( 'Full GAM slot path for the right sticky column.', 'adx-ad-inserter' ); ?></span>
			</div>

		</div>

		<!-- Refresh Toggle -->
		<div style="margin-bottom:20px;">
			<label style="font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; margin-bottom:6px;">
				<input type="hidden" name="adxbyms_side_rail_refresh_enabled" value="false" />
				<input type="checkbox" id="adxbyms_side_rail_refresh_enabled" name="adxbyms_side_rail_refresh_enabled" value="true" <?php checked( $refresh_enabled, true ); ?> />
				<?php esc_html_e( 'Enable Viewability-Based Auto-Refresh', 'adx-ad-inserter' ); ?>
			</label>
			<span class="help-text" style="margin-left:24px;"><?php esc_html_e( 'Refreshes side rail slots only when currently visible in the viewport. Minimum interval enforced below.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Refresh Interval -->
		<div style="margin-bottom:20px; <?php echo $refresh_enabled ? '' : 'display:none;'; ?>" id="side-rail-interval-wrapper">
			<label for="adxbyms_side_rail_refresh_interval" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Refresh Interval (Minimum 30 seconds)', 'adx-ad-inserter' ); ?>
			</label>
			<input type="number" name="adxbyms_side_rail_refresh_interval" id="adxbyms_side_rail_refresh_interval" value="<?php echo esc_attr( $refresh_interval ); ?>" min="30" style="max-width:150px;">
			<span class="help-text"><?php esc_html_e( 'Auto-refresh rate in seconds. Must be at least 30s per Google AdX / GAM policy.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Shortcode Display -->
		<div class="shortcode-helper" style="margin-top:20px;">
			<span><?php esc_html_e( 'Shortcode placement code:', 'adx-ad-inserter' ); ?></span>
			<code>[ms_side_rail]</code>
		</div>

	</div>
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		const refreshCheckbox = document.getElementById('adxbyms_side_rail_refresh_enabled');
		const intervalWrapper = document.getElementById('side-rail-interval-wrapper');
		if (refreshCheckbox && intervalWrapper) {
			refreshCheckbox.addEventListener('change', function() {
				intervalWrapper.style.display = this.checked ? 'block' : 'none';
			});
		}
	});
</script>
