<?php
/**
 * Custom Header/Footer Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$adxbyms_header_code = get_option( 'adxbyms_header_code', '' );
$adxbyms_footer_code = get_option( 'adxbyms_footer_code', '' );
?>

<div id="tab-header-footer" class="adx-tab" style="display:none;">
	<h3><?php echo esc_html( __( 'Header/Footer Code', 'adx-ad-inserter' ) ); ?></h3>

	<!-- Enable Toggle -->
	<p>
		<label>
			<input type="hidden" name="adxbyms_custom_enabled" value="false" />
			<input type="checkbox" id="adxbyms_custom_enabled" name="adxbyms_custom_enabled" value="true" <?php checked( get_option( 'adxbyms_custom_enabled' ), 'true' ); ?> />
			<?php echo esc_html( __( 'Enable Header/Footer Code', 'adx-ad-inserter' ) ); ?>
		</label>
	</p>

	<div class="custom-code-wrapper">
		<div class="custom-tab-buttons">
			<button type="button" class="custom-tab-toggle active" data-tab="header"><?php echo esc_html( __( 'Header', 'adx-ad-inserter' ) ); ?></button>
			<button type="button" class="custom-tab-toggle" data-tab="footer"><?php echo esc_html( __( 'Footer', 'adx-ad-inserter' ) ); ?></button>
		</div>
		<div class="custom-tab-content" id="custom-code-header">
			<label for="adxbyms_header_code"><strong><?php echo esc_html( __( 'Header Code (within &lt;head&gt;)', 'adx-ad-inserter' ) ); ?></strong></label><br>
			<textarea name="adxbyms_header_code" id="adxbyms_header_code"><?php echo esc_textarea( $adxbyms_header_code ); ?></textarea>
		</div>
		<div class="custom-tab-content" id="custom-code-footer" style="display:none;">
			<label for="adxbyms_footer_code"><strong><?php echo esc_html( __( 'Footer Code (before &lt;/body&gt;)', 'adx-ad-inserter' ) ); ?></strong></label><br>
			<textarea name="adxbyms_footer_code" id="adxbyms_footer_code"><?php echo esc_textarea( $adxbyms_footer_code ); ?></textarea>
		</div>
	</div>
</div>
