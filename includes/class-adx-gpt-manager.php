<?php
/**
 * Centralized GPT Manager
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Includes
 */

defined( 'ABSPATH' ) || exit;

class Adx_Gpt_Manager {

	/**
	 * Instance of this class.
	 *
	 * @var Adx_Gpt_Manager
	 */
	private static $instance = null;

	/**
	 * Track if GPT script has been enqueued.
	 *
	 * @var bool
	 */
	private $enqueued = false;

	/**
	 * Registered GPT slots.
	 *
	 * @var array
	 */
	private $slots = array();

	/**
	 * Get class instance.
	 *
	 * @return Adx_Gpt_Manager
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_gpt_script' ) );
		add_filter( 'script_loader_tag', array( $this, 'force_async_attribute' ), 10, 2 );
		add_action( 'wp_footer', array( $this, 'print_inline_gpt_init' ), 5 );
	}

	/**
	 * Enqueue Google Publisher Tag script safely.
	 */
	public function enqueue_gpt_script() {
		if ( $this->enqueued ) {
			return;
		}

		wp_register_script(
			'adxbyms-gpt',
			'https://securepubads.g.doubleclick.net/tag/js/gpt.js',
			array(),
			'1.0.0',
			false // Load in head for earlier slot registration or footer if preferred. We set to false (head) but async so it doesn't block rendering.
		);

		wp_enqueue_script( 'adxbyms-gpt' );
		$this->enqueued = true;
	}

	/**
	 * Force async attribute on GPT script tag.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string The modified script tag.
	 */
	public function force_async_attribute( $tag, $handle ) {
		if ( 'adxbyms-gpt' === $handle && false === strpos( $tag, ' async' ) ) {
			$tag = str_replace( ' src', ' async src', $tag );
		}
		return $tag;
	}

	/**
	 * Print global init script to prevent duplicate googletag definitions.
	 */
	public function print_inline_gpt_init() {
		// Define window.googletag once globally
		?>
		<script type="text/javascript">
			window.googletag = window.googletag || { cmd: [] };
			<?php if ( get_option( 'adxbyms_lazy_load', 'true' ) === 'true' ) : ?>
			// Enable lazy loading if active
			window.googletag.cmd.push(function() {
				googletag.pubads().enableLazyLoad({
					fetchMarginPercent: 200,  // Fetch ads when within 2 viewports
					renderMarginPercent: 100, // Render ads when within 1 viewport
					mobileScaling: 2.0        // Double the margins on mobile devices
				});
			});
			<?php endif; ?>
		</script>
		<?php
	}

	/**
	 * Register a GPT slot to be rendered.
	 *
	 * @param string $network_code The full GAM ad slot path.
	 * @param array  $sizes        The sizes array.
	 * @param string $div_id       The target div ID.
	 * @param string $alignment    Slot alignment.
	 * @param string $mapping_js   Optional responsive sizing JS code.
	 * @param array  $targeting    Optional targeting key-values.
	 * @return string The div HTML container.
	 */
	public function render_gpt_slot( $network_code, $sizes, $div_id, $alignment = 'left', $mapping_js = '', $targeting = array() ) {
		if ( empty( $network_code ) ) {
			return '';
		}

		$div_id    = esc_attr( $div_id );
		$alignment = in_array( $alignment, array( 'left', 'center', 'right', 'full' ), true ) ? $alignment : 'left';

		// Determine alignment style
		$align_style = 'display:table;margin:12px 0;text-align:left;';
		if ( 'center' === $alignment ) {
			$align_style = 'display:table;margin:12px auto;text-align:center;';
		} elseif ( 'right' === $alignment ) {
			$align_style = 'display:table;margin:12px 0 12px auto;text-align:right;';
		} elseif ( 'full' === $alignment ) {
			$align_style = 'display:block;width:100%;margin:12px 0;text-align:center;';
		}

		// Normalize sizes for JS representation
		$sizes_js = $this->format_sizes_for_js( $sizes );

		// Hostname dynamic setting
		$site_host = wp_parse_url( get_site_url(), PHP_URL_HOST );

		// Build slot JS call
		ob_start();
		?>
		<div id="<?php echo esc_attr( $div_id ); ?>" class="adxbyms-gpt-container adx-align-<?php echo esc_attr( $alignment ); ?>" style="<?php echo esc_attr( $align_style ); ?>">
			<span style="opacity:0.3;display:block;font-size:9px;text-align:center;"><?php esc_html_e( 'Advertisement', 'adx-ad-inserter' ); ?></span>
			<script type="text/javascript">
				window.googletag = window.googletag || { cmd: [] };
				window.googletag.cmd.push(function() {
					try {
						<?php if ( ! empty( $mapping_js ) ) : ?>
							// Size mapping logic
							var sizeMapping = <?php echo $mapping_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
							var slot = googletag.defineSlot('<?php echo esc_js( $network_code ); ?>', <?php echo $sizes_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>, '<?php echo esc_js( $div_id ); ?>')
								.defineSizeMapping(sizeMapping)
								.addService(googletag.pubads());
						<?php else : ?>
							var slot = googletag.defineSlot('<?php echo esc_js( $network_code ); ?>', <?php echo $sizes_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>, '<?php echo esc_js( $div_id ); ?>')
								.addService(googletag.pubads());
						<?php endif; ?>

						<?php if ( ! empty( $site_host ) ) : ?>
							googletag.pubads().set('page_url', '<?php echo esc_js( $site_host ); ?>');
						<?php endif; ?>

						<?php if ( ! empty( $targeting ) && is_array( $targeting ) ) : ?>
							<?php foreach ( $targeting as $key => $val ) : ?>
								slot.setTargeting('<?php echo esc_js( $key ); ?>', '<?php echo esc_js( $val ); ?>');
							<?php endforeach; ?>
						<?php endif; ?>

						googletag.enableServices();
						googletag.display('<?php echo esc_js( $div_id ); ?>');
					} catch(e) {
						console.error("[AdX] GPT registration error:", e);
					}
				});
			</script>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Helper to format size inputs into standard JS arrays.
	 *
	 * @param mixed $sizes String sizes or arrays of sizes.
	 * @return string JS array representation.
	 */
	public function format_sizes_for_js( $sizes ) {
		if ( empty( $sizes ) ) {
			return '[300, 250]';
		}

		if ( is_string( $sizes ) ) {
			$sizes = array( $sizes );
		}

		$out = array();
		foreach ( $sizes as $sz ) {
			$sz = strtolower( trim( $sz ) );
			if ( 'fluid' === $sz ) {
				$out[] = "'fluid'";
			} else {
				$nums = array_map( 'intval', explode( 'x', str_replace( ' ', '', $sz ) ) );
				if ( 2 === count( $nums ) && $nums[0] && $nums[1] ) {
					$out[] = '[' . $nums[0] . ',' . $nums[1] . ']';
				}
			}
		}

		if ( empty( $out ) ) {
			return '[300, 250]';
		}

		if ( 1 === count( $out ) ) {
			return $out[0];
		}

		return '[' . implode( ',', $out ) . ']';
	}
}
