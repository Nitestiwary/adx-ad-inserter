<?php
/**
 * Global Settings Tab Panel (Consolidating Exclude Links, Header/Footer, Ads.txt)
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates/Settings
 */

defined( 'ABSPATH' ) || exit;

// Retrieve variables
$exclusions  = get_option( 'adxbyms_exclude_links', '' );
$header_code = get_option( 'adxbyms_header_code', '' );
$footer_code = get_option( 'adxbyms_footer_code', '' );
$ads_txt_en  = ( 'true' === get_option( 'adxbyms_ads_txt_enabled', 'false' ) );
$ads_txt_val = get_option( 'adxbyms_ads_txt_code', '' );
?>

<div id="tab-global-settings" class="adx-tab">
	<h2 class="tab-title"><?php esc_html_e( 'Global Settings & Utilities', 'adx-ad-inserter' ); ?></h2>

	<!-- Horizontal Subtabs Selector for Settings -->
	<div class="display-tabs" style="margin-bottom: 24px;">
		<div class="global-tab display-tab tab-green" style="padding: 12px 20px;">
			<?php esc_html_e( 'Exclude Links', 'adx-ad-inserter' ); ?>
		</div>
		<div class="global-tab display-tab tab-green" style="padding: 12px 20px;">
			<?php esc_html_e( 'Header/Footer Scripts', 'adx-ad-inserter' ); ?>
		</div>
		<div class="global-tab display-tab tab-green" style="padding: 12px 20px;">
			<?php esc_html_e( 'Ads.Txt Manager', 'adx-ad-inserter' ); ?>
		</div>
	</div>

	<!-- Subtab Panels -->
	<div class="global-tab-contents">
		
		<!-- 1. Exclude Links Panel -->
		<div class="global-content display-content">
			<h3 style="margin-top:0; font-size:1.15rem; font-weight:700; border-bottom:1px solid #cbd5e1; padding-bottom:8px; margin-bottom:16px;">
				<?php esc_html_e( 'Exclude Links & Target Pages', 'adx-ad-inserter' ); ?>
			</h3>
			
			<div style="margin-bottom: 20px;">
				<label for="adxbyms_exclude_links" style="display:block; font-weight:700; margin-bottom:8px;">
					<?php esc_html_e( 'Excluded URLs / Path Prefixes (Comma-Separated)', 'adx-ad-inserter' ); ?>
				</label>
				<textarea name="adxbyms_exclude_links" id="adxbyms_exclude_links" style="height: 150px; font-family:monospace; font-size:0.9rem;" placeholder="https://example.com/privacy-policy/, /about-us/, /shop/checkout"><?php echo esc_textarea( $exclusions ); ?></textarea>
				
				<div style="margin-top:12px; background:#fff; padding:16px; border:1px solid var(--adx-border); border-radius:8px;">
					<h4 style="margin:0 0 8px 0; font-weight:700; color:var(--adx-text);"><?php esc_html_e( 'Rules & Formats Supported:', 'adx-ad-inserter' ); ?></h4>
					<ul style="list-style:disc; padding-left:20px; margin:0; font-size:0.85rem; color:var(--adx-text-muted); line-height:1.6;">
						<li><strong><?php esc_html_e( 'Absolute URL Matching:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Paste full links including protocol (e.g. ', 'adx-ad-inserter' ); ?><code>https://site.com/about/</code><?php esc_html_e( '). Performs exact page-to-page matching.', 'adx-ad-inserter' ); ?></li>
						<li><strong><?php esc_html_e( 'Relative Path Matching:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Use context relative URIs (e.g. ', 'adx-ad-inserter' ); ?><code>/privacy-policy/</code><?php esc_html_e( ' or ', 'adx-ad-inserter' ); ?><code>/contact</code><?php esc_html_e( '). Trailing slashes are normalized automatically.', 'adx-ad-inserter' ); ?></li>
						<li><strong><?php esc_html_e( 'Sub-path Exclusions:', 'adx-ad-inserter' ); ?></strong> <?php esc_html_e( 'Excludes matching children implicitly (e.g. ', 'adx-ad-inserter' ); ?><code>/checkout</code><?php esc_html_e( ' will exclude ', 'adx-ad-inserter' ); ?><code>/checkout/pay/</code><?php esc_html_e( ').', 'adx-ad-inserter' ); ?></li>
					</ul>
				</div>
			</div>
		</div>

		<!-- 2. Header/Footer Scripts Panel -->
		<div class="global-content display-content">
			<h3 style="margin-top:0; font-size:1.15rem; font-weight:700; border-bottom:1px solid #cbd5e1; padding-bottom:8px; margin-bottom:16px;">
				<?php esc_html_e( 'Header & Footer Custom Scripts injection', 'adx-ad-inserter' ); ?>
			</h3>

			<!-- Tab buttons for Header / Footer toggle -->
			<div class="custom-tabs flex w-full gap-2" style="margin-bottom:15px; border-bottom:none; padding-bottom:0;">
				<div class="custom-tab-toggle custom-tab-btn active" data-tab="header" style="padding:8px 16px; font-weight:600; border-radius:6px; cursor:pointer;">
					<?php esc_html_e( 'Header Script Slot', 'adx-ad-inserter' ); ?>
				</div>
				<div class="custom-tab-toggle custom-tab-btn" data-tab="footer" style="padding:8px 16px; font-weight:600; border-radius:6px; cursor:pointer;">
					<?php esc_html_e( 'Footer Script Slot', 'adx-ad-inserter' ); ?>
				</div>
			</div>

			<!-- Header Content Block -->
			<div id="custom-code-header" class="custom-tab-content-block active" style="border: 1px solid var(--adx-border); border-radius:8px; padding:20px; background:#fff;">
				<label for="adxbyms_header_code" style="display:block; font-weight:700; margin-bottom:6px;">
					<?php esc_html_e( 'Header Raw Script Injection Textarea (<head>)', 'adx-ad-inserter' ); ?>
				</label>
				<textarea name="adxbyms_header_code" id="adxbyms_header_code" class="ad-textarea"><?php echo esc_textarea( wp_unslash( $header_code ) ); ?></textarea>
				<span class="help-text"><?php esc_html_e( 'Paste initialization codes, stylesheets, or standard header scripts to run inside the site <head> tag.', 'adx-ad-inserter' ); ?></span>
			</div>

			<!-- Footer Content Block -->
			<div id="custom-code-footer" class="custom-tab-content-block" style="border: 1px solid var(--adx-border); border-radius:8px; padding:20px; background:#fff; display:none;">
				<label for="adxbyms_footer_code" style="display:block; font-weight:700; margin-bottom:6px;">
					<?php esc_html_e( 'Footer Raw Script Injection Textarea (</body>)', 'adx-ad-inserter' ); ?>
				</label>
				<textarea name="adxbyms_footer_code" id="adxbyms_footer_code" class="ad-textarea"><?php echo esc_textarea( wp_unslash( $footer_code ) ); ?></textarea>
				<span class="help-text"><?php esc_html_e( 'Paste conversion pixels, script trackers, or footer HTML blocks to run right before the closing </body> tag.', 'adx-ad-inserter' ); ?></span>
			</div>
		</div>

		<!-- 3. Ads.Txt Manager Panel -->
		<div class="global-content display-content">
			<h3 style="margin-top:0; font-size:1.15rem; font-weight:700; border-bottom:1px solid #cbd5e1; padding-bottom:8px; margin-bottom:16px;">
				<?php esc_html_e( 'Authorized Sellers List (ads.txt) Editor', 'adx-ad-inserter' ); ?>
			</h3>

			<!-- Active toggle -->
			<div style="background:#eff6ff; padding:12px; border-radius:8px; border-left:4px solid var(--adx-primary); margin-bottom:16px;">
				<label style="font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
					<input type="hidden" name="adxbyms_ads_txt_enabled" value="false" />
					<input type="checkbox" id="adxbyms_ads_txt_enabled" name="adxbyms_ads_txt_enabled" value="true" <?php checked( get_option( 'adxbyms_ads_txt_enabled' ), 'true' ); ?> />
					<?php esc_html_e( 'Enable Dynamic /ads.txt rewrites', 'adx-ad-inserter' ); ?>
				</label>
			</div>

			<!-- Code Area -->
			<div>
				<label for="adxbyms_ads_txt_code" style="display:block; font-weight:700; margin-bottom:8px;">
					<?php esc_html_e( 'Ads.txt Plain Text Lines', 'adx-ad-inserter' ); ?>
				</label>
				<textarea name="adxbyms_ads_txt_code" id="adxbyms_ads_txt_code" class="ad-textarea" style="height:200px; color:#58a6ff;"><?php echo esc_textarea( $ads_txt_val ); ?></textarea>
				
				<div style="margin-top:12px; background:#fff; padding:16px; border:1px solid var(--adx-border); border-radius:8px;">
					<h4 style="margin:0 0 6px 0; font-weight:700;"><?php esc_html_e( 'Ads.txt Line Format Example:', 'adx-ad-inserter' ); ?></h4>
					<code>google.com, pub-xxxxxxxxxxxxxxxx, DIRECT, f08c47fec0942fa0</code>
					<span class="help-text" style="margin-top:8px;"><?php esc_html_e( 'Once enabled, the plugin intercepts site requests for /ads.txt and serves this content dynamically.', 'adx-ad-inserter' ); ?></span>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	// Custom header/footer inner tab selector logic
	document.addEventListener('DOMContentLoaded', function() {
		const headerBtn = document.querySelector('.custom-tab-toggle[data-tab="header"]');
		const footerBtn = document.querySelector('.custom-tab-toggle[data-tab="footer"]');
		
		const headerBlock = document.getElementById('custom-code-header');
		const footerBlock = document.getElementById('custom-code-footer');

		if(headerBtn && footerBtn && headerBlock && footerBlock) {
			headerBtn.addEventListener('click', function() {
				headerBtn.classList.add('active');
				footerBtn.classList.remove('active');
				headerBlock.style.display = 'block';
				footerBlock.style.display = 'none';
			});

			footerBtn.addEventListener('click', function() {
				footerBtn.classList.add('active');
				headerBtn.classList.remove('active');
				footerBlock.style.display = 'block';
				headerBlock.style.display = 'none';
			});
		}
	});
</script>
