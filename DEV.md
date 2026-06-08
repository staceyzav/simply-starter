# Simply Design — Dev Working Notes
**Internal use only. Not for clients.**
Last updated: 2026-06-04 (evening)

---

## How to Pick Up After a Restart

**First thing every session:** Update the `Last updated:` date at the top of this file so we always know how fresh the notes are.

Read this file first. Then read `STANDARDS.md` for the system architecture. Then check git status for what's uncommitted. That's enough context to continue without re-explaining anything.

Key files:
- `STANDARDS.md` — system architecture, plugin rules, AI assistant guide (ships with theme)
- `style.css` — all theme CSS, token defaults at top of `:root`
- `functions.php` — all theme PHP
- `assets/js/simply-scroll.js` — mobile menu, smooth scroll, accordion
- `page-templates/` — Page Builder + Standard Post templates

---

## Current Checklist

### Simply Starter Theme
- [x] Token system (`--client-*` + `--ss-*`)
- [x] Section colors — `.is-dark` `.is-light` `.is-brand-1` `.is-brand-2`
- [x] Section colors via inheritance only — no `!important` on `p`/`li`/`span`
- [x] Wireframe mode (admin bar toggle, deactivates client config)
- [x] Welcome page at Appearance → Simply Starter
- [x] Toolset container default inner width (JS-based, 1200px)
- [x] Fixed header + `.ss-homepage` / `.ss-interior` body classes
- [x] Mobile slide-in menu (`simply-scroll.js`)
- [x] 4-column footer + `.footer-bottom-bar`
- [x] Page Builder template (full-width, no title/meta)
- [x] Standard Post template (800px centered, featured image hero)
- [x] Disable Comments toggle
- [x] IMF-specific code removed from theme (fonts, hero CSS, imf-scroll.js)
- [x] `STANDARDS.md` written — plugin rules, token system, AI assistant guide
- [x] `DEV.md` written — build status, checklist, session continuity
- [x] Plugin naming settled — Simply Branded + Simply AI Custom Extensions
- [ ] Commit + tag v2.10.0
- [ ] `DEV.md` and `STANDARDS.md` added to git + committed
- [ ] `.github/` folder — review and commit or clean up
- [ ] Test full theme on a clean site (no IMF plugins)
- [ ] **Break Genesis dependency — make Simply Starter a standalone WP theme** ← final goal

### Simply Branded (Per-Client Plugin)
- [ ] Build clean template plugin (stripped of all IMF-specific content)
- [ ] Document: plugin header, token setup, font loading options
- [ ] Test: tokens override Simply Starter defaults correctly
- [ ] Test: wireframe mode deactivates Simply Branded and falls back cleanly

### Simply AI Custom Extensions (Per-Client Plugin)
- [ ] Build clean template plugin (empty starter with correct structure)
- [ ] Include example: one custom post type, one shortcode stub
- [ ] Document: what goes here vs. what goes in Simply Branded

### Simply Blocks (Paid Suite)
- [x] Simply Section block — section color classes, bg/image/video support
- [x] Simply Container block — max-width, narrow/full-width classes
- [x] Simply News block — 3-column feed, category filters
- [x] Section colors use inheritance (no `!important` on text elements) — fixed
- [ ] Finalize block styles and test on non-IMF site
- [ ] Add `STANDARDS.md` to Simply Blocks repo
- [ ] Ship with Simply Starter as the default page-building system

### Free Plugins — WP.org Prep
Each plugin needs: i18n, readme.txt, settings page → simplydesign.com, screenshots

| Plugin | Exists | i18n | Readme | Settings page | Submitted |
|--------|--------|------|--------|---------------|-----------|
| Simply News | ✅ | ☐ | ☐ | ☐ | ☐ |
| Simply Events | ✅ | ☐ | ☐ | ☐ | ☐ |
| Simply Utility Bar | ✅ | ☐ | ☐ | ☐ | ☐ |
| Simply FAQs | ☐ planned | — | — | — | — |
| Simply Logo Slider | ☐ planned | — | — | — | — |

**Simply News**
- Shortcode: `[simply_news]` — post feed, 3 columns, category filters, read more link
- Block: Simply News block (part of Simply Blocks)
- Tokens: `--client-accent`, `--client-font-primary`, `--sn-radius`
- Works on any theme; picks up WP global styles automatically

**Simply Events**
- Shortcode: `[simply_events]` — event cards with date block, title, location, filters
- Tokens: `--client-accent`, `--client-heading`, `--client-font-display`, `--se-radius`
- Works on any theme; picks up WP global styles automatically

**Simply Utility Bar**
- Sticky top bar that scrolls away on scroll-down, returns on scroll-up
- Extracted from `imf-scroll.js` into its own plugin
- No tokens yet — needs `--client-*` integration pass before WP.org

**Simply FAQs** *(planned)*
- Accordion-style FAQ, shortcode-based
- Tokens: `--client-accent` for open state, `--client-font-primary` for body text

**Simply Logo Slider** *(planned)*
- Sponsor/partner logo carousel, shortcode-based
- Grayscale logos on hover → full color, configurable speed

- [ ] Decide WP.org submission order — which plugin goes first?

### Simply Config (Future — Open Question)
- [ ] **Decide:** build it, bake basic options into Simply Starter welcome page, or skip?
  - Option A: Skip — AI builds Simply Branded per client from STANDARDS.md
  - Option B: Bake basic color pickers into Appearance → Simply Starter (no new plugin)
  - Option C: Full standalone plugin — color pickers, font input, logo, custom CSS → tokens

### Paid Suite Launch (simplydesign.com)
- [ ] Landing page for Simply Starter + Simply Blocks + Simply Config
- [ ] Lemon Squeezy account + product setup
- [ ] License/activation system decision
- [ ] Pricing page

---

## Open Decisions

- **Simply Config** — build it, bake it into theme settings, or skip it if AI handles brand setup well enough?
- **WP.org submission order** — which free plugin goes first?
- **Simply Blocks pricing** — part of Simply Starter bundle or separate?

---

## Build Notes (running log)

**2026-06-05 (continued)**
- Simply Logo Slider: added crop-tight helper text + boost checkbox to CPT meta box
- Simply Logo Slider block: added boost checkbox per logo in custom mode, tips below preview
- Logo slider: drag-to-scroll (mouse + touch) on both standalone and block
- Logo slider: centering fix when not enough logos to scroll (standalone now matches block)
- Logo slider: boost CSS + drag CSS added to block's style.scss, synced with standalone

**2026-06-05**
- Added rounded corners to `.wp-block-button__link` in IMF config
- Added `padding: 8px 40px` and `font-size: 14px` to `.wp-block-button__link` + `.se-events-cta` in IMF config
- Added `.is-dark .wp-block-button__link` outline style to Simply Starter (white border, transparent bg, fills on hover)
- Simply News: fixed IMF class leak (imf-series-block → sn-feed-block), added simplydesign.com link to settings, wrote readme.txt
- Simply Section block: added `paddingUnit` (px/%), `innerWidthUnit` (px/%), `marginTop`, `marginBottom` — built and shipped

**2026-06-04 (evening) — stopped here**
- Fixed Simply Events stretch-link bug — `.se-event-card` was missing `position: relative`, causing the `::after` pseudo-element (`inset: 0`) to climb the DOM and cover the entire page including the hero
- File fixed: `simply-events/assets/css/simply-events.css` — added `position: relative` to `.se-event-card`
- Next up: commit v2.10.0, then build Simply Branded template plugin

**2026-06-04 (morning)**
- Completed IMF/Simply Starter separation — all IMF-specific styles moved to `imf-client-config`
- Removed `NothingYouCouldDo` fonts from theme (IMF-only, belongs in client config)
- Removed `imf-scroll.js` (moved to `simply-utility-bar` plugin)
- Fixed section color inheritance — no more `!important` on text elements in Simply Blocks
- Added Page Builder + Standard Post page templates (v2.10.0)
- Added Disable Comments toggle (v2.10.0)
- Wrote `STANDARDS.md` — ships with theme for AI assistance
- Added Plugin Design Principles, Common Tasks, and For AI Assistants sections to STANDARDS.md
- Settled plugin naming: Simply Branded (styles) + Simply AI Custom Extensions (functions)

**Product strategy settled 2026-06-04:**
- Free on WP.org: Simply News, Events, FAQs, Logo Slider, Utility Bar
- Paid suite (simplydesign.com): Simply Starter + Simply Blocks + Simply Config
- Team-only users while we dial it in, no paid tier until product is solid
- Lemon Squeezy for payments when ready

---

## Plugin Architecture Quick Ref

```
Simply Starter (theme — paid suite)
  └── --client-* defaults + --ss-* structural tokens
  └── Genesis child, fixed header, page templates, wireframe mode

Simply Blocks (plugin — paid suite)
  └── Simply Section, Simply Container, Simply News block
  └── Reads --client-* tokens, section color via inheritance

Simply Branded (per-client plugin)
  └── Overrides --client-* tokens for the client's brand
  └── Loads fonts (Typekit / Google / self-hosted)
  └── Client-specific CSS overrides

Simply AI Custom Extensions (per-client plugin)
  └── Custom post types, taxonomies, shortcodes, admin tweaks
  └── AI-built, client-specific — nothing here touches Simply plugins or theme

Simply News / Events / FAQs / etc. (free plugins — WP.org)
  └── Shortcode-based, zero dependencies, works on any theme
  └── Read --client-* tokens with fallbacks
```
