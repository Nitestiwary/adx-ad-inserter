<?php
/**
 * Frontend Public Area Controller
 *
 * @package    AdX-Ad-Inserter
 * @subpackage Public
 */

defined( 'ABSPATH' ) || exit;

class Adx_Public {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );

		// Register shortcodes
		add_shortcode( 'ms_display_ad', array( $this, 'render_display_ad_shortcode' ) );
		add_shortcode( 'ms_custom_ad', array( $this, 'render_custom_ad_shortcode' ) );
		add_shortcode( 'ms_responsive_ad', array( $this, 'render_responsive_ad_shortcode' ) );
		add_shortcode( 'ms_flying_carpet', array( $this, 'render_flying_carpet_shortcode' ) );
		add_shortcode( 'ms_side_rail', array( $this, 'render_side_rail_shortcode' ) );

		// Core hooks for automatic ad insertion
		add_filter( 'the_content', array( $this, 'inject_in_content_ads' ), 99 );
		add_action( 'loop_start', array( $this, 'inject_before_post_ads' ) );
		add_action( 'loop_end', array( $this, 'inject_after_post_ads' ) );
		
		// Header/Footer injections
		add_action( 'wp_head', array( $this, 'inject_header_ads' ) );
		add_action( 'wp_footer', array( $this, 'inject_footer_ads' ) );

		// Excerpt and comments filters
		add_filter( 'the_excerpt', array( $this, 'inject_excerpt_ads' ), 99 );
		add_action( 'comment_form_before', array( $this, 'inject_before_comments_ads' ) );
		add_action( 'comment_form_after', array( $this, 'inject_after_comments_ads' ) );
		add_filter( 'comment_text', array( $this, 'inject_between_comments_ads' ), 99, 2 );

		// Ads.txt Rewrite and Query Variables
		add_filter( 'query_vars', array( $this, 'register_ads_txt_query_var' ) );
		add_action( 'template_redirect', array( $this, 'serve_ads_txt' ), 0 );
		
		// Admin Bar Utilities
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 100 );
	}


	/**
	 * Register & Enqueue public CSS/JS assets.
	 */
	public function enqueue_public_assets() {
		if ( Adx_Exclusions::is_current_page_excluded() ) {
			return;
		}

		$css_version = ADXBYMS_VERSION . '.' . ( file_exists( ADXBYMS_PATH . 'assets/css/public.css' ) ? filemtime( ADXBYMS_PATH . 'assets/css/public.css' ) : time() );
		$js_version  = ADXBYMS_VERSION . '.' . ( file_exists( ADXBYMS_PATH . 'assets/js/public.js' ) ? filemtime( ADXBYMS_PATH . 'assets/js/public.js' ) : time() );

		wp_enqueue_style(
			'adxbyms-public-css',
			ADXBYMS_URL_MODULAR . 'assets/css/public.css',
			array(),
			$css_version
		);

		wp_enqueue_script(
			'adxbyms-public-js',
			ADXBYMS_URL_MODULAR . 'assets/js/public.js',
			array( 'jquery' ),
			$js_version,
			true
		);

		// Localize popup settings
		$popup_freq = get_option( 'adxbyms_popup_frequency', 'session' );
		$popup_sec  = absint( get_option( 'adxbyms_popup_scroll_trigger', 60 ) ) / 100;
		$popup_code = get_option( 'adxbyms_popup_network_code', '' );

		wp_localize_script(
			'adxbyms-public-js',
			'ADXBYMS_POPUP_DATA',
			array(
				'network_code'   => esc_js( $popup_code ),
				'scroll_trigger' => (float) $popup_sec,
				'frequency'      => esc_js( $popup_freq ),
			)
		);

		// Localize offerwall settings
		$offerwall_enabled = ( 'true' === get_option( 'adxbyms_offerwall_onscroll_enabled', 'false' ) );
		$offerwall_code    = get_option( 'adxbyms_offerwall_onscroll_network_code', '' );
		$offerwall_logo    = get_option( 'adxbyms_offerwall_onscroll_logo_url', '' );

		if ( empty( $offerwall_logo ) ) {
			$offerwall_logo = plugin_dir_url( ADXBYMS_FILE ) . 'assets/img/company_logo.png';
		}

		wp_localize_script(
			'adxbyms-public-js',
			'ADXBYMS_OFFERWALL_DATA',
			array(
				'enabled'        => $offerwall_enabled && ! empty( $offerwall_code ),
				'networkCode'    => esc_js( $offerwall_code ),
				'logoUrl'        => esc_url( $offerwall_logo ),
				'triggerPercent' => 60,
			)
		);

		// Localize Button Rewarded settings
		$btn_rewarded_enabled = ( 'true' === get_option( 'adxbyms_ad2_enabled', 'false' ) );
		$btn_rewarded_code    = get_option( 'adxbyms_ad2_network_code', '' );
		$btn_rewarded_kw      = get_option( 'adxbyms_ad2_keywords', '' );

		wp_localize_script(
			'adxbyms-public-js',
			'ADXBYMS_BUTTON_REWARDED_DATA',
			array(
				'enabled'     => $btn_rewarded_enabled && ! empty( $btn_rewarded_code ),
				'networkCode' => esc_js( $btn_rewarded_code ),
				'keywords'    => esc_js( $btn_rewarded_kw ),
				'logoUrl'     => esc_url( $offerwall_logo ),
			)
		);
	}

	/**
	 * check whether ads can render based on current context (exclusion, global status).
	 *
	 * @return bool
	 */
	private function can_render_ads() {
		if ( is_admin() ) {
			return false;
		}
		if ( Adx_Exclusions::is_current_page_excluded() ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if page types filter matches the current page.
	 */
	private function check_page_types( $pages ) {
		if ( empty( $pages ) ) {
			return true;
		}
		
		foreach ( (array) $pages as $page ) {
			switch ( $page ) {
				case 'all':
				case 'entire_website':
					return true;
				case 'post':
					if ( is_single() ) {
						return true;
					}
					break;
				case 'homepage':
					if ( is_front_page() || is_home() ) {
						return true;
					}
					break;
				case 'category':
					if ( is_category() ) {
						return true;
					}
					break;
				case 'static':
					if ( is_page() ) {
						return true;
					}
					break;
				case 'search':
					if ( is_search() ) {
						return true;
					}
					break;
				case 'tag':
					if ( is_tag() ) {
						return true;
					}
					break;
			}
		}
		return false;
	}

	/**
	 * Auto Inject Ads In Content.
	 */
	public function inject_in_content_ads( $content ) {
		$show_positions = false;
		if ( isset( $_GET['adx_show_positions'] ) ) {
			if ( '1' === $_GET['adx_show_positions'] ) {
				setcookie( 'adx_show_positions', '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
				$_COOKIE['adx_show_positions'] = '1';
				$show_positions = true;
			} else {
				setcookie( 'adx_show_positions', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
				if ( isset( $_COOKIE['adx_show_positions'] ) ) {
					unset( $_COOKIE['adx_show_positions'] );
				}
			}
		} elseif ( isset( $_COOKIE['adx_show_positions'] ) && '1' === $_COOKIE['adx_show_positions'] ) {
			$show_positions = true;
		}

		if ( $show_positions && current_user_can( 'manage_options' ) ) {
			return $this->inject_placeholder_positions( $content );
		}

		if ( ! $this->can_render_ads() || empty( $content ) || ! is_string( $content ) ) {
			return $content;
		}

		// 1. standard Display Ads (In-content slots)
		if ( 'true' === get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
				$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
				$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
				$offset    = get_option( "adxbyms_slot_{$i}_offset", '1' );
				$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );
				$show_label = ( 'true' === get_option( "adxbyms_slot_{$i}_show_label" ) );

				if ( ! $enabled || empty( $network ) || empty( $sizes ) ) {
					continue;
				}

				// Skip before/after hooks inside content filter
				if ( in_array( $insertion, array( 'before_post', 'after_post', 'manual', 'before_excerpt', 'after_excerpt', 'before_comments', 'between_comments', 'after_comments', 'footer' ), true ) ) {
					continue;
				}

				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				if ( in_array( $insertion, array( 'before_html', 'inside_html', 'after_html' ), true ) ) {
					$div_id  = 'div-gpt-ad-slot-html-' . $i . '-' . uniqid();
					$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment );
					if ( $show_label ) {
						$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
					}
					$placeholder = '<div class="adxbyms-html-placeholder" data-selector="' . esc_attr( $offset ) . '" data-action="' . esc_attr( $insertion ) . '" style="display:none;">' . $ad_html . '</div>';
					$content .= $placeholder;
					continue;
				}

				$div_id  = 'div-gpt-ad-slot-' . $i . '-' . uniqid();
				$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment );
				if ( $show_label ) {
					$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
				}
				$content = Adx_Content_Inserter::insert( $content, $ad_html, $insertion, absint( $offset ) );
			}
		}

		// 2. Adsense Ads / Custom (Feature 1)
		if ( 'true' === get_option( 'adxbyms_custom_adsense_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
				$code      = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
				$insertion = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", '' );
				$offset    = get_option( "adxbyms_custom_adsense_block_{$i}_offset", '1' );
				$alignment = get_option( "adxbyms_custom_adsense_block_{$i}_alignment", 'center' );
				$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array() );
				$pages     = (array) get_option( "adxbyms_custom_adsense_block_{$i}_pages", array() );

				if ( ! $enabled || empty( $code ) || in_array( $insertion, array( 'manual', 'sticky_bottom', 'before_excerpt', 'after_excerpt', 'before_comments', 'between_comments', 'after_comments', 'footer' ), true ) ) {
					continue;
				}

				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				if ( in_array( $insertion, array( 'before_html', 'inside_html', 'after_html' ), true ) ) {
					$ad_html = $this->build_custom_html_container( wp_unslash( $code ), $alignment );
					$placeholder = '<div class="adxbyms-html-placeholder" data-selector="' . esc_attr( $offset ) . '" data-action="' . esc_attr( $insertion ) . '" style="display:none;">' . $ad_html . '</div>';
					$content .= $placeholder;
					continue;
				}

				$ad_html = $this->build_custom_html_container( wp_unslash( $code ), $alignment );
				$content = Adx_Content_Inserter::insert( $content, $ad_html, $insertion, absint( $offset ) );
			}
		}

		// 3. Responsive Ads (Feature 3)
		if ( 'true' === get_option( 'adxbyms_responsive_ads_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_responsive_block_{$i}_network_code", '' );
				$insertion = get_option( "adxbyms_responsive_block_{$i}_insertion", '' );
				$offset    = get_option( "adxbyms_responsive_block_{$i}_offset", '1' );
				$alignment = get_option( "adxbyms_responsive_block_{$i}_alignment", 'center' );
				$devices   = (array) get_option( "adxbyms_responsive_block_{$i}_devices", array() );
				$pages     = (array) get_option( "adxbyms_responsive_block_{$i}_pages", array() );
				$show_label = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_show_label" ) );

				if ( ! $enabled || empty( $network ) || in_array( $insertion, array( 'manual', 'before_excerpt', 'after_excerpt', 'before_comments', 'between_comments', 'after_comments', 'footer' ), true ) ) {
					continue;
				}

				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				if ( in_array( $insertion, array( 'before_html', 'inside_html', 'after_html' ), true ) ) {
					$ad_html = $this->build_responsive_gpt_ad( $network, $i, $alignment );
					if ( $show_label ) {
						$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
					}
					$placeholder = '<div class="adxbyms-html-placeholder" data-selector="' . esc_attr( $offset ) . '" data-action="' . esc_attr( $insertion ) . '" style="display:none;">' . $ad_html . '</div>';
					$content .= $placeholder;
					continue;
				}

				$ad_html = $this->build_responsive_gpt_ad( $network, $i, $alignment );
				if ( $show_label ) {
					$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
				}
				$content = Adx_Content_Inserter::insert( $content, $ad_html, $insertion, absint( $offset ) );
			}
		}

		// 4. Flying Carpet Ads (Feature 5)
		if ( 'true' === get_option( 'adxbyms_flying_carpet_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_flying_carpet_block_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_flying_carpet_block_{$i}_network_code", '' );
				$insertion = get_option( "adxbyms_flying_carpet_block_{$i}_insertion", '' );
				$offset    = absint( get_option( "adxbyms_flying_carpet_block_{$i}_offset", 2 ) );
				$alignment = get_option( "adxbyms_flying_carpet_block_{$i}_alignment", 'center' );
				$devices   = (array) get_option( "adxbyms_flying_carpet_block_{$i}_devices", array() );
				$pages     = (array) get_option( "adxbyms_flying_carpet_block_{$i}_pages", array() );

				if ( ! $enabled || empty( $network ) || 'manual' === $insertion ) {
					continue;
				}

				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				$ad_html = $this->build_flying_carpet_ad( $network, $i, $alignment );
				$content = Adx_Content_Inserter::insert( $content, $ad_html, $insertion, $offset );
			}
		}

		return $content;
	}

	/**
	 * Hook-based insertion: Before Post.
	 */
	public function inject_before_post_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}
		if ( 'true' !== get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			return;
		}

		for ( $i = 1; $i <= 10; $i++ ) {
			$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
			$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
			$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
			$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
			$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
			$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
			$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );
			$show_label = ( 'true' === get_option( "adxbyms_slot_{$i}_show_label" ) );

			if ( ! $enabled || empty( $network ) || empty( $sizes ) || 'before_post' !== $insertion ) {
				continue;
			}

			if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
				continue;
			}

			$div_id  = 'div-gpt-ad-slot-before-' . $i;
			$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment );
			if ( $show_label ) {
				$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
			}
			echo $ad_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Hook-based insertion: After Post.
	 */
	public function inject_after_post_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}
		if ( 'true' !== get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			return;
		}

		for ( $i = 1; $i <= 10; $i++ ) {
			$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
			$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
			$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
			$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
			$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
			$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
			$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );
			$show_label = ( 'true' === get_option( "adxbyms_slot_{$i}_show_label" ) );

			if ( ! $enabled || empty( $network ) || empty( $sizes ) || 'after_post' !== $insertion ) {
				continue;
			}

			if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
				continue;
			}

			$div_id  = 'div-gpt-ad-slot-after-' . $i;
			$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment );
			if ( $show_label ) {
				$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
			}
			echo $ad_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public static $comment_counter = 0;

	/**
	 * Auto Inject Ads In Excerpt.
	 */
	public function inject_excerpt_ads( $excerpt ) {
		if ( ! $this->can_render_ads() || empty( $excerpt ) || ! is_string( $excerpt ) ) {
			return $excerpt;
		}

		// Check Display Slots
		if ( 'true' === get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
				$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
				$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );

				if ( ! $enabled || empty( $network ) || empty( $sizes ) ) {
					continue;
				}
				if ( 'before_excerpt' !== $insertion && 'after_excerpt' !== $insertion ) {
					continue;
				}
				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				$div_id  = 'div-gpt-ad-slot-ex-' . $i . '-' . uniqid();
				$ad_html = $this->build_display_gpt_ad( $network, $sizes, $div_id, $alignment, $i );

				if ( 'before_excerpt' === $insertion ) {
					$excerpt = $ad_html . $excerpt;
				} else {
					$excerpt = $excerpt . $ad_html;
				}
			}
		}

		// Check Custom Adsense
		if ( 'true' === get_option( 'adxbyms_custom_adsense_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
				$code      = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
				$insertion = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_custom_adsense_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_custom_adsense_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array() );

				if ( ! $enabled || empty( $code ) ) {
					continue;
				}
				if ( 'before_excerpt' !== $insertion && 'after_excerpt' !== $insertion ) {
					continue;
				}
				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				$ad_html = $this->build_custom_html_container( wp_unslash( $code ), $alignment );

				if ( 'before_excerpt' === $insertion ) {
					$excerpt = $ad_html . $excerpt;
				} else {
					$excerpt = $excerpt . $ad_html;
				}
			}
		}

		// Check Responsive Ads
		if ( 'true' === get_option( 'adxbyms_responsive_ads_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_responsive_block_{$i}_network_code", '' );
				$insertion = get_option( "adxbyms_responsive_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_responsive_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_responsive_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_responsive_block_{$i}_devices", array() );

				if ( ! $enabled || empty( $network ) ) {
					continue;
				}
				if ( 'before_excerpt' !== $insertion && 'after_excerpt' !== $insertion ) {
					continue;
				}
				if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				$ad_html = $this->build_responsive_gpt_ad( $network, $i, $alignment );

				if ( 'before_excerpt' === $insertion ) {
					$excerpt = $ad_html . $excerpt;
				} else {
					$excerpt = $excerpt . $ad_html;
				}
			}
		}

		return $excerpt;
	}

	/**
	 * Before comments section hook handler.
	 */
	public function inject_before_comments_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}
		$this->render_comments_ads_by_insertion( 'before_comments' );
	}

	/**
	 * After comments section hook handler.
	 */
	public function inject_after_comments_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}
		$this->render_comments_ads_by_insertion( 'after_comments' );
	}

	/**
	 * Between comments loop parser.
	 */
	public function inject_between_comments_ads( $comment_text, $comment = null ) {
		if ( ! $this->can_render_ads() || empty( $comment_text ) ) {
			return $comment_text;
		}

		self::$comment_counter++;
		$offset = self::$comment_counter;

		// 1. Display Slots
		if ( 'true' === get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
				$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
				$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );
				$ad_offset = get_option( "adxbyms_slot_{$i}_offset", '1' );

				if ( $enabled && ! empty( $network ) && ! empty( $sizes ) && 'between_comments' === $insertion && (int) $offset === absint( $ad_offset ) ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						$div_id  = 'div-gpt-ad-slot-bc-' . $i . '-' . uniqid();
						$ad_html = $this->build_display_gpt_ad( $network, $sizes, $div_id, $alignment, $i );
						$comment_text .= $ad_html;
					}
				}
			}
		}

		// 2. Custom Adsense
		if ( 'true' === get_option( 'adxbyms_custom_adsense_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
				$code      = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
				$insertion = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_custom_adsense_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_custom_adsense_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array() );
				$ad_offset = get_option( "adxbyms_custom_adsense_block_{$i}_offset", '1' );

				if ( $enabled && ! empty( $code ) && 'between_comments' === $insertion && (int) $offset === absint( $ad_offset ) ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						$ad_html = $this->build_custom_html_container( wp_unslash( $code ), $alignment );
						$comment_text .= $ad_html;
					}
				}
			}
		}

		// 3. Responsive Ads
		if ( 'true' === get_option( 'adxbyms_responsive_ads_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_responsive_block_{$i}_network_code", '' );
				$insertion = get_option( "adxbyms_responsive_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_responsive_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_responsive_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_responsive_block_{$i}_devices", array() );
				$ad_offset = get_option( "adxbyms_responsive_block_{$i}_offset", '1' );

				if ( $enabled && ! empty( $network ) && 'between_comments' === $insertion && (int) $offset === absint( $ad_offset ) ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						$ad_html = $this->build_responsive_gpt_ad( $network, $i, $alignment );
						$comment_text .= $ad_html;
					}
				}
			}
		}

		return $comment_text;
	}

	/**
	 * Helper helper render comments ads.
	 */
	private function render_comments_ads_by_insertion( $insertion_type ) {
		// 1. Display Slots
		if ( 'true' === get_option( 'adxbyms_slot_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_slot_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_slot_{$i}_network_code", '' );
				$sizes     = (array) get_option( "adxbyms_slot_{$i}_sizes", array() );
				$insertion = get_option( "adxbyms_slot_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_slot_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_slot_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_slot_{$i}_devices", array() );

				if ( $enabled && ! empty( $network ) && ! empty( $sizes ) && $insertion_type === $insertion ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						$div_id  = 'div-gpt-ad-slot-cm-' . $i . '-' . uniqid();
						$ad_html = $this->build_display_gpt_ad( $network, $sizes, $div_id, $alignment, $i );
						echo $ad_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		}

		// 2. Custom Adsense
		if ( 'true' === get_option( 'adxbyms_custom_adsense_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
				$code      = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
				$insertion = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_custom_adsense_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_custom_adsense_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array() );

				if ( $enabled && ! empty( $code ) && $insertion_type === $insertion ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						echo $this->build_custom_html_container( wp_unslash( $code ), $alignment ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		}

		// 3. Responsive Ads
		if ( 'true' === get_option( 'adxbyms_responsive_ads_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_responsive_block_{$i}_enabled" ) );
				$network   = get_option( "adxbyms_responsive_block_{$i}_network_code", '' );
				$insertion = get_option( "adxbyms_responsive_block_{$i}_insertion", '' );
				$alignment = get_option( "adxbyms_responsive_block_{$i}_alignment", 'center' );
				$pages     = (array) get_option( "adxbyms_responsive_block_{$i}_pages", array() );
				$devices   = (array) get_option( "adxbyms_responsive_block_{$i}_devices", array() );

				if ( $enabled && ! empty( $network ) && $insertion_type === $insertion ) {
					if ( $this->check_page_types( $pages ) && Adx_Device::matches( $devices ) ) {
						echo $this->build_responsive_gpt_ad( $network, $i, $alignment ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		}
	}

	/**
	 * Render Custom Alignment Div.
	 */
	private function build_custom_html_container( $html_code, $alignment ) {
		$alignment = in_array( $alignment, array( 'left', 'center', 'right', 'full' ), true ) ? $alignment : 'center';
		$style = 'display:table;margin:12px 0;text-align:left;';
		if ( 'center' === $alignment ) {
			$style = 'display:table;margin:12px auto;text-align:center;';
		} elseif ( 'right' === $alignment ) {
			$style = 'display:table;margin:12px 0 12px auto;text-align:right;';
		} elseif ( 'full' === $alignment ) {
			$style = 'display:block;width:100%;margin:12px 0;text-align:center;';
		}

		return '<div class="adxbyms-custom-container adx-align-' . esc_attr( $alignment ) . '" style="' . esc_attr( $style ) . '">' . $html_code . '</div>';
	}

	/**
	 * Build responsive size mapping for Feature 3.
	 */
	private function build_responsive_gpt_ad( $network, $index, $alignment ) {
		$div_id = 'ms-responsive-ad-' . $index;
		
		// Auto-generate standard map size mapping JS
		$mapping_js = 'googletag.sizeMapping()
			.addSize([800, 90], [728, 90])
			.addSize([0, 0], [300, 250])
			.build()';

		// GAM maps both size categories
		$sizes = array( '728x90', '300x250' );

		$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment, $mapping_js );
		
		$show_label = ( 'true' === get_option( "adxbyms_responsive_block_{$index}_show_label" ) );
		if ( $show_label ) {
			$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
		}
		return $ad_html;
	}

	/**
	 * Build Flying Carpet parallax ad for Feature 5.
	 */
	private function build_flying_carpet_ad( $network, $index, $alignment ) {
		$div_id = 'ms-flying-carpet-' . $index;
		$site_host = wp_parse_url( get_site_url(), PHP_URL_HOST );
		
		// Page URL dynamic replacements (Feature 5 Requirement)
		$current_url = home_url( add_query_arg( null, null ) );

		ob_start();
		?>
		<div class="ms-flying-carpet-wrapper adx-align-<?php echo esc_attr( $alignment ); ?>">
			<div class="ms-flying-carpet-container">
				<div class="ms-flying-carpet-parallax">
					<div id="<?php echo esc_attr( $div_id ); ?>" class="ms-flying-carpet-slot"></div>
				</div>
			</div>
			
			<script type="text/javascript">
				window.googletag = window.googletag || { cmd: [] };
				window.googletag.cmd.push(function() {
					try {
						var slot = googletag.defineSlot('<?php echo esc_js( $network ); ?>', [[300, 250], [300, 600]], '<?php echo esc_js( $div_id ); ?>')
							.addService(googletag.pubads());
						
						googletag.pubads().set('page_url', '<?php echo esc_js( $current_url ); ?>');
						googletag.enableServices();
						googletag.display('<?php echo esc_js( $div_id ); ?>');
					} catch(e) {
						console.error("[AdX Carpet] GPT Carpet registration error:", e);
					}
				});
			</script>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Side Rails render in body for Feature 6.
	 */
	public function render_side_rails() {
		if ( ! $this->can_render_ads() ) {
			return;
		}

		if ( 'true' !== get_option( 'adxbyms_side_rail_enabled', 'false' ) ) {
			return;
		}

		if ( wp_is_mobile() ) {
			return; // Desktop only screens (>1200px)
		}

		// Support separate left/right slots; fall back to shared slot for backwards compat
		$shared_network = get_option( 'adxbyms_side_rail_network_code', '' );
		$left_network   = get_option( 'adxbyms_side_rail_left_network_code', $shared_network );
		$right_network  = get_option( 'adxbyms_side_rail_right_network_code', $shared_network );

		// At least one slot must be configured
		if ( empty( $left_network ) && empty( $right_network ) ) {
			return;
		}

		$refresh_enabled  = ( 'true' === get_option( 'adxbyms_side_rail_refresh_enabled', 'false' ) );
		$refresh_interval = max( 30, absint( get_option( 'adxbyms_side_rail_refresh_interval', 30 ) ) );

		// Render Left and Right Rails containers
		?>
		<?php if ( ! empty( $left_network ) ) : ?>
		<div id="ms-side-rail-left" class="ms-side-rail-container">
			<button class="ms-side-rail-close" aria-label="Close left advertisement" onclick="document.getElementById('ms-side-rail-left').style.display='none';">×</button>
			<div id="ms-side-rail-left-slot" class="ms-side-rail-slot"></div>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $right_network ) ) : ?>
		<div id="ms-side-rail-right" class="ms-side-rail-container">
			<button class="ms-side-rail-close" aria-label="Close right advertisement" onclick="document.getElementById('ms-side-rail-right').style.display='none';">×</button>
			<div id="ms-side-rail-right-slot" class="ms-side-rail-slot"></div>
		</div>
		<?php endif; ?>

		<script type="text/javascript">
			window.googletag = window.googletag || { cmd: [] };
			window.googletag.cmd.push(function() {
				try {
					var slotsToRefresh = [];

					<?php if ( ! empty( $left_network ) ) : ?>
					var leftSlot = googletag.defineSlot('<?php echo esc_js( $left_network ); ?>', [[120, 600], [160, 600]], 'ms-side-rail-left-slot')
						.addService(googletag.pubads());
					slotsToRefresh.push(leftSlot);
					<?php endif; ?>

					<?php if ( ! empty( $right_network ) ) : ?>
					var rightSlot = googletag.defineSlot('<?php echo esc_js( $right_network ); ?>', [[120, 600], [160, 600]], 'ms-side-rail-right-slot')
						.addService(googletag.pubads());
					slotsToRefresh.push(rightSlot);
					<?php endif; ?>

					googletag.enableServices();

					<?php if ( ! empty( $left_network ) ) : ?>
					googletag.display('ms-side-rail-left-slot');
					<?php endif; ?>
					<?php if ( ! empty( $right_network ) ) : ?>
					googletag.display('ms-side-rail-right-slot');
					<?php endif; ?>

					<?php if ( $refresh_enabled ) : ?>
					// Viewability-based refreshing
					var lastRefresh = Date.now();
					var refreshInterval = <?php echo (int) $refresh_interval; ?> * 1000;

					window.addEventListener('scroll', function() {
						if (Date.now() - lastRefresh > refreshInterval) {
							var checkEl = document.getElementById('ms-side-rail-left-slot') || document.getElementById('ms-side-rail-right-slot');
							if (checkEl && checkEl.getBoundingClientRect().top < window.innerHeight && checkEl.getBoundingClientRect().bottom > 0) {
								googletag.pubads().refresh(slotsToRefresh);
								lastRefresh = Date.now();
								console.log("[AdX Rails] Refreshed visible side rails");
							}
						}
					}, { passive: true });
					<?php endif; ?>

				} catch(e) {
					console.error("[AdX Rails] GPT Side Rails error:", e);
				}
			});
		</script>
		<?php
	}

	/**
	 * Inject Header Ads (wp_head).
	 */
	public function inject_header_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}

		// 1. Legacy Anchor Ads
		if ( 'true' === get_option( 'adxbyms_anchor_enabled', 'false' ) ) {
			$anchor_code = get_option( 'adxbyms_anchor_network_code', '' );
			$anchor_pos  = get_option( 'adxbyms_anchor_position', 'TOP_ANCHOR' );
			
			if ( ! empty( $anchor_code ) ) {
				?>
				<script type="text/javascript">
					window.googletag = window.googletag || { cmd: [] };
					googletag.cmd.push(function() {
						try {
							var posKey = '<?php echo esc_js( $anchor_pos ); ?>';
							var fmt = (googletag.enums && googletag.enums.OutOfPageFormat) ? googletag.enums.OutOfPageFormat[posKey] : null;
							if (fmt) {
								var slot = googletag.defineOutOfPageSlot('<?php echo esc_js( $anchor_code ); ?>', fmt);
								if (slot) {
									slot.addService(googletag.pubads());
									googletag.enableServices();
									googletag.display(slot);
								}
							}
						} catch(e) {}
					});
				</script>
				<?php
			}
		}

		// 2. Custom header scripts
		if ( 'true' === get_option( 'adxbyms_custom_enabled', 'false' ) ) {
			$header_code = get_option( 'adxbyms_header_code', '' );
			if ( ! empty( $header_code ) ) {
				echo wp_unslash( $header_code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Inject Footer Ads (wp_footer).
	 */
	public function inject_footer_ads() {
		if ( ! $this->can_render_ads() ) {
			return;
		}

		// 1. Popup script configuration & overlay div (Feature 4 Update)
		if ( 'true' === get_option( 'adxbyms_popup_enabled', 'false' ) ) {
			$popup_devices = get_option( 'adxbyms_popup_devices', 'all' );
			$popup_pages   = (array) get_option( 'adxbyms_popup_pages', array( 'all' ) );

			// Check targets
			if ( Adx_Device::matches( $popup_devices ) && $this->check_page_types( $popup_pages ) ) {
				?>
				<div id="adxbyms-popup-overlay" class="adxbyms-popup-overlay-container" style="display:none;">
					<div class="adxbyms-popup-content-box">
						<div class="adxbyms-popup-topbar">
							<button type="button" class="adxbyms-popup-close-btn" aria-label="Close overlay advertisement">×</button>
						</div>
						<div id="adxbyms-popup-slot-div" class="adxbyms-popup-ad-slot"></div>
					</div>
				</div>
				<?php
			}
		}

		// 2. Side Rails insertion
		$this->render_side_rails();

		// 3. Custom Adsense / Sticky Bottom Ads (Feature 1)
		if ( 'true' === get_option( 'adxbyms_custom_adsense_enabled', 'false' ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$i}_enabled" ) );
				$code      = get_option( "adxbyms_custom_adsense_block_{$i}_code", '' );
				$insertion = get_option( "adxbyms_custom_adsense_block_{$i}_insertion", '' );
				$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$i}_devices", array() );

				if ( ! $enabled || empty( $code ) || 'sticky_bottom' !== $insertion ) {
					continue;
				}

				if ( ! Adx_Device::matches( $devices ) ) {
					continue;
				}

				?>
				<div class="adxbyms-sticky-bottom-bar">
					<button class="adxbyms-sticky-close" onclick="this.closest('.adxbyms-sticky-bottom-bar').remove();" aria-label="Close bottom advertisement">×</button>
					<div class="adxbyms-sticky-content">
						<?php echo wp_unslash( $code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<?php
			}
		}

		// 4. Custom footer scripts
		if ( 'true' === get_option( 'adxbyms_footer_custom_enabled', 'false' ) ) {
			$footer_code = get_option( 'adxbyms_footer_code', '' );
			if ( ! empty( $footer_code ) ) {
				echo wp_unslash( $footer_code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Shortcodes rendering callbacks.
	 */
	public function render_display_ad_shortcode( $atts ) {
		if ( ! $this->can_render_ads() ) {
			return '';
		}

		$args = shortcode_atts( array( 'id' => '' ), $atts );
		$id   = absint( $args['id'] );

		if ( $id < 1 || $id > 10 ) {
			return '';
		}

		$enabled = ( 'true' === get_option( "adxbyms_slot_{$id}_enabled" ) );
		$network = get_option( "adxbyms_slot_{$id}_network_code", '' );
		$sizes   = (array) get_option( "adxbyms_slot_{$id}_sizes", array() );
		$align   = get_option( "adxbyms_slot_{$id}_alignment", 'center' );

		if ( ! $enabled || empty( $network ) || empty( $sizes ) ) {
			return '';
		}

		$div_id = 'ms-display-ad-sc-' . $id . '-' . uniqid();
		return $this->build_display_gpt_ad( $network, $sizes, $div_id, $align, $id );
	}

	public function render_custom_ad_shortcode( $atts ) {
		if ( ! $this->can_render_ads() ) {
			return '';
		}

		$args = shortcode_atts( array( 'id' => '' ), $atts );
		$id   = absint( $args['id'] );

		if ( $id < 1 || $id > 10 ) {
			return '';
		}

		$enabled   = ( 'true' === get_option( "adxbyms_custom_adsense_block_{$id}_enabled" ) );
		$code      = get_option( "adxbyms_custom_adsense_block_{$id}_code", '' );
		$align     = get_option( "adxbyms_custom_adsense_block_{$id}_alignment", 'center' );
		$devices   = (array) get_option( "adxbyms_custom_adsense_block_{$id}_devices", array() );
		$pages     = (array) get_option( "adxbyms_custom_adsense_block_{$id}_pages", array() );

		if ( ! $enabled || empty( $code ) ) {
			return '';
		}

		if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
			return '';
		}

		return $this->build_custom_html_container( wp_unslash( $code ), $align );
	}

	public function render_responsive_ad_shortcode( $atts ) {
		if ( ! $this->can_render_ads() ) {
			return '';
		}

		$args = shortcode_atts( array( 'id' => '' ), $atts );
		$id   = absint( $args['id'] );

		if ( $id < 1 || $id > 5 ) {
			return '';
		}

		$enabled   = ( 'true' === get_option( "adxbyms_responsive_block_{$id}_enabled" ) );
		$network   = get_option( "adxbyms_responsive_block_{$id}_network_code", '' );
		$align     = get_option( "adxbyms_responsive_block_{$id}_alignment", 'center' );
		$devices   = (array) get_option( "adxbyms_responsive_block_{$id}_devices", array() );
		$pages     = (array) get_option( "adxbyms_responsive_block_{$id}_pages", array() );

		if ( ! $enabled || empty( $network ) ) {
			return '';
		}

		if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
			return '';
		}

		return $this->build_responsive_gpt_ad( $network, $id, $align );
	}

	public function render_flying_carpet_shortcode( $atts ) {
		if ( ! $this->can_render_ads() ) {
			return '';
		}

		$args = shortcode_atts( array( 'id' => '' ), $atts );
		$id   = absint( $args['id'] );

		if ( $id < 1 || $id > 5 ) {
			return '';
		}

		$enabled   = ( 'true' === get_option( "adxbyms_flying_carpet_block_{$id}_enabled" ) );
		$network   = get_option( "adxbyms_flying_carpet_block_{$id}_network_code", '' );
		$align     = get_option( "adxbyms_flying_carpet_block_{$id}_alignment", 'center' );
		$devices   = (array) get_option( "adxbyms_flying_carpet_block_{$id}_devices", array() );
		$pages     = (array) get_option( "adxbyms_flying_carpet_block_{$id}_pages", array() );

		if ( ! $enabled || empty( $network ) ) {
			return '';
		}

		if ( ! $this->check_page_types( $pages ) || ! Adx_Device::matches( $devices ) ) {
			return '';
		}

		return $this->build_flying_carpet_ad( $network, $id, $align );
	}

	public function render_side_rail_shortcode() {
		// Side rails render inside wp_footer so shortcode is just a placeholder trigger
		ob_start();
		$this->render_side_rails();
		return ob_get_clean();
	}

	/**
	 * Register Admin Bar Menu for AdX Placements.
	 */
	public function add_admin_bar_menu( $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) || is_admin() ) {
			return;
		}

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$is_showing = ( isset( $_GET['adx_show_positions'] ) && '1' === $_GET['adx_show_positions'] ) || ( ! isset( $_GET['adx_show_positions'] ) && isset( $_COOKIE['adx_show_positions'] ) && '1' === $_COOKIE['adx_show_positions'] );

		$admin_bar->add_node( array(
			'id'    => 'adx-ad-inserter',
			'title' => '<span class="ab-icon dashicons dashicons-layout" style="margin-top:2px;"></span>AdX Ad Inserter',
			'href'  => admin_url( 'admin.php?page=adx-ad-inserter' ),
		) );

		if ( $is_showing ) {
			$url = add_query_arg( 'adx_show_positions', '0', $current_url );
			$title = 'Hide Positions';
		} else {
			$url = add_query_arg( 'adx_show_positions', '1', $current_url );
			$title = 'Show Positions';
		}

		$admin_bar->add_node( array(
			'parent' => 'adx-ad-inserter',
			'id'     => 'adx-show-positions',
			'title'  => $title,
			'href'   => $url,
		) );
	}

	/**
	 * Inject visual placeholder positions instead of real ads.
	 */
	private function inject_placeholder_positions( $content ) {
		$positions = array(
			'before_content'   => array( 'Before Content', 1 ),
			'before_paragraph' => array( 'Before Paragraph', 10 ),
			'after_paragraph'  => array( 'After Paragraph', 10 ),
			'before_heading'   => array( 'Before Heading', 5 ),
			'between_content'  => array( 'Between Content (Middle)', 1 ),
			'after_content'    => array( 'After Content', 1 ),
		);

		foreach ( $positions as $type => $info ) {
			$label_prefix = $info[0];
			$max_offset   = $info[1];
			for ( $i = $max_offset; $i >= 1; $i-- ) { // Reverse loop to avoid offset shifting when inserting multiple tags
				$label = $max_offset > 1 ? "{$label_prefix} {$i}" : $label_prefix;
				$html  = '<div style="background:#e0f2fe; border:2px dashed #3b82f6; color:#1e40af; font-weight:bold; padding:10px; margin:15px 0; text-align:center; text-transform:uppercase; clear:both; font-family:sans-serif; font-size:13px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">' . esc_html( $label ) . '</div>';
				
				$content = Adx_Content_Inserter::insert( $content, $html, $type, $i );
			}
		}

		return $content;
	}

	/**
	 * Register ads.txt query var.
	 */
	public function register_ads_txt_query_var( $vars ) {
		$vars[] = 'adxbyms_ads_txt';
		return $vars;
	}

	/**
	 * Serve ads.txt dynamically.
	 */
	public function serve_ads_txt() {
		if ( is_admin() ) {
			return;
		}

		$request_uri_raw = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
		if ( false === $request_uri_raw ) {
			$request_uri_raw = '';
		}
		$request_uri = sanitize_text_field( wp_unslash( $request_uri_raw ) );
		$path        = strtolower( strtok( $request_uri, '?' ) );

		// 1. Redirect /index.php/ads.txt to /ads.txt
		if ( '/index.php/ads.txt' === $path ) {
			wp_safe_redirect( home_url( '/ads.txt' ), 301 );
			exit;
		}

		// 2. Redirect /ads.txt/ to /ads.txt.
		if ( '/ads.txt/' === $path ) {
			wp_safe_redirect( home_url( '/ads.txt' ), 301 );
			exit;
		}

		$is_ads_rewrite = get_query_var( 'adxbyms_ads_txt' );
		$is_index_ads   = ( '/index.php/ads.txt' === $path );
		if ( ! $is_ads_rewrite && ! $is_index_ads ) {
			return;
		}

		$enabled = get_option( 'adxbyms_ads_txt_enabled', 'false' );
		$code    = (string) get_option( 'adxbyms_ads_txt_code', '' );
		if ( 'true' !== $enabled || '' === trim( $code ) ) {
			return;
		}

		// Clean any existing output buffers before serving ads.txt
		if ( function_exists( 'ob_get_level' ) ) {
			$ob_level = ob_get_level();
			if ( $ob_level < 0 ) {
				$ob_level = 0;
			}
			$max_clean_level = 3;
			$clean_attempts  = 0;
			$max_attempts    = 5;

			while ( $ob_level > 0 && $ob_level <= $max_clean_level && $clean_attempts < $max_attempts ) {
				if ( ob_get_level() <= 0 ) {
					break;
				}
				$buffer_content = ob_get_contents();
				if ( false === $buffer_content ) {
					break;
				}
				@ob_end_clean();
				$new_level = ob_get_level();
				if ( $new_level >= $ob_level || $new_level < 0 ) {
					break;
				}
				$ob_level = $new_level;
				$clean_attempts++;
			}
		}

		nocache_headers();
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
		echo rtrim( $code, "\r\n" ) . "\n";
		exit;
	}

	/**
	 * Build Display GPT ad with optional advertisement label.
	 */
	private function build_display_gpt_ad( $network, $sizes, $div_id, $alignment, $i ) {
		$ad_html = Adx_Gpt_Manager::get_instance()->render_gpt_slot( $network, $sizes, $div_id, $alignment );
		$show_label = ( 'true' === get_option( "adxbyms_slot_{$i}_show_label" ) );
		if ( $show_label ) {
			$ad_html = '<div class="adx-advertisement-label" style="text-align:center; font-size:12px; color:#64748b; margin-bottom:4px; font-family:sans-serif; width:100%; clear:both;">---Advertisement---</div>' . $ad_html;
		}
		return $ad_html;
	}
}

