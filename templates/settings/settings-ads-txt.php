<?php
/**
 * Ads.txt Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$adxbyms_ads_txt_code = get_option( 'adxbyms_ads_txt_code', '' );
?>

<div id="tab-ads-txt" class="adx-tab" style="display:none;">
	<h3><?php echo esc_html( __( 'Ads.txt', 'adx-ad-inserter' ) ); ?></h3>

	<!-- Enable Toggle -->
	<p>
		<label>
			<input type="hidden" name="adxbyms_ads_txt_enabled" value="false" />
			<input type="checkbox" id="adxbyms_ads_txt_enabled" name="adxbyms_ads_txt_enabled" value="true" <?php checked( get_option( 'adxbyms_ads_txt_enabled' ), 'true' ); ?> />
			<?php echo esc_html( __( 'Enable Ads Txt', 'adx-ad-inserter' ) ); ?>
		</label>
	</p>

	<div class="ads-txt-content" id="ads-txt-code">
		<label for="adxbyms_ads_txt_code"><strong><?php echo esc_html( __( 'ads.txt', 'adx-ad-inserter' ) ); ?></strong></label><br>
		<textarea rows="20" name="adxbyms_ads_txt_code" id="adxbyms_ads_txt_code"><?php echo esc_textarea( $adxbyms_ads_txt_code ); ?></textarea>
	</div>
</div>
