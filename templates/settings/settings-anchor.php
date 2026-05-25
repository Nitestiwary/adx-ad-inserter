<?php
/**
 * Anchor Ad Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="tab-anchor" class="adx-tab" style="display:none">
	<h3>Anchor Ad</h3>

	<p>
		<input type="hidden" name="adxbyms_anchor_enabled" value="false" />
		<label>
			<input type="checkbox" id="adxbyms_anchor_enabled" name="adxbyms_anchor_enabled" value="true" <?php checked( get_option( 'adxbyms_anchor_enabled' ), 'true' ); ?> />
			Enable Anchor Ad
		</label>
	</p>

	<p>
		<label for="adxbyms_anchor_position"><strong>Anchor Position</strong></label><br>
		<select id="adxbyms_anchor_position" name="adxbyms_anchor_position">
			<option value="TOP_ANCHOR" <?php selected( get_option( 'adxbyms_anchor_position', 'TOP_ANCHOR' ), 'TOP_ANCHOR' ); ?>>Top Anchor</option>
			<option value="BOTTOM_ANCHOR" <?php selected( get_option( 'adxbyms_anchor_position', 'TOP_ANCHOR' ), 'BOTTOM_ANCHOR' ); ?>>Bottom Anchor</option>
		</select>
	</p>

	<p>
		<label for="adxbyms_anchor_network_code"><strong>Anchor Ad Slot</strong></label><br>
		<input type="text" id="adxbyms_anchor_network_code" name="adxbyms_anchor_network_code" value="<?php echo esc_attr( get_option( 'adxbyms_anchor_network_code' ) ); ?>" class="regular-text" />
	</p>
</div>
