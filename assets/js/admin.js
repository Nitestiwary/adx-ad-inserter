/**
 * Admin Panel Javascript - AdX Ad Inserter Premium Updates
 */

document.addEventListener('DOMContentLoaded', function () {
	console.log('[AdX] Admin JS Loaded');

	// --- 1. Primary Left Sidebar Tabs Switching ---
	const tabs = document.querySelectorAll('.nav-tab');
	const tabContents = document.querySelectorAll('.adx-tab');

	function hideAllPrimaryTabs() {
		tabContents.forEach(content => {
			content.style.display = 'none';
		});
		tabs.forEach(tab => {
			tab.classList.remove('nav-tab-active');
		});
	}

	tabs.forEach(tab => {
		tab.addEventListener('click', function (e) {
			e.preventDefault();
			const targetId = tab.getAttribute('data-target');
			if (!targetId) return;

			hideAllPrimaryTabs();

			// Activate tab link
			tab.classList.add('nav-tab-active');

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
		const tabLink = document.querySelector(`.nav-tab[data-target="${activeTab}"]`);
		if (tabLink) {
			tabLink.classList.add('nav-tab-active');
		}
		document.getElementById(activeTab).style.display = 'block';
	} else {
		// Fallback default
		const defaultTab = document.querySelector('.nav-tab');
		if (defaultTab) {
			defaultTab.click();
		}
	}

	// --- 2. Subtab Horizontal Switching (e.g. Display Ad Blocks, Responsive Blocks, etc.) ---
	function setupSubtabs(tabClass, contentClass) {
		const tabContainers = document.querySelectorAll(tabClass).forEach(tab => {
			tab.addEventListener('click', function () {
				const parent = tab.closest('.adx-tab');
				if (!parent) return;

				const siblingTabs = parent.querySelectorAll(tabClass);
				const siblingContents = parent.querySelectorAll(contentClass);

				// Find index of clicked tab
				let index = 0;
				for (let i = 0; i < siblingTabs.length; i++) {
					if (siblingTabs[i] === tab) {
						index = i;
						break;
					}
				}

				// Deactivate siblings
				siblingTabs.forEach(t => t.classList.remove('active'));
				siblingContents.forEach(c => c.classList.remove('active'));

				// Activate current
				tab.classList.add('active');
				if (siblingContents[index]) {
					siblingContents[index].classList.add('active');
				}
			});
		});

		// Trigger click on first subtab for each section on load
		document.querySelectorAll('.adx-tab').forEach(section => {
			const firstTab = section.querySelector(tabClass);
			if (firstTab) {
				firstTab.click();
			}
		});
	}

	setupSubtabs('.display-tab', '.display-content');
	setupSubtabs('.responsive-tab', '.responsive-content');
	setupSubtabs('.flying-carpet-tab', '.flying-carpet-content');

	// --- 3. Collapsible Card Toggle (for Adsense Ads / Custom) ---
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

	// --- 4. Offset Input Display Toggle based on Insertion Selectors ---
	const insertionSelectors = document.querySelectorAll('select[id$="_insertion"]');
	
	function toggleOffsetField(selectElement) {
		if (!selectElement) return;

		// Extract target ID components
		const parentRow = selectElement.closest('.flex-row-fields') || selectElement.closest('.display-content') || selectElement.closest('.card-body') || selectElement.closest('.custom-tab-content-block');
		if (!parentRow) return;

		const offsetWrapper = parentRow.querySelector('.offset-wrapper');
		if (!offsetWrapper) return;

		const value = selectElement.value;
		// Show offset for specific paragraph, image, heading, or carpet selectors
		if (['before_paragraph', 'after_paragraph', 'before_image', 'after_image', 'before_heading', 'after_paragraph_x'].includes(value)) {
			offsetWrapper.style.display = 'flex';
		} else {
			offsetWrapper.style.display = 'none';
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
			
			// Visually fade container if disabled
			const container = document.querySelector('.settings-container');
			if (container) {
				if (this.checked) {
					container.style.opacity = '1';
					container.style.pointerEvents = 'auto';
					container.style.cursor = 'default';
				} else {
					container.style.opacity = '0.5';
					container.style.pointerEvents = 'none';
					container.style.cursor = 'not-allowed';
				}
			}
		});
	}
});
