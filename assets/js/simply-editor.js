/**
 * Simply Starter — simply-editor.js
 * Version: 1.1.0
 * Author: Simply Design
 *
 * Sets default inner container width AND padding on new Toolset containers.
 * Runs in the block editor only — not on the front end.
 *
 * Uses wp.data.subscribe to watch for newly inserted Toolset containers
 * with null inner width and immediately sets them to the configured defaults.
 *
 * Width value localized from PHP via simplyEditorData.containerWidth
 * (set in Appearance -> Simply Starter -> Container Settings)
 *
 * Padding defaults: 80px top/bottom, 5px left/right
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

					// Spread existing style so we don't wipe other style properties
					var existingStyle = block.attributes.style || {};

					dispatch.updateBlockAttributes( block.clientId, {
						inner: {
							width:     containerWidth,
							widthUnit: 'px',
						},
						style: Object.assign( {}, existingStyle, {
							padding: {
								enabled:       true,
								paddingTop:    '80px',
								paddingBottom: '80px',
								paddingLeft:   '5px',
								paddingRight:  '5px',
							}
						} )
					} );

					console.log(
						'Simply Starter: set Toolset container ' +
						block.clientId +
						' — width: ' + containerWidth + 'px, padding: 80px 5px'
					);
				} else {
					// Already has a width set — mark as processed, don't touch
					processed[ block.clientId ] = true;
				}
			} );
		} );

	} );

} )();
