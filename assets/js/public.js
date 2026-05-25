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
	});

})(jQuery);
