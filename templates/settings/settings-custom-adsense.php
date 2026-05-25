<?php
/**
 * Adsense / Custom Ads Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="tab-custom-adsense" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Adsense Ads / Custom', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<label style="font-weight: 700; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
			<input type="hidden" name="adxbyms_custom_adsense_enabled" value="false" />
			<input type="checkbox" id="adxbyms_custom_adsense_enabled" name="adxbyms_custom_adsense_enabled" value="true" <?php checked( get_option( 'adxbyms_custom_adsense_enabled' ), 'true' ); ?> />
			<?php esc_html_e( 'Enable Adsense / Custom Ads Subsystem', 'adx-ad-inserter' ); ?>
		</label>
		<span class="help-text" style="margin-left: 24px;"><?php esc_html_e( 'Enable or disable all Adsense / Custom script blocks globally.', 'adx-ad-inserter' ); ?></span>
	</div>

	<!-- Collapsible Card Blocks List -->
	<div class="custom-adsense-blocks">
		<?php
		for ( $i = 1; $i <= 10; $i++ ) :
			$enabled    = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
			$code       = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
			$insertion  = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", 'before_content' );
			$offset     = absint( get_option( "adxbyms_custom_adsense_block_{$i}_offset", 1 ) );
			$alignment  = get_option( "adxbyms_custom_adsense_block_{$i}_alignment", 'center' );
			$devices    = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array( 'all' ) );
			$pages      = (array) get_option( "adxbyms_custom_adsense_block_{$i}_pages", array() );

			$card_badge = $enabled ? 'badge-active' : 'badge-inactive';
			$badge_label = $enabled ? __( 'Active', 'adx-ad-inserter' ) : __( 'Disabled', 'adx-ad-inserter' );
			?>
			<div class="collapsible-card">
				<div class="card-header">
					<div style="display:flex; align-items:center; gap:12px;">
						<span class="card-arrow">▶</span>
						<h3><?php printf( esc_html__( 'Custom Ad Block %d', 'adx-ad-inserter' ), (int) $i ); ?></h3>
					</div>
					<span class="card-badge <?php echo esc_attr( $card_badge ); ?>"><?php echo esc_html( $badge_label ); ?></span>
				</div>

				<div class="card-body">
					<!-- Active Toggle inside card -->
					<div style="margin-bottom:16px;">
						<label style="font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
							<input type="hidden" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_enabled" value="false" />
							<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_enabled" value="true" <?php checked( $enabled, true ); ?> />
							<?php esc_html_e( 'Activate this custom block', 'adx-ad-inserter' ); ?>
						</label>
					</div>

					<!-- Raw Code Textarea -->
					<div style="margin-bottom:20px;">
						<label for="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_code" style="display:block; font-weight:700; margin-bottom:6px;">
							<?php esc_html_e( 'HTML / JS Ad Code Script Textarea', 'adx-ad-inserter' ); ?>
						</label>
						<textarea name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_code" id="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_code" class="ad-textarea"><?php echo esc_textarea( wp_unslash( $code ) ); ?></textarea>
						<span class="help-text"><?php esc_html_e( 'Paste your Google AdSense code, custom script tags, or basic HTML code here.', 'adx-ad-inserter' ); ?></span>
					</div>

					<!-- Insertion Settings Row -->
					<div class="flex-row-fields">
						<div>
							<label for="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_insertion"><?php esc_html_e( 'Placement Insertion', 'adx-ad-inserter' ); ?></label>
							<select name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_insertion" id="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_insertion">
								<option value="disabled" <?php selected( $insertion, 'disabled' ); ?>><?php esc_html_e( 'Disabled', 'adx-ad-inserter' ); ?></option>
								<option value="before_post" <?php selected( $insertion, 'before_post' ); ?>><?php esc_html_e( 'Before post', 'adx-ad-inserter' ); ?></option>
								<option value="before_content" <?php selected( $insertion, 'before_content' ); ?>><?php esc_html_e( 'Before content', 'adx-ad-inserter' ); ?></option>
								<option value="before_paragraph" <?php selected( $insertion, 'before_paragraph' ); ?>><?php esc_html_e( 'Before paragraph', 'adx-ad-inserter' ); ?></option>
								<option value="after_paragraph" <?php selected( $insertion, 'after_paragraph' ); ?>><?php esc_html_e( 'After paragraph', 'adx-ad-inserter' ); ?></option>
								<option value="before_image" <?php selected( $insertion, 'before_image' ); ?>><?php esc_html_e( 'Before image', 'adx-ad-inserter' ); ?></option>
								<option value="after_image" <?php selected( $insertion, 'after_image' ); ?>><?php esc_html_e( 'After image', 'adx-ad-inserter' ); ?></option>
								<option value="after_content" <?php selected( $insertion, 'after_content' ); ?>><?php esc_html_e( 'After content', 'adx-ad-inserter' ); ?></option>
								<option value="after_post" <?php selected( $insertion, 'after_post' ); ?>><?php esc_html_e( 'After post', 'adx-ad-inserter' ); ?></option>
								<option value="before_excerpt" <?php selected( $insertion, 'before_excerpt' ); ?>><?php esc_html_e( 'Before excerpt', 'adx-ad-inserter' ); ?></option>
								<option value="after_excerpt" <?php selected( $insertion, 'after_excerpt' ); ?>><?php esc_html_e( 'After excerpt', 'adx-ad-inserter' ); ?></option>
								<option value="between_posts" <?php selected( $insertion, 'between_posts' ); ?>><?php esc_html_e( 'Between posts', 'adx-ad-inserter' ); ?></option>
								<option value="before_comments" <?php selected( $insertion, 'before_comments' ); ?>><?php esc_html_e( 'Before comments', 'adx-ad-inserter' ); ?></option>
								<option value="between_comments" <?php selected( $insertion, 'between_comments' ); ?>><?php esc_html_e( 'Between comments', 'adx-ad-inserter' ); ?></option>
								<option value="after_comments" <?php selected( $insertion, 'after_comments' ); ?>><?php esc_html_e( 'After comments', 'adx-ad-inserter' ); ?></option>
								<option value="footer" <?php selected( $insertion, 'footer' ); ?>><?php esc_html_e( 'Footer', 'adx-ad-inserter' ); ?></option>
								<option value="before_html" <?php selected( $insertion, 'before_html' ); ?>><?php esc_html_e( 'Before HTML element', 'adx-ad-inserter' ); ?></option>
								<option value="inside_html" <?php selected( $insertion, 'inside_html' ); ?>><?php esc_html_e( 'Inside HTML element', 'adx-ad-inserter' ); ?></option>
								<option value="after_html" <?php selected( $insertion, 'after_html' ); ?>><?php esc_html_e( 'After HTML element', 'adx-ad-inserter' ); ?></option>
							</select>
						</div>

						<div class="offset-wrapper">
							<label for="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_offset"><?php esc_html_e( 'Index (X)', 'adx-ad-inserter' ); ?></label>
							<input type="text" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_offset" id="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_offset" value="<?php echo esc_attr( $offset ); ?>">
							<span class="help-text" style="font-size: 0.72rem; margin-top:2px; line-height:1.2;"><?php esc_html_e( 'Position offset (e.g. Xth paragraph)', 'adx-ad-inserter' ); ?></span>
						</div>

						<div>
							<label for="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_alignment"><?php esc_html_e( 'Alignment Mode', 'adx-ad-inserter' ); ?></label>
							<select name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_alignment" id="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_alignment">
								<option value="left" <?php selected( $alignment, 'left' ); ?>><?php esc_html_e( 'Left Align', 'adx-ad-inserter' ); ?></option>
								<option value="center" <?php selected( $alignment, 'center' ); ?>><?php esc_html_e( 'Centered', 'adx-ad-inserter' ); ?></option>
								<option value="right" <?php selected( $alignment, 'right' ); ?>><?php esc_html_e( 'Right Align', 'adx-ad-inserter' ); ?></option>
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
										<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_pages[]" value="<?php echo esc_attr( $val ); ?>" <?php checked( in_array( $val, $pages, true ), true ); ?> />
										<?php echo esc_html( $lbl ); ?>
									</label>
								<?php endforeach; ?>
							</div>
						</div>

						<div style="flex: 1;">
							<label style="font-weight:700; margin-bottom:8px;"><?php esc_html_e( 'Target Screen Size / Device Type', 'adx-ad-inserter' ); ?></label>
							<div class="form-grid" style="margin: 0; background:#fff; grid-template-columns: 1fr;">
								<label>
									<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_devices[]" value="all" <?php checked( in_array( 'all', $devices, true ), true ); ?> />
									<?php esc_html_e( 'All Devices (Responsive)', 'adx-ad-inserter' ); ?>
								</label>
								<label>
									<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_devices[]" value="desktop" <?php checked( in_array( 'desktop', $devices, true ), true ); ?> />
									<?php esc_html_e( 'Desktop Only', 'adx-ad-inserter' ); ?>
								</label>
								<label>
									<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_devices[]" value="tablet" <?php checked( in_array( 'tablet', $devices, true ), true ); ?> />
									<?php esc_html_e( 'Tablet Devices', 'adx-ad-inserter' ); ?>
								</label>
								<label>
									<input type="checkbox" name="adxbyms_custom_adsense_block_<?php echo esc_attr( $i ); ?>_devices[]" value="mobile" <?php checked( in_array( 'mobile', $devices, true ), true ); ?> />
									<?php esc_html_e( 'Mobile Phones', 'adx-ad-inserter' ); ?>
								</label>
							</div>
						</div>
					</div>

					<!-- Shortcode Code Display -->
					<div style="margin-top:20px; background:#f8fafc; padding:12px; border-radius:6px; border:1px solid var(--adx-border);">
						<span style="font-weight: 600; font-size: 0.85rem; color: var(--adx-text-muted);">
							<?php esc_html_e( 'Shortcode placement code:', 'adx-ad-inserter' ); ?>
							<code style="background:#fff; padding:3px 6px; border:1px solid #cbd5e1; border-radius:4px; color:#c084fc; font-family:monospace; margin-left:8px;">[ms_custom_ad id="<?php echo esc_attr( $i ); ?>"]</code>
						</span>
					</div>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>
