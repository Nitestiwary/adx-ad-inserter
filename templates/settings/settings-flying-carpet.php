<?php
/**
 * Flying Carpet Ads Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="tab-flying-carpet" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Flying Carpet Parallax Ads', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight: 700; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
			<input type="hidden" name="adxbyms_flying_carpet_enabled" value="false" />
			<input type="checkbox" id="adxbyms_flying_carpet_enabled" name="adxbyms_flying_carpet_enabled" value="true" <?php checked( get_option( 'adxbyms_flying_carpet_enabled' ), 'true' ); ?> />
			<?php esc_html_e( 'Enable Flying Carpet Ads Subsystem', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'Renders premium, full-screen parallax scrolling ad blocks within post context layouts, optimized for mobile viewports.', 'adx-ad-inserter' ); ?></span>
	</div>

	<!-- Sub-tab Selector Horizontal Links -->
	<div class="display-tabs">
		<?php
		for ( $i = 1; $i <= 5; $i++ ) :
			$sub_enabled = ( 'true' === get_option( "adxbyms_flying_carpet_block_{$i}_enabled" ) );
			$sub_code    = trim( (string) get_option( "adxbyms_flying_carpet_block_{$i}_network_code", '' ) );
			
			$badge_class = 'tab-grey';
			if ( ! empty( $sub_code ) ) {
				$badge_class = $sub_enabled ? 'tab-green' : 'tab-red';
			}
			?>
			<div class="flying-carpet-tab display-tab <?php echo esc_attr( $badge_class ); ?>">
				<?php printf( esc_html__( 'Block %d', 'adx-ad-inserter' ), (int) $i ); ?>
			</div>
		<?php endfor; ?>
	</div>

	<!-- Tab Panels Content -->
	<div class="flying-carpet-tab-contents">
		<?php
		for ( $i = 1; $i <= 5; $i++ ) :
			$enabled    = ( 'true' === get_option( "adxbyms_flying_carpet_block_{$i}_enabled" ) );
			$code       = get_option( "adxbyms_flying_carpet_block_{$i}_network_code", '' );
			$insertion  = get_option( "adxbyms_flying_carpet_block_{$i}_insertion", 'between_content' );
			$offset     = absint( get_option( "adxbyms_flying_carpet_block_{$i}_offset", 2 ) );
			$alignment  = get_option( "adxbyms_flying_carpet_block_{$i}_alignment", 'center' );
			$devices    = (array) get_option( "adxbyms_flying_carpet_block_{$i}_devices", array( 'all' ) );
			$pages      = (array) get_option( "adxbyms_flying_carpet_block_{$i}_pages", array() );
			?>
			<div class="flying-carpet-content display-content">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
					<h3 style="margin:0 !important; font-size:1.2rem; font-weight:700;">
						<?php printf( esc_html__( 'Flying Carpet Block %d Settings', 'adx-ad-inserter' ), (int) $i ); ?>
					</h3>
					
					<label style="font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
						<input type="hidden" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_enabled" value="false" />
						<input type="checkbox" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_enabled" value="true" <?php checked( $enabled, true ); ?> />
						<?php esc_html_e( 'Block Active', 'adx-ad-inserter' ); ?>
					</label>
				</div>

				<!-- Ad Slot Path -->
				<div style="margin-bottom: 20px;">
					<label for="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_network_code" style="display:block; font-weight:700; margin-bottom:6px;">
						<?php esc_html_e( 'Ad Slot Path (e.g. /125263783/FlyingCarpet)', 'adx-ad-inserter' ); ?>
					</label>
					<input type="text" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_network_code" id="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_network_code" value="<?php echo esc_attr( $code ); ?>" placeholder="/125263783/FlyingCarpet">
				</div>

				<!-- Placement Controls Row -->
				<div class="flex-row-fields">
					<div>
						<label for="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_insertion"><?php esc_html_e( 'Insertion Target', 'adx-ad-inserter' ); ?></label>
						<select name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_insertion" id="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_insertion">
							<option value="after_paragraph" <?php selected( $insertion, 'after_paragraph' ); ?>><?php esc_html_e( 'After paragraph X', 'adx-ad-inserter' ); ?></option>
							<option value="between_content" <?php selected( $insertion, 'between_content' ); ?>><?php esc_html_e( 'Between content (Middle)', 'adx-ad-inserter' ); ?></option>
							<option value="before_content" <?php selected( $insertion, 'before_content' ); ?>><?php esc_html_e( 'Before content', 'adx-ad-inserter' ); ?></option>
							<option value="after_content" <?php selected( $insertion, 'after_content' ); ?>><?php esc_html_e( 'After content', 'adx-ad-inserter' ); ?></option>
							<option value="manual" <?php selected( $insertion, 'manual' ); ?>><?php esc_html_e( 'Manual Shortcode Only', 'adx-ad-inserter' ); ?></option>
						</select>
					</div>

					<div class="offset-wrapper">
						<label for="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_offset"><?php esc_html_e( 'Index (X)', 'adx-ad-inserter' ); ?></label>
						<input type="number" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_offset" id="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_offset" value="<?php echo esc_attr( $offset ); ?>" min="1" max="50">
					</div>

					<div>
						<label for="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_alignment"><?php esc_html_e( 'Alignment Style', 'adx-ad-inserter' ); ?></label>
						<select name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_alignment" id="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_alignment">
							<option value="center" <?php selected( $alignment, 'center' ); ?>><?php esc_html_e( 'Centered', 'adx-ad-inserter' ); ?></option>
							<option value="full" <?php selected( $alignment, 'full' ); ?>><?php esc_html_e( 'Full Width', 'adx-ad-inserter' ); ?></option>
						</select>
					</div>
				</div>

				<!-- Target Screens & Targeting Page Types -->
				<div class="flex-row-fields" style="margin-top: 24px;">
					<div style="flex: 1.5;">
						<label style="font-weight:700; margin-bottom:8px;"><?php esc_html_e( 'Display Page Exceptions / Target Pages', 'adx-ad-inserter' ); ?></label>
						<div class="form-grid" style="margin: 0; background:#fff;">
							<?php
							$page_types = array(
								'post'     => __( 'Single Posts', 'adx-ad-inserter' ),
								'homepage' => __( 'Homepage', 'adx-ad-inserter' ),
								'category' => __( 'Category Pages', 'adx-ad-inserter' ),
								'static'   => __( 'Static Pages', 'adx-ad-inserter' ),
								'search'   => __( 'Search Results', 'adx-ad-inserter' ),
								'tag'      => __( 'Tag Archives', 'adx-ad-inserter' ),
							);
							foreach ( $page_types as $val => $lbl ) :
								?>
								<label>
									<input type="checkbox" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_pages[]" value="<?php echo esc_attr( $val ); ?>" <?php checked( in_array( $val, $pages, true ), true ); ?> />
									<?php echo esc_html( $lbl ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div style="flex: 1;">
						<label style="font-weight:700; margin-bottom:8px;"><?php esc_html_e( 'Device Target', 'adx-ad-inserter' ); ?></label>
						<div class="form-grid" style="margin: 0; background:#fff; grid-template-columns: 1fr;">
							<label>
								<input type="checkbox" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_devices[]" value="all" <?php checked( in_array( 'all', $devices, true ), true ); ?> />
								<?php esc_html_e( 'All Devices (Highly Recommended)', 'adx-ad-inserter' ); ?>
							</label>
							<label>
								<input type="checkbox" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_devices[]" value="desktop" <?php checked( in_array( 'desktop', $devices, true ), true ); ?> />
								<?php esc_html_e( 'Desktop Screens', 'adx-ad-inserter' ); ?>
							</label>
							<label>
								<input type="checkbox" name="adxbyms_flying_carpet_block_<?php echo esc_attr( $i ); ?>_devices[]" value="mobile" <?php checked( in_array( 'mobile', $devices, true ), true ); ?> />
								<?php esc_html_e( 'Mobile Devices Only', 'adx-ad-inserter' ); ?>
							</label>
						</div>
					</div>
				</div>

				<!-- Shortcode Display -->
				<div style="margin-top:20px; background:#fff; padding:12px; border-radius:6px; border:1px solid var(--adx-border);">
					<span style="font-weight: 600; font-size: 0.85rem; color: var(--adx-text-muted);">
						<?php esc_html_e( 'Shortcode placement code:', 'adx-ad-inserter' ); ?>
						<code style="background:#f1f5f9; padding:3px 6px; border-radius:4px; color:#c084fc; font-family:monospace; margin-left:8px;">[ms_flying_carpet id="<?php echo esc_attr( $i ); ?>"]</code>
					</span>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>
