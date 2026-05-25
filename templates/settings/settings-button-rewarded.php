<?php
/**
 * Button Rewarded Ad Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="tab-button-rewarded" class="adx-tab" style="display:none">
	<h3>Button Rewarded Ad</h3>

	<!--     <p>
		<input type="hidden" name="adxbyms_ad2_enabled" value="false" />
		<label>
			<input type="checkbox"
					id="adxbyms_ad2_enabled"
					name="adxbyms_ad2_enabled"
					value="true" <?php checked( get_option( 'adxbyms_ad2_enabled' ), 'true' ); ?> />
			Enable Button Rewarded Ad
		</label>
	</p> -->

	<!-- 🧪 Extra Test Checkbox (duplicate field) -->
	<p>
		<label>
			<input type="checkbox" id="adxbyms_ad2_enabled" name="adxbyms_ad2_enabled" value="true" <?php checked( get_option( 'adxbyms_ad2_enabled' ), 'true' ); ?> />
			Enable Button Rewarded Ad
		</label>
	</p>

	<p>
		<label for="adxbyms_ad2_network_code"><strong>Button Rewarded Ad Slot

			</strong> <span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; vertical-align: middle; margin-left: 5px; color: #2271b1; cursor: help;" title="Ex: 12345678/example"></span></label><br>
		<input type="text" id="adxbyms_ad2_network_code" name="adxbyms_ad2_network_code" value="<?php echo esc_attr( get_option( 'adxbyms_ad2_network_code' ) ); ?>" class="regular-text" />
	</p>

	<p>
		<label for="adxbyms_ad2_keywords"><strong>Trigger Keywords (comma-separated)</strong></label><br>
		<input type="text" id="adxbyms_ad2_keywords" name="adxbyms_ad2_keywords" value="<?php echo esc_attr( get_option( 'adxbyms_ad2_keywords' ) ); ?>" class="regular-text" placeholder="Click, Download, Apply now" />
		<br>
		<span class="description">
			The ad will trigger on links/buttons containing these keywords.
		</span>
	</p>

	<p>
		<!-- Branding removed (no frontend credit links). -->
	</p>
</div>