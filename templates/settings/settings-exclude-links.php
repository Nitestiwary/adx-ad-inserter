<?php
/**
 * Exclude Links Settings Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

$exclusions = get_option( 'adxbyms_exclude_links', '' );
?>

<div id="tab-exclude-links" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Exclude Links / Pages', 'adx-ad-inserter' ); ?></h2>

	<div style="background:#eff6ff; padding:15px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:20px;">
		<span style="font-weight: 600; font-size: 0.95rem; color: var(--adx-text);"><?php esc_html_e( 'Manage Ad Exclusion Paths globally. Ads of all formats (Display, Custom, Popups, Rails, Anchors, etc.) will be automatically suppressed on matching layouts.', 'adx-ad-inserter' ); ?></span>
	</div>

	<div style="border: 1px solid var(--adx-border); border-radius: 8px; padding: 24px; background: #f8fafc;">
		
		<!-- Exclude Links Input -->
		<div style="margin-bottom: 20px;">
			<label for="adxbyms_exclude_links" style="display:block; font-weight:700; margin-bottom:8px;">
				<?php esc_html_e( 'Excluded URLs / Path Prefixes (Comma-Separated)', 'adx-ad-inserter' ); ?>
			</label>
			<textarea name="adxbyms_exclude_links" id="adxbyms_exclude_links" style="height: 150px; font-family:monospace; font-size:0.9rem;" placeholder="https://example.com/privacy-policy/, /about-us/, /shop/checkout"><?php echo esc_textarea( $exclusions ); ?></textarea>
			
			<div style="margin-top:12px; background:#fff; padding:16px; border:1px solid var(--adx-border); border-radius:8px;">
				<h4 style="margin:0 0 8px 0; font-weight:700; color:var(--adx-text);"><?php esc_html_e( 'Exclusion Rules & Formats Supported:', 'adx-ad-inserter' ); ?></h4>
				<ul style="list-style:disc; padding-left:20px; margin:0; font-size:0.85rem; color:var(--adx-text-muted); line-height:1.6;">
					<li><strong><?php esc_html_e( 'Absolute URL Matching:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Paste full links including protocol (e.g. ', 'adx-ad-inserter' ); ?><code>https://site.com/about/</code><?php esc_html_e( '). Performs exact page-to-page matching.', 'adx-ad-inserter' ); ?></li>
					<li><strong><?php esc_html_e( 'Relative Path Matching:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Use context relative URIs (e.g. ', 'adx-ad-inserter' ); ?><code>/privacy-policy/</code><?php esc_html_e( ' or ', 'adx-ad-inserter' ); ?><code>/contact</code><?php esc_html_e( '). Paths are normalized and trailing slashes are ignored automatically.', 'adx-ad-inserter' ); ?></li>
					<li><strong><?php esc_html_e( 'Folder / Sub-path Exclusions:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Excludes matching children implicitly (e.g. ', 'adx-ad-inserter' ); ?><code>/checkout</code><?php esc_html_e( ' will exclude ', 'adx-ad-inserter' ); ?><code>/checkout/pay/</code><?php esc_html_e( ' and ', 'adx-ad-inserter' ); ?><code>/checkout/success/</code><?php esc_html_e( ').', 'adx-ad-inserter' ); ?></li>
					<li><strong><?php esc_html_e( 'Query-String Safe:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'The matching engine ignores GET query parameters during comparison so matching is safe and reliable.', 'adx-ad-inserter' ); ?></li>
				</ul>
			</div>
		</div>

	</div>
</div>
