/**
 * IMF Theme — imf-scroll.js
 * Version: 1.7.0
 * Author: Simply Design
 *
 * 1. Utility bar scroll-away
 * 2. Mobile menu — slide-in panel, X animation on hamburger
 * 3. Smooth scroll for menu anchor links
 * 4. Mobile sub-menu accordion
 *
 * No jQuery. Vanilla JS only.
 */

( function () {

	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var body       = document.body;
		var utilityBar = document.querySelector( '.simply-utility-bar' );
		var menuToggle = document.querySelector( '.menu-toggle' );
		var THRESHOLD  = 20;
		var ticking    = false;
		var wasScrolled = false;

		console.log( 'IMF scroll JS v1.7.0 loaded' );


		// -----------------------------------------------------------------
		// 1. UTILITY BAR SCROLL-AWAY
		// -----------------------------------------------------------------

		if ( utilityBar ) body.classList.add( 'has-utility-bar' );

		function onScroll() {
			var y = window.scrollY || window.pageYOffset;
			var scrolled = y > THRESHOLD;
			if ( scrolled === wasScrolled ) return;
			wasScrolled = scrolled;
			if ( scrolled ) {
				if ( utilityBar ) utilityBar.classList.add( 'scrolled-away' );
				body.classList.add( 'scrolled' );
			} else {
				if ( utilityBar ) utilityBar.classList.remove( 'scrolled-away' );
				body.classList.remove( 'scrolled' );
			}
		}

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				requestAnimationFrame( function () { onScroll(); ticking = false; } );
			}
		}, { passive: true } );

		onScroll();


		// -----------------------------------------------------------------
		// 2. MOBILE MENU
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
		// 3. SMOOTH SCROLL — menu anchor links close menu then scroll
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
		// 4. ACCORDION (mobile only)
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
