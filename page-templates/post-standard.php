<?php
/**
 * Template Name: Standard Post
 * Template Post Type: post, page
 *
 * Constrained 800px reading-width layout.
 * Title, content, meta all render normally.
 */

add_filter( 'body_class', function( $classes ) {
	$classes[] = 'simply-post-layout';
	return $classes;
} );

// Featured image above entry content
add_action( 'genesis_before_entry_content', 'simply_post_featured_image' );

function simply_post_featured_image() {
	if ( ! has_post_thumbnail() ) return;
	if ( get_post_meta( get_the_ID(), '_simply_hide_featured_image', true ) ) return;
	echo '<div class="simply-post-hero">';
	the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) );
	echo '</div>';
}

genesis();
