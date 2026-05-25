<?php
/**
 * Global Settings Tab Panel (Refreshed Separately as Card Panels)
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

// Retrieve variables
$exclusions       = get_option( 'adxbyms_exclude_links', '' );
$header_code      = get_option( 'adxbyms_header_code', '' );
$footer_code      = get_option( 'adxbyms_footer_code', '' );
$custom_enabled   = ( 'true' === get_option( 'adxbyms_custom_enabled', 'false' ) );
$ads_txt_en       = ( 'true' === get_option( 'adxbyms_ads_txt_enabled', 'false' ) );
$ads_txt_val      = get_option( 'adxbyms_ads_txt_code', '' );

// Calculate badges
$excl_badge       = ! empty( trim( $exclusions ) ) ? 'badge-active' : 'badge-inactive';
$excl_label       = ! empty( trim( $exclusions ) ) ? __( 'Active', 'adx-ad-inserter' ) : __( 'Empty', 'adx-ad-inserter' );

$custom_badge     = $custom_enabled ? 'badge-active' : 'badge-inactive';
$custom_label     = $custom_enabled ? __( 'Active', 'adx-ad-inserter' ) : __( 'Disabled', 'adx-ad-inserter' );

$ads_badge        = $ads_txt_en ? 'badge-active' : 'badge-inactive';
$ads_label        = $ads_txt_en ? __( 'Active', 'adx-ad-inserter' ) : __( 'Disabled', 'adx-ad-inserter' );
?>

<div id="tab-global-settings" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Global Settings & Utilities', 'adx-ad-inserter' ); ?></h2>

	<div class="global-settings-cards">

		<!-- Card 1: Exclude Links Panel -->
		<div class="collapsible-card">
			<div class="card-header">
				<h3><span class="dashicons dashicons-dismiss" style="margin-right:10px; vertical-align:middle; color:#ef4444;"></span><?php esc_html_e( '1. Exclude Links & Paths', 'adx-ad-inserter' ); ?></h3>
				<div class="card-header-actions">
					<span class="card-badge <?php echo esc_attr( $excl_badge ); ?>"><?php echo esc_html( $excl_label ); ?></span>
					<span class="card-arrow">&rsaquo;</span>
				</div>
			</div>
			<div class="card-body">
				<p class="description" style="margin-top: 0; margin-bottom: 16px; color: var(--adx-text-muted);">
					<?php esc_html_e( 'Suppress all ad injections globally on specific URLs or path folders.', 'adx-ad-inserter' ); ?>
				</p>

				<div style="margin-bottom: 20px;">
					<label for="adxbyms_exclude_links" style="display:block; font-weight:700; margin-bottom:8px; font-size:0.9rem;">
						<?php esc_html_e( 'Excluded URLs / Paths (Comma-Separated):', 'adx-ad-inserter' ); ?>
					</label>
					<textarea name="adxbyms_exclude_links" id="adxbyms_exclude_links" style="height: 120px; font-family:monospace; font-size:0.9rem;" placeholder="https://example.com/privacy-policy/, /about-us/, /shop/checkout"><?php echo esc_textarea( $exclusions ); ?></textarea>
				</div>

				<div style="background:#f8fafc; padding:20px; border:1px solid var(--adx-border); border-radius:10px; margin-top:20px;">
					<h4 style="margin:0 0 10px 0; font-weight:700; color:var(--adx-text); font-size:0.92rem;"><?php esc_html_e( 'Rule Formats Supported:', 'adx-ad-inserter' ); ?></h4>
					<table style="width:100%; border-collapse:collapse; font-size:0.85rem; color:var(--adx-text-muted); line-height:1.6;">
						<tr style="border-bottom:1px solid #e2e8f0;">
							<td style="padding:6px 0; font-weight:600; width:30%;">Absolute URL</td>
							<td style="padding:6px 0;"><code>https://site.com/about/</code> - Performs exact page match.</td>
						</tr>
						<tr style="border-bottom:1px solid #e2e8f0;">
							<td style="padding:6px 0; font-weight:600;">Relative Path</td>
							<td style="padding:6px 0;"><code>/privacy-policy/</code> - Trailing slashes are normalized automatically.</td>
						</tr>
						<tr>
							<td style="padding:6px 0; font-weight:600;">Sub-path Folder</td>
							<td style="padding:6px 0;"><code>/checkout</code> - Automatically excludes all child paths (e.g. <code>/checkout/pay/</code>).</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<!-- Card 2: Header Custom Scripts -->
		<div class="collapsible-card">
			<div class="card-header">
				<h3><span class="dashicons dashicons-code-standards" style="margin-right:10px; vertical-align:middle; color:#6366f1;"></span><?php esc_html_e( '2. Header Script Injection (<head>)', 'adx-ad-inserter' ); ?></h3>
				<div class="card-header-actions">
					<span class="card-badge <?php echo esc_attr( $custom_badge ); ?>"><?php echo esc_html( $custom_label ); ?></span>
					<span class="card-arrow">&rsaquo;</span>
				</div>
			</div>
			<div class="card-body">
				<div style="display:flex; justify-content:space-between; align-items:center; background: #eff6ff; padding:12px 20px; border-radius:8px; margin-bottom:20px; border-left:4px solid var(--adx-primary);">
					<span style="font-weight: 600; font-size: 0.95rem; color: var(--adx-text);"><?php esc_html_e( 'Enable Custom Scripts Subsystem', 'adx-ad-inserter' ); ?></span>
					<label class="switch">
						<input type="hidden" name="adxbyms_custom_enabled" value="false" />
						<input type="checkbox" name="adxbyms_custom_enabled" value="true" <?php checked( $custom_enabled, true ); ?> />
						<span class="slider round"></span>
					</label>
				</div>

				<div>
					<label for="adxbyms_header_code" style="display:block; font-weight:700; margin-bottom:8px; font-size:0.9rem;">
						<?php esc_html_e( 'Header Raw Script Injection Textarea:', 'adx-ad-inserter' ); ?>
					</label>
					<textarea name="adxbyms_header_code" id="adxbyms_header_code" class="ad-textarea" placeholder="<script type=&quot;text/javascript&quot;>&#10;  // Custom head scripts here&#10;</script>"><?php echo esc_textarea( wp_unslash( $header_code ) ); ?></textarea>
					<span class="help-text" style="margin-top:6px;"><?php esc_html_e( 'Paste initialization codes, external tag managers, meta tags, or stylesheets to run inside the site <head> tag.', 'adx-ad-inserter' ); ?></span>
				</div>
			</div>
		</div>

		<!-- Card 3: Footer Custom Scripts -->
		<div class="collapsible-card">
			<div class="card-header">
				<h3><span class="dashicons dashicons-editor-code" style="margin-right:10px; vertical-align:middle; color:#a855f7;"></span><?php esc_html_e( '3. Footer Script Injection (</body>)', 'adx-ad-inserter' ); ?></h3>
				<div class="card-header-actions">
					<span class="card-badge <?php echo esc_attr( $custom_badge ); ?>"><?php echo esc_html( $custom_label ); ?></span>
					<span class="card-arrow">&rsaquo;</span>
				</div>
			</div>
			<div class="card-body">
				<!-- Footer scripts section without duplicate toggle -->

				<p class="description" style="margin-top: 0; margin-bottom: 20px; color: var(--adx-text-muted);">
					<?php esc_html_e( 'Inject tracker pixels, conversion counters, or body script tags globally right before the closing </body> tag.', 'adx-ad-inserter' ); ?>
				</p>

				<div>
					<label for="adxbyms_footer_code" style="display:block; font-weight:700; margin-bottom:8px; font-size:0.9rem;">
						<?php esc_html_e( 'Footer Raw Script Injection Textarea:', 'adx-ad-inserter' ); ?>
					</label>
					<textarea name="adxbyms_footer_code" id="adxbyms_footer_code" class="ad-textarea" placeholder="<script>&#10;  // Custom body scripts here&#10;</script>"><?php echo esc_textarea( wp_unslash( $footer_code ) ); ?></textarea>
					<span class="help-text" style="margin-top:6px;"><?php esc_html_e( 'Scripts here are injected late in the DOM footer, keeping page layout calculations fast and responsive.', 'adx-ad-inserter' ); ?></span>
				</div>
			</div>
		</div>

		<!-- Card 4: Ads.Txt Manager -->
		<div class="collapsible-card">
			<div class="card-header">
				<h3><span class="dashicons dashicons-media-text" style="margin-right:10px; vertical-align:middle; color:#10b981;"></span><?php esc_html_e( '4. Authorized Sellers List (ads.txt) Editor', 'adx-ad-inserter' ); ?></h3>
				<div class="card-header-actions">
					<span class="card-badge <?php echo esc_attr( $ads_badge ); ?>"><?php echo esc_html( $ads_label ); ?></span>
					<span class="card-arrow">&rsaquo;</span>
				</div>
			</div>
			<div class="card-body">
				<div style="display:flex; justify-content:space-between; align-items:center; background: #ecfdf5; padding:12px 20px; border-radius:8px; margin-bottom:20px; border-left:4px solid var(--adx-success);">
					<span style="font-weight: 600; font-size: 0.95rem; color: var(--adx-text);"><?php esc_html_e( 'Enable Dynamic /ads.txt rewrites', 'adx-ad-inserter' ); ?></span>
					<label class="switch">
						<input type="hidden" name="adxbyms_ads_txt_enabled" value="false" />
						<input type="checkbox" id="adxbyms_ads_txt_enabled" name="adxbyms_ads_txt_enabled" value="true" <?php checked( $ads_txt_en, true ); ?> />
						<span class="slider round"></span>
					</label>
				</div>

				<div style="margin-bottom: 20px;">
					<label for="adxbyms_ads_txt_code" style="display:block; font-weight:700; margin-bottom:8px; font-size:0.9rem;">
						<?php esc_html_e( 'Ads.txt Plain Text Lines (one per line):', 'adx-ad-inserter' ); ?>
					</label>
					<textarea name="adxbyms_ads_txt_code" id="adxbyms_ads_txt_code" class="ad-textarea" style="height:150px; color:#a78bfa;" placeholder="google.com, pub-xxxxxxxxxxxxxxxx, DIRECT, f08c47fec0942fa0"><?php echo esc_textarea( $ads_txt_val ); ?></textarea>
				</div>

				<div style="background:#f8fafc; padding:20px; border:1px solid var(--adx-border); border-radius:10px;">
					<h4 style="margin:0 0 8px 0; font-weight:700; font-size:0.92rem;"><?php esc_html_e( 'Ads.txt Line Format:', 'adx-ad-inserter' ); ?></h4>
					<code style="font-size:0.85rem; background:#fff; padding:6px 12px; border-radius:6px; display:block; border:1px solid #cbd5e1; margin-bottom:10px;">google.com, pub-1029384756102938, DIRECT, f08c47fec0942fa0</code>
					<span class="help-text" style="line-height:1.5; font-size:0.82rem;"><?php esc_html_e( 'Once activated, the plugin intercepts standard /ads.txt server requests and dynamically outputs these seller listings correctly.', 'adx-ad-inserter' ); ?></span>
				</div>
			</div>
		</div>

	</div>
</div>
