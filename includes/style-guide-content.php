<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the block content for the Simply Starter Style Guide page.
 * Created automatically on theme activation if the page doesn't exist.
 */
function simply_style_guide_content() {
	return '
<!-- wp:simply-blocks/section {"sectionColor":"is-home-hero","paddingTop":160,"paddingBottom":160} -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Hero Headline Goes Here</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-script"} -->
<p class="ss-script">A script tagline sits right here beneath the headline.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Primary Button</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Outline Button</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- /wp:simply-blocks/section -->

<!-- wp:simply-blocks/section -->

<!-- wp:paragraph -->
<p>This page is your visual reference for all styles available in Simply Starter. Use it as a working canvas — edit any block to see how the controls work. <strong>To apply a style:</strong> select a block → look for the Styles panel in the right sidebar, or use the toolbar buttons above the block.</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Typography — Headings</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Heading 1 — Page Title</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Body copy follows a heading. Use H1 once per page — typically the page or post title. Font size scales fluidly from mobile to desktop using clamp() — no extra work needed.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Heading 2 — Section Title</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>H2 is the workhorse heading for dividing content into sections. Use it generously inside page sections and long-form posts.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Heading 3 — Subsection</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>H3 sits under an H2 to break a section into smaller topics. Great for FAQs, feature lists, and structured content.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Heading 4 — Group Label</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>H4 is for labeling groups of related items — card clusters, sidebar sections, or detailed breakdowns within a subsection.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":5} -->
<h5 class="wp-block-heading">Heading 5 — Minor Label</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>H5 is a fine-grain label. Use sparingly — typically for metadata, captions, or supplementary structure inside a component.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">Heading 6 — Smallest Label</h6>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>H6 matches body text size. At this level, hierarchy comes from context and weight rather than scale. Use for administrative labels or deeply nested structures.</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Label / Eyebrow</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>An eyebrow label sits above a heading to add context — like a category, section name, or short descriptor. <strong>How to apply:</strong> select any heading block → click the <strong>LBL</strong> toolbar button, or open the Styles panel in the right sidebar → choose <strong>Label / Eyebrow</strong>. It works in both places and stays in sync.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Upcoming Events</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Competition Schedule</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The eyebrow above has zero bottom margin — it tucks close to the heading below it automatically. Works the same whether a heading or paragraph follows.</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Lists</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Bulleted List</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><li>List items are tightly spaced — 4px between each item</li><li>Indent is 24px from the left edge</li><li>Nested lists use a circle bullet automatically</li><li>Use for unordered collections where sequence doesn\'t matter</li></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Numbered List</h3>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><li>Use for steps, rankings, or anything where order matters</li><li>Same tight spacing as bulleted lists</li><li>Nesting uses a lettered sub-list</li><li>Pair with H3 or H4 to introduce the list</li></ol>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Buttons</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Use the core <strong>Buttons</strong> block. The default style picks up the accent color from the token system. The outline style is available for use on dark or image sections.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Primary Button</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Outline Button</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"className":"is-style-eyebrow"} -->
<h2 class="wp-block-heading is-style-eyebrow">Section Colors</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Add a color scheme to any Group block via <strong>Advanced → Additional CSS class</strong>. Available classes: is-dark, is-light, is-brand-1, is-brand-2. Colors are set by your Simply Branded plugin tokens and update site-wide automatically.</p>
<!-- /wp:paragraph -->

<!-- /wp:simply-blocks/section -->

<!-- wp:group {"className":"is-dark","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","left":"32px","right":"32px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-dark" style="padding-top:48px;padding-bottom:48px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Dark Section — is-dark</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Near-black background, light text. Strong contrast — use for hero areas, CTAs, or visual anchors on the page.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button on Dark</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-light","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","left":"32px","right":"32px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-light" style="padding-top:48px;padding-bottom:48px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Light Section — is-light</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Very light gray background, dark text. Use to create subtle alternating sections without heavy contrast.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button on Light</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-brand-1","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","left":"32px","right":"32px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-brand-1" style="padding-top:48px;padding-bottom:48px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Brand 1 — is-brand-1</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Primary brand accent color as a full section background. Set via the --client-section-brand1-* tokens in your Simply Branded plugin.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button on Brand 1</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-brand-2","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","left":"32px","right":"32px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-brand-2" style="padding-top:48px;padding-bottom:48px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Brand 2 — is-brand-2</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Secondary brand color — typically a deep complementary tone. Set via the --client-section-brand2-* tokens in your Simply Branded plugin.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button on Brand 2</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
';
}
