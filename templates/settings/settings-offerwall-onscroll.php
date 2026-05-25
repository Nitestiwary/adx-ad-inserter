<?php
/**
 * Offerwall On-Scroll Ad Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="tab-offerwall-onscroll" class="adx-tab" style="display:none">
	<h3>Offerwall Ad</h3>

	<p>
		<input type="hidden" name="adxbyms_offerwall_onscroll_enabled" value="false" />
		<label>
			<input type="checkbox" id="adxbyms_offerwall_onscroll_enabled" name="adxbyms_offerwall_onscroll_enabled" value="true" <?php checked( get_option( 'adxbyms_offerwall_onscroll_enabled' ), 'true' ); ?> />
			Enable Offerwall Ad
		</label>
	</p>

	<p>
		<label for="adxbyms_offerwall_onscroll_network_code">
			<strong>Ad Slot</strong>
			<span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; vertical-align: middle; margin-left: 5px; color: #2271b1; cursor: help;" title="Ex: 12345678/example"></span>
		</label><br>
		<input type="text" id="adxbyms_offerwall_onscroll_network_code" name="adxbyms_offerwall_onscroll_network_code" value="<?php echo esc_attr( get_option( 'adxbyms_offerwall_onscroll_network_code' ) ); ?>" class="regular-text" placeholder="/22859853152/MS_024JOBS_Scroll_Offerwall" />
	</p>

	<p>
		<label for="adxbyms_offerwall_onscroll_logo_url"><strong>Publisher Logo URL</strong></label><br>
		<input type="text" id="adxbyms_offerwall_onscroll_logo_url" name="adxbyms_offerwall_onscroll_logo_url" value="<?php echo esc_attr( get_option( 'adxbyms_offerwall_onscroll_logo_url' ) ); ?>" class="regular-text" placeholder="https://monetiscope.com/wp-content/uploads/2025/05/cropped-e-2.png" />
		<br><span class="description">
			If left empty, a default Monetiscope logo will be used.
		</span>
	</p>

	<p>
		<!-- Branding removed (no frontend credit links). -->
	</p>
</div>