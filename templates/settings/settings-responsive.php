<?php
/**
 * Responsive Ads Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="tab-responsive-ads" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Responsive Ads (GAM)', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight: 700; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
			<input type="hidden" name="adxbyms_responsive_ads_enabled" value="false" />
			<input type="checkbox" id="adxbyms_responsive_ads_enabled" name="adxbyms_responsive_ads_enabled" value="true" <?php checked( get_option( 'adxbyms_responsive_ads_enabled' ), 'true' ); ?> />
			<?php esc_html_e( 'Enable Responsive Ads Subsystem', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'Auto-generates Google Ad Manager size mapping: 728x90 on Desktop and 300x250 on Mobile devices.', 'adx-ad-inserter' ); ?></span>
	</div>

	<!-- Sub-tab Selector Horizontal Links -->
	<div class="display-tabs">
		<?php
		for ( $i = 1; $i <= 5; $i++ ) :
			$sub_enabled = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
			$sub_code    = trim( (string) get_option( "adxbyms_responsive_block_{$i}_network_code", '' ) );
			
			$badge_class = 'tab-grey';
			if ( ! empty( $sub_code ) ) {
				$badge_class = $sub_enabled ? 'tab-green' : 'tab-red';
			}
			?>
			<div class="responsive-tab <?php echo esc_attr( $badge_class ); ?>" style="padding: 10px 18px;">
				<?php printf( esc_html__( 'Responsive Ad %d', 'adx-ad-inserter' ), (int) $i ); ?>
			</div>
		<?php endfor; ?>
	</div>

	<!-- Tab Panels Content -->
	<div class="responsive-tab-contents">
		<?php
		for ( $i = 1; $i <= 5; $i++ ) :
			$enabled    = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
			$code       = get_option( "adxbyms_responsive_block_{$i}_network_code", '' );
			$insertion  = get_option( "adxbyms_responsive_block_{$i}_insertion", 'before_content' );
			$offset     = absint( get_option( "adxbyms_responsive_block_{$i}_offset", 1 ) );
			$alignment  = get_option( "adxbyms_responsive_block_{$i}_alignment", 'center' );
			$devices    = (array) get_option( "adxbyms_responsive_block_{$i}_devices", array( 'desktop', 'mobile' ) );
			?>
			<div class="responsive-content">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
					<h3 style="margin:0 !important; font-size:1.2rem; font-weight:700;">
						<?php printf( esc_html__( 'Responsive Ad Block %d Options', 'adx-ad-inserter' ), (int) $i ); ?>
					</h3>
					
					<label style="font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
						<input type="hidden" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_enabled" value="false" />
						<input type="checkbox" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_enabled" value="true" <?php checked( $enabled, true ); ?> />
						<?php esc_html_e( 'Block Active', 'adx-ad-inserter' ); ?>
					</label>
				</div>

				<!-- Ad Slot Path -->
				<div style="margin-bottom: 20px;">
					<label for="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_network_code" style="display:block; font-weight:700; margin-bottom:6px;">
						<?php esc_html_e( 'Ad Slot Path (e.g. /125263783/Res)', 'adx-ad-inserter' ); ?>
					</label>
					<input type="text" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_network_code" id="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_network_code" value="<?php echo esc_attr( $code ); ?>" placeholder="/125263783/Res">
				</div>

				<!-- Placement Controls Row -->
				<div class="flex-row-fields">
					<div>
						<label for="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_insertion"><?php esc_html_e( 'Insertion Target', 'adx-ad-inserter' ); ?></label>
						<select name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_insertion" id="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_insertion">
							<option value="before_content" <?php selected( $insertion, 'before_content' ); ?>><?php esc_html_e( 'Before Content', 'adx-ad-inserter' ); ?></option>
							<option value="after_content" <?php selected( $insertion, 'after_content' ); ?>><?php esc_html_e( 'After Content', 'adx-ad-inserter' ); ?></option>
							<option value="before_paragraph" <?php selected( $insertion, 'before_paragraph' ); ?>><?php esc_html_e( 'Before Paragraph X', 'adx-ad-inserter' ); ?></option>
							<option value="after_paragraph" <?php selected( $insertion, 'after_paragraph' ); ?>><?php esc_html_e( 'After Paragraph X', 'adx-ad-inserter' ); ?></option>
							<option value="before_image" <?php selected( $insertion, 'before_image' ); ?>><?php esc_html_e( 'Before Image X', 'adx-ad-inserter' ); ?></option>
							<option value="after_image" <?php selected( $insertion, 'after_image' ); ?>><?php esc_html_e( 'After Image X', 'adx-ad-inserter' ); ?></option>
							<option value="before_heading" <?php selected( $insertion, 'before_heading' ); ?>><?php esc_html_e( 'Before Heading X', 'adx-ad-inserter' ); ?></option>
							<option value="manual" <?php selected( $insertion, 'manual' ); ?>><?php esc_html_e( 'Manual Shortcode Only', 'adx-ad-inserter' ); ?></option>
						</select>
					</div>

					<div class="offset-wrapper">
						<label for="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_offset"><?php esc_html_e( 'Index (X)', 'adx-ad-inserter' ); ?></label>
						<input type="number" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_offset" id="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_offset" value="<?php echo esc_attr( $offset ); ?>" min="1" max="50">
						<span class="help-text" style="font-size: 0.72rem; margin-top:2px; line-height:1.2;"><?php esc_html_e( 'Position offset (e.g. Xth paragraph)', 'adx-ad-inserter' ); ?></span>
					</div>

					<div>
						<label for="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_alignment"><?php esc_html_e( 'Alignment Style', 'adx-ad-inserter' ); ?></label>
						<select name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_alignment" id="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_alignment">
							<option value="left" <?php selected( $alignment, 'left' ); ?>><?php esc_html_e( 'Left Align', 'adx-ad-inserter' ); ?></option>
							<option value="center" <?php selected( $alignment, 'center' ); ?>><?php esc_html_e( 'Centered', 'adx-ad-inserter' ); ?></option>
							<option value="right" <?php selected( $alignment, 'right' ); ?>><?php esc_html_e( 'Right Align', 'adx-ad-inserter' ); ?></option>
						</select>
					</div>
				</div>

				<!-- Device targeting -->
				<div style="margin-top: 20px;">
					<label style="font-weight:700; margin-bottom:8px; display:block;"><?php esc_html_e( 'Device Target', 'adx-ad-inserter' ); ?></label>
					<div class="form-grid" style="margin: 0; background:#fff;">
						<label>
							<input type="checkbox" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_devices[]" value="desktop" <?php checked( in_array( 'desktop', $devices, true ), true ); ?> />
							<?php esc_html_e( 'Desktop (Auto loads 728x90)', 'adx-ad-inserter' ); ?>
						</label>
						<label>
							<input type="checkbox" name="adxbyms_responsive_block_<?php echo esc_attr( $i ); ?>_devices[]" value="mobile" <?php checked( in_array( 'mobile', $devices, true ), true ); ?> />
							<?php esc_html_e( 'Mobile / Tablet (Auto loads 300x250)', 'adx-ad-inserter' ); ?>
						</label>
					</div>
				</div>

				<!-- Shortcode Display -->
				<div style="margin-top:20px; background:#fff; padding:12px; border-radius:6px; border:1px solid var(--adx-border);">
					<span style="font-weight: 600; font-size: 0.85rem; color: var(--adx-text-muted);">
						<?php esc_html_e( 'Shortcode placement code:', 'adx-ad-inserter' ); ?>
						<code style="background:#f1f5f9; padding:3px 6px; border-radius:4px; color:#c084fc; font-family:monospace; margin-left:8px;">[ms_responsive_ad id="<?php echo esc_attr( $i ); ?>"]</code>
					</span>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>
