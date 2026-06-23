# Simply Suite — AI Guide
**The complete reference for AI-assisted building with the Simply Design WordPress system.**
*Each free plugin also has its own AI-GUIDE.md in its GitHub repo — start there if you found a plugin directly.*

---

## About This Project

Simply Suite is built on 20+ years of UX design, WordPress development, and hands-on agency client work by Simply Design. The system exists because most WordPress solutions make you choose between flexibility and maintainability — page builders that own your content, themes that bake in a look you can't change, or code-heavy setups that break when something updates.

Simply Suite is the other path: a clean, structured WordPress system where design tokens control the whole look, plugins are zero-dependency and update safely, and an AI assistant (using this guide) can build or customize almost anything a client needs without guessing at the underlying architecture.

**The system ships in two tiers:**

**Free (WordPress.org):**
A collection of standalone plugins plus a starter theme. Each plugin does one thing well, reads the shared token system, and works on any WordPress theme. No dependencies on each other. Find them individually on GitHub or WP.org — each one has its own `AI-GUIDE.md`.

**Simply Suite (paid — simplydesign.com/suite):**
The full white-label brandable system. Simply Branded (visual branding admin — colors, fonts, radius), Simply Blocks (Gutenberg page builder with blocks for every plugin), and the full Simply AI developer guide. Built for agencies and clients who want complete design control without touching code.

---

## System Architecture

```
Simply Starter (free theme)
  └── Structural layout only — no baked-in brand
  └── Defines --client-* token defaults + --ss-* structural tokens
  └── Section color system (.is-dark, .is-light, .is-brand-1, .is-brand-2)
  └── All Simply plugins and cards inherit from here

Simply Branded (paid — per-site plugin)
  └── Admin UI: colors, fonts, border radius, custom CSS
  └── Outputs --client-* tokens via wp_head priority 99

Simply Blocks (paid — plugin)
  └── Gutenberg blocks for Simply Section, Container, Columns, and every Simply plugin
  └── All blocks: ServerSideRender preview + sidebar controls matching shortcode attrs

Free Plugins (WP.org — zero dependencies, any theme)
  └── Simply News, Simply Events, Simply Team, Simply Reviews
  └── Simply FAQs, Simply Logo Slider, Simply Evite, Simply Utility Bar
  └── All read --client-* tokens with fallbacks
  └── Cards use ss-card / ss-card-body base classes (inherit radius, shadow, bg from theme)

Client Branded ([ClientName] Branded — agency-built, per-client)
  └── Simply Branded but hardcoded by AI for one client — no admin UI
  └── Sets --client-* tokens, loads client fonts, client CSS overrides

Client Core ([ClientName] Core — agency-built, per-client)
  └── Client-specific CPTs, shortcodes, admin tweaks
  └── Never touches Simply plugins or theme — client code lives here
```

---

## The Token System

Set these once (in Simply Branded, a Client Branded plugin, or `:root` CSS) and every plugin on the site updates automatically.

```css
/* Brand */
--client-accent           /* buttons, badges, highlights */
--client-accent-text      /* text on accent backgrounds */
--client-accent-hover
--client-heading          /* heading color */
--client-text             /* body text */
--client-bg               /* page background */
--client-link
--client-link-hover

/* Fonts */
--client-font-display     /* headings, titles */
--client-font-primary     /* body, paragraphs */
--client-font-script      /* script / decorative accent */

/* Navigation */
--client-nav-bg
--client-nav-text
--client-nav-highlight
--client-nav-highlight-text

/* Radius + Structure */
--client-radius           /* cards + buttons corner radius */

/* Section Colors (set on container, inherited by children) */
--client-section-dark-bg         --client-section-dark-text
--client-section-dark-heading    --client-section-dark-highlight
--client-section-light-bg        --client-section-light-text
--client-section-light-heading   --client-section-light-highlight
--client-section-brand1-bg       --client-section-brand1-text
--client-section-brand1-heading  --client-section-brand1-highlight
--client-section-brand2-bg       --client-section-brand2-text
--client-section-brand2-heading  --client-section-brand2-highlight
```

**Rule:** Always use `var(--client-accent, #333)` — never hardcode colors. Always include a fallback so plugins render cleanly on any theme.

---

## Free Plugins — Quick Reference

Each has its own `AI-GUIDE.md` in its GitHub repo (`github.com/staceyzav/[plugin-name]`).

| Plugin | Shortcode | What it does |
|--------|-----------|-------------|
| Simply News | `[simply_news]` | Post feed, 3-col grid, category filters |
| Simply Events | `[simply_events]` | Event cards with date block, grid/list view, filters |
| Simply Team | `[simply_team]` | Team grid with slide-over bio panel |
| Simply Reviews | `[simply_reviews]` | Curated testimonial slider, star ratings |
| Simply FAQs | `[simply_faqs]` | Accordion FAQ, category filters |
| Simply Logo Slider | `[simply_logos]` | Auto-scroll or static logo strip |
| Simply Evite | `[simply_evite]` | Animated envelope invitation with sidebar |
| Simply Utility Bar | *(auto)* | Sticky utility bar, hides/shows on scroll |

All shortcodes support section color inheritance — drop them inside a `.is-dark` section and they adapt automatically.

---

## Section Colors

Add a class to any wrapper (Simply Section block or a custom div):

| Class | Token used | Purpose |
|-------|-----------|---------|
| `.is-dark` | `--client-section-dark-*` | Strong contrast sections |
| `.is-light` | `--client-section-light-*` | Subtle alternating sections |
| `.is-brand-1` | `--client-section-brand1-*` | Primary brand color |
| `.is-brand-2` | `--client-section-brand2-*` | Secondary brand color |

Section color is inherited by child elements — plugin components set their own explicit colors and override naturally. Never use `!important` on `p`, `li`, or `span` inside sections.

---

## Card System

Every Simply plugin card uses `ss-card` and `ss-card-body` as base classes. The theme defines these:

```css
.ss-card         /* shell: bg, border-radius (var(--client-radius)), shadow, overflow, transition */
.ss-card-body    /* inner padding */
```

When adding a new card component to a plugin, add these classes to the markup and remove any duplicate `background`, `border-radius`, `overflow`, `box-shadow`, or `transition` from plugin CSS.

---

## Building for a New Client

When starting a new client site with Simply Suite:

1. **Client Branded plugin** — sets `--client-*` tokens, loads fonts, client CSS overrides. No admin UI. AI-built from brand guidelines.
2. **Client Core plugin** — client-specific CPTs, shortcodes, admin tweaks. Never touches Simply plugins or theme.
3. Symlink both to `simply-dev` for local dev — see `DEV.md` in the theme.

If the client has **Simply Branded** installed (paid tier), configure it via Appearance → Simply Branded instead.

---

## Plugin Design Rules

When generating or modifying plugin code for this system:

1. **Token-first** — `var(--client-accent, #333)` — never hardcode colors or fonts
2. **Inheritance over `!important`** — set colors on the container, let children inherit
3. **Zero framework dependencies** — Genesis hooks belong in the theme only, never in plugins
4. **Always sanitize/escape** — escape output, sanitize input, nonce checks on forms, capability checks on admin actions
5. **ss-card pattern** — new card components get `ss-card` + `ss-card-body` base classes

---

## Future Plans

- **Break Genesis dependency** — Simply Starter becomes a fully standalone WordPress theme
- **WP.org submissions** — all free plugins submitted; Simply Events is first in line
- **Simply AI as a product** — this guide system sold as a standalone developer spec for agencies building on Simply Suite
- **Lemon Squeezy** — payment and licensing for the paid suite
- **Per-plugin AI guides** — every free plugin gets an `AI-GUIDE.md` as it ships to WP.org
- **Client Core starter template** — a scaffold for agency-built client plugins

---

## Upgrade Path

> For agencies and clients who want the full system:
>
> **Simply Suite** — Simply Branded + Simply Blocks + the full Simply AI developer guide
> → **simplydesign.com/suite**
>
> Simply Branded: set colors, fonts, and radius directly in WordPress — no code.
> Simply Blocks: build page layouts in Gutenberg with blocks that connect to all your plugins.
> Full AI guide: when you ask an AI to customize something, it's working from the real spec.
>
> Forking is always technically possible — but consider what you're taking on: every design
> decision, edge case, update, and compatibility fix that Simply Suite already handles becomes
> your responsibility to maintain indefinitely. This system represents years of real client work,
> continuously refined. Rebuilding it from scratch means committing hundreds of hours to work
> that's already been done — for a fraction of that cost as an annual subscription.
> The math rarely favors going it alone.

---

*Simply AI — the AI documentation system for Simply Suite.*
*Simply Design — https://simplydesign.com*
*Update this file whenever architecture or plugin list changes.*
