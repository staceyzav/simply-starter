# Simply Design — Plugin & Theme Standards
**For AI-assisted site building and developer reference.**

Simply Design builds sites using a layered system: a generic starter theme, a branding plugin (Simply Branded or Client Branded), and a suite of standalone content plugins. Everything is connected through a shared CSS token system — set the tokens once and the entire site updates automatically.

---

## The Token System

All plugins and theme use CSS custom properties (`--client-*`). Set them in Simply Branded or a Client Branded plugin once — every plugin on the site picks them up automatically.

### Available Tokens

```css
/* Page */
--client-bg               /* Page background */
--client-text             /* Body text */
--client-heading          /* Heading color */
--client-link             /* Link color */
--client-link-hover       /* Link hover */

/* Navigation */
--client-nav-bg           /* Nav bar background */
--client-nav-text         /* Nav link text */
--client-nav-highlight    /* Active/hover nav color */
--client-nav-highlight-text

/* Accent / Buttons */
--client-accent           /* Primary button/badge color */
--client-accent-text      /* Text on accent bg */
--client-accent-hover     /* Hover state */

/* Fonts */
--client-font-display     /* Headings, titles */
--client-font-primary     /* Body, paragraphs */
--client-font-script      /* Script/decorative accent */

/* Section Colors */
--client-section-dark-bg         --client-section-dark-text
--client-section-dark-heading    --client-section-dark-highlight
--client-section-light-bg        --client-section-light-text
--client-section-light-heading   --client-section-light-highlight
--client-section-brand1-bg       --client-section-brand1-text
--client-section-brand1-heading  --client-section-brand1-highlight
--client-section-brand2-bg       --client-section-brand2-text
--client-section-brand2-heading  --client-section-brand2-highlight
```

### Fallback Chain
Tokens resolve in this order — highest priority first:
1. **Client Branded or Simply Branded** — full client brand (colors, fonts, logo)
2. **WordPress global styles** — any FSE/block theme's theme.json presets
3. **Simply Starter defaults** — clean, professional generic look

This means all Simply plugins work on ANY WordPress theme, even without the client config.

---

## Plugin Design Principles

Every Simply plugin follows these rules. When generating code for this system, follow them exactly.

1. **Token-first** — Never hardcode colors or fonts. Always use `--client-*` tokens with a fallback: `var(--client-accent, #333)`.
2. **WP Global Styles interop** — `--client-*` defaults pull from `--wp--preset--*` first, so plugins automatically inherit any theme's registered colors and fonts with zero config.
3. **Inheritance over `!important`** — Section colors are set on the container only. Never apply `color` or `background-color` with `!important` to `p`, `li`, `span`, or text elements. Inner plugin components set their own explicit colors and win naturally over inherited values.
4. **Framework-agnostic plugins** — Plugins use pure CSS and vanilla JS only. Genesis hooks and template functions belong in Simply Starter (theme) only — never in plugins.
5. **Always provide fallbacks** — Every `var()` in a plugin must include a hardcoded fallback as the last argument so it renders cleanly on any WordPress theme without any config.

---

## Simply Starter Theme

**Handle:** `simply-starter`
**Purpose:** Generic Genesis child theme. No client branding — structure only.

### Internal Tokens (never overridden by client)
```css
--ss-white, --ss-off-white, --ss-light-gray, --ss-dark-gray, --ss-near-black
--ss-header-height   /* Fixed nav height (default 80px) */
--ss-max-width       /* Content max-width (default 1200px) */
--ss-transition      /* Standard CSS transition */
--ss-shadow          /* Standard box shadow */
```

### Key Features
- Fixed header, overlays hero on homepage
- `.ss-homepage` / `.ss-interior` body classes (homepage = no header padding, interior = padded)
- Mobile slide-in menu via `simply-scroll.js`
- 4-column footer widget area + `.footer-bottom-bar`
- Wireframe mode (toggle from admin bar — deactivates branding plugin for UX review)
- Container width configurable from Appearance → Simply Starter

### Bleed Image Utility
`.bleed` makes a child image fill its container edge-to-edge (`object-fit: cover`). Add it to a Simply Column block's Additional CSS class — the image will stretch to match the height of the adjacent column.

Works by passing `height: 100%` through the WP figure wrapper to the image, so it relies on the container having a resolved height (grid items always do). If used outside a grid column, set an explicit height on the container.

### Responsive Visibility Utilities
Add to any block via **Advanced → Additional CSS class** in the block editor.

| Class | Behavior |
|---|---|
| `.hide-below-480` | Hidden below 480px |
| `.hide-below-600` | Hidden below 600px |
| `.hide-below-768` | Hidden below 768px |
| `.hide-below-960` | Hidden below 960px |
| `.hide-above-480` | Hidden at 480px and above |
| `.hide-above-600` | Hidden at 600px and above |
| `.hide-above-768` | Hidden at 768px and above |
| `.hide-above-960` | Hidden at 960px and above |

**Common patterns:**
- Show something only on mobile: `hide-above-600`
- Show something only on desktop: `hide-below-960`
- Hide on small mobile only: `hide-above-480` on the full version + `hide-below-480` on a simplified version

### Page Templates
- **Page Builder** (default for pages) — full-width, no title/meta
- **Standard Post** (default for posts) — 800px centered, featured image hero
- **Landing** — strips nav, utility bar, header padding, footer, and footer widgets. Content starts at viewport top. Use for event/campaign/evite pages.

### Auto-Updates
Simply Starter self-updates via GitHub releases (`staceyzav/simply-starter`). The updater class lives at `includes/class-github-updater.php`. To ship an update:
1. Bump `Version:` in `style.css` and the version string in `functions.php`
2. `git add . && git commit -m "..."  && git tag vX.X.X && git push && git push origin vX.X.X`
3. All connected sites see "Update available" in WP Admin

---

## Simply Blocks Plugin

**Blocks:** Simply Section, Simply Container, Simply Columns, Simply News, Simply FAQs, Simply Logo Slider

### Simply Section
Wraps full-width page sections with background color/image/video support.

**Usage:** Add a Simply Section block, then add content inside it.

**Color schemes** — add class to the block:
- `.is-dark` — dark bg, light text
- `.is-light` — light gray bg, dark text
- `.is-brand-1` — brand accent color 1
- `.is-brand-2` — brand accent color 2

Colors are set by the `--client-section-*` tokens. Section color is inherited by all children — plugin components and editor-set block colors override naturally.

**Background options:** solid color, image (via block settings), video (YouTube or self-hosted)

**Hero sections** (`.is-home-hero`, `.is-page-hero`) — both get the `hero` class automatically. When a hero section contains two buttons, the first is outlined automatically via `box-shadow: inset` using `--client-section-dark-bg`. No extra configuration needed.

**Mobile padding override** — in the Layout panel, toggle "Override padding on mobile" to reveal top/bottom padding sliders that only apply at `max-width: 767px`. Outputs a scoped `<style>` tag per block instance — no CSS cascade issues, no `!important` needed. Use this instead of fighting block editor inline styles from the theme/branded CSS.

### Simply Container
Inner content container. Controls max-width and padding.

**Classes:**
- `.full-width` — removes max-width constraint
- `.narrow` — 800px max-width

### Simply News (Block)
Three-column post feed with category filters. Uses `--client-*` tokens for all colors and fonts.

**Tokens:** `--sn-columns` (grid columns), `--sn-radius` (card photo corner radius)

---

## Simply News Plugin

**Shortcode:** `[simply_news]`
**CSS handle:** `simply-news`

### Shortcode Attributes
```
[simply_news
  limit="6"       — number of posts
  columns="3"     — grid columns
  category="news" — filter by category slug
  read_more="Read Now"
  heading="Latest News"
]
```

### CSS Tokens Used
| Token | Purpose | Fallback |
|-------|---------|---------|
| `--client-accent` | Filter button color | `#333` |
| `--client-accent-text` | Filter button text | `#fff` |
| `--client-font-primary` | Card title/text font | `sans-serif` |
| `--sn-radius` | Card photo border-radius | `0` |

### Card Classes
`.sn-feed`, `.sn-card`, `.sn-card__photo`, `.sn-card__body`, `.sn-card__title`, `.sn-card__category`, `.sn-card__date`, `.sn-card__read-more`

---

## Simply Events Plugin

**Shortcode:** `[simply_events]`
**CSS handle:** `simply-events`

### Tokens Used
| Token | Purpose | Fallback |
|-------|---------|---------|
| `--client-accent` | Date block bg | `#555` |
| `--client-accent-text` | Date block text | `#fff` |
| `--client-heading` | Title + filter | `#2d2d2d` |
| `--client-section-light-bg` | Events section bg | `#f2f2f2` |
| `--client-font-display` | Dates + titles | `Arial` |
| `--client-font-primary` | Body text | `Arial` |
| `--client-radius` | Card border-radius (via `ss-card`) | `0` |

### Card Classes
`.se-events-block`, `.se-event-card.ss-card`, `.se-event-card__date`, `.se-event-card__day`, `.se-event-card__month`, `.se-event-card__body.ss-card-body`, `.se-event-card__title`

---

## Simply Team Plugin

**Shortcode:** `[simply_team]`
**CSS handle:** `simply-team`

### Shortcode Attributes
```
[simply_team
  limit="12"    — number of members
  columns="3"   — grid columns (default 3)
]
```

### Tokens Used
| Token | Purpose | Fallback |
|-------|---------|---------|
| `--client-accent` | Button/link color | `#2563eb` |
| `--client-heading` | Card name color | `#1a1a1a` |
| `--client-font-display` | Name heading font | `sans-serif` |
| `--client-font-primary` | Body/contact text | `sans-serif` |
| `--client-radius` | Card border-radius (via `ss-card`) | `0` |

### Card Classes
`.st-team`, `.st-card`, `.st-card__inner.ss-card`, `.st-card__photo`, `.st-card__body.ss-card-body`, `.st-card__name`, `.st-card__role`, `.st-card__more`

### Slide Panel
Clicking "More Info" opens a slide-over panel from the left with full bio. Panel classes: `.st-panel`, `.st-panel__header`, `.st-panel__photo`, `.st-panel__name`, `.st-panel__bio`, `.st-panel__close`. Overlay: `.st-overlay`.

---

## Simply Branded Plugin (Paid Upgrade)

**Purpose:** Branding admin UI for Simply Starter sites. Outputs `--client-*` tokens via `wp_head` at priority 99 — overrides all theme defaults.

### What it controls (admin UI)
- **Colors** — 6 palette fields: Light Neutral, Dark Neutral, Brand 1, Brand 2, Highlight, Highlight 2. All `--client-section-*` tokens derived automatically.
- **Border radius** — one setting, applies globally to all `ss-card` elements and buttons via `--client-radius`
- **Border width** — global border width
- **Fonts** — 3 slots: Display (headings), Primary (body), Highlight/Script. Supports Typekit kit ID, Google Fonts URL, or self-hosted.
- **Custom CSS** — freeform CSS block appended after tokens

### Client Branded (agency alternative — AI-built)
For Simply Design agency clients, we build a **Client Branded** plugin instead — same role as Simply Branded but hardcoded:
- No admin UI — all values set in code by AI
- Plugin name: `[ClientName] Branded` (e.g. IMF Branded, Simply Invited Branded)
- Built from this STANDARDS.md + the client's brand brief
- See Common Tasks below for build instructions

### Rounded shapes
All `ss-card` elements automatically inherit `--client-radius`. Set it once in Simply Branded's admin UI or in Client Branded CSS:
```css
:root { --client-radius: 8px; }
```
Buttons do not auto-inherit — add explicit CSS to Simply Branded custom CSS or Client Branded:
```css
.button, input[type="submit"], .wp-block-button__link {
    border-radius: var(--client-radius, 0) !important;
}
```
For photo-only radius on Simply News cards (open-card design, no ss-card shell):
```css
:root { --sn-radius: 8px; }
```

---

## Simply Logo Slider Plugin

**Shortcode:** `[simply_logos]`
**CSS handle:** `simply-logo-slider`

Auto-scrolling logo strip. Grayscale by default, full color on hover, pauses on hover. Only animates when logos exceed container width.

### Shortcode Attributes
```
[simply_logos
  height="60"     — logo height in px (default: 60)
  speed="30"      — scroll duration in seconds — lower = faster (default: 30)
  gap="80"        — space between logos in px (default: 80)
  limit="-1"      — max logos to show, -1 for all (default: -1)
  order="ASC"     — sort order by menu_order: ASC or DESC (default: ASC)
  static="1"      — static mode: no animation, full color, no repeat (default: off)
]
```

**Static mode** — use `static="1"` for footer logo bars, partner grids, or anywhere logos should display without scrolling. Logos appear full color at rest (opacity 0.8 on hover). Combines cleanly with the footer-bottom-bar widget. Example: `[simply_logos static="1" height="120" gap="1"]`

Each logo CPT entry supports: featured image, link URL (`_logo_url`), boost flag (`_logo_boost` — makes that logo 30% taller). Links open in new tab automatically when URL is set.

---

## Simply Podcasts Plugin

**Shortcode:** `[simply_podcasts]`
**CPT:** `simply_podcast`
**CSS handle:** `simply-podcasts`
**Path:** `wp-content/plugins/simply-podcasts/`

RSS-synced podcast CPT. Pulls episodes from any podcast RSS feed (Buzzsprout, Libsyn, etc.) into WP. Displays as a responsive card grid with inline audio player, optional season filter buttons, and a full-description popup.

**Setup:** Settings → Simply Podcasts → paste RSS feed URL → Save → WP Admin → Podcast → Sync Podcasts Now.

### Shortcode Attributes
```
[simply_podcasts
  columns="3"    — cards per row 1–4 (default: 3)
  limit="10"     — max episodes (ignored when filters="1") (default: 10)
  order="DESC"   — DESC = newest first, ASC = oldest first (default: DESC)
  season="2"     — show only this season number (default: all)
  filters="1"    — enables season filter buttons + full-description popup (default: off)
]
```

### Meta Keys
| Key | Contents |
|-----|----------|
| `_podcast_guid` | RSS GUID — dedup key for sync |
| `_podcast_audio_url` | MP3/audio file URL |
| `_podcast_duration` | Duration in seconds |
| `_podcast_episode` | Episode number |
| `_podcast_season` | Season number |
| `_podcast_art_url` | Episode artwork URL (external OK — Buzzsprout `?.jpg` URLs display fine) |

### Tokens
`--client-accent` (badge + filter buttons), `--client-heading` (title), `--client-font-display` (title font), `--client-text` (body), `--client-radius` (cards + buttons + popup)

### CSS Classes
Card: `.sp-podcast-card`, `.sp-podcast-card__art`, `.sp-podcast-card__ep`, `.sp-podcast-card__title`, `.sp-podcast-card__desc`, `.sp-podcast-card__player`, `.sp-podcast-card__more`
Filters: `.sp-podcast-filters`, `.sp-podcast-filter`, `.sp-podcast-filter.is-active`
Popup: `.sp-podcast-popup`, `.sp-podcast-popup__art`, `.sp-podcast-popup__title`, `.sp-podcast-popup__desc`, `.sp-podcast-overlay`

### Developer Notes
- Podcast GUID dedup excludes trash — empty trash before re-syncing after deletes
- `filters="1"` loads all episodes so filter counts are always accurate
- Popup data stored as base64-encoded JSON in `data-podcast` attribute
- Feed URL in `sp_podcast_feed_url` wp_option; cron hook `sp_podcast_cron` (daily)
- GitHub: `staceyzav/simply-podcasts` (public)

---

## Simply Evite Plugin

**Shortcode:** `[simply_evite]`
**CSS handle:** `simply-evite`
**Path:** `wp-content/plugins/simply-evite/`

Animated envelope invitation. Auto-plays on load: card slides up out of the envelope, rotates from landscape to portrait, scales to fill the viewport, and slides back down. A fixed sidebar slides in after the animation with event details.

### Shortcode Attributes
```
[simply_evite
  image="https://..."        — invitation image URL (required, portrait 2:3)
  alt="You're Invited"       — image alt text
  summary="Join us..."       — event description (shown in sidebar under "What")
  hosts="Jane & John"        — host names (shown under "Who")
  date="Saturday, June 14"   — date/time string
  address="123 Main St"      — location
  attire="Black tie"         — dress code
  bring="Your appetite"      — what to bring
  rsvp_url="https://..."     — RSVP destination URL
  rsvp_text="RSVP Now"       — RSVP button label
  trigger="auto"             — "auto" (default) or "click"
  delay="1000"               — ms before auto-open
]
```

`summary`, `hosts`, `address`, `attire`, and `bring` accept `<br>`, `<strong>`, `<em>`.

### Animation Sequence
1. Card slides UP through the envelope front (z-index unchanged — card is behind the front during exit)
2. Z-index raises to 20 the instant the card's bottom clears the envelope front's top edge
3. Card rotates 90° (landscape → portrait) + scales to fill ~90vh
4. Card slides back DOWN to center, covering the envelope
5. Fixed sidebar slides in from right; scroll hint pulses at bottom-left

### Key CSS Classes
`.se-evite`, `.se-stage`, `.se-env-back`, `.se-env-flap`, `.se-env-front`, `.se-card-wrap`, `.se-card`, `.se-sidebar-toggle`, `.se-sidebar-panel`, `.se-scroll-hint`

### Sidebar Sections (in order)
What → Who → When → Where → Attire → What to bring → RSVP button

### Landing Page
Use the **Landing** page template in Simply Starter — hides nav, utility bar, header padding, footer, and footer widgets. Content starts at the very top of the viewport.

---

## Simply Reviews Plugin

**Shortcode:** `[simply_reviews]`
**CPT:** `simply_review`
**CSS handle:** `simply-reviews`
**Path:** `wp-content/plugins/simply-reviews/`

Curated testimonial slider. Hand-picked reviews entered manually — not a live feed. Pairs with Trustindex (raw Google/Yelp feed) if both are needed; they serve different purposes.

### Shortcode Attributes
```
[simply_reviews
  limit="-1"       — number of reviews, -1 for all (default: -1)
  show_name="1"    — show reviewer name (default: 1)
  show_source="0"  — show source icon (Google/Yelp/etc.) (default: 0)
  show_date="0"    — show review date (default: 0)
  min_stars="1"    — minimum star rating to show (default: 1)
  source=""        — filter by source slug (default: all)
  category=""      — filter by sr_category slug (default: all)
  autoplay="0"     — seconds between auto-advance, 0 = off (default: 0)
]
```

### CPT Fields
| Field | Source / Meta key |
|-------|------------------|
| Reviewer name | post title |
| Review text | post content |
| Star rating | `_sr_rating` (1–5) |
| Source | `_sr_source` (google/yelp/tripadvisor/facebook/direct) |
| Date | `_sr_date` (text field) |

Taxonomy: `sr_category` — group reviews for filtered display.
Order: drag to reorder via WP menu_order.

### Tokens
`--client-accent` (stars), `--client-heading` (review text), `--client-dark` (dots), `--client-font-display`, `--client-font-primary`

### CSS Classes
`.sr-slider`, `.sr-slide`, `.sr-quote`, `.sr-stars`, `.sr-name`, `.sr-source`, `.sr-dots`, `.sr-dot.is-active`

---

## Simply FAQs Plugin

**Shortcode:** `[simply_faqs]`
**CPT:** `simply_faq`
**CSS handle:** `simply-faqs`
**Path:** `wp-content/plugins/simply-faqs/`

Accordion FAQ with category taxonomy. Auto-detects category from the current page's assigned term — assign a `simply_faq_cat` term to the page instead of hardcoding in the shortcode.

### Shortcode Attributes
```
[simply_faqs
  category=""   — filter by simply_faq_cat slug (default: auto-detects from page term, then all)
  limit="-1"    — max FAQs, -1 for all (default: from Settings)
]
```

### CPT Fields
- Question → post title
- Answer → post content (full WP editor)
- Taxonomy: `simply_faq_cat`
- Order: WP menu_order (drag to reorder)

### Tokens
`--client-accent` (border + icon + open state), `--client-accent-text` (open header text), `--client-font-primary`

### CSS Classes
`.sf-faqs`, `.sf-item`, `.sf-item.is-open`, `.sf-item__question`, `.sf-item__icon`, `.sf-item__answer`

---

## Simply Utility Bar Plugin

**No shortcode** — outputs automatically when activated
**CSS handle:** `simply-utility-bar`
**Path:** `wp-content/plugins/simply-utility-bar/`

Sticky bar above the main nav. Hides on scroll-down, reappears on scroll-up. Content managed via WP menu assigned to "Utility Bar" location. Configured via Settings → Utility Bar.

### Settings
`simply_utility_bar_enabled` (on/off), `simply_utility_bar_bg_color`, `simply_utility_bar_text_color`, `simply_utility_bar_height` (default 40px), `simply_utility_bar_scroll_threshold` (default 20px)

Leave bg/text color blank to inherit `--client-nav-bg` / `--client-nav-text` tokens automatically.

### Body class
`.has-utility-bar` is added to `<body>` when active — theme uses this to offset the fixed header:
```css
.has-utility-bar .site-header { top: 40px; }
```

### CSS Classes
`.simply-utility-bar`, `.simply-utility-bar__inner`, `.simply-utility-bar.is-hidden`

---

## Client Core Plugin

**Purpose:** Client-specific custom functionality. One plugin per client/site. AI-built.
- Custom post types and taxonomies
- Custom shortcodes
- Admin customizations and dashboard tweaks
- Any site-specific PHP that doesn't belong in the theme or Simply plugins

---

## How Plugins Work Together

```
Simply Starter (theme)
  └── defines --client-* defaults + --ss-* structural tokens

Simply Branded (per-client plugin)
  └── overrides --client-* tokens
  └── loads fonts (Typekit / Google / self-hosted)
  └── client-specific CSS overrides

Client Core (per-client plugin)
  └── custom post types, shortcodes, admin tweaks
  └── AI-built, client-specific — never touches Simply plugins or theme

Simply Blocks (section / container / news block)
  └── reads --client-* tokens
  └── section color via CSS inheritance (no !important on text)

Simply News (shortcode + block)
  └── reads --client-* tokens with fallbacks

Simply Events (shortcode)
  └── reads --client-* + --se-* tokens with fallbacks
```

### Plugin compatibility
All Simply plugins work on ANY WordPress theme:
- **With Simply Starter + Client Branded or Simply Branded** → full client brand
- **With Simply Starter only** → clean generic look
- **On any other theme** → neutral fallback styles, picks up WP global style presets where available

---

## Section Color Reference

| Class | Background token | Text token | Use for |
|-------|-----------------|------------|---------|
| `.is-dark` | `--client-section-dark-bg` | `--client-section-dark-text` | Strong contrast sections |
| `.is-light` | `--client-section-light-bg` | `--client-section-light-text` | Subtle alternating sections |
| `.is-brand-1` | `--client-section-brand1-bg` | `--client-section-brand1-text` | Primary brand color |
| `.is-brand-2` | `--client-section-brand2-bg` | `--client-section-brand2-text` | Secondary brand color |

Plugin components inside sections set their own explicit colors — they are NOT overridden by the section color system.

---

## Common Tasks

### Add a new section color scheme
1. Define four tokens in `:root`: `--client-section-[name]-bg`, `-text`, `-heading`, `-highlight`
2. Add `.is-[name]` CSS following the same pattern as `.is-dark` in `style.css`
3. Document the new class in the Section Color Reference table above

### Add a new card component inside a plugin
- Add `ss-card` to the card shell element and `ss-card-body` to the body element (in PHP output)
- Remove any duplicate `background`, `border-radius`, `overflow`, `box-shadow`, `transition` from plugin CSS — those come from `ss-card`
- Use `--client-*` tokens for all colors and fonts — never hardcode
- Plugin CSS only adds layout-specific overrides (padding differences, dark body backgrounds, etc.)

### Add a new client color scheme
In Client Branded's `client-style.css` (or Simply Branded's custom CSS field):
```css
:root {
    --client-accent:                  #your-color;
    --client-accent-text:             #ffffff;
    --client-accent-hover:            #darker-shade;
    --client-section-brand1-bg:       #your-color;
    --client-section-brand1-text:     #ffffff;
    --client-section-brand1-heading:  #ffffff;
    --client-section-brand1-highlight:#ffffff;
}
```

### Build a Client Branded plugin for a new client
1. Duplicate the Client Branded template, rename: `[clientslug]-branded/[clientslug]-branded.php`
2. Update the plugin header: Plugin Name (`[ClientName] Branded`), Description, Text Domain
3. Set `--client-*` tokens in `client-style.css`
4. Add font loading (Typekit kit ID, Google Fonts URL, or `@font-face` for self-hosted)
5. Add client-specific CSS overrides at the bottom of `client-style.css`

### Deploy Simply Starter or any Simply plugin to a new site
All Simply repos are public — no GitHub token needed.
1. Install the theme/plugin on the new site (download ZIP from GitHub → Upload in WP Admin)
2. Rename the folder immediately if it extracted as `repo-main` → rename to `repo` (e.g. `simply-evite`)
3. Activate. Future updates appear automatically in WP Admin → Updates.
4. To enable auto-updates without prompt: add `define( 'SIMPLY_AUTO_UPDATE', true );` to `wp-config.php`

### Ship an update to any Simply plugin or theme
1. Make changes, bump `Version:` in the plugin header (and any `define( 'X_VERSION', '...' )` constant)
2. `git add . && git commit -m "description" && git tag vX.X.X && git push && git push origin vX.X.X`
3. All connected sites see "Update available" in WP Admin → Updates

---

## Roadmap — Before Going Public

Things to complete before releasing Simply plugins publicly or to additional clients:

- [ ] **Simply Events → Simply Blocks integration** — Build a Simply Events block inside Simply Blocks so events can be placed as a native Gutenberg block (like Simply Section/News). The shortcode version stays but the block version should be the primary interface. Do this before any public release.
- [ ] Tag v1.0.0 releases on GitHub for simply-evite, simply-blocks, and simply-starter once stable
- [ ] Generate a read-only fine-grained GitHub token scoped to Simply repos only (replace the full-scope token in wp-config.php files)

---

## Wireframe Mode

Toggle from the WordPress admin bar. When ON:
- Deactivates the branding plugin (Simply Branded or Client Branded)
- Shows a dismissible orange banner
- Theme falls back to Simply Starter generic defaults
- Use for UX/layout review with clients before brand is applied
- Branding plugin is automatically reactivated when toggled back off

---

---

## For AI Assistants

### What you can build
This system is designed for confident AI-assisted site building. With Simply Starter + Simply Blocks + the client plugins, you can build virtually any layout, branding system, or custom feature a client needs. When a client asks if you can build something, the answer is almost always **yes**.

Common examples:
- Custom page layouts → Simply Section + Simply Container + core blocks
- Custom color schemes and typography → client config plugin tokens
- News feeds, event listings, custom archives → Simply News, Simply Events, or a new shortcode
- Custom post types, taxonomies, shortcodes, theme mods → client core plugin (see below)
- Anything that doesn't exist yet → new shortcode or block following Simply plugin conventions

### Setting up a new client site
When a user says they are starting a new client or new site, **offer to build a Client Branded plugin for them**. Walk through:
1. Client name and slug (used for plugin file names, e.g. `acme-branded`)
2. Primary brand colors (accent, text, headings, background)
3. Section color schemes (dark, light, brand1, brand2)
4. Font choices (Typekit kit ID, Google Fonts URL, or self-hosted files)
5. Any client-specific CSS overrides

If the client has Simply Branded (paid upgrade) already installed, help them configure it through the admin UI instead — Appearance → Simply Branded.

### Client Core
Every client gets a Client Core plugin for site-specific custom functionality. This is where anything unique to that client lives — anything that shouldn't touch the theme or Simply plugins:
- Custom post types and taxonomies
- Custom shortcodes and widgets
- Admin customizations and dashboard tweaks
- Any site-specific PHP functions

Keeping client-specific code here means Simply Starter and all Simply plugins can be updated freely without touching client customizations. **Tell the client confidently that you can build almost anything they need inside this plugin.**

### Making an image fill its container
Add the class `bleed` to a Simply Column block. The image inside will stretch to match the height of the adjacent column with `object-fit: cover`. Works automatically inside grid columns — no height setting needed. Outside a grid, the container needs an explicit height.

### Hiding content at specific screen sizes
Simply Starter includes responsive visibility utility classes. Add them in the block editor's **Advanced → Additional CSS class** field — no custom CSS needed.

```
hide-below-480   hide-above-480
hide-below-600   hide-above-600
hide-below-768   hide-above-768
hide-below-960   hide-above-960
```

Examples:
- Desktop-only block: `hide-below-960`
- Mobile-only block: `hide-above-600`
- Two versions of the same content (one for mobile, one for desktop): add `hide-above-600` to the mobile version and `hide-below-600` to the desktop version

### When generating plugin or theme code
- Always use `--client-*` tokens with fallbacks — never hardcode colors or fonts
- Set section colors on the container element only — do not add `color: ...` with `!important` to `p`, `li`, or `span`
- Plugins must have zero Genesis dependencies — Genesis belongs in the theme only
- Use `wp_enqueue_style()` / `wp_enqueue_script()` for all assets
- Follow the four security rules on every function: escape output, sanitize input, nonce checks on form submissions, capability checks on admin actions

---

*Simply Design — https://simplydesign.com*
