( function () {
	'use strict';

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

		var lightboxImg = document.createElement( 'img' );
		lightboxImg.className = 'simply-lightbox__img';
		lightboxImg.alt = '';

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
		overlay.appendChild( lightboxImg );
		overlay.appendChild( next );
		document.body.appendChild( overlay );

		// ── Resolve full-size URL ──────────────────────────────────────────────

		function getFullSrc( source ) {
			// Prefer parent <a> href — set "Link to: Media File" in gallery block
			var anchor = source.closest( 'a' );
			if ( anchor && anchor.href ) return anchor.href;

			// Fall back to largest srcset candidate
			if ( source.srcset ) {
				var candidates = source.srcset.split( ',' ).map( function ( s ) {
					var parts = s.trim().split( /\s+/ );
					return { url: parts[0], w: parseInt( parts[1] ) || 0 };
				} );
				candidates.sort( function ( a, b ) { return b.w - a.w; } );
				if ( candidates[0] && candidates[0].url ) return candidates[0].url;
			}
			return source.src;
		}

		// ── State ─────────────────────────────────────────────────────────────

		var current = 0;

		function show( index ) {
			current = ( index + imgs.length ) % imgs.length;
			var source = imgs[ current ];
			lightboxImg.src = getFullSrc( source );
			lightboxImg.alt = source.alt || '';
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
		}

		// ── Intercept clicks — use capture to fire before WP native handlers ──

		imgs.forEach( function ( source, i ) {
			source.style.cursor = 'zoom-in';

			// Attach in capture phase on the figure so we catch the click before
			// WP's Interactivity API or any other handler on the anchor/image
			var figure = source.closest( 'figure' );
			var target = figure || source.closest( 'a' ) || source;

			target.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				e.stopImmediatePropagation();
				open( i );
			}, true ); // true = capture phase
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
