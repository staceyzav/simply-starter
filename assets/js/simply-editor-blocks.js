/**
 * Simply Starter — Block editor toolbar extensions.
 * Adds a "Label / Eyebrow" toggle button to the heading block toolbar.
 */
( function() {
	'use strict';

	var el         = wp.element.createElement;
	var Fragment   = wp.element.Fragment;
	var addFilter  = wp.hooks.addFilter;
	var hoc        = wp.compose.createHigherOrderComponent;
	var BlockControls = wp.blockEditor.BlockControls;
	var ToolbarGroup  = wp.components.ToolbarGroup;
	var ToolbarButton = wp.components.ToolbarButton;

	var EYEBROW_CLASS = 'is-style-eyebrow';

	// Label / Eyebrow icon — a small "Aa" SVG
	var eyebrowIcon = el( 'svg', {
			xmlns:   'http://www.w3.org/2000/svg',
			viewBox: '0 0 24 24',
			width:   '24',
			height:  '24',
			fill:    'currentColor',
		},
		el( 'text', {
			x:          '2',
			y:          '11',
			fontFamily: 'serif',
			fontSize:   '9',
			fontWeight: '700',
			letterSpacing: '0.5',
		}, 'LBL' ),
		el( 'rect', { x: '2', y: '13', width: '20', height: '1.5', rx: '0.75' } )
	);

	var withEyebrowToolbar = hoc( function( BlockEdit ) {
		return function( props ) {
			if ( props.name !== 'core/heading' ) {
				return el( BlockEdit, props );
			}

			var className = props.attributes.className || '';
			var isActive  = className.indexOf( EYEBROW_CLASS ) !== -1;

			function toggle() {
				var next;
				if ( isActive ) {
					next = className.replace( /\bis-style-eyebrow\b/g, '' ).trim() || undefined;
				} else {
					// Remove any other is-style-* first so styles don't stack
					var cleaned = className.replace( /\bis-style-\S+/g, '' ).trim();
					next = ( cleaned ? cleaned + ' ' : '' ) + EYEBROW_CLASS;
				}
				props.setAttributes( { className: next } );
			}

			return el(
				Fragment, null,
				el( BlockControls, { group: 'block' },
					el( ToolbarGroup, null,
						el( ToolbarButton, {
							icon:        eyebrowIcon,
							label:       'Label / Eyebrow',
							onClick:     toggle,
							isActive:    isActive,
							showTooltip: true,
						} )
					)
				),
				el( BlockEdit, props )
			);
		};
	}, 'withEyebrowToolbar' );

	addFilter( 'editor.BlockEdit', 'simply-starter/eyebrow-toolbar', withEyebrowToolbar );

} )();
