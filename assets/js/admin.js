/**
 * Admin Panel Javascript - AdX Ad Inserter Premium Updates
 */

document.addEventListener('DOMContentLoaded', function () {
	console.log('[AdX] Admin JS Loaded');

	// --- 1. Primary Left Sidebar Tabs Switching ---
	const tabs = document.querySelectorAll('.adx-nav-tab');
	const tabContents = document.querySelectorAll('.adx-tab');

	function hideAllPrimaryTabs() {
		tabContents.forEach(content => {
			content.style.display = 'none';
		});
		tabs.forEach(tab => {
			tab.classList.remove('adx-nav-tab-active');
		});
	}

	tabs.forEach(tab => {
		tab.addEventListener('click', function (e) {
			e.preventDefault();
			const targetId = tab.getAttribute('data-target');
			if (!targetId) return;

			hideAllPrimaryTabs();

			// Activate tab link
			tab.classList.add('adx-nav-tab-active');

			// Show content tab
			const contentDiv = document.getElementById(targetId);
			if (contentDiv) {
				contentDiv.style.display = 'block';
			}

			// Store active tab in sessionStorage so it stays after save
			sessionStorage.setItem('adx_active_tab', targetId);
		});
	});

	// Restore active tab after form save
	const activeTab = sessionStorage.getItem('adx_active_tab');
	if (activeTab && document.getElementById(activeTab)) {
		hideAllPrimaryTabs();
		const tabLink = document.querySelector(`.adx-nav-tab[data-target="${activeTab}"]`);
		if (tabLink) {
			tabLink.classList.add('adx-nav-tab-active');
		}
		document.getElementById(activeTab).style.display = 'block';
	} else {
		// Fallback default
		const defaultTab = document.querySelector('.adx-nav-tab');
		if (defaultTab) {
			defaultTab.click();
		}
	}

	// --- 2. Subtab Horizontal Switching (Scoped per parent tab to prevent leakage) ---
	function setupSubtabs(tabClass, contentClass) {
		document.querySelectorAll('.adx-tab').forEach(parentTab => {
			const siblingTabs = parentTab.querySelectorAll(tabClass);
			const siblingContents = parentTab.querySelectorAll(contentClass);
			if (siblingTabs.length === 0) return;

			siblingTabs.forEach((tab, index) => {
				tab.addEventListener('click', function () {
					// Deactivate siblings within this parent only
					siblingTabs.forEach(t => t.classList.remove('active'));
					siblingContents.forEach(c => c.classList.remove('active'));

					// Activate selected
					tab.classList.add('active');
					if (siblingContents[index]) {
						siblingContents[index].classList.add('active');
					}
				});
			});

			// Trigger click on first subtab for this section on load
			if (siblingTabs[0]) {
				siblingTabs[0].click();
			}
		});
	}

	setupSubtabs('.display-tab', '.display-content');
	setupSubtabs('.global-tab', '.global-content');


	// --- 3. Collapsible Card Toggle (for Adsense / Settings / etc.) ---
	const cardHeaders = document.querySelectorAll('.card-header');
	cardHeaders.forEach(header => {
		header.addEventListener('click', function () {
			const card = header.closest('.collapsible-card');
			if (!card) return;

			const body = card.querySelector('.card-body');
			if (!body) return;

			const isOpen = card.classList.contains('card-open');

			if (isOpen) {
				card.classList.remove('card-open');
				jQuery(body).slideUp(200);
			} else {
				card.classList.add('card-open');
				jQuery(body).slideDown(200);
			}
		});
	});

	// --- 4. Offset Input Display Toggle (Dynamic Dimming and Disabling) ---
	const insertionSelectors = document.querySelectorAll('select[id$="_insertion"]');
	
	function toggleOffsetField(selectElement) {
		if (!selectElement) return;

		// Extract target ID components
		const parentRow = selectElement.closest('.flex-row-fields') || selectElement.closest('.display-content') || selectElement.closest('.card-body') || selectElement.closest('.custom-tab-content-block');
		if (!parentRow) return;

		const offsetWrapper = parentRow.querySelector('.offset-wrapper');
		if (!offsetWrapper) return;

		const value = selectElement.value;
		const inputField = offsetWrapper.querySelector('input');

		// Show/Enable offset/CSS selector input for relevant insertion targets
		if (['disabled', 'before_content', 'after_content', 'before_post', 'after_post', 'before_comments', 'after_comments', 'footer'].includes(value)) {
			offsetWrapper.classList.add('disabled');
			if (inputField) {
				inputField.disabled = true;
				inputField.style.cursor = 'not-allowed';
			}
		} else {
			offsetWrapper.classList.remove('disabled');
			if (inputField) {
				inputField.disabled = false;
				inputField.style.cursor = 'text';
			}
		}
	}

	insertionSelectors.forEach(select => {
		select.addEventListener('change', function () {
			toggleOffsetField(select);
		});
		// Run initial state check
		toggleOffsetField(select);
	});

	// --- 5. Save Success Notice ---
	const urlParams = new URLSearchParams(window.location.search);
	if (urlParams.get('settings-updated') === 'true') {
		const notice = document.createElement('div');
		notice.className = 'adx-saved-notice';
		notice.textContent = 'Settings Saved Successfully!';
		document.body.appendChild(notice);

		jQuery(notice).fadeIn(300).delay(2500).fadeOut(400, function() {
			notice.remove();
		});
	}

	// --- 6. Global Plugin Switch Title Sync ---
	const globalSwitch = document.getElementById('adxbyms_enabled');
	const globalTitle = document.querySelector('.toggle-title');
	
	if (globalSwitch && globalTitle && typeof adxbyms_strings !== 'undefined') {
		globalSwitch.addEventListener('change', function () {
			globalTitle.textContent = this.checked ? adxbyms_strings.pluginActive : adxbyms_strings.pluginInactive;
			
			const container = document.querySelector('.settings-container');
			if (container) {
				if (this.checked) {
					container.style.opacity = '1';
				} else {
					container.style.opacity = '0.85';
				}
			}
		});
	}

	// --- 7. Onboarding Modal Logic ---
	const setupOverlay = document.getElementById('ms-setup-overlay');
	if (setupOverlay) {
		const remindBtn = document.getElementById('ms-setup-remind-later');
		const closeBtn = document.getElementById('ms-setup-close');
		const alreadyBtn = document.getElementById('ms-setup-already-registered');

		function dismissSetup(actionName = 'ms_setup_remind_later') {
			setupOverlay.style.opacity = '0';
			setTimeout(() => {
				setupOverlay.style.display = 'none';
			}, 300);

			// AJAX call
			if (typeof adxbyms_strings !== 'undefined' && adxbyms_strings.ajaxUrl) {
				jQuery.post(adxbyms_strings.ajaxUrl, {
					action: actionName,
					nonce: adxbyms_strings.setupNonce
				});
			}
		}

		if (remindBtn) {
			remindBtn.addEventListener('click', function(e) {
				e.preventDefault();
				dismissSetup('ms_setup_remind_later');
			});
		}

		if (alreadyBtn) {
			alreadyBtn.addEventListener('click', function(e) {
				e.preventDefault();
				dismissSetup('ms_setup_already_registered');
			});
		}

		if (closeBtn) {
			closeBtn.addEventListener('click', function(e) {
				e.preventDefault();
				dismissSetup('ms_setup_remind_later');
			});
		}
	}
});
