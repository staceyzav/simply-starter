/**
 * Simply Starter — simply-editor.js
 * Version: 1.0.0
 * Author: Simply Design
 *
 * Sets default inner container width on Toolset containers.
 * Runs in the block editor only — not on the front end.
 *
 * Uses wp.data.subscribe to watch for newly inserted Toolset containers
 * with null inner width and immediately sets them to the configured default.
 *
 * Width value localized from PHP via simplyEditorData.containerWidth
 * (set in Appearance -> Simply Starter -> Container Settings)
 */

( function () {

	'use strict';

	// Wait for the block editor to be ready
	wp.domReady( function () {

		var select   = wp.data.select( 'core/block-editor' );
		var dispatch = wp.data.dispatch( 'core/block-editor' );

		// Width from PHP settings — falls back to 1200 if not set
		var containerWidth = (
			window.simplyEditorData &&
			window.simplyEditorData.containerWidth
		) ? parseInt( window.simplyEditorData.containerWidth ) : 1200;

		// Track blocks we've already processed so we don't re-fire
		var processed = {};

		// Helper — recursively get all blocks including nested
		function getAllBlocks( blocks ) {
			var all = [];
			blocks.forEach( function ( block ) {
				all.push( block );
				if ( block.innerBlocks && block.innerBlocks.length ) {
					all = all.concat( getAllBlocks( block.innerBlocks ) );
				}
			} );
			return all;
		}

		// Subscribe to block editor state changes
		var unsubscribe = wp.data.subscribe( function () {

			var allBlocks = getAllBlocks( select.getBlocks() );

			allBlocks.forEach( function ( block ) {

				// Only target Toolset containers
				if ( block.name !== 'toolset-blocks/container' ) return;

				// Skip if already processed
				if ( processed[ block.clientId ] ) return;

				var inner = block.attributes.inner;

				// Only set if inner width is null (default, unset state)
				if ( ! inner || inner.width === null ) {

					processed[ block.clientId ] = true;

					dispatch.updateBlockAttributes( block.clientId, {
						inner: {
							width:     containerWidth,
							widthUnit: 'px',
						}
					} );

					console.log(
						'Simply Starter: set Toolset container ' +
						block.clientId +
						' inner width to ' + containerWidth + 'px'
					);
				} else {
					// Already has a width set — mark as processed, don't touch
					processed[ block.clientId ] = true;
				}
			} );
		} );

	} );

} )();
