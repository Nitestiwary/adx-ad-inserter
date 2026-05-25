<?php
/**
 * Interstitial Ad Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$adxbyms_interstitial_enabled = get_option( 'adxbyms_interstitial_enabled', 'false' );
$adxbyms_interstitial_netcode = get_option( 'adxbyms_interstitial_network_code', '' );
?>
<div id="tab-interstitial" class="adx-tab" style="display:none;">
	<h3>Interstitial Ad</h3>

	<p>
	<label>
		<input type="hidden" name="adxbyms_interstitial_enabled" value="false" />
		<input type="checkbox" id="adxbyms_interstitial_enabled" name="adxbyms_interstitial_enabled" value="true" <?php checked( $adxbyms_interstitial_enabled, 'true' ); ?> />
		Enable Interstitial Slot
	</label>
	</p>

	<p>
	<label for="adxbyms_interstitial_network_code"><strong>Ad Slot</strong><span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; vertical-align: middle; margin-left: 5px; color: #2271b1; cursor: help;" title="Ex: 12345678/example"></span></label><br>
	<input type="text" id="adxbyms_interstitial_network_code" name="adxbyms_interstitial_network_code" value="<?php echo esc_attr( $adxbyms_interstitial_netcode ); ?>" class="regular-text" placeholder="/23269135876/MS_TheGorakhpur_Interstitial" />
	<br><span class="description">
		Ad unit path must start with a slash.
	</span>
	</p>
</div>
