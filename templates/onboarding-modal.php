<?php
/**
 * Onboarding Modal Template
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Templates
 */

defined( 'ABSPATH' ) || exit;

$site_url = urlencode( site_url() );
$return_url = urlencode( admin_url( 'admin.php?page=adx-ad-inserter&registered=1' ) );
$plugin_version = urlencode( ADXBYMS_VERSION );

$registration_url = "https://monetiscope.com/register/?site_url={$site_url}&return_url={$return_url}&plugin_version={$plugin_version}";
?>

<div id="ms-setup-overlay" class="ms-setup-overlay">
	<div class="ms-setup-modal">
		<div class="ms-setup-close" id="ms-setup-close">&times;</div>
		
		<div class="ms-setup-header">
			<img src="<?php echo esc_url( plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/logo.png' ); ?>" alt="Monetiscope Logo" class="ms-setup-logo" onerror="this.style.display='none'">
			<h2><?php esc_html_e( 'Setup Your Account', 'adx-ad-inserter' ); ?></h2>
		</div>
		
		<div class="ms-setup-body">
			<p><?php esc_html_e( 'Register your Monetiscope account to access plugin updates, support, optimization tools, and future premium monetization features.', 'adx-ad-inserter' ); ?></p>
		</div>
		
		<div class="ms-setup-footer">
			<a href="<?php echo esc_url( $registration_url ); ?>" class="button button-primary ms-setup-btn-primary">
				<?php esc_html_e( 'Continue to Sign-up', 'adx-ad-inserter' ); ?>
			</a>
			<button type="button" id="ms-setup-remind-later" class="button button-secondary ms-setup-btn-secondary">
				<?php esc_html_e( 'Remind Me Later', 'adx-ad-inserter' ); ?>
			</button>
		</div>
	</div>
</div>
