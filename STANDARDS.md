# Simply Design — Plugin & Theme Standards
**For AI-assisted site building and developer reference.**

Simply Design builds sites using a layered system: a generic starter theme, a client brand config plugin, and a suite of standalone content plugins. Everything is connected through a shared CSS token system — set the tokens once and the entire site updates automatically.

---

## The Token System

All plugins and theme use CSS custom properties (`--client-*`). Set them in the client config plugin once — every plugin on the site picks them up automatically.

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
1. **Simply Client Config** — full client brand (colors, fonts, logo)
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
- Wireframe mode (toggle from admin bar — deactivates client config for UX review)
- Container width configurable from Appearance → Simply Starter

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

**Blocks:** Simply Section, Simply Container, Simply News

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
| `--se-radius` | Card border-radius | `0` |

### Card Classes
`.se-events-block`, `.se-event-card`, `.se-event-card__date`, `.se-event-card__day`, `.se-event-card__month`, `.se-event-card__body`, `.se-event-card__title`

---

## Simply Branded Plugin

**Purpose:** Brand configuration for a specific client. One plugin per client/site.

### What it controls
1. **CSS tokens** — overrides all `--client-*` in `:root`
2. **Fonts** — Typekit, Google Fonts, or self-hosted (auto `@font-face` generated)
3. **Client-specific CSS overrides** — anything in `client-style.css`

### How to brand a new site
1. Duplicate the Simply Branded plugin template, rename for the client
2. Set `--client-*` tokens in `client-style.css`
3. Add font loading (Typekit kit ID, Google Fonts URL, or `@font-face` for self-hosted)
4. Add any client-specific CSS overrides at the bottom of `client-style.css`

### Rounded shapes
The theme and plugins default to square (`border-radius: 0`). Add these to Simply Branded to round:
```css
/* Buttons */
.button, input[type="submit"], .ss-btn, .simply-btn, .sn-filter-btn,
.wp-block-button__link {
    border-radius: 100px !important;
}
/* Cards */
.ss-card, .sn-card__photo, .simply-post-hero { border-radius: 8px; }
.ss-badge { border-radius: 20px; }
/* Tokens */
:root { --sn-radius: 8px; --se-radius: 8px; }
```

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

## Simply AI Custom Extensions Plugin

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

Simply AI Custom Extensions (per-client plugin)
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
- **With Simply Starter + Client Config** → full client brand
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

### Add a new client color scheme
In the client config plugin's `client-style.css`:
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

### Add a new card component inside a plugin
- Use `--client-*` tokens for all colors and fonts — never hardcode
- Set explicit colors on the card element itself (not relying on section inheritance)
- Add a plugin-scoped token for anything configurable: `--plugin-radius`, `--plugin-columns`
- Every token needs a fallback: `var(--plugin-radius, 0)`

### Build a Simply Branded plugin for a new client
Duplicate the Simply Branded plugin template, rename for the client, then:
1. Update the plugin header (Plugin Name, Description, Text Domain)
2. Set `--client-*` tokens in `client-style.css`
3. Add font loading (Typekit ID, Google Fonts URL, or `@font-face` for self-hosted)
4. Add any client-specific CSS overrides at the bottom of `client-style.css`

### Deploy Simply Starter or Simply Evite to a new site
1. Install the theme/plugin on the new site
2. Add to `wp-config.php`: `define( 'SIMPLY_GITHUB_TOKEN', 'ghp_...' );`
3. Future updates appear automatically in WP Admin → Updates

### Ship an update to Simply Evite or Simply Starter
1. Make changes, bump `SE_VERSION` (plugin) or `Version:` in `style.css` + updater line in `functions.php` (theme)
2. `git add . && git commit -m "description"  && git tag vX.X.X && git push && git push origin vX.X.X`
3. All connected sites see the update within 6 hours (or immediately after checking for updates)

---

## Wireframe Mode

Toggle from the WordPress admin bar. When ON:
- Deactivates the client config plugin
- Shows a dismissible orange banner
- Theme falls back to Simply Starter generic defaults
- Use for UX/layout review with clients before brand is applied

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
When a user says they are starting a new client or new site, **offer to build the Simply Branded plugin for them**. Walk through:
1. Client name and slug (used for plugin file names and CSS prefix)
2. Primary brand colors (accent, text, headings, background)
3. Section color schemes (dark, light, brand1, brand2)
4. Font choices (Typekit kit ID, Google Fonts URL, or self-hosted files)
5. Logo and any other brand assets

### Simply AI Custom Extensions
Every client gets a Simply AI Custom Extensions plugin for site-specific custom functionality. This is where anything unique to that client lives — anything that shouldn't touch the theme or Simply plugins:
- Custom post types and taxonomies
- Custom shortcodes and widgets
- Admin customizations and dashboard tweaks
- Any site-specific PHP functions

Keeping client-specific code here means Simply Starter and all Simply plugins can be updated freely without touching client customizations. **Tell the client confidently that you can build almost anything they need inside this plugin.**

### When generating plugin or theme code
- Always use `--client-*` tokens with fallbacks — never hardcode colors or fonts
- Set section colors on the container element only — do not add `color: ...` with `!important` to `p`, `li`, or `span`
- Plugins must have zero Genesis dependencies — Genesis belongs in the theme only
- Use `wp_enqueue_style()` / `wp_enqueue_script()` for all assets
- Follow the four security rules on every function: escape output, sanitize input, nonce checks on form submissions, capability checks on admin actions

---

*Simply Design — https://simplydesign.com*
