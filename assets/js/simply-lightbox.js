( function () {
	'use strict';

	function getLargestSrc( img ) {
		if ( img.srcset ) {
			var candidates = img.srcset.split( ',' ).map( function ( s ) {
				var parts = s.trim().split( /\s+/ );
				return { url: parts[0], w: parseInt( parts[1] ) || 0 };
			} );
			candidates.sort( function ( a, b ) { return b.w - a.w; } );
			if ( candidates[0] && candidates[0].url ) return candidates[0].url;
		}
		return img.src;
	}

	function initLightbox() {
		var imgs = Array.from( document.querySelectorAll( '.wp-block-gallery .wp-block-image img' ) );
		if ( ! imgs.length ) return;

		// ── Build overlay ──────────────────────────────────────────────────────

		var overlay = document.createElement( 'div' );
		overlay.className = 'simply-lightbox';
		overlay.setAttribute( 'role', 'dialog' );
		overlay.setAttribute( 'aria-modal', 'true' );
		overlay.setAttribute( 'aria-label', 'Image viewer' );
		overlay.hidden = true;

		var img = document.createElement( 'img' );
		img.className = 'simply-lightbox__img';
		img.alt = '';

		var close = document.createElement( 'button' );
		close.className = 'simply-lightbox__close';
		close.setAttribute( 'aria-label', 'Close' );
		close.innerHTML = '&times;';

		var prev = document.createElement( 'button' );
		prev.className = 'simply-lightbox__prev';
		prev.setAttribute( 'aria-label', 'Previous image' );
		prev.innerHTML = '&#8249;';

		var next = document.createElement( 'button' );
		next.className = 'simply-lightbox__next';
		next.setAttribute( 'aria-label', 'Next image' );
		next.innerHTML = '&#8250;';

		overlay.appendChild( close );
		overlay.appendChild( prev );
		overlay.appendChild( img );
		overlay.appendChild( next );
		document.body.appendChild( overlay );

		// ── State ─────────────────────────────────────────────────────────────

		var current = 0;

		function show( index ) {
			current = ( index + imgs.length ) % imgs.length;
			var source = imgs[ current ];
			img.src = getLargestSrc( source );
			img.alt = source.alt || '';
			prev.hidden = next.hidden = imgs.length < 2;
		}

		function open( index ) {
			show( index );
			overlay.hidden = false;
			document.body.classList.add( 'simply-lightbox-open' );
			close.focus();
		}

		function closeLightbox() {
			overlay.hidden = true;
			document.body.classList.remove( 'simply-lightbox-open' );
			imgs[ current ].focus();
		}

		// ── Make gallery images clickable — attach to <a> if present ────────

		imgs.forEach( function ( source, i ) {
			source.style.cursor = 'zoom-in';

			var anchor = source.closest( 'a' );
			var target = anchor || source;

			if ( ! anchor ) {
				target.setAttribute( 'tabindex', '0' );
				target.setAttribute( 'role', 'button' );
			}

			target.setAttribute( 'aria-label', ( source.alt || 'Image ' + ( i + 1 ) ) + ' — click to enlarge' );

			target.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				e.stopPropagation();
				open( i );
			} );

			if ( ! anchor ) {
				target.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); open( i ); }
				} );
			}
		} );

		// ── Overlay events ────────────────────────────────────────────────────

		close.addEventListener( 'click', closeLightbox );
		prev.addEventListener( 'click', function () { show( current - 1 ); } );
		next.addEventListener( 'click', function () { show( current + 1 ); } );

		overlay.addEventListener( 'click', function ( e ) {
			if ( e.target === overlay ) closeLightbox();
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( overlay.hidden ) return;
			if ( e.key === 'Escape' )     closeLightbox();
			if ( e.key === 'ArrowLeft' )  show( current - 1 );
			if ( e.key === 'ArrowRight' ) show( current + 1 );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initLightbox );
	} else {
		initLightbox();
	}
} )();
