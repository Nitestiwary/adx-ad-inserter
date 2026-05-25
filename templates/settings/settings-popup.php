<?php
/**
 * Popup Ads Settings Template (Updated)
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$enabled          = ( 'true' === get_option( 'adxbyms_popup_enabled', 'false' ) );
$code             = get_option( 'adxbyms_popup_network_code', '' );
$scroll_trigger   = absint( get_option( 'adxbyms_popup_scroll_trigger', 60 ) );
$frequency        = get_option( 'adxbyms_popup_frequency', 'session' ); // 'session' | '24h'
$target_devices   = get_option( 'adxbyms_popup_devices', 'all' ); // 'all' | 'desktop' | 'mobile'
$target_pages     = (array) get_option( 'adxbyms_popup_pages', array( 'all' ) ); // 'all', 'homepage', 'post', 'category'
?>

<div id="tab-popup-updated" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Popup Ads', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight: 700; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
			<input type="hidden" name="adxbyms_popup_enabled" value="false" />
			<input type="checkbox" id="adxbyms_popup_enabled" name="adxbyms_popup_enabled" value="true" <?php checked( $enabled, true ); ?> />
			<?php esc_html_e( 'Enable Popup Ads Subsystem', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'Trigger premium floating overlay ads upon scroll depth filters.', 'adx-ad-inserter' ); ?></span>
	</div>

	<div style="border: 1px solid var(--adx-border); border-radius: 8px; padding: 24px; background: #f8fafc;">
		
		<!-- Ad Slot -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_popup_network_code" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Google Ad Manager Popup Slot Path', 'adx-ad-inserter' ); ?>
			</label>
			<input type="text" name="adxbyms_popup_network_code" id="adxbyms_popup_network_code" value="<?php echo esc_attr( $code ); ?>" placeholder="/23118073583/MS_Steppa_300x250_5">
		</div>

		<!-- Scroll Trigger Depth -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_popup_scroll_trigger" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Scroll Trigger Percentage (60% - 100%)', 'adx-ad-inserter' ); ?>
			</label>
			<input type="number" name="adxbyms_popup_scroll_trigger" id="adxbyms_popup_scroll_trigger" value="<?php echo esc_attr( $scroll_trigger ); ?>" min="60" max="100" style="max-width: 150px;">
			<span class="help-text"><?php esc_html_e( 'Specify the scroll threshold down the viewport at which the popup will dynamically fade in.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Rate Limiting (Feature 4 Update: 24h option added) -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_popup_frequency" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Frequency capping', 'adx-ad-inserter' ); ?>
			</label>
			<select name="adxbyms_popup_frequency" id="adxbyms_popup_frequency" style="max-width: 300px;">
				<option value="session" <?php selected( $frequency, 'session' ); ?>><?php esc_html_e( 'Show ad once per session', 'adx-ad-inserter' ); ?></option>
				<option value="24h" <?php selected( $frequency, '24h' ); ?>><?php esc_html_e( 'Show ad one time in 24 hours', 'adx-ad-inserter' ); ?></option>
			</select>
			<span class="help-text"><?php esc_html_e( 'Limits how often the popup emerges for individual visitors using client sessionStorage / localStorage cookies.', 'adx-ad-inserter' ); ?></span>
		</div>

		<!-- Device Targeting dropdown -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_popup_devices" style="display:block; font-weight:700; margin-bottom:6px;">
				<?php esc_html_e( 'Target Screen Sizing', 'adx-ad-inserter' ); ?>
			</label>
			<select name="adxbyms_popup_devices" id="adxbyms_popup_devices" style="max-width: 300px;">
				<option value="all" <?php selected( $target_devices, 'all' ); ?>><?php esc_html_e( 'All devices', 'adx-ad-inserter' ); ?></option>
				<option value="desktop" <?php selected( $target_devices, 'desktop' ); ?>><?php esc_html_e( 'Desktop only', 'adx-ad-inserter' ); ?></option>
				<option value="mobile" <?php selected( $target_devices, 'mobile' ); ?>><?php esc_html_e( 'Mobile only', 'adx-ad-inserter' ); ?></option>
			</select>
		</div>

		<!-- Pages Targeting Exception checkboxes -->
		<div style="margin-bottom: 20px;">
			<label style="display:block; font-weight:700; margin-bottom:8px;"><?php esc_html_e( 'Page / Post Context Targeting', 'adx-ad-inserter' ); ?></label>
			<div class="form-grid" style="background:#fff; margin:0;">
				<label>
					<input type="checkbox" name="adxbyms_popup_pages[]" value="all" <?php checked( in_array( 'all', $target_pages, true ), true ); ?> />
					<?php esc_html_e( 'Entire Website', 'adx-ad-inserter' ); ?>
				</label>
				<label>
					<input type="checkbox" name="adxbyms_popup_pages[]" value="homepage" <?php checked( in_array( 'homepage', $target_pages, true ), true ); ?> />
					<?php esc_html_e( 'Homepage Only', 'adx-ad-inserter' ); ?>
				</label>
				<label>
					<input type="checkbox" name="adxbyms_popup_pages[]" value="post" <?php checked( in_array( 'post', $target_pages, true ), true ); ?> />
					<?php esc_html_e( 'Single Post Only', 'adx-ad-inserter' ); ?>
				</label>
				<label>
					<input type="checkbox" name="adxbyms_popup_pages[]" value="category" <?php checked( in_array( 'category', $target_pages, true ), true ); ?> />
					<?php esc_html_e( 'Category Pages', 'adx-ad-inserter' ); ?>
				</label>
			</div>
			<span class="help-text"><?php esc_html_e( 'If "Entire Website" is selected, it takes precedence. Otherwise, the popup triggers only on checked context layouts.', 'adx-ad-inserter' ); ?></span>
		</div>

	</div>
</div>