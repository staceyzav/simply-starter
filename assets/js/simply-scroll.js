/**
 * Simply Starter — simply-scroll.js
 * Version: 1.8.0
 * Author: Simply Design
 *
 * 1. Mobile menu — slide-in panel, X animation on hamburger
 * 2. Smooth scroll for menu anchor links
 * 3. Mobile sub-menu accordion
 *
 * Utility bar scroll-away moved to simply-utility-bar plugin.
 * No jQuery. Vanilla JS only.
 */

( function () {

	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var body       = document.body;
		var menuToggle = document.querySelector( '.menu-toggle' );


		// -----------------------------------------------------------------
		// 1. MOBILE MENU
		// -----------------------------------------------------------------

		if ( ! menuToggle ) return;

		// Overlay — clicking it closes menu
		var overlay = document.createElement( 'div' );
		overlay.className = 'imf-menu-overlay';
		body.appendChild( overlay );

		function openMenu() {
			body.classList.add( 'mobile-menu-open' );
			menuToggle.setAttribute( 'aria-expanded', 'true' );
			body.style.overflow = 'hidden';
			console.log( 'IMF: open' );
		}

		function closeMenu() {
			body.classList.remove( 'mobile-menu-open' );
			menuToggle.setAttribute( 'aria-expanded', 'false' );
			body.style.overflow = '';
			document.querySelectorAll( '.menu-item-has-children' )
				.forEach( function( i ) { i.classList.remove( 'sub-menu-open' ); } );
			console.log( 'IMF: close' );
		}

		menuToggle.addEventListener( 'click', function () {
			body.classList.contains( 'mobile-menu-open' ) ? closeMenu() : openMenu();
		} );

		overlay.addEventListener( 'click', closeMenu );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) closeMenu();
		} );

		window.addEventListener( 'resize', function () {
			if ( window.innerWidth >= 960 ) closeMenu();
		} );


		// -----------------------------------------------------------------
		// 2. SMOOTH SCROLL — menu anchor links close menu then scroll
		// -----------------------------------------------------------------

		document.querySelectorAll( '.nav-primary a[href*="#"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var href = link.getAttribute( 'href' );
				var hash = href.indexOf( '#' ) !== -1 ? href.split( '#' )[1] : null;
				if ( ! hash ) return;
				var target = document.getElementById( hash );
				if ( ! target ) return;
				e.preventDefault();
				closeMenu();
				// Small delay so menu closes before scrolling
				setTimeout( function () {
					target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				}, 300 );
			} );
		} );


		// -----------------------------------------------------------------
		// 3. ACCORDION (mobile only)
		// -----------------------------------------------------------------

		document.querySelectorAll( '.nav-primary .menu-item-has-children' )
			.forEach( function ( item ) {
				var link = item.querySelector( ':scope > a' );
				if ( ! link ) return;
				link.addEventListener( 'click', function ( e ) {
					if ( window.innerWidth >= 960 ) return;
					e.preventDefault();
					var isOpen = item.classList.contains( 'sub-menu-open' );
					document.querySelectorAll( '.nav-primary .menu-item-has-children' )
						.forEach( function ( o ) {
							if ( o !== item ) o.classList.remove( 'sub-menu-open' );
						} );
					item.classList.toggle( 'sub-menu-open', ! isOpen );
				} );
			} );

	} );

} )();
