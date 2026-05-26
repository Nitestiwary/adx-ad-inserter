=== AdX Ad Inserter - Adsense and Ad Manager Ads ===
Contributors: monetiscopeadx
Donate link: https://monetiscope.com/
Tags: ads, adsense, ad-manager, google-adx, ad-inserter
Requires at least: 5.0
Tested up to: 7.0
Stable tag: 1.3.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Insert Google AdX, Ad Manager, AdSense, popup, rewarded, side rails, and in-content ads. Features an ads.txt editor and centralized GPT manager.

== Description ==

AdX Ad Inserter by Monetiscope is a professional-grade, lightweight, and publisher-friendly ad insertion and monetization management plugin for WordPress. Completely compliant with WordPress Plugin Guidelines, Google Ad Manager (GAM), Google AdX, and AdSense policies, it helps you manage and display your ad inventory safely and efficiently without writing any code.

This version introduces **AdX Ad Inserter Premium Updates**, introducing advanced ad formats, a centralized GPT manager, robust viewport-relative lazy loading, and fine-grained URL exclusions to maximize ad yield while keeping your Core Web Vitals pristine.

= Key Subsystems & Features =

* **Centralized GPT Script Manager** - Loads `gpt.js` exactly once globally with proper async queuing to prevent duplicate initialization, reduce Cumulative Layout Shift (CLS), and boost page load times.
* **AdSense Ads / Custom Sections** - 10 independent code blocks supporting raw script textareas, strict admin HTML sanitization, 8 insertion hook categories (including Before Headings!), alignments, and separate mobile/tablet/desktop screen filters.
* **Responsive Ads** - 5 independent slots auto-mapping GAM creatives dynamically (728x90 on Desktop and 300x250 on Mobile viewports) with unique div ID allocations and custom "Index (X)" offset targets.
* **Updated Popup Overlay** - 24-hour rate limit tracking using client-side localStorage, device filter switches, and advanced category/post page targeting options.
* **Flying Carpet Ads** - 5 parallax scrolling ad blocks with viewport-relative clipping, lazy loading, and smooth CSS acceleration.
* **Side Rail Ads** - Sticky left and right column rails for screens wider than 1200px. Supports viewability-based dynamic refreshing (min 30s) and close buttons.
* **Exclude Links & Pages** - Suppress all ad layouts on specific pages using comma-separated paths or queries.
* **ads.txt Manager (Built-in)** - Edit `/ads.txt` directly from your admin panel. Includes fail-safes and query-vars rewrites.

= Placement & Targeting Controls =

* **Before Content** - Prepend ads to the start of post contents.
* **After Content** - Append ads to the end of post contents.
* **Before / After Paragraph X** - Parse and insert ads dynamically before/after the Xth paragraph.
* **Before / After Image X** - Insert ads before/after the Xth image or figure.
* **Before Heading X** - Position ads right above the Xth heading tag (h1, h2, h3, h4, h5, h6).
* **Sticky Bottom** - Locked bottom overlay with a user close toggle.
* **Parallax Carpets** - Premium middle-of-content viewport scrolling layers.
* **Desktop Side Rails** - Sticky outer columns floating next to the post wrapper.
* **Shortcodes** - Insert ads manually anywhere in posts, pages, or widgets using shortcodes:
  * `[ms_display_ad id="X"]`
  * `[ms_custom_ad id="X"]`
  * `[ms_responsive_ad id="X"]`
  * `[ms_flying_carpet id="X"]`
  * `[ms_side_rail]`

== Installation ==

= Automatic Installation =

1. Go to **Plugins → Add New** in your WordPress admin panel.
2. Search for "AdX Ad Inserter".
3. Click **Install Now** and then **Activate**.

= Manual Installation =

1. Download the plugin ZIP file.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Select the downloaded ZIP file and click **Install Now**.
4. Activate the plugin.

== Configuration ==

1. Navigate to **AdX Ad Inserter** in your WordPress admin menu.
2. Enable the master toggle: set to **Plugin Active**.
3. Configure your ad blocks under individual tabs:
   * **Display Slots**: Configure standard GAM units (adds support for 320x480 and 480x320 mobile dimensions).
   * **Adsense Ads / Custom**: Paste your raw AdSense or scripts, choose insertion (e.g. before heading), alignment, and targets.
   * **Responsive Ads**: Enter your responsive ad slot line and define standard placements. Supports precise "Index (X)" offset targets.
   * **Popup Ads**: Enter slot path, trigger scroll depth, target screens, and set cap to "one time in 24 hours".
   * **Flying Carpet Ads**: Enable full-screen mobile-optimized parallax slots inside content paragraphs.
   * **Side Rail Ads**: Add a single slot line to automatically float matching left/right columns on desktop screens.
   * **Exclude Links**: Paste comma-separated URLs or paths to block ads on checkout, cart, or landing pages.
4. Click **Save Changes** and enjoy safe, optimized monetization!

== Frequently Asked Questions ==

= Why do we encourage users to register a Monetiscope account? =
Registering your free Monetiscope account unlocks a suite of powerful benefits designed to maximize your publishing revenue. Registered users receive priority technical support directly from our ad-ops experts, access to premium monetization optimization tools, seamless plugin updates, and early access to upcoming features like advanced Header Bidding and specialized ad layouts. Registration is entirely optional but highly recommended to get the most out of your ad inventory!

= Does AdX Ad Inserter inject any forced third-party ads? =
No. 100% control remains with you. The plugin only displays the ad units, scripts, and codes that you configure in the admin panel.

= Does this version support Google AdSense? =
Yes. Use the new **Adsense Ads / Custom** section to paste AdSense auto-ads or responsive script blocks directly. The plugin handles secure script parsing for administrators.

= How does the 24-hour popup frequency cap work? =
If set to 24 hours, the plugin saves a timestamp in the visitor's browser localStorage when the popup overlay is shown. The popup will be automatically suppressed for 24 hours even if the user clears their session.

= Will Side Rail ads show on tablets or mobile phones? =
No. Side rails automatically hide on screen widths below 1200px to comply with layout standards and prevent overlapping main content.

= How does viewability-based auto-refresh work? =
If auto-refresh is enabled on Side Rails, the plugin calculates whether the left/right rails are currently visible in the active viewport. It triggers a refresh only when visible, with a minimum 30-second interval, protecting your advertiser quality score.

== External Services ==

This plugin enqueues the following external script resources:

* **Google Publisher Tag (gpt.js)**:
  * **URL**: `https://securepubads.g.doubleclick.net/tag/js/gpt.js`
  * **Purpose**: Serves GAM / AdX slots on the public frontend. Loaded once asynchronously.
  * **Privacy**: Google collects cookies and usage details according to [Google Privacy Policy](https://policies.google.com/privacy).

* **Zapier Chatbot (Admin Only)**:
  * **URL**: `https://interfaces.zapier.com/assets/web-components/zapier-interfaces/zapier-interfaces.esm.js`
  * **Purpose**: Provides administrative help desk widgets on the settings dashboard.
  * **Privacy**: [Zapier Privacy Policy](https://zapier.com/privacy).

== Changelog ==

= 1.3.1 =
* Added: First-time setup onboarding system to seamlessly register users with Monetiscope for priority support, updates, and optimization tools.
* Added: Display Page Exceptions / Target Pages checklists across all Display, Custom, Responsive, and Flying Carpet ad blocks.
* Added: Expanded Placement Insertion target options to 19 standard categories, including Excerpt, Comment Form sections, and dynamic selector HTML element transplanting.
* Added: Enable Custom Scripts Subsystem toggle control inside the Footer Script Injection Card.
* Fixed: Broken sidebar headers overlapping WordPress core top horizontal nav-tabs by isolating vertical tabs completely with `.adx-nav-tab` selectors.
* Restructured: Reorganized plugin into premium modular architecture (admin, public, includes, assets, templates).
* Refactored: Overhauled settings page UI with a premium, modern Slate/Indigo HSL theme and custom Outfit/Inter Google Fonts family.
* Improved: Grouped primary sidebar navigation under distinct Standard Placements, Advanced Ads, and Configuration categories with Dashicons, renaming the tab to a simple "Settings" button.
* Restructured: Split Exclude Links, Header Script, Footer Script, and Ads.txt Manager separately as distinct stacked collapsible card panels vertically.
* Improved: Refined Responsive Ads "Index (X)" offset field to keep it visible at all times, adding dynamic dimmed/disabled states when standard insertions are selected, and instantly illuminating it on tag-based placements.
* Restored: Fully re-integrated the broken frontend loader for Offerwall Ads (onscroll) and added a highly requested close/skip button, allowing visitors to temporarily skip the ad for their session.
* Fixed: Updated the Zapier chatbot script widget to run asynchronously in the admin panel.
* Added: Adsense Ads / Custom section supporting 10 independent blocks, raw textareas, heading insertions, and alignments.
* Added: Display Ads dimension update adding 320x480 and 480x320 selections.
* Added: Responsive Ads section auto-mapping 728x90 (Desktop) and 300x250 (Mobile) GAM slots.
* Added: Popup Ad update introducing 24-hour localStorage rate limiting, context page filters, and screen categories.
* Added: Flying Carpet Ads section supporting 5 parallax scrolling in-content blocks with viewport lazy loading.
* Added: Side Rail Ads supporting desktop left/right sticky rails and viewability auto-refresh (min 30s).
* Added: Exclude Links section allowing global path-prefix and query-safe exclusions.
* Improved: Centralized GPT Manager enqueues gpt.js exactly once asynchronously and queues all defineSlots securely.
* Security: Implemented unfiltered_html capability filters, wp_kses sanitizations, and robust nonces on options inputs.

= 1.3.0 =
* Bugs fixed
* Security Fixes

= 1.2.0 =
* Improved: Before Post ad placement finds title accurately across themes.
* Improved: Output buffering replaced with clean hooks.
* Security: Proper escaping and sanitizations.

= 1.1.0 =
* Added: Device targeting for display ads.
* Added: Page type targeting.
* Added: Sub-slots up to 10 display units.

= 1.0.0 =
* Initial release of AdX Ad Inserter.

== Upgrade Notice ==

= 1.3.1 =
Highly recommended premium upgrade. Adds new ad formats (AdSense, Custom, Responsive, Flying Carpet Parallax, Sticky Side Rails), centralized GPT loading for faster pages, 24h popup capping, and robust URL exclusion targeting. Completely backward-compatible.
