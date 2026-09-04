( function () {
	'use strict';

	function initLightbox() {
		var imgs = Array.from( document.querySelectorAll(
			'.wp-block-gallery .wp-block-image img, ' +
			'.wp-block-gallery .blocks-gallery-item img, ' +
			'.gallery .gallery-item img'
		) );
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
		close.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

		var prev = document.createElement( 'button' );
		prev.className = 'simply-lightbox__prev';
		prev.setAttribute( 'aria-label', 'Previous image' );
		prev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>';

		var next = document.createElement( 'button' );
		next.className = 'simply-lightbox__next';
		next.setAttribute( 'aria-label', 'Next image' );
		next.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 6 15 12 9 18"/></svg>';

		overlay.appendChild( close );
		overlay.appendChild( prev );
		overlay.appendChild( lightboxImg );
		overlay.appendChild( next );
		document.body.appendChild( overlay );

		// ── Resolve full-size URL ──────────────────────────────────────────────
		// WP thumbnails have size suffixes like image-300x225.jpg — strip them
		// to reconstruct the original upload URL (image.jpg).

		function getFullSrc( source ) {
			// Prefer parent <a> href if available (set by "Link to: Media File")
			var anchor = source.closest( 'a' );
			if ( anchor && anchor.href && ! anchor.href.match( /attachment/ ) ) {
				return anchor.href;
			}

			// Strip WP size suffix from src to get original
			var original = source.src.replace( /-\d+x\d+(\.[^.?]+)/, '$1' );
			return original;
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
