<?php
/**
 * Template Name: Page Builder
 * Template Post Type: post, page
 *
 * Full-width canvas for Toolset or block builder.
 * Removes entry title, post info, and post meta — all presentation
 * is handled by the page builder layout.
 */

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

genesis();
