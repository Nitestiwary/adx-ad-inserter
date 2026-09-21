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

	<div style="margin-top: 15px;">
		<label style="font-weight:700; margin-bottom:8px; display:block;">
			<?php esc_html_e( 'Target Pages', 'adx-ad-inserter' ); ?>
		</label>
		<div class="form-grid" style="margin: 0; background:#fff;">
			<?php
			$anchor_pages = (array) get_option( 'adxbyms_anchor_pages', array() );
			$page_types   = array(
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
					<input type="checkbox" name="adxbyms_anchor_pages[]" value="<?php echo esc_attr( $val ); ?>" <?php checked( in_array( $val, $anchor_pages, true ), true ); ?> />
					<?php echo esc_html( $lbl ); ?>
				</label>
			<?php endforeach; ?>
		</div>
		<span class="description" style="font-size:0.8rem; color:#666; display:inline-block; margin-top:6px;">
			<?php esc_html_e( 'Leave all unchecked to show on all pages.', 'adx-ad-inserter' ); ?>
		</span>
	</div>
</div>
