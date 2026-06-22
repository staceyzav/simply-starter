<?php
/**
 * Simply Starter — functions.php
 *
 * Simply Design starter child theme for Genesis Framework.
 * Fonts and brand colors are handled by Simply Branded or a Client Branded plugin.
 * Do not add client-specific code here.
 *
 * @package Simply Starter
 * @author  Simply Design
 * @license GPL-2.0-or-later
 * @link    https://simplydesign.com
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// GitHub auto-updater — checks staceyzav/simply-starter for new releases.
require_once get_stylesheet_directory() . '/includes/class-github-updater.php';
new Simply_GitHub_Updater( 'theme', 'simply-starter', 'staceyzav/simply-starter', '2.10.9' );

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

require_once get_stylesheet_directory() . '/includes/style-guide-content.php';

// Genesis responsive menu system removed entirely.
// Simply Starter uses its own slide-in mobile menu via simply-scroll.js.

// Dequeue Genesis responsive menu scripts — we don't use them.
add_action( 'wp_enqueue_scripts', 'simply_dequeue_genesis_responsive_menu', 99 );
function simply_dequeue_genesis_responsive_menu() {
	wp_dequeue_script( 'genesis-responsive-menu' );
	wp_deregister_script( 'genesis-responsive-menu' );
}

// Output our hamburger toggle button before the primary nav.
// Hooked into genesis_header at priority 11 (after logo at 10, before nav at 12).
add_action( 'genesis_header', 'simply_hamburger_toggle', 11 );
function simply_hamburger_toggle() {
	// Only output on mobile — CSS hides it at 960px+.
	echo '<button class="menu-toggle" aria-expanded="false" aria-controls="menu-primary">'
		. '<span class="hamburger-bar"></span>'
		. '<span class="hamburger-bar"></span>'
		. '<span class="hamburger-bar"></span>'
		. '<span class="sr-only">Open menu</span>'
		. '</button>';
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		null
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_filter( 'body_class', 'genesis_sample_body_classes' );
/**
 * Add additional classes to the body element.
 *
 * @since 3.4.1
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function genesis_sample_body_classes( $classes ) {

	if ( ! genesis_is_amp() ) {
		// Add 'no-js' class to the body class values.
		$classes[] = 'no-js';
	}
	return $classes;
}

add_action( 'genesis_before', 'genesis_sample_js_nojs_script', 1 );
/**
 * Echo the script that changes 'no-js' class to 'js'.
 *
 * @since 3.4.1
 */
function genesis_sample_js_nojs_script() {

	if ( genesis_is_amp() ) {
		return;
	}

	?>
	<script>
	//<![CDATA[
	(function(){
		var c = document.body.classList;
		c.remove( 'no-js' );
		c.add( 'js' );
	})();
	//]]>
	</script>
	<?php
}

add_filter( 'wp_resource_hints', 'genesis_sample_resource_hints', 10, 2 );
/**
 * Add preconnect for Google Fonts.
 *
 * @since 3.4.1
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function genesis_sample_resource_hints( $urls, $relation_type ) {

	if ( wp_style_is( genesis_get_theme_handle() . '-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Sets full-width content as the default layout for all pages and posts.
// Prevents having to manually set it in the Genesis metabox each time.
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}


// ==========================================================================
// SIMPLY STARTER ADDITIONS — Simply Design
// Appended below all Genesis Sample functions.
// ==========================================================================



// --------------------------------------------------------------------------
// BODY CLASSES
// .ss-homepage → transparent header overlays hero on front page
// .ss-interior → solid header on all interior pages
// --------------------------------------------------------------------------

add_filter( 'body_class', 'simply_body_classes' );

function simply_body_classes( $classes ) {
	$classes[] = is_front_page() ? 'ss-homepage' : 'ss-interior';
	return $classes;
}


// --------------------------------------------------------------------------
// SCROLL JS
// Handles utility bar slide-away and header transparent -> solid transition.
// --------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'simply_enqueue_scroll_js' );

function simply_enqueue_scroll_js() {
	wp_enqueue_script(
		'simply-scroll',
		get_stylesheet_directory_uri() . '/assets/js/simply-scroll.js',
		array(),
		'1.8.0', // bump this version any time simply-scroll.js changes to bust cache
		true     // load in footer
	);
}



// Hero functionality — use Simply Hero plugin (https://simplydesign.com/simply-hero)

// ==========================================================================
// DYNAMIC CONTAINER CSS
// Outputs .tb-container-inner max-width based on simply_container_width option.
// This reinforces the block default set by simply_toolset_container_defaults().
// ==========================================================================

add_action( 'wp_enqueue_scripts', 'simply_container_css', 25 );

function simply_container_css() {
	$width = (int) get_option( 'simply_container_width', 1200 );

	$css = "
		:root {
			--ss-max-width: {$width}px;
		}
		.tb-container-inner {
			max-width: {$width}px;
			margin-left: auto;
			margin-right: auto;
			padding-left: 5px;
			padding-right: 5px;
			box-sizing: border-box;
		}
	";

	wp_add_inline_style( genesis_get_theme_handle(), $css );
}

// ==========================================================================
// EDITOR STYLESHEET
// Loads theme CSS into the Gutenberg editor so block styles
// show correct colors in the backend when selected.
// ==========================================================================

add_action( 'after_setup_theme', 'simply_editor_styles' );

function simply_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}


// ==========================================================================
// GUTENBERG BLOCK STYLES
// Registers "Dark", "Light", "Brand 1", "Brand 2" styles for Group block.
// Appears in block Style panel on right side of editor.
// CSS classes: is-dark, is-light, is-brand-1, is-brand-2
// ==========================================================================

add_action( 'init', 'simply_register_block_styles' );

function simply_register_block_styles() {

	// Name without 'is-' prefix — Gutenberg prepends 'is-style-' automatically.
	// So 'dark' becomes class 'is-style-dark' on the block.
	// Toolset users add 'is-dark' directly — CSS targets both.
	$styles = array(
		array(
			'name'  => 'dark',
			'label' => 'Dark Section',
		),
		array(
			'name'  => 'light',
			'label' => 'Light Section',
		),
		array(
			'name'  => 'brand-1',
			'label' => 'Brand Section 1',
		),
		array(
			'name'  => 'brand-2',
			'label' => 'Brand Section 2',
		),
	);

	// Register for Group block and Cover block
	$blocks = array(
		'core/group',
		'core/cover',
	);

	foreach ( $blocks as $block ) {
		foreach ( $styles as $style ) {
			register_block_style( $block, $style );
		}
	}

	// Eyebrow / Label style for heading blocks
	register_block_style( 'core/heading', array(
		'name'  => 'eyebrow',
		'label' => 'Label / Eyebrow',
	) );
}


// ==========================================================================
// WIREFRAME MODE
// Toggle via admin bar or WP option.
// When ON: neutral charcoal styles + client-visible banner.
// When OFF: client config takes over.
// ==========================================================================

// --- Toggle handler — processes admin bar click ---
add_action( 'init', 'simply_handle_wireframe_toggle' );

function simply_handle_wireframe_toggle() {
	if (
		isset( $_GET['simply_wireframe_toggle'] ) &&
		current_user_can( 'manage_options' ) &&
		wp_verify_nonce( $_GET['_wpnonce'], 'simply_wireframe_toggle' )
	) {
		$current = get_option( 'simply_wireframe_mode', false );
		update_option( 'simply_wireframe_mode', ! $current );
		wp_safe_redirect( remove_query_arg( array( 'simply_wireframe_toggle', '_wpnonce' ) ) );
		exit;
	}
}

// --- Admin bar button ---
add_action( 'admin_bar_menu', 'simply_wireframe_admin_bar', 100 );

function simply_wireframe_admin_bar( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) return;

	$is_on    = get_option( 'simply_wireframe_mode', false );
	$label    = $is_on ? '⬤ Wireframe: ON' : '◯ Wireframe: OFF';
	$color    = $is_on ? '#f0a500' : '#4CAF50';
	$url      = wp_nonce_url(
		add_query_arg( 'simply_wireframe_toggle', '1' ),
		'simply_wireframe_toggle'
	);

	$wp_admin_bar->add_node( array(
		'id'    => 'simply-wireframe-toggle',
		'title' => '<span style="color:' . $color . ';font-weight:700;">' . $label . '</span>',
		'href'  => $url,
		'meta'  => array( 'title' => 'Toggle wireframe mode on/off' ),
	) );
}

// --- Wireframe popup output ---
add_action( 'genesis_before', 'simply_wireframe_banner' );

function simply_wireframe_banner() {
	if ( ! get_option( 'simply_wireframe_mode', false ) ) return;
	$default = "UX Preview — You're viewing the layout and content structure. Design and branding is not applied so you can focus on content review and UX flow.";
	$message = get_option( 'simply_wireframe_message', '' );
	if ( '' === $message ) $message = $default;
	?>
	<div class="simply-wireframe-popup" id="simply-wireframe-popup">
		<div class="simply-wireframe-popup__overlay" onclick="simplyDismissWireframe()"></div>
		<div class="simply-wireframe-popup__box">
			<button class="simply-wireframe-popup__close" onclick="simplyDismissWireframe()" aria-label="Dismiss">&times;</button>
			<p class="simply-wireframe-popup__message"><?php echo nl2br( esc_html( $message ) ); ?></p>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<a class="simply-wireframe-popup__toggle" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'simply_wireframe_toggle', '1' ), 'simply_wireframe_toggle' ) ); ?>">
				Turn off wireframe mode
			</a>
			<?php endif; ?>
		</div>
	</div>
	<script>
	var simplyWfMsg = <?php echo wp_json_encode( $message ); ?>;
	function simplyDismissWireframe() {
		localStorage.setItem( 'simply_wireframe_dismissed', simplyWfMsg );
		document.getElementById( 'simply-wireframe-popup' ).style.display = 'none';
	}
	if ( localStorage.getItem( 'simply_wireframe_dismissed' ) === simplyWfMsg ) {
		document.getElementById( 'simply-wireframe-popup' ).style.display = 'none';
	}
	</script>
	<?php
}

// --- Wireframe body class ---
add_filter( 'body_class', 'simply_wireframe_body_class' );

function simply_wireframe_body_class( $classes ) {
	if ( get_option( 'simply_wireframe_mode', false ) ) {
		$classes[] = 'simply-wireframe';
	}
	return $classes;
}


// ==========================================================================
// SIMPLY STARTER WELCOME PAGE
// Replaces Genesis "Get Started" / Starter Packs screen.
// Accessible at Appearance -> Simply Starter in the admin.
// ==========================================================================


// Redirect Genesis "Child Theme Setup" page to our welcome page.
add_action( 'admin_init', 'simply_redirect_genesis_onboarding' );

function simply_redirect_genesis_onboarding() {
	if (
		isset( $_GET['page'] ) &&
		$_GET['page'] === 'genesis-getting-started' &&
		current_user_can( 'manage_options' )
	) {
		wp_safe_redirect( admin_url( 'themes.php?page=simply-starter-welcome' ) );
		exit;
	}
}


// ==========================================================================
// HELPER — find active branding plugin (Simply Branded, Client Branded, or
// legacy Client Config). Returns plugin name string or false.
// Matches: simply-branded, *-branded, *-client-config slugs.
// ==========================================================================

function simply_is_branding_plugin( $plugin_file ) {
	return (
		strpos( $plugin_file, '-branded'     ) !== false ||
		strpos( $plugin_file, 'client-config') !== false
	);
}

function simply_get_active_client_config() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();
	foreach ( $all_plugins as $plugin_file => $plugin_data ) {
		if ( simply_is_branding_plugin( $plugin_file ) && is_plugin_active( $plugin_file ) ) {
			return $plugin_data['Name'];
		}
	}
	return false;
}


// ==========================================================================
// WIREFRAME MODE — AUTO-TOGGLE BRANDING PLUGIN
// When wireframe turns ON: deactivate Simply Branded / Client Branded, store slug.
// When wireframe turns OFF: reactivate the stored branding plugin.
// ==========================================================================

// Override the existing wireframe toggle handler to add plugin auto-toggle.
remove_action( 'init', 'simply_handle_wireframe_toggle' );
add_action( 'init', 'simply_handle_wireframe_toggle_v2' );

function simply_handle_wireframe_toggle_v2() {
	if (
		! isset( $_GET['simply_wireframe_toggle'] ) ||
		! current_user_can( 'manage_options' ) ||
		! wp_verify_nonce( $_GET['_wpnonce'], 'simply_wireframe_toggle' )
	) {
		return;
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$currently_on = get_option( 'simply_wireframe_mode', false );

	if ( ! $currently_on ) {

		// --- Turning wireframe ON ---
		// Find and deactivate any active branding plugin.
		$all_plugins = get_plugins();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			if ( simply_is_branding_plugin( $plugin_file ) && is_plugin_active( $plugin_file ) ) {
				// Store slug so we can reactivate later.
				update_option( 'simply_wireframe_saved_plugin', $plugin_file );
				deactivate_plugins( $plugin_file );
				break;
			}
		}

		update_option( 'simply_wireframe_mode', true );

	} else {

		// --- Turning wireframe OFF ---
		// Reactivate the stored client config plugin.
		$saved_plugin = get_option( 'simply_wireframe_saved_plugin', '' );
		if ( $saved_plugin && file_exists( WP_PLUGIN_DIR . '/' . $saved_plugin ) ) {
			activate_plugin( $saved_plugin );
		}

		update_option( 'simply_wireframe_mode', false );
	}

	wp_safe_redirect( remove_query_arg( array( 'simply_wireframe_toggle', '_wpnonce' ) ) );
	exit;
}


// ==========================================================================
// WELCOME SCREEN
// Replaces Genesis Sample's "Get Started / Starter Packs" onboarding page.
// Accessible at Genesis -> Child Theme Setup in the dashboard.
// Note sheet updated with each major release.
// ==========================================================================

// Remove Genesis Sample's onboarding page content
add_filter( 'genesis_theme_settings_defaults', 'simply_override_onboarding' );
add_action( 'genesis_theme_settings_metaboxes', 'simply_remove_onboarding_metabox' );

function simply_remove_onboarding_metabox( $pagehook ) {
	remove_meta_box( 'genesis-theme-settings-onboarding', $pagehook, 'main' );
}

// Save handler for Simply Starter settings
add_action( 'admin_init', 'simply_save_settings' );

function simply_save_settings() {
	if (
		! isset( $_POST['simply_settings_nonce'] ) ||
		! wp_verify_nonce( $_POST['simply_settings_nonce'], 'simply_save_settings' ) ||
		! current_user_can( 'manage_options' )
	) {
		return;
	}

	if ( isset( $_POST['simply_container_width'] ) ) {
		$width = absint( $_POST['simply_container_width'] );
		// Sanity check — between 600 and 2400px
		if ( $width >= 600 && $width <= 2400 ) {
			update_option( 'simply_container_width', $width );
		}
	}

	if ( isset( $_POST['simply_wireframe_message'] ) ) {
		update_option( 'simply_wireframe_message', sanitize_textarea_field( wp_unslash( $_POST['simply_wireframe_message'] ) ) );
	}

	wp_safe_redirect( add_query_arg( array(
		'page'  => 'simply-starter-welcome',
		'saved' => '1',
	), admin_url( 'themes.php' ) ) );
	exit;
}

// Add our own welcome page under Appearance -> Simply Starter
add_action( 'admin_menu', 'simply_welcome_page_menu' );

function simply_welcome_page_menu() {
	add_theme_page(
		'Simply Starter — Welcome',
		'Simply Starter',
		'manage_options',
		'simply-starter-welcome',
		'simply_welcome_page_output'
	);
}

function simply_welcome_page_output() {

	$client_config_active = false;
	$active_plugins = get_option( 'active_plugins', array() );
	foreach ( $active_plugins as $plugin ) {
		if ( simply_is_branding_plugin( $plugin ) ) {
			$client_config_active = true;
			break;
		}
	}

	$wireframe_on = get_option( 'simply_wireframe_mode', false );
	?>
	<div class="wrap" style="max-width:1200px;">

		<h1 style="font-size:28px; font-weight:700; margin-bottom:4px;">
			Simply Starter <span style="font-size:16px; color:#888; font-weight:400;">v<?php echo wp_get_theme()->get( 'Version' ); ?></span>
		</h1>
		<p style="color:#888; margin-top:0;">By <a href="https://simplydesign.com" target="_blank">Simply Design</a></p>

		<hr>

		<?php if ( ! $client_config_active ) : ?>
		<div style="background:#fff3cd; border-left:4px solid #f0a500; padding:16px 20px; margin-bottom:24px; border-radius:0 4px 4px 0;">
			<strong>⚠️ No Branding Plugin Detected</strong> — Simply Starter is running in wireframe mode. Install <strong>Simply Branded</strong> or a <strong>Client Branded</strong> plugin to apply your brand.
		</div>
		<?php else : ?>
		<div style="background:#d4edda; border-left:4px solid #4CAF50; padding:16px 20px; margin-bottom:24px; border-radius:0 4px 4px 0;">
			<strong>✓ Branding Plugin Active</strong> — Your brand is configured and ready.
		</div>
		<?php endif; ?>

		<div style="display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start;">

			<?php /* ── LEFT: Plugin Installer ── */ ?>
			<div>
				<?php simply_render_plugin_installer(); ?>
			</div>

			<?php /* ── RIGHT: Setup + Reference ── */ ?>
			<div>

				<?php
				$cl = get_option( 'simply_setup_checklist', [] );
				$checked = fn( $k ) => ! empty( $cl[ $k ] ) ? 'checked' : '';
				$cb = fn( $key, $label ) =>
					'<li style="display:flex;align-items:center;gap:8px;line-height:1.8;">'
					. '<input type="checkbox" class="ss-checklist-item" id="ss-cl-' . $key . '" data-key="' . $key . '" ' . $checked( $key ) . ' style="width:16px;height:16px;cursor:pointer;flex-shrink:0;">'
					. '<label for="ss-cl-' . $key . '" style="cursor:pointer;font-size:13px;">' . $label . '</label>'
					. '</li>';
				?>
				<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
					<h2 style="margin-top:0; font-size:16px;">🚀 Setup Checklist</h2>
					<ul style="list-style:none;margin:0;padding:0;">
						<li style="display:flex;align-items:center;gap:8px;line-height:1.8;">
							<?php if ( $client_config_active ) : ?>
							<span style="color:#4CAF50;font-size:16px;">✓</span>
							<span style="font-size:13px;text-decoration:line-through;color:#aaa;">Install &amp; activate a Branding plugin</span>
							<?php else : ?>
							<span style="font-size:16px;">⬜</span>
							<span style="font-size:13px;">Install &amp; activate a Branding plugin</span>
							<?php endif; ?>
						</li>
						<?php echo $cb( 'logo',    'Upload logo via <a href="' . admin_url('customize.php') . '">Customize → Site Identity</a>' ); ?>
						<?php echo $cb( 'homepage','Set homepage via <a href="' . admin_url('customize.php') . '">Customize → Homepage Settings</a>' ); ?>
						<?php echo $cb( 'menus',   'Add menus via <a href="' . admin_url('nav-menus.php') . '">Appearance → Menus</a>' ); ?>
						<?php echo $cb( 'navcta',  'Add <code>cta</code> CSS class to nav CTA item' ); ?>
						<?php echo $cb( 'widgets', 'Populate footer widgets via <a href="' . admin_url('widgets.php') . '">Appearance → Widgets</a>' ); ?>
						<?php echo $cb( 'tagline', 'Update tagline in <a href="' . admin_url('options-general.php') . '">Settings → General</a>' ); ?>
					</ul>
					<script>
					jQuery(function($){
						$('.ss-checklist-item').on('change', function(){
							$.post(simplyInstaller.ajaxUrl, {
								action:   'simply_toggle_checklist',
								nonce:    simplyInstaller.nonce,
								item:     $(this).data('key'),
								checked:  $(this).is(':checked') ? 1 : 0,
							});
						});
					});
					</script>
				</div>

				<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
					<h2 style="margin-top:0; font-size:16px;">🔭 Wireframe Mode</h2>
					<p style="font-size:13px; color:#555;">Toggle from the <strong>admin bar</strong>. When ON, neutral styles apply and a centered popup shows.</p>
					<p>
						State: <strong><?php echo $wireframe_on ? '<span style="color:#f0a500;">⬤ ON</span>' : '<span style="color:#4CAF50;">◯ OFF</span>'; ?></strong>
						&nbsp;
						<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'simply_wireframe_toggle', '1' ), 'simply_wireframe_toggle' ) ); ?>" style="font-size:13px;">
							<?php echo $wireframe_on ? 'Turn off' : 'Turn on'; ?>
						</a>
					</p>
					<?php
					$wf_default = "UX Preview — You're viewing the layout and content structure. Design and branding is not applied so you can focus on content review and UX flow.";
					$wf_saved   = get_option( 'simply_wireframe_message', '' );
					?>
					<form method="post" style="margin-top:4px;">
						<?php wp_nonce_field( 'simply_save_settings', 'simply_settings_nonce' ); ?>
						<label for="simply_wireframe_message" style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Popup message</label>
						<textarea id="simply_wireframe_message" name="simply_wireframe_message" rows="3" style="width:100%; font-size:13px; padding:8px; border:1px solid #ccc; border-radius:4px; resize:vertical;"><?php echo esc_textarea( $wf_saved ); ?></textarea>
						<p style="font-size:11px; color:#aaa; margin:4px 0 10px;">Leave blank to use the default message.</p>
						<input type="submit" class="button button-secondary" value="Save Message" style="font-size:12px;">
					</form>
				</div>

				<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
					<h2 style="margin-top:0; font-size:16px;">🎨 Section Color Classes</h2>
					<table style="width:100%; border-collapse:collapse; font-size:13px;">
						<tr style="background:#f5f5f5;">
							<th style="padding:6px 10px; text-align:left; border:1px solid #e2e2e2;">Class</th>
							<th style="padding:6px 10px; text-align:left; border:1px solid #e2e2e2;">Use for</th>
						</tr>
						<?php foreach ( [
							[ 'is-dark',    'Dark bg, light text' ],
							[ 'is-light',   'Light bg, dark text' ],
							[ 'is-brand-1', 'Brand color 1' ],
							[ 'is-brand-2', 'Brand color 2' ],
						] as $i => $row ) : ?>
						<tr<?php echo $i % 2 ? ' style="background:#f9f9f9"' : ''; ?>>
							<td style="padding:6px 10px; border:1px solid #e2e2e2;"><code><?php echo $row[0]; ?></code></td>
							<td style="padding:6px 10px; border:1px solid #e2e2e2; color:#555;"><?php echo $row[1]; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>

				<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px;">
					<h2 style="margin-top:0; font-size:16px;">📋 Tips</h2>
					<ul style="line-height:2; color:#444; margin:0; padding-left:20px; font-size:13px;">
						<li>Typekit fonts require Adobe Fonts privacy disclosure in your Privacy Policy</li>
						<li>Footer Bottom Bar widget area outputs only when it has content</li>
						<li>Logo supports PNG and SVG</li>
					</ul>
				</div>

			</div><?php /* end right column */ ?>

		</div><?php /* end grid */ ?>

		<p style="color:#aaa; font-size:12px; margin-top:24px; text-align:center;">
			Simply Starter <?php echo wp_get_theme()->get( 'Version' ); ?> — Built by
			<a href="https://simplydesign.com" target="_blank" style="color:#aaa;">Simply Design</a>
		</p>

	</div>
	<?php
}


// ==========================================================================
// SIMPLY PLUGIN INSTALLER
// Registry, AJAX handlers, and welcome-page UI.
//
// To flip a plugin from GitHub → WP.org when it ships:
//   change  'source' => 'github'  to  'source' => 'wporg'
// The UI, install flow, and activate flow are identical either way.
// ==========================================================================

function simply_plugin_registry() {
	return [
		[
			'name'        => 'Simply Blocks',
			'slug'        => 'simply-blocks',
			'file'        => 'simply-blocks/simply-blocks.php',
			'description' => 'Gutenberg blocks for the Simply suite — Section, Container, Columns, Events, FAQs, News, and Logo Slider.',
			'github'      => 'staceyzav/simply-blocks',
			'source'      => 'github',
			'tier'        => 'paid',
		],
		[
			'name'        => 'Simply Branded',
			'slug'        => 'simply-branded',
			'file'        => 'simply-branded/simply-branded.php',
			'description' => 'Brand admin UI — colors, border radius, fonts, and custom CSS. Outputs --client-* tokens to the theme.',
			'github'      => 'staceyzav/simply-branded',
			'source'      => 'github',
			'tier'        => 'paid',
		],
		[
			'name'        => 'Simply Events',
			'slug'        => 'simply-events',
			'file'        => 'simply-events/simply-events.php',
			'description' => 'Events CPT with date blocks, category filters, list/grid views, and single event pages.',
			'github'      => 'staceyzav/simply-events',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply News',
			'slug'        => 'simply-news',
			'file'        => 'simply-news/simply-news.php',
			'description' => 'Post feed with photo, category badge, source publication, and optional external link.',
			'github'      => 'staceyzav/simply-news',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply Team',
			'slug'        => 'simply-team',
			'file'        => 'simply-team/simply-team.php',
			'description' => 'Team member CPT with headshot, role, contact info, and slide-over bio panel.',
			'github'      => 'staceyzav/simply-team',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply FAQs',
			'slug'        => 'simply-faqs',
			'file'        => 'simply-faqs/simply-faqs.php',
			'description' => 'Accordion FAQ shortcode with category filters and smooth open/close animation.',
			'github'      => 'staceyzav/simply-faqs',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply Logo Slider',
			'slug'        => 'simply-logo-slider',
			'file'        => 'simply-logo-slider/simply-logo-slider.php',
			'description' => 'Partner/sponsor logo carousel with static mode, drag support, and order control.',
			'github'      => 'staceyzav/simply-logo-slider',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply Utility Bar',
			'slug'        => 'simply-utility-bar',
			'file'        => 'simply-utility-bar/simply-utility-bar.php',
			'description' => 'Sticky top bar that hides on scroll-down and returns on scroll-up.',
			'github'      => 'staceyzav/simply-utility-bar',
			'source'      => 'github',
			'tier'        => 'free',
		],
		[
			'name'        => 'Simply Reviews',
			'slug'        => 'simply-reviews',
			'file'        => 'simply-reviews/simply-reviews.php',
			'description' => 'Curated review slider with star ratings, source labels, dot navigation, and autoplay.',
			'github'      => 'staceyzav/simply-reviews',
			'source'      => 'github',
			'tier'        => 'free',
		],
	];
}

// ── Inject nonce + ajaxUrl into welcome page head ─────────────────────────────

add_action( 'admin_head', 'simply_installer_head' );
function simply_installer_head() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'appearance_page_simply-starter-welcome' ) return;
	printf(
		'<script>var simplyInstaller=%s;</script>',
		wp_json_encode( [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'simply_installer_nonce' ),
		] )
	);
}

// ── AJAX: Install plugin ───────────────────────────────────────────────────────

add_action( 'wp_ajax_simply_install_plugin', 'simply_ajax_install_plugin' );
function simply_ajax_install_plugin() {
	// Buffer any stray output so PHP warnings don't corrupt the JSON response
	ob_start();

	check_ajax_referer( 'simply_installer_nonce', 'nonce' );
	if ( ! current_user_can( 'install_plugins' ) ) {
		ob_end_clean();
		wp_send_json_error( 'Permission denied.' );
	}

	$slug        = sanitize_key( $_POST['slug']        ?? '' );
	$source      = sanitize_key( $_POST['source']      ?? 'github' );
	$github      = sanitize_text_field( $_POST['github']      ?? '' );
	$plugin_file = sanitize_text_field( $_POST['plugin_file'] ?? '' );
	$is_update   = ! empty( $_POST['is_update'] );

	if ( ! $slug ) { ob_end_clean(); wp_send_json_error( 'Missing plugin slug.' ); }

	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	WP_Filesystem();
	global $wp_filesystem;

	// For updates: remove the existing plugin folder first so install() doesn't
	// hit the "destination already exists" error (more reliable than overwrite_package).
	if ( $is_update && $plugin_file ) {
		$plugin_dir = WP_PLUGIN_DIR . '/' . dirname( $plugin_file );
		if ( $wp_filesystem->is_dir( $plugin_dir ) ) {
			$wp_filesystem->delete( $plugin_dir, true );
		}
		// Clear the cached version transient so it refreshes after update
		delete_transient( 'spi_latest_' . $slug );
	}

	if ( $source === 'wporg' ) {
		$api = plugins_api( 'plugin_information', [
			'slug'   => $slug,
			'fields' => [ 'download_link' => true ],
		] );
		if ( is_wp_error( $api ) ) { ob_end_clean(); wp_send_json_error( $api->get_error_message() ); }
		$package = $api->download_link;
	} else {
		// GitHub ZIP extracts as {slug}-main — rename it to {slug} before WP moves it
		$package = "https://github.com/{$github}/archive/refs/heads/main.zip";

		add_filter( 'upgrader_source_selection', function( $source_path, $remote_source ) use ( $slug ) {
			global $wp_filesystem;
			$basename = basename( untrailingslashit( $source_path ) );
			if ( $basename !== $slug ) {
				$new_path = trailingslashit( $remote_source ) . $slug;
				if ( $wp_filesystem->move( $source_path, $new_path ) ) {
					return trailingslashit( $new_path );
				}
			}
			return $source_path;
		}, 1, 2 );
	}

	$skin   = new WP_Ajax_Upgrader_Skin();
	$result = ( new Plugin_Upgrader( $skin ) )->install( $package );
	ob_end_clean();

	if ( is_wp_error( $result ) )            wp_send_json_error( $result->get_error_message() );
	if ( $result === false )                 wp_send_json_error( 'Download failed. Check the server can make outbound HTTPS requests (curl/allow_url_fopen).' );
	if ( $skin->get_errors()->has_errors() ) wp_send_json_error( implode( ' ', $skin->get_error_messages() ) );

	wp_send_json_success();
}

// ── AJAX: Activate plugin ──────────────────────────────────────────────────────

add_action( 'wp_ajax_simply_activate_plugin', 'simply_ajax_activate_plugin' );
function simply_ajax_activate_plugin() {
	check_ajax_referer( 'simply_installer_nonce', 'nonce' );
	if ( ! current_user_can( 'activate_plugins' ) ) wp_send_json_error( 'Permission denied.' );

	$plugin_file = sanitize_text_field( $_POST['plugin_file'] ?? '' );
	if ( ! $plugin_file ) wp_send_json_error( 'Missing plugin file.' );

	$result = activate_plugin( $plugin_file );
	if ( is_wp_error( $result ) ) wp_send_json_error( $result->get_error_message() );

	wp_send_json_success();
}

// ── AJAX: Deactivate plugin ────────────────────────────────────────────────────

add_action( 'wp_ajax_simply_deactivate_plugin', 'simply_ajax_deactivate_plugin' );
function simply_ajax_deactivate_plugin() {
	check_ajax_referer( 'simply_installer_nonce', 'nonce' );
	if ( ! current_user_can( 'deactivate_plugins' ) ) wp_send_json_error( 'Permission denied.' );

	$plugin_file = sanitize_text_field( $_POST['plugin_file'] ?? '' );
	if ( ! $plugin_file ) wp_send_json_error( 'Missing plugin file.' );

	deactivate_plugins( $plugin_file );
	wp_send_json_success();
}

// ── AJAX: Save setup checklist state ──────────────────────────────────────────

add_action( 'wp_ajax_simply_toggle_checklist', 'simply_ajax_toggle_checklist' );
function simply_ajax_toggle_checklist() {
	check_ajax_referer( 'simply_installer_nonce', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Permission denied.' );

	$item    = sanitize_key( $_POST['item']    ?? '' );
	$checked = ! empty( $_POST['checked'] );

	if ( ! $item ) wp_send_json_error( 'Missing item.' );

	$cl = get_option( 'simply_setup_checklist', [] );
	if ( $checked ) {
		$cl[ $item ] = true;
	} else {
		unset( $cl[ $item ] );
	}
	update_option( 'simply_setup_checklist', $cl, false );

	wp_send_json_success();
}

// ── AJAX: Batch version check (GitHub releases + transient cache) ─────────────

add_action( 'wp_ajax_simply_check_versions', 'simply_ajax_check_versions' );
function simply_ajax_check_versions() {
	check_ajax_referer( 'simply_installer_nonce', 'nonce' );
	if ( ! current_user_can( 'install_plugins' ) ) wp_send_json_error( 'Permission denied.' );

	$plugins  = simply_plugin_registry();
	$versions = [];

	foreach ( $plugins as $plugin ) {
		$slug   = $plugin['slug'];
		$cached = get_transient( 'spi_latest_' . $slug );

		if ( $cached !== false ) {
			$versions[ $slug ] = $cached;
			continue;
		}

		$latest = null;

		if ( $plugin['source'] === 'wporg' ) {
			$api    = plugins_api( 'plugin_information', [ 'slug' => $slug, 'fields' => [ 'version' => true ] ] );
			$latest = is_wp_error( $api ) ? null : ( $api->version ?? null );
		} elseif ( ! empty( $plugin['github'] ) ) {
			$resp = wp_remote_get( "https://api.github.com/repos/{$plugin['github']}/releases/latest", [
				'timeout' => 5,
				'headers' => [ 'User-Agent' => 'WordPress/Simply-Starter' ],
			] );
			if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
				$body   = json_decode( wp_remote_retrieve_body( $resp ), true );
				$latest = ltrim( $body['tag_name'] ?? '', 'v' );
			}
		}

		if ( $latest ) {
			set_transient( 'spi_latest_' . $slug, $latest, HOUR_IN_SECONDS );
			$versions[ $slug ] = $latest;
		}
	}

	wp_send_json_success( $versions );
}

// ── Detect active client-specific plugins (branded/core, not in registry) ─────

function simply_get_custom_plugins() {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	// Registry slugs — exclude these from custom detection
	$registry_slugs = array_column( simply_plugin_registry(), 'slug' );

	$custom = [];
	foreach ( get_option( 'active_plugins', [] ) as $plugin_file ) {
		$slug = dirname( $plugin_file );

		// Skip anything already in the registry
		if ( in_array( $slug, $registry_slugs, true ) ) continue;

		// Detect client-specific plugins: branding (-branded, client-config) or core (-core)
		$is_branded = simply_is_branding_plugin( $plugin_file );
		$is_core    = substr( $slug, -5 ) === '-core';

		if ( ! $is_branded && ! $is_core ) continue;

		$data     = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file, false, false );
		$custom[] = [
			'name'        => $data['Name']        ?? $slug,
			'description' => $data['Description'] ?? ( $is_core ? 'Client-specific functions and custom post types.' : 'Client-specific branding plugin.' ),
			'version'     => $data['Version']     ?? '',
			'slug'        => $slug,
			'file'        => $plugin_file,
			'source'      => 'custom',
			'github'      => '',
			'tier'        => 'custom',
		];
	}
	return $custom;
}

// ── Plugin installer UI (called from simply_welcome_page_output) ───────────────

function simply_render_plugin_installer() {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$registry       = simply_plugin_registry();
	$custom_plugins = simply_get_custom_plugins();

	// Tier order, labels, and pill styles
	$tiers = [
		'custom' => [ 'label' => '✦ Custom',      'pill' => 'background:#d97706;color:#fff' ],
		'paid'   => [ 'label' => '⭐ Paid Suite',  'pill' => 'background:#1d2327;color:#fff' ],
		'free'   => [ 'label' => '✓ Free Plugins', 'pill' => 'background:#0073aa;color:#fff' ],
	];

	// Bucket registry plugins by tier; prepend custom plugins
	$by_tier = [ 'custom' => $custom_plugins ];
	foreach ( $registry as $p ) {
		$by_tier[ $p['tier'] ][] = $p;
	}

	// If a custom branding plugin is active, note it for the Simply Branded row
	$custom_branding_active = ! empty( $custom_plugins );
	?>
	<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
		<h2 style="margin-top:0; font-size:18px;">🔌 Simply Plugins</h2>
		<p style="color:#666; margin:-8px 0 16px; font-size:13px;">Manage Simply plugins directly. Status and versions update live — no page reload.</p>

		<?php foreach ( $tiers as $tier => $tier_info ) :
			$tier_plugins = $by_tier[ $tier ] ?? [];
			if ( empty( $tier_plugins ) ) continue;
		?>
		<span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;<?php echo $tier_info['pill']; ?>;padding:3px 10px;border-radius:3px;margin:20px 0 8px;display:inline-block;">
			<?php echo esc_html( $tier_info['label'] ); ?>
		</span>
		<table style="width:100%; border-collapse:collapse; margin-bottom:4px;">
			<?php foreach ( $tier_plugins as $plugin ) :
				$installed  = file_exists( WP_PLUGIN_DIR . '/' . $plugin['file'] );
				$active     = $installed && is_plugin_active( $plugin['file'] );
				$row_id     = 'spi-' . esc_attr( $plugin['slug'] );
				$is_custom  = ( $plugin['source'] === 'custom' );

				// Installed version (fast local read)
				$inst_ver = '';
				if ( $installed ) {
					$pdata    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin['file'], false, false );
					$inst_ver = $pdata['Version'] ?? ( $plugin['version'] ?? '' );
				}

				// Escape data attrs
				$d = array_map( 'esc_attr', [
					'slug'   => $plugin['slug'],
					'source' => $plugin['source'],
					'github' => $plugin['github'] ?? '',
					'file'   => $plugin['file'],
					'row'    => $row_id,
				] );

				// Badge
				if ( $active ) {
					$badge = '<span style="color:#4CAF50;font-size:12px;font-weight:700;">● Active</span>';
				} elseif ( $installed ) {
					$badge = '<span style="color:#aaa;font-size:12px;font-weight:700;">○ Installed</span>';
				} elseif ( $plugin['slug'] === 'simply-branded' && $custom_branding_active ) {
					$badge = '<span style="color:#4CAF50;font-size:12px;font-weight:700;">● Custom Active</span>';
				} else {
					$badge = '<span style="color:#ccc;font-size:12px;font-weight:700;">— Not installed</span>';
				}

				// Actions
				$update_btn = '<button class="button spi-update" style="display:none;margin-left:4px"
					data-slug="' . $d['slug'] . '" data-source="' . $d['source'] . '"
					data-github="' . $d['github'] . '" data-file="' . $d['file'] . '"
					data-row="' . $d['row'] . '">Update</button>';

				$deact_btn = '<button class="button spi-deactivate" style="color:#a00;margin-left:4px"
					data-file="' . $d['file'] . '" data-row="' . $d['row'] . '">Deactivate</button>';

				if ( $active ) {
					$action = $is_custom ? $deact_btn : ( $update_btn . $deact_btn );
				} elseif ( $installed ) {
					$action = '<button class="button button-primary spi-activate"
						data-file="' . $d['file'] . '" data-row="' . $d['row'] . '">Activate</button>' . $update_btn;
				} elseif ( $plugin['slug'] === 'simply-branded' && $custom_branding_active ) {
					$action = '<span style="color:#999;font-size:11px;">Not needed<br><span style="color:#4CAF50;">Custom Branding Applied</span></span>';
				} else {
					$action = '<button class="button button-primary spi-install"
						data-slug="' . $d['slug'] . '" data-source="' . $d['source'] . '"
						data-github="' . $d['github'] . '" data-file="' . $d['file'] . '"
						data-row="' . $d['row'] . '">Install</button>';
				}

				// Version line (installed version shown immediately; latest filled async)
				$ver_html = '';
				if ( ! $is_custom ) {
					$inst_label = $inst_ver ? 'v' . esc_html( $inst_ver ) : '';
					$ver_html   = '<span style="font-size:11px;color:#aaa;display:block;margin-top:2px;">'
						. $inst_label
						. '<span class="spi-ver-check" data-slug="' . $d['slug'] . '" data-installed="' . esc_attr( $inst_ver ) . '"> · checking…</span>'
						. '</span>';
				} elseif ( $inst_ver ) {
					$ver_html = '<span style="font-size:11px;color:#aaa;display:block;margin-top:2px;">v' . esc_html( $inst_ver ) . '</span>';
				}
			?>
			<tr id="<?php echo $row_id; ?>" style="border-bottom:1px solid #f5f5f5;">
				<td style="padding:12px 10px 12px 0; width:130px; vertical-align:middle;"><?php echo $badge; ?></td>
				<td style="padding:12px 10px; vertical-align:top;">
					<strong style="font-size:13px;"><?php echo esc_html( $plugin['name'] ); ?></strong>
					<?php echo $ver_html; ?>
					<span style="color:#777; font-size:12px; line-height:1.4; display:block; margin-top:2px;"><?php echo esc_html( $plugin['description'] ); ?></span>
				</td>
				<td style="padding:12px 0 12px 10px; text-align:right; vertical-align:middle; white-space:nowrap; width:170px;"><?php echo $action; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php endforeach; ?>
	</div>

	<script>
	(function($){
		var ajaxUrl = simplyInstaller.ajaxUrl, nonce = simplyInstaller.nonce;

		// ── Version comparison ──────────────────────────────────────────────
		function versionGt(a, b) {
			var av = String(a||'0').split('.').map(Number);
			var bv = String(b||'0').split('.').map(Number);
			for (var i = 0; i < 3; i++) {
				var ai = av[i]||0, bi = bv[i]||0;
				if (ai > bi) return true;
				if (ai < bi) return false;
			}
			return false;
		}

		// ── Async version check on page load ────────────────────────────────
		$.post(ajaxUrl, { action:'simply_check_versions', nonce:nonce })
		.done(function(res){
			if (!res.success) return;
			$.each(res.data, function(slug, latest){
				var $vc = $('.spi-ver-check[data-slug="'+slug+'"]');
				if (!$vc.length) return;
				var installed = $vc.data('installed');
				if (installed && versionGt(latest, installed)) {
					$vc.html(' · <span style="color:#d97706;font-weight:600;">v'+latest+' available</span>');
					// Show the Update button for this row
					$('#spi-'+slug+' .spi-update').show();
				} else if (installed) {
					$vc.html(' · <span style="color:#4CAF50;">✓ up to date</span>');
				} else {
					// Not installed — just show latest
					$vc.html('Latest: v'+latest);
				}
			});
		})
		.fail(function(){ $('.spi-ver-check').html(''); }); // fail silently

		// ── Helpers ─────────────────────────────────────────────────────────
		function badge(color, label) {
			return '<span style="color:'+color+';font-size:12px;font-weight:700;">'+label+'</span>';
		}
		function updateRow(rowId, badgeHtml, actionHtml) {
			$('#'+rowId).find('td:first-child').html(badgeHtml);
			$('#'+rowId).find('td:last-child').html(actionHtml||'');
		}
		function mkUpdate(slug, source, github, file, row) {
			return '<button class="button spi-update" style="margin-left:4px" data-slug="'+slug+'" data-source="'+source+'" data-github="'+github+'" data-file="'+file+'" data-row="'+row+'">Update</button>';
		}
		function activeActions(file, row, slug, source, github) {
			return mkUpdate(slug,source,github,file,row)
				+ ' <button class="button spi-deactivate" data-file="'+file+'" data-row="'+row+'" style="color:#a00;margin-left:4px">Deactivate</button>';
		}
		function installedActions(file, row, slug, source, github) {
			return '<button class="button button-primary spi-activate" data-file="'+file+'" data-row="'+row+'">Activate</button>'
				+ ' ' + mkUpdate(slug,source,github,file,row);
		}
		function getRowData($btn) {
			var $row = $('#'+$btn.data('row'));
			return {
				slug:   $btn.data('slug')   || $row.find('.spi-update').data('slug'),
				source: $btn.data('source') || $row.find('.spi-update').data('source'),
				github: $btn.data('github') || $row.find('.spi-update').data('github'),
				file:   $btn.data('file'),
				row:    $btn.data('row'),
			};
		}

		// ── Install ─────────────────────────────────────────────────────────
		$(document).on('click', '.spi-install', function(){
			var $btn = $(this), p = getRowData($btn);
			$btn.prop('disabled',true).text('Installing…');
			$.post(ajaxUrl, { action:'simply_install_plugin', nonce:nonce, slug:p.slug, source:p.source, github:p.github, plugin_file:p.file })
			.done(function(res){
				if (res.success) updateRow(p.row, badge('#aaa','○ Installed'), installedActions(p.file,p.row,p.slug,p.source,p.github));
				else { $btn.prop('disabled',false).text('Install'); alert('Install failed: '+(res.data||'Unknown error')); }
			})
			.fail(function(xhr){ $btn.prop('disabled',false).text('Install'); alert('Install failed ('+xhr.status+'): '+xhr.responseText.substring(0,200)); });
		});

		// ── Activate ────────────────────────────────────────────────────────
		$(document).on('click', '.spi-activate', function(){
			var $btn = $(this), p = getRowData($btn);
			$btn.prop('disabled',true).text('Activating…');
			$.post(ajaxUrl, { action:'simply_activate_plugin', nonce:nonce, plugin_file:p.file })
			.done(function(res){
				if (res.success) updateRow(p.row, badge('#4CAF50','● Active'), activeActions(p.file,p.row,p.slug,p.source,p.github));
				else { $btn.prop('disabled',false).text('Activate'); alert('Activation failed: '+(res.data||'Unknown error')); }
			})
			.fail(function(){ $btn.prop('disabled',false).text('Activate'); alert('Activation failed.'); });
		});

		// ── Deactivate ──────────────────────────────────────────────────────
		$(document).on('click', '.spi-deactivate', function(){
			var $btn = $(this), p = getRowData($btn);
			if (!confirm('Deactivate '+$('#'+p.row+' strong').first().text()+'?')) return;
			$btn.prop('disabled',true).text('Deactivating…');
			$.post(ajaxUrl, { action:'simply_deactivate_plugin', nonce:nonce, plugin_file:p.file })
			.done(function(res){
				if (res.success) updateRow(p.row, badge('#aaa','○ Installed'), installedActions(p.file,p.row,p.slug,p.source,p.github));
				else { $btn.prop('disabled',false).text('Deactivate'); alert('Deactivation failed: '+(res.data||'Unknown error')); }
			})
			.fail(function(){ $btn.prop('disabled',false).text('Deactivate'); alert('Deactivation failed.'); });
		});

		// ── Update ──────────────────────────────────────────────────────────
		$(document).on('click', '.spi-update', function(){
			var $btn = $(this), p = getRowData($btn);
			var name = $('#'+p.row+' strong').first().text();
			if (!confirm('Update '+name+' to the latest version from GitHub?')) return;
			$btn.prop('disabled',true).text('Updating…');
			$.post(ajaxUrl, { action:'simply_install_plugin', nonce:nonce, slug:p.slug, source:p.source, github:p.github, plugin_file:p.file, is_update:1 })
			.done(function(res){
				if (res.success) {
					$btn.prop('disabled',false).hide();
					$('#'+p.row+' .spi-ver-check').html(' · <span style="color:#4CAF50;">✓ updated</span>');
				} else {
					$btn.prop('disabled',false).text('Update');
					alert('Update failed: '+(res.data||'Unknown error'));
				}
			})
			.fail(function(xhr){ $btn.prop('disabled',false).text('Update'); alert('Update failed ('+xhr.status+'): '+xhr.responseText.substring(0,200)); });
		});
	})(jQuery);
	</script>
	<?php
}

// ==========================================================================
// TOOLSET CONTAINER DEFAULTS — EDITOR SCRIPT
// Sets inner container width on new Toolset containers via block editor JS.
// PHP filter approach didn't work — Toolset registers blocks in JS directly.
// This script subscribes to block editor changes and sets width on insertion.
//
// Width stored in wp_options as 'simply_container_width' (default: 1200).
// Configurable in Appearance -> Simply Starter -> Container Settings.
// ==========================================================================

// Inject list styles directly into the block editor iframe.
// Frontend uses .entry-content scope; editor has no such wrapper so styles
// must be injected separately via block_editor_settings_all.
add_filter( 'block_editor_settings_all', 'simply_editor_list_styles' );

function simply_editor_list_styles( $settings ) {
	$settings['styles'][] = array( 'css' => '
		ul, ol { padding-left: 40px; margin-bottom: 30px; }
		ul > li { list-style-type: disc; margin-bottom: 10px; }
		ol > li { list-style-type: decimal; margin-bottom: 10px; }
		ul ul > li, ol ul > li { list-style-type: circle; }
		ul ol > li, ol ol > li { list-style-type: decimal; }
		ul ul, ol ul, ul ol, ol ol { margin-bottom: 0; }
	' );
	return $settings;
}


add_action( 'enqueue_block_editor_assets', 'simply_enqueue_editor_blocks' );

function simply_enqueue_editor_blocks() {
	wp_enqueue_script(
		'simply-editor-blocks',
		get_stylesheet_directory_uri() . '/assets/js/simply-editor-blocks.js',
		array( 'wp-hooks', 'wp-element', 'wp-compose', 'wp-block-editor', 'wp-components' ),
		'1.0.0',
		true
	);
}

add_action( 'enqueue_block_editor_assets', 'simply_enqueue_editor_script' );

function simply_enqueue_editor_script() {

	wp_enqueue_script(
		'simply-editor',
		get_stylesheet_directory_uri() . '/assets/js/simply-editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-data', 'wp-edit-post' ),
		'1.2.0',
		true
	);

	// Pass PHP settings to JS
	wp_localize_script( 'simply-editor', 'simplyEditorData', array(
		'containerWidth' => (int) get_option( 'simply_container_width', 1200 ),
	) );
}


// ==========================================================================
// FOOTER BOTTOM BAR WIDGET AREA
// Full-width widget area below the 4 footer columns.
// Use for partner logos, social links, copyright line, etc.
// Only outputs when the widget area has content.
// ==========================================================================

add_action( 'after_setup_theme', 'simply_register_footer_bottom_bar' );

function simply_register_footer_bottom_bar() {
	register_sidebar( array(
		'name'          => __( 'Footer Bottom Bar', 'simply-starter' ),
		'id'            => 'footer-bottom-bar',
		'description'   => __( 'Full-width area below footer columns. Use for partner logos, social links, or copyright.', 'simply-starter' ),
		'before_widget' => '<div class="footer-bottom-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-bottom-widget-title">',
		'after_title'   => '</h4>',
	) );
}

// Output below Genesis footer widgets at priority 15
add_action( 'genesis_before_footer', 'simply_footer_bottom_bar', 15 );

function simply_footer_bottom_bar() {
	if ( ! is_active_sidebar( 'footer-bottom-bar' ) ) return;

	echo '<div class="footer-bottom-bar">';
	echo '<div class="footer-bottom-bar__inner">';
	dynamic_sidebar( 'footer-bottom-bar' );
	echo '</div>';
	echo '</div>';
}

// Remove the front-end post edit link for logged-in users
add_filter( 'edit_post_link', '__return_empty_string' );

// ==========================================================================
// FOOTER CREDITS
// On first admin load after activation, replaces the Genesis default footer shortcodes
// (Genesis Framework link, login link, child theme link) with a clean Simply Starter credit.
// After that the customizer field is live — admin changes take effect normally.
// ==========================================================================

add_action( 'admin_init', 'simply_footer_credits' );

function simply_footer_credits() {
	$settings    = get_option( 'genesis-settings', [] );
	$footer_text = $settings['footer_text'] ?? '';

	// Only overwrite while the Genesis default shortcodes are still present.
	// Once an admin customizes the field and saves, we leave it alone.
	$has_genesis_defaults = strpos( $footer_text, 'footer_genesis_link' ) !== false
		|| strpos( $footer_text, 'footer_loginout' ) !== false
		|| strpos( $footer_text, 'footer_childtheme_link' ) !== false
		|| empty( $footer_text );

	if ( $has_genesis_defaults ) {
		$settings['footer_text'] = '[footer_copyright before="Copyright "] &nbsp;&middot;&nbsp; Simply Starter by <a href="https://simplydesign.com" target="_blank" rel="noopener noreferrer">Simply Design</a>';
		update_option( 'genesis-settings', $settings );
	}
}

// Replace linked author with plain name in post meta
add_filter( 'genesis_post_info', function( $info ) {
	return str_replace( '[post_author_posts_link]', '[post_author]', $info );
} );

// Remove "Filed Under" categories from post footer meta
add_filter( 'genesis_post_meta', function( $meta ) {
	return str_replace( '[post_categories]', '', trim( $meta ) );
} );


// ==========================================================================
// STYLE GUIDE PAGE
// Created automatically on theme activation. Skipped if already exists.
// ==========================================================================

add_action( 'after_switch_theme', 'simply_create_style_guide' );

function simply_create_style_guide() {
	$existing = get_page_by_path( 'style-guide' );
	if ( $existing ) return;

	$page_id = wp_insert_post( array(
		'post_title'     => 'Style Guide',
		'post_name'      => 'style-guide',
		'post_content'   => simply_style_guide_content(),
		'post_status'    => 'draft',
		'post_type'      => 'page',
		'page_template'  => 'page-templates/page-builder.php',
		'comment_status' => 'closed',
	) );

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/page-builder.php' );
	}
}


// ==========================================================================
// WIDGET CSS CLASS
// Adds a "CSS Class" field to every widget settings form.
// The class is applied to the widget's outer wrapper element.
// In the block widget editor, individual blocks also support
// Advanced → Additional CSS class(es) per block.
// ==========================================================================

// Add CSS class input to each widget form
add_action( 'in_widget_form', function( $widget, $return, $instance ) {
	$css_class = isset( $instance['simply_css_class'] ) ? $instance['simply_css_class'] : '';
	?>
	<div style="border-top:1px solid #ddd;margin-top:10px;padding-top:10px;">
		<label for="<?php echo esc_attr( $widget->get_field_id( 'simply_css_class' ) ); ?>" style="display:block;font-weight:600;font-size:12px;margin-bottom:4px;">
			<?php esc_html_e( 'CSS Class', 'simply-starter' ); ?>
		</label>
		<input type="text"
			id="<?php echo esc_attr( $widget->get_field_id( 'simply_css_class' ) ); ?>"
			name="<?php echo esc_attr( $widget->get_field_name( 'simply_css_class' ) ); ?>"
			value="<?php echo esc_attr( $css_class ); ?>"
			style="width:100%;"
			placeholder="my-class another-class">
	</div>
	<?php
}, 10, 3 );

// Save the CSS class value
add_filter( 'widget_update_callback', function( $instance, $new_instance ) {
	$instance['simply_css_class'] = isset( $new_instance['simply_css_class'] )
		? sanitize_text_field( $new_instance['simply_css_class'] )
		: '';
	return $instance;
}, 10, 2 );

// Inject class into the widget wrapper before_widget
add_filter( 'dynamic_sidebar_params', function( $params ) {
	global $wp_registered_widgets;

	$widget_id = $params[0]['widget_id'];

	if ( ! isset( $wp_registered_widgets[ $widget_id ] ) ) return $params;

	$widget_obj = $wp_registered_widgets[ $widget_id ];

	if ( ! isset( $widget_obj['callback'][0] ) || ! is_object( $widget_obj['callback'][0] ) ) return $params;

	$widget        = $widget_obj['callback'][0];
	$all_instances = $widget->get_settings();
	$number        = $params[1]['number'];

	if ( ! isset( $all_instances[ $number ]['simply_css_class'] ) ) return $params;

	$extra = trim( $all_instances[ $number ]['simply_css_class'] );

	if ( empty( $extra ) ) return $params;

	// Append to the first class=" attribute found in before_widget, whatever its value
	$params[0]['before_widget'] = preg_replace(
		'/class="([^"]*)"/',
		'class="$1 ' . esc_attr( $extra ) . '"',
		$params[0]['before_widget'],
		1
	);

	return $params;
} );


// ==========================================================================
// YOU MAY ALSO LIKE — related posts section
// Outputs before footer on single posts. Same-category posts first,
// falls back to most recent if category has fewer than 3 matches.
// Uses sn-card markup (simply-news plugin) for consistent card design.
// ==========================================================================

add_action( 'genesis_before_footer', 'simply_also_like_section', 5 );

function simply_also_like_section() {
	if ( ! is_singular( 'post' ) ) return;

	$post_id = get_the_ID();
	$limit   = 3;

	// Try same-category posts first
	$cats  = get_the_category( $post_id );
	$posts = array();

	if ( $cats ) {
		$posts = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => $limit,
			'post__not_in'   => array( $post_id ),
			'category__in'   => wp_list_pluck( $cats, 'term_id' ),
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
	}

	// Fall back to most recent posts
	if ( count( $posts ) < $limit ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
		$fill    = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => $limit - count( $posts ),
			'post__not_in'   => $exclude,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		$posts = array_merge( $posts, $fill );
	}

	if ( empty( $posts ) ) return;

	?>
	<section class="simply-also-like is-dark">
		<div class="simply-also-like__inner">

			<h2>You May Also Like</h2>

			<div class="sn-feed" style="--sn-columns: 3">
				<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
					<?php
					$permalink = get_permalink( $post->ID );
					$cats      = get_the_category( $post->ID );
					$cat_label = $cats ? esc_html( strtoupper( $cats[0]->name ) ) : '';
					$thumb_url = get_the_post_thumbnail_url( $post->ID, 'large' );
					?>
					<article class="sn-card">
						<a class="sn-card__link" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>"></a>
						<?php if ( $thumb_url ) : ?>
						<div class="sn-card__photo">
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>" loading="lazy">
						</div>
						<?php endif; ?>
						<div class="sn-card__body">
							<?php if ( $cat_label ) : ?>
							<p class="sn-card__category"><?php echo $cat_label; ?></p>
							<?php endif; ?>
							<h3 class="sn-card__title"><?php echo esc_html( get_the_title( $post->ID ) ); ?></h3>
							<span class="sn-card__read-more"><?php esc_html_e( 'Read Now', 'simply-starter' ); ?></span>
						</div>
					</article>
				<?php endforeach; wp_reset_postdata(); ?>
			</div>

		</div>
	</section>
	<?php
}


// ==========================================================================
// DEFAULT TEMPLATE BEHAVIOR
// Pages default to Page Builder (full-width, no title/meta).
// Posts default to Standard Post (800px reading width).
// User overrides by choosing a different template in the editor.
// ==========================================================================

add_action( 'genesis_before', 'simply_apply_default_layout' );

function simply_apply_default_layout() {
	$template   = get_page_template_slug();
	$is_default = empty( $template ) || $template === 'default';

	if ( is_page() && $is_default ) {
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}

// body_class fires before genesis_before so post layout class needs its own hook
add_filter( 'body_class', 'simply_post_layout_body_class' );

function simply_post_layout_body_class( $classes ) {
	if ( ! is_singular( 'post' ) ) return $classes;
	$template = get_page_template_slug();
	if ( empty( $template ) || $template === 'default' ) {
		$classes[] = 'simply-post-layout';
		// Also hook featured image for default posts
		add_action( 'genesis_before_entry_content', 'simply_post_featured_image_default' );
	}
	return $classes;
}

function simply_post_featured_image_default() {
	if ( ! has_post_thumbnail() ) return;
	if ( get_post_meta( get_the_ID(), '_simply_hide_featured_image', true ) ) return;
	echo '<div class="simply-post-hero">';
	the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) );
	echo '</div>';
}


// ==========================================================================
// FEATURED IMAGE TOGGLE — meta box on posts
// ==========================================================================

add_action( 'add_meta_boxes', 'simply_featured_image_meta_box' );

function simply_featured_image_meta_box() {
	add_meta_box(
		'simply_featured_image_toggle',
		__( 'Featured Image Display', 'simply-starter' ),
		'simply_featured_image_meta_box_cb',
		'post',
		'side',
		'low'
	);
}

function simply_featured_image_meta_box_cb( $post ) {
	wp_nonce_field( 'simply_featured_image_toggle', 'simply_featured_image_nonce' );
	$hide = get_post_meta( $post->ID, '_simply_hide_featured_image', true );
	?>
	<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
		<input type="checkbox" name="simply_hide_featured_image" value="1" <?php checked( $hide, '1' ); ?>>
		<?php esc_html_e( 'Hide featured image in single post view', 'simply-starter' ); ?>
	</label>
	<?php
}

add_action( 'save_post_post', 'simply_save_featured_image_toggle' );

function simply_save_featured_image_toggle( $post_id ) {
	if (
		! isset( $_POST['simply_featured_image_nonce'] ) ||
		! wp_verify_nonce( $_POST['simply_featured_image_nonce'], 'simply_featured_image_toggle' ) ||
		defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	if ( isset( $_POST['simply_hide_featured_image'] ) ) {
		update_post_meta( $post_id, '_simply_hide_featured_image', '1' );
	} else {
		delete_post_meta( $post_id, '_simply_hide_featured_image' );
	}
}


// ==========================================================================
// OPEN GRAPH — og:image + basic tags for link previews (iMessage, Slack, etc.)
// Uses the page/post featured image. Falls back to the site icon if set.
// ==========================================================================

add_action( 'wp_head', 'simply_og_tags', 1 );

function simply_og_tags() {
	if ( ! is_singular() ) return;

	$post_id = get_queried_object_id();

	$title       = get_the_title( $post_id );
	$url         = get_permalink( $post_id );
	$description = has_excerpt( $post_id )
		? get_the_excerpt()
		: wp_trim_words( get_post_field( 'post_content', $post_id ), 30, '' );

	$image_url = '';
	if ( has_post_thumbnail( $post_id ) ) {
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
	} elseif ( has_site_icon() ) {
		$image_url = get_site_icon_url( 512 );
	}

	echo '<meta property="og:type"        content="website">' . "\n";
	echo '<meta property="og:url"         content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:title"       content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $image_url ) {
		echo '<meta property="og:image"       content="' . esc_url( $image_url ) . '">' . "\n";
		echo '<meta name="twitter:card"       content="summary_large_image">' . "\n";
		echo '<meta name="twitter:image"      content="' . esc_url( $image_url ) . '">' . "\n";
	}
}

// ==========================================================================
// COMMENTS — sitewide disable toggle
// Controlled via Appearance → Simply Starter → Site Settings.
// ==========================================================================

// COMMENTS — migrate simply_disable_comments → Genesis setting (one-time, runs on admin_init)
add_action( 'admin_init', 'simply_migrate_comments_setting' );
function simply_migrate_comments_setting() {
	$old = get_option( 'simply_disable_comments' );
	if ( $old === false ) return; // already migrated or never set

	if ( (int) $old === 1 ) {
		$genesis = get_option( 'genesis-settings', [] );
		$genesis['comments_posts'] = 0;
		update_option( 'genesis-settings', $genesis );
	}

	delete_option( 'simply_disable_comments' );
}

// Pages: always off — comment forms don't render on pages anyway,
// but this closes them in the database and hides the customizer control.
add_filter( 'genesis_pre_get_option_comments_pages',   '__return_zero' );
add_filter( 'genesis_pre_get_option_trackbacks_posts', '__return_zero' );
add_filter( 'genesis_pre_get_option_trackbacks_pages', '__return_zero' );

// When posts comments are disabled via Genesis customizer, remove the Comments admin menu.
add_action( 'admin_menu', function() {
	if ( ! genesis_get_option( 'comments_posts' ) ) {
		remove_menu_page( 'edit-comments.php' );
	}
}, 99 );

// Enable shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );
