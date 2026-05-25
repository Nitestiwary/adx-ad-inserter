/**
 * Frontend Public Javascript - AdX Ad Inserter Premium Updates
 */

(function ($) {
	'use strict';

	$(document).ready(function () {
		console.log('[AdX] Public JS Active');

		// --- 1. Popup Ad Module with 24-Hour and Session Capping (Feature 4) ---
		const popupModule = {
			config: window.ADXBYMS_POPUP_DATA || {},
			sessionKey: 'adxbyms_popup_session_shown',
			localKey: 'adxbyms_popup_24h_shown',
			overlay: $('#adxbyms-popup-overlay'),
			adRequested: false,

			init: function () {
				if (!this.overlay.length || !this.config.network_code) {
					return;
				}

				// Check frequency caps
				if (this.isCapped()) {
					return;
				}

				// Hook scroll listener with passive option
				window.addEventListener('scroll', this.onScroll.bind(this), { passive: true });
			},

			isCapped: function () {
				// Session cap check
				if (this.config.frequency === 'session') {
					if (sessionStorage.getItem(this.sessionKey) === 'true') {
						return true;
					}
				}

				// 24 Hour local storage cap check
				if (this.config.frequency === '24h') {
					const lastShown = localStorage.getItem(this.localKey);
					if (lastShown) {
						const diff = Date.now() - parseInt(lastShown, 10);
						if (diff < 24 * 60 * 60 * 1000) {
							return true; // Capped
						}
					}
				}

				return false;
			},

			setCapped: function () {
				if (this.config.frequency === 'session') {
					sessionStorage.setItem(this.sessionKey, 'true');
				} else if (this.config.frequency === '24h') {
					localStorage.setItem(this.localKey, Date.now().toString());
				}
			},

			onScroll: function () {
				if (this.adRequested) return;

				const st = window.scrollY || document.documentElement.scrollTop || 0;
				const vh = window.innerHeight || 0;
				const dh = Math.max(
					document.documentElement.scrollHeight || 0,
					document.body ? document.body.scrollHeight : 0
				);
				const maxScroll = Math.max(dh - vh, 1);
				const currentPercent = st / maxScroll;

				const triggerPercent = this.config.scroll_trigger || 0.6;

				if (currentPercent >= triggerPercent) {
					this.displayPopup();
				}
			},

			displayPopup: function () {
				this.adRequested = true;
				this.setCapped();

				// Fade overlay
				this.overlay.css('display', 'flex');
				setTimeout(() => {
					this.overlay.addClass('show');
				}, 50);

				// Request slot render
				window.googletag = window.googletag || { cmd: [] };
				window.googletag.cmd.push(() => {
					try {
						// Register popup slot dynamically
						const slotId = 'adxbyms-popup-slot-div';
						const slot = googletag.defineSlot(
							this.config.network_code,
							[[300, 250], [336, 280], [300, 280], [250, 250], [200, 200]],
							slotId
						).addService(googletag.pubads());

						googletag.pubads().set('page_url', window.location.href);

						// Handle slot load callback to hide if ad is empty (GAM standard check)
						googletag.pubads().addEventListener('slotRenderEnded', (e) => {
							if (e.slot === slot && e.isEmpty) {
								this.closePopup();
							}
						});

						googletag.enableServices();
						googletag.display(slotId);
					} catch (e) {
						console.error('[AdX Popup] Registration error:', e);
					}
				});

				// Wire close handlers
				this.overlay.find('.adxbyms-popup-close-btn').on('click', () => {
					this.closePopup();
				});

				// Allow backdrop click dismiss
				this.overlay.on('click', (e) => {
					if (e.target === this.overlay[0]) {
						this.closePopup();
					}
				});
			},

			closePopup: function () {
				this.overlay.removeClass('show');
				setTimeout(() => {
					this.overlay.css('display', 'none');
				}, 300);

				// Remove scroll listener
				window.removeEventListener('scroll', this.onScroll);
			}
		};

		popupModule.init();

		// --- 2. Flying Carpet Parallax Optimizations (Feature 5) ---
		// Automatically hide fixed elements outside viewport to save GPU paint operations
		if ('IntersectionObserver' in window) {
			const carpetContainers = document.querySelectorAll('.ms-flying-carpet-container');
			const observer = new IntersectionObserver((entries) => {
				entries.forEach(entry => {
					const slot = entry.target.querySelector('.ms-flying-carpet-slot');
					if (!slot) return;
					
					if (entry.isIntersecting) {
						slot.style.visibility = 'visible';
						slot.style.pointerEvents = 'auto';
					} else {
						slot.style.visibility = 'hidden';
						slot.style.pointerEvents = 'none';
					}
				});
			}, { threshold: 0.1 });

			carpetContainers.forEach(container => {
				observer.observe(container);
			});
		}

		// --- 3. Offerwall Ad Consent Overlays with Close Icon (Skip temporarily) ---
		const offerwallModule = {
			config: window.ADXBYMS_OFFERWALL_DATA || {},
			sessionKey: 'adxbyms_offerwall_shown',
			bar: null,
			rewardedEvt: null,
			adInitialized: false,

			init: function () {
				if (!this.config.enabled || !this.config.networkCode) {
					return;
				}

				if (sessionStorage.getItem(this.sessionKey) === 'true') {
					return; // Already shown or skipped in this session
				}

				this.buildUI();
				
				// Show on scroll > triggerPercent
				window.addEventListener('scroll', this.onScroll.bind(this), { passive: true });
			},

			buildUI: function () {
				this.bar = document.createElement("div");
				this.bar.id = "adxbyms-offerwall-bar";
				this.bar.style.display = "none";

				const barHTML = [
					'<button class="offerwall-close-btn" aria-label="Close Pop-up ad">&times;</button>',
					'<img class="offerwall-logo" alt="Publisher Logo" src="' + this.config.logoUrl + '">',
					'<h2>Unlock more content</h2>',
					'<p>Engage with a quick offer to continue accessing content on this site.</p>',
					'<button class="offerwall-btn" disabled style="cursor:not-allowed; opacity:0.8;">',
					'View a short ad <span class="offerwall-loading" style="display:none; margin-left:8px; font-size:12px;">Loading...</span>',
					'</button>'
				];

				this.bar.innerHTML = barHTML.join("");
				document.body.appendChild(this.bar);

				// Wire events using delegation to ensure they always fire
				$(this.bar).on('click', '.offerwall-close-btn', (e) => {
					e.preventDefault();
					this.dismissTemporarily();
				});

				$(this.bar).on('click', '.offerwall-btn', (e) => {
					e.preventDefault();
					if (!this.rewardedEvt) {
						console.warn('[AdX] Rewarded ad not ready yet.');
						return;
					}
					
					try {
						this.rewardedEvt.makeRewardedVisible();
						this.bar.style.display = "none";
					} catch (err) {
						console.error('[AdX] Failed to show rewarded ad', err);
						this.bar.style.display = "none";
					}
				});
			},

			dismissTemporarily: function () {
				this.bar.style.display = "none";
				sessionStorage.setItem(this.sessionKey, 'true'); // sets session skip cap
				window.removeEventListener('scroll', this.onScroll);
			},

			onScroll: function () {
				if (sessionStorage.getItem(this.sessionKey) === 'true') return;

				const st = window.scrollY || document.documentElement.scrollTop || 0;
				const vh = window.innerHeight || 0;
				const dh = Math.max(
					document.documentElement.scrollHeight || 0,
					document.body ? document.body.scrollHeight : 0
				);
				const maxScroll = Math.max(dh - vh, 1);
				const currentPercent = (st + vh) / dh * 100;

				const triggerPercent = this.config.triggerPercent || 60;

				if (currentPercent >= triggerPercent) {
					this.showOfferwall();
				}
			},

			showOfferwall: function () {
				this.bar.style.display = "block";
				this.initRewardedSlot();
			},

			initRewardedSlot: function () {
				if (this.adInitialized) return;
				this.adInitialized = true;

				window.googletag = window.googletag || { cmd: [] };
				
				// Helper checks for OutOfPage define slots
				const checkAndRegister = () => {
					googletag.cmd.push(() => {
						try {
							const slot = googletag.defineOutOfPageSlot(
								this.config.networkCode,
								googletag.enums.OutOfPageFormat.REWARDED
							);

							if (!slot) {
								this.bar.style.display = "none";
								return;
							}

							slot.addService(googletag.pubads());
							
							googletag.pubads().addEventListener('rewardedSlotReady', (evt) => {
								if (evt.slot === slot) {
									this.rewardedEvt = evt;
									const btn = $(this.bar).find('.offerwall-btn');
									btn.prop('disabled', false).css({
										'cursor': 'pointer',
										'opacity': '1'
									});
								}
							});

							googletag.pubads().addEventListener('rewardedSlotGranted', (evt) => {
								if (evt.slot === slot) {
									sessionStorage.setItem(this.sessionKey, 'true');
								}
							});

							googletag.pubads().addEventListener('rewardedSlotClosed', (evt) => {
								if (evt.slot === slot) {
									$(this.bar).find('.offerwall-loading').hide();
									// Destroy the slot to allow other out-of-page slots (like Button Rewarded) to work
									googletag.destroySlots([slot]);
									this.rewardedEvt = null;
									this.adInitialized = false;
								}
							});

							// Timeout: If ad doesn't load within 15 seconds, hide to keep UX clean
							setTimeout(() => {
								if (!this.rewardedEvt) {
									this.bar.style.display = "none";
								}
							}, 15000);

							googletag.enableServices();
							googletag.display(slot);

						} catch (e) {
							console.error('[AdX Offerwall] GPT Registration error:', e);
							this.bar.style.display = "none";
						}
					});
				};

				if (typeof googletag !== 'undefined' && typeof googletag.defineOutOfPageSlot !== 'undefined') {
					checkAndRegister();
				} else {
					var attempts = 0;
					const timer = setInterval(() => {
						attempts++;
						if (typeof googletag !== 'undefined' && typeof googletag.defineOutOfPageSlot !== 'undefined') {
							clearInterval(timer);
							checkAndRegister();
						} else if (attempts >= 100) {
							clearInterval(timer);
							this.bar.style.display = "none";
						}
					}, 100);
				}
			}
		};

		offerwallModule.init();

		// --- 3.5. Button Rewarded Ad (Keyword Click) ---
		const btnRewardedModule = {
			config: window.ADXBYMS_BUTTON_REWARDED_DATA || {},
			rewardedEvt: null,
			pendingTargetUrl: null,

			init: function () {
				if (!this.config.enabled || !this.config.networkCode || !this.config.keywords) {
					return;
				}

				const keywords = this.config.keywords.split(',').map(k => k.trim().toLowerCase()).filter(k => k);
				if (keywords.length === 0) return;

				// Pre-load the rewarded ad slot
				this.initRewardedSlot();

				// Bind to clicks on links/buttons
				$('body').on('click', 'a, button', (e) => {
					const el = $(e.currentTarget);
					const text = el.text().toLowerCase();
					
					let match = false;
					for (const kw of keywords) {
						if (text.includes(kw)) {
							match = true;
							break;
						}
					}
					
					if (match) {
						if (this.rewardedEvt) {
							e.preventDefault();
							const href = el.attr('href');
							this.pendingTargetUrl = (href && href !== '#' && !href.startsWith('javascript:')) ? href : null;
							
							// Show the consent popup instead of immediately triggering the ad
							this.showConsentPopup();
						} else {
							// Ad not ready yet, let the normal click happen
							console.warn('[AdX Btn Rewarded] Ad not ready yet, ignoring keyword click.');
						}
					}
				});
			},

			buildConsentUI: function () {
				this.consentOverlay = document.createElement("div");
				this.consentOverlay.id = "adxbyms-btn-rewarded-consent";
				this.consentOverlay.style.cssText = "display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.4); z-index:2147483647; align-items:center; justify-content:center;";

				const modalHTML = [
					'<div style="background:#ffffff; padding:32px 24px; border-radius:8px; width:90%; max-width:380px; text-align:center; font-family:-apple-system, BlinkMacSystemFont, sans-serif; box-shadow:0 10px 25px rgba(0,0,0,0.2);">',
					'<h3 style="margin:0 0 12px 0; font-size:1.15rem; color:#333; font-weight:normal;">To <strong>proceed</strong>, please watch a short ad</h3>',
					'<p style="margin:0 0 24px 0; color:#888; font-size:0.9rem;">(Cancel = no continuation)</p>',
					'<div style="display:flex; gap:12px; justify-content:center;">',
					'<button id="adxbyms-btn-rewarded-allow" style="flex:1; padding:12px 16px; border:none; background:#3b82f6; color:#ffffff; border-radius:6px; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Watch Ad</button>',
					'<button id="adxbyms-btn-rewarded-cancel" style="flex:1; padding:12px 16px; border:none; background:#ef4444; color:#ffffff; border-radius:6px; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Not Now</button>',
					'</div>',
					'</div>'
				];

				this.consentOverlay.innerHTML = modalHTML.join("");
				document.body.appendChild(this.consentOverlay);

				// Wire buttons
				$(this.consentOverlay).on('click', '#adxbyms-btn-rewarded-cancel', () => {
					this.consentOverlay.style.display = "none";
					this.pendingTargetUrl = null;
				});

				$(this.consentOverlay).on('click', '#adxbyms-btn-rewarded-allow', () => {
					this.consentOverlay.style.display = "none";
					try {
						this.rewardedEvt.makeRewardedVisible();
					} catch (err) {
						console.error('[AdX Btn Rewarded] Failed to show ad', err);
						if (this.pendingTargetUrl) window.location.href = this.pendingTargetUrl;
					}
				});
			},

			showConsentPopup: function () {
				if (!this.consentOverlay) {
					this.buildConsentUI();
				}
				this.consentOverlay.style.display = "flex";
			},

			initRewardedSlot: function () {
				window.googletag = window.googletag || { cmd: [] };
				
				const checkAndRegister = () => {
					googletag.cmd.push(() => {
						try {
							const slot = googletag.defineOutOfPageSlot(
								this.config.networkCode,
								googletag.enums.OutOfPageFormat.REWARDED
							);

							if (!slot) return;

							slot.addService(googletag.pubads());
							
							googletag.pubads().addEventListener('rewardedSlotReady', (evt) => {
								if (evt.slot === slot) {
									this.rewardedEvt = evt;
								}
							});

							googletag.pubads().addEventListener('rewardedSlotClosed', (evt) => {
								if (evt.slot === slot) {
									if (this.pendingTargetUrl) {
										window.location.href = this.pendingTargetUrl;
									}
									// Destroy and request a new one for next click, freeing up the out-of-page slot limit
									googletag.destroySlots([slot]);
									this.rewardedEvt = null;
									this.initRewardedSlot();
								}
							});

							googletag.enableServices();
							googletag.display(slot);

						} catch (e) {
							console.error('[AdX Btn Rewarded] GPT Registration error:', e);
						}
					});
				};

				if (typeof googletag !== 'undefined' && typeof googletag.defineOutOfPageSlot !== 'undefined') {
					checkAndRegister();
				} else {
					var attempts = 0;
					const timer = setInterval(() => {
						attempts++;
						if (typeof googletag !== 'undefined' && typeof googletag.defineOutOfPageSlot !== 'undefined') {
							clearInterval(timer);
							checkAndRegister();
						} else if (attempts >= 100) {
							clearInterval(timer);
						}
					}, 100);
				}
			}
		};

		btnRewardedModule.init();

		// --- 4. Client-Side HTML Selector Transplant Engine ---
		$('.adxbyms-html-placeholder').each(function () {
			const placeholder = $(this);
			const selector = placeholder.attr('data-selector');
			const action = placeholder.attr('data-action');
			if (!selector) return;

			const targets = $(selector);
			if (targets.length === 0) {
				console.warn('[AdX Inserter] Target selector not found in DOM:', selector);
				return;
			}

			const adContent = placeholder.contents();
			if (adContent.length === 0) return;

			if (action === 'before_html') {
				targets.first().before(adContent);
			} else if (action === 'inside_html') {
				targets.first().append(adContent);
			} else if (action === 'after_html') {
				targets.first().after(adContent);
			}

			placeholder.remove();
		});
	});

})(jQuery);
