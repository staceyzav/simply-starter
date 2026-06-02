# Simply Starter — Changelog
https://simplydesign.com

## [2.8.0] — 2026-05-27

### Fixed
- Footer Bottom Bar widget area restored — was accidentally dropped during build process
- Footer Bottom Bar output hook restored (genesis_before_footer priority 15)

---

## [2.7.0] — 2026-05-27

### Fixed
- Toolset container default width — PHP filter approach replaced with JS editor script
  block_type_metadata_settings filter didn't work because Toolset registers blocks in JS
  New approach: wp.data.subscribe watches for new containers, sets inner width immediately
  Uses wp.data.dispatch('core/block-editor').updateBlockAttributes — stable WP core API

### Added
- simply-editor.js — block editor script, loads only in wp-admin editor
  Subscribes to block changes, finds Toolset containers with null inner width
  Sets them to configured width (default 1200px) automatically on insertion
  Processes each block only once via processed{} tracker
  Handles nested blocks recursively
  Logs to console: "Simply Starter: set Toolset container [id] inner width to 1200px"
- simplyEditorData localized variable — passes PHP settings to editor JS
  containerWidth reads from simply_container_width wp_option

---

## [2.6.0] — 2026-05-27

### Added
- Toolset container inner width default — block_type_metadata_settings filter
  sets inner container to 1200px on ALL new Toolset containers automatically
  No more enabling inner container per block — it's on by default
- Container Settings on welcome page (Appearance → Simply Starter)
  Field to set default inner container width (600–2400px, default 1200px)
  Saves to wp_options as simply_container_width
- Dynamic CSS output — .tb-container-inner max-width reads from saved option
  Change the setting once, all containers update automatically
- Settings save handler with nonce verification and sanity check

### Notes
- Existing Toolset containers unaffected — update individually in Toolset settings
- New containers get 1200px inner width automatically from block insertion

---

## [2.5.0] — 2026-05-27

### Fixed
- Group block inner container: max-width 1200px, padding 0 5px — matches Toolset pattern
- Group block: replaced max-width:100% with proper constraint on .is-layout-constrained
- Toolset outer container: removed all overrides — Toolset default 25px padding untouched
- Toolset inner (.tb-container-inner): max-width 1200px, padding 0 5px only when present
- Removed aggressive Case A Toolset outer container rules from v2.4.0

### Changed
- h1-h6 margin: 10px 0 5px — tighter heading spacing below

---

## [2.4.0] — 2026-05-27

### Fixed
- Toolset container inner width — two structural cases handled:
  A) No inner div (default): override outer padding, constrain direct children
  B) With inner div: .tb-container-inner gets max-width, padding zeroed on outer
- Toolset default 25px padding overridden with 5% horizontal, 60px vertical

---

## [2.3.0] — 2026-05-27

### Fixed
- Group block colors not applying — Gutenberg/Genesis CSS had higher specificity
  Fix: full .wp-block-group.is-style-dark selectors with !important
- Group block inner container width — Gutenberg's is-layout-constrained was
  overriding our max-width. Fixed by targeting that class specifically
- Toolset container inner width — inline styles overriding CSS
  Fix: .tb-container.is-dark > .tb-container-inner with !important

---

## [2.2.0] — 2026-05-27

### Added
- Welcome page at Appearance -> Simply Starter replaces Genesis "Get Started" screen
- Welcome page: branded content, client config status, wireframe toggle, setup checklist
- Redirect from Genesis onboarding URL to Simply Starter welcome page
- Wireframe mode auto-toggle: turning ON deactivates client config plugin, turning OFF reactivates it
- Plugin slug stored in wp_options so reactivation survives page loads
- simply_get_active_client_config() helper function

### Fixed
- Container CSS: outer sections now truly full-width (bg/images edge to edge)
- Inner content containers max-width 1200px centered with 5% horizontal padding
- Section classes on Toolset containers correctly separated outer/inner concerns
- Gutenberg Group block inner containers also get max-width constraint

---

## [2.8.0] — 2026-05-27

### Fixed
- Footer Bottom Bar widget area restored — was accidentally dropped during build process
- Footer Bottom Bar output hook restored (genesis_before_footer priority 15)

---

## [2.7.0] — 2026-05-27

### Fixed
- Toolset container default width — PHP filter approach replaced with JS editor script
  block_type_metadata_settings filter didn't work because Toolset registers blocks in JS
  New approach: wp.data.subscribe watches for new containers, sets inner width immediately
  Uses wp.data.dispatch('core/block-editor').updateBlockAttributes — stable WP core API

### Added
- simply-editor.js — block editor script, loads only in wp-admin editor
  Subscribes to block changes, finds Toolset containers with null inner width
  Sets them to configured width (default 1200px) automatically on insertion
  Processes each block only once via processed{} tracker
  Handles nested blocks recursively
  Logs to console: "Simply Starter: set Toolset container [id] inner width to 1200px"
- simplyEditorData localized variable — passes PHP settings to editor JS
  containerWidth reads from simply_container_width wp_option

---

## [2.6.0] — 2026-05-27

### Added
- Toolset container inner width default — block_type_metadata_settings filter
  sets inner container to 1200px on ALL new Toolset containers automatically
  No more enabling inner container per block — it's on by default
- Container Settings on welcome page (Appearance → Simply Starter)
  Field to set default inner container width (600–2400px, default 1200px)
  Saves to wp_options as simply_container_width
- Dynamic CSS output — .tb-container-inner max-width reads from saved option
  Change the setting once, all containers update automatically
- Settings save handler with nonce verification and sanity check

### Notes
- Existing Toolset containers unaffected — update individually in Toolset settings
- New containers get 1200px inner width automatically from block insertion

---

## [2.5.0] — 2026-05-27

### Fixed
- Group block inner container: max-width 1200px, padding 0 5px — matches Toolset pattern
- Group block: replaced max-width:100% with proper constraint on .is-layout-constrained
- Toolset outer container: removed all overrides — Toolset default 25px padding untouched
- Toolset inner (.tb-container-inner): max-width 1200px, padding 0 5px only when present
- Removed aggressive Case A Toolset outer container rules from v2.4.0

### Changed
- h1-h6 margin: 10px 0 5px — tighter heading spacing below

---

## [2.4.0] — 2026-05-27

### Fixed
- Toolset container inner width — two structural cases handled:
  A) No inner div (default): override outer padding, constrain direct children
  B) With inner div: .tb-container-inner gets max-width, padding zeroed on outer
- Toolset default 25px padding overridden with 5% horizontal, 60px vertical

---

## [2.3.0] — 2026-05-27

### Fixed
- Group block colors not applying — Gutenberg/Genesis CSS had higher specificity
  Fix: full .wp-block-group.is-style-dark selectors with !important
- Group block inner container width — Gutenberg's is-layout-constrained was
  overriding our max-width. Fixed by targeting that class specifically
- Toolset container inner width — inline styles overriding CSS
  Fix: .tb-container.is-dark > .tb-container-inner with !important

---

## [2.2.0] — 2026-05-27

### Added
- Welcome screen at Appearance -> Simply Starter
  - Setup checklist with live status (client config active/not)
  - Section color scheme reference table
  - Wireframe mode status + toggle link
  - Notes and tips section
  - Replaces Genesis Sample "Starter Packs" onboarding page
- Client config detection: shows install prompt if not active

### Fixed
- Gutenberg block styles: renamed from is-dark to dark so Gutenberg
  correctly outputs is-style-dark (not is-style-is-dark)
- CSS now targets both is-dark (Toolset) and is-style-dark (Gutenberg)
- Section inner container: Toolset .tb-container-inner constrained to 1200px
- Section inner container: Gutenberg .wp-block-group__inner-container constrained
- Section bg now truly full width — padding removed from outer, applied to inner only

---

## [2.1.0] — 2026-05-27

### Fixed
- Text Domain corrected to simply-starter (was genesis-sample) — this was preventing
  genesis_get_theme_handle() from returning the correct stylesheet handle, causing
  the client config plugin to fail silently

### Added
- Editor stylesheet support (add_editor_style) — block style colors now visible in backend
- Toolset container defaults: max-width 1200px, padding 0 5%, override with full-width or narrow class
- Editor-side CSS for .is-dark/.is-light/.is-brand-1/.is-brand-2 block previews

---

## [2.0.0] — 2026-05-27

### Added
- Full client token system: --client-bg, --client-text, --client-heading, --client-link
- Nav tokens: --client-nav-bg/text/highlight/highlight-text
- Accent tokens: --client-accent/text/hover
- 4 section color schemes: --client-section-dark/light/brand1/brand2 (bg/text/heading/highlight)
- .is-dark, .is-light, .is-brand-1, .is-brand-2 CSS classes for sections
- Gutenberg block styles registered for Group + Cover blocks
- Wireframe mode: admin bar toggle, saves as WP option
- Wireframe banner: client-visible, dismissible, admin-only "turn off" link
- Wireframe body class: .simply-wireframe
- Global default styles: body, headings, links, buttons via client tokens
- --ss-* structural tokens (internal, never overridden by client)

### Changed
- Token system completely redesigned — no --client-primary, fully decoupled
- --imf-* aliases now map to --client-* tokens automatically
- Wireframe neutral defaults: charcoal #2d2d2d primary, gray #6b6b6b accent

## [1.0.0] — 2026-05-27
### Initial release
