<?php
/**
 * Simply Starter — functions.php
 *
 * Simply Design starter child theme for Genesis Framework.
 * Fonts and brand colors are handled by the Simply Client Config plugin.
 * Do not add client-specific code here.
 *
 * @package Simply Starter
 * @author  Simply Design
 * @license GPL-2.0-or-later
 * @link    https://simplydesign.com
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

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

// Genesis responsive menu system removed entirely.
// IMF uses its own slide-in mobile menu via imf-scroll.js.

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
		. '<span class="imf-sr-only">Open menu</span>'
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
// IMF THEME ADDITIONS — Simply Design
// Appended below all Genesis Sample functions.
// ==========================================================================



// --------------------------------------------------------------------------
// BODY CLASSES
// .imf-homepage → transparent header overlays hero
// .imf-interior → always solid navy header
// --------------------------------------------------------------------------

add_filter( 'body_class', 'simply_body_classes' );

function simply_body_classes( $classes ) {
	$classes[] = is_front_page() ? 'imf-homepage' : 'imf-interior';
	return $classes;
}


// --------------------------------------------------------------------------
// SCROLL JS
// Handles utility bar slide-away and header transparent -> solid transition.
// --------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'simply_enqueue_scroll_js' );

function simply_enqueue_scroll_js() {
	wp_enqueue_script(
		'imf-scroll',
		get_stylesheet_directory_uri() . '/assets/js/simply-scroll.js',
		array(),
		'1.7.0', // bump this version any time imf-scroll.js changes to bust cache
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

// --- Wireframe banner output ---
add_action( 'genesis_before', 'simply_wireframe_banner' );

function simply_wireframe_banner() {
	if ( ! get_option( 'simply_wireframe_mode', false ) ) return;
	?>
	<div class="simply-wireframe-banner" id="simply-wireframe-banner">
		<strong>UX Preview</strong> — You're viewing the layout and content structure.
		Final design and branding coming soon.
		<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'simply_wireframe_toggle', '1' ), 'simply_wireframe_toggle' ) ); ?>">
				Turn off wireframe mode
			</a>
		<?php endif; ?>
		<button class="simply-wireframe-banner__dismiss" onclick="this.parentElement.style.display='none'" aria-label="Dismiss">×</button>
	</div>
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

add_action( 'admin_menu', 'simply_register_welcome_page' );

function simply_register_welcome_page() {
	add_theme_page(
		__( 'Simply Starter', 'simply-starter' ),
		__( 'Simply Starter', 'simply-starter' ),
		'manage_options',
		'simply-starter-welcome',
		'simply_render_welcome_page'
	);
}

function simply_render_welcome_page() {

	$version        = wp_get_theme()->get( 'Version' );
	$client_active  = simply_get_active_client_config();
	$wireframe_on   = get_option( 'simply_wireframe_mode', false );
	$toggle_url     = wp_nonce_url(
		add_query_arg( 'simply_wireframe_toggle', '1', admin_url() ),
		'simply_wireframe_toggle'
	);

	?>
	<div class="wrap" style="max-width:800px;">

		<div style="display:flex;align-items:center;gap:16px;margin:24px 0 8px;">
			<h1 style="margin:0;">Simply Starter <span style="font-size:14px;color:#666;font-weight:400;">v<?php echo esc_html( $version ); ?></span></h1>
			<span style="background:<?php echo $wireframe_on ? '#f0a500' : '#4CAF50'; ?>;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
				<?php echo $wireframe_on ? 'Wireframe Mode ON' : 'Live Mode'; ?>
			</span>
		</div>

		<p style="color:#666;margin-bottom:32px;">
			A Simply Design starter theme for Genesis Framework.
			<a href="https://simplydesign.com" target="_blank">simplydesign.com</a>
		</p>

		<hr>

		<!-- GETTING STARTED -->
		<h2>Get started with your branded theme by Simply Starter</h2>

		<p>Simply Starter offers a plugin config for your brand.<br>
		Simply Design will install and configure this for you, or you can
		<a href="https://simplydesign.com/simply-client-config" target="_blank">download it here</a>
		and do it yourself. If you would like to hire us, find us at
		<a href="https://simplydesign.com" target="_blank">SimplyDesign.com</a>.</p>

		<?php if ( ! $client_active ) : ?>
		<div style="background:#fff3cd;border:1px solid #ffc107;border-radius:6px;padding:16px 20px;margin:20px 0;">
			<strong>⚠ Simply Client Config not detected.</strong>
			Without it the theme shows wireframe neutral styles only.
			Install and activate the Simply Client Config plugin to apply your brand colors and fonts.
		</div>
		<?php else : ?>
		<div style="background:#d4edda;border:1px solid #28a745;border-radius:6px;padding:16px 20px;margin:20px 0;">
			<strong>✓ Client Config active:</strong> <?php echo esc_html( $client_active ); ?>
		</div>
		<?php endif; ?>

		<hr>

		<!-- WIREFRAME MODE -->
		<h2>Wireframe Mode</h2>
		<p>Toggle wireframe mode to switch between neutral UX styles and your branded config.
		Great for presenting layout and content structure to clients before branding is finalized.</p>

		<p>
			<a href="<?php echo esc_url( $toggle_url ); ?>" class="button <?php echo $wireframe_on ? 'button-secondary' : 'button-primary'; ?>">
				<?php echo $wireframe_on ? 'Turn Wireframe OFF (go live)' : 'Turn Wireframe ON (show neutral)'; ?>
			</a>
			<?php if ( $wireframe_on && $client_active ) : ?>
				<span style="margin-left:12px;color:#666;font-size:13px;">Client config will be auto-reactivated when you turn wireframe off.</span>
			<?php endif; ?>
		</p>

		<hr>

		<!-- SETUP CHECKLIST -->
		<h2>Setup Checklist</h2>
		<ol style="line-height:2.2;">
			<li>Install &amp; activate <strong>Simply Client Config</strong> plugin — sets your brand colors and fonts</li>
			<li>Upload logo via <a href="<?php echo admin_url( 'customize.php' ); ?>">Appearance → Customize → Site Identity</a></li>
			<li>Set homepage via <a href="<?php echo admin_url( 'customize.php?autofocus[section]=static_front_page' ); ?>">Appearance → Customize → Homepage Settings</a></li>
			<li>Add menus via <a href="<?php echo admin_url( 'nav-menus.php' ); ?>">Appearance → Menus</a> — register Primary + Utility locations</li>
			<li>Add class <code>menu-highlight</code> to your CTA nav item (Screen Options → CSS Classes)</li>
			<li>Populate footer widgets via <a href="<?php echo admin_url( 'widgets.php' ); ?>">Appearance → Widgets</a> (4 columns + Footer Bottom Bar)</li>
			<li>Update tagline in <a href="<?php echo admin_url( 'options-general.php' ); ?>">Settings → General → Tagline</a></li>
			<li>Add <code>is-dark</code> / <code>is-light</code> / <code>is-brand-1</code> / <code>is-brand-2</code> to Gutenberg Group blocks or Toolset containers for section colors</li>
			<li>Add privacy disclosure for Adobe Fonts if using Typekit in client config</li>
		</ol>

		<hr>

		<p style="color:#999;font-size:12px;">
			Simply Starter v<?php echo esc_html( $version ); ?> by
			<a href="https://simplydesign.com" target="_blank">Simply Design</a>.
			Built on Genesis Framework.
		</p>

	</div>
	<?php
}

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
// HELPER — find active client config plugin
// Returns plugin name string or false.
// ==========================================================================

function simply_get_active_client_config() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();
	foreach ( $all_plugins as $plugin_file => $plugin_data ) {
		if (
			strpos( $plugin_file, 'client-config' ) !== false &&
			is_plugin_active( $plugin_file )
		) {
			return $plugin_data['Name'];
		}
	}
	return false;
}


// ==========================================================================
// WIREFRAME MODE — AUTO-TOGGLE CLIENT CONFIG PLUGIN
// When wireframe turns ON: deactivate client config, store its slug.
// When wireframe turns OFF: reactivate the stored client config.
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
		// Find and deactivate any active client config plugin.
		$all_plugins = get_plugins();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			if (
				strpos( $plugin_file, 'client-config' ) !== false &&
				is_plugin_active( $plugin_file )
			) {
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
		if ( strpos( $plugin, 'client-config' ) !== false ) {
			$client_config_active = true;
			break;
		}
	}

	$wireframe_on = get_option( 'simply_wireframe_mode', false );
	?>
	<div class="wrap" style="max-width:800px;">

		<h1 style="font-size:28px; font-weight:700; margin-bottom:4px;">
			Simply Starter <span style="font-size:16px; color:#888; font-weight:400;">v<?php echo wp_get_theme()->get( 'Version' ); ?></span>
		</h1>
		<p style="color:#888; margin-top:0;">By <a href="https://simplydesign.com" target="_blank">Simply Design</a></p>

		<hr>

		<?php if ( ! $client_config_active ) : ?>
		<div style="background:#fff3cd; border-left:4px solid #f0a500; padding:16px 20px; margin-bottom:24px; border-radius:0 4px 4px 0;">
			<strong>⚠️ Client Config Plugin Not Active</strong><br>
			Get started with your branded theme by Simply Starter.<br><br>
			Simply Starter offers a plugin config for your brand.
			Simply Design will install and configure this for you or you can
			<a href="https://simplydesign.com/simply-client-config" target="_blank">download it here</a>
			and do it yourself. If you would like to hire us, find us at
			<a href="https://simplydesign.com" target="_blank">SimplyDesign.com</a>
		</div>
		<?php else : ?>
		<div style="background:#d4edda; border-left:4px solid #4CAF50; padding:16px 20px; margin-bottom:24px; border-radius:0 4px 4px 0;">
			<strong>✓ Client Config Active</strong> — Your brand is configured and ready.
		</div>
		<?php endif; ?>

		<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
			<h2 style="margin-top:0; font-size:18px;">🚀 Setup Checklist</h2>
			<ul style="line-height:2;">
				<li><?php echo $client_config_active ? '✅' : '⬜'; ?> Install &amp; activate Simply Client Config plugin</li>
				<li>⬜ Upload logo via <a href="<?php echo admin_url('customize.php'); ?>">Appearance → Customize → Site Identity</a></li>
				<li>⬜ Set homepage via <a href="<?php echo admin_url('customize.php'); ?>">Appearance → Customize → Homepage Settings</a></li>
				<li>⬜ Add menus via <a href="<?php echo admin_url('nav-menus.php'); ?>">Appearance → Menus</a> — Primary + Utility locations</li>
				<li>⬜ Add <code>menu-highlight</code> CSS class to CTA nav item (Screen Options → CSS Classes)</li>
				<li>⬜ Populate footer widgets via <a href="<?php echo admin_url('widgets.php'); ?>">Appearance → Widgets</a> (4 columns + Footer Bottom Bar)</li>
				<li>⬜ Update tagline in <a href="<?php echo admin_url('options-general.php'); ?>">Settings → General → Tagline</a></li>
			</ul>
		</div>

		<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
			<h2 style="margin-top:0; font-size:18px;">🎨 Section Color Schemes</h2>
			<p>Add these classes to any <strong>Toolset container</strong> or <strong>Gutenberg Group block</strong>:</p>
			<table style="width:100%; border-collapse:collapse;">
				<tr style="background:#f5f5f5;">
					<th style="padding:8px 12px; text-align:left; border:1px solid #e2e2e2;">Toolset class</th>
					<th style="padding:8px 12px; text-align:left; border:1px solid #e2e2e2;">Gutenberg style</th>
					<th style="padding:8px 12px; text-align:left; border:1px solid #e2e2e2;">Use for</th>
				</tr>
				<tr>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;"><code>is-dark</code></td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Dark Section</td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Dark bg, light text</td>
				</tr>
				<tr style="background:#f9f9f9;">
					<td style="padding:8px 12px; border:1px solid #e2e2e2;"><code>is-light</code></td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Light Section</td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Light bg, dark text</td>
				</tr>
				<tr>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;"><code>is-brand-1</code></td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Brand Section 1</td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Optional brand color</td>
				</tr>
				<tr style="background:#f9f9f9;">
					<td style="padding:8px 12px; border:1px solid #e2e2e2;"><code>is-brand-2</code></td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Brand Section 2</td>
					<td style="padding:8px 12px; border:1px solid #e2e2e2;">Optional brand color</td>
				</tr>
			</table>
			<p style="margin-bottom:0; color:#666; font-size:13px;">Containers are full-width bg with 1200px inner content constraint automatically.</p>
		</div>

		<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px; margin-bottom:24px;">
			<h2 style="margin-top:0; font-size:18px;">🔭 Wireframe Mode</h2>
			<p>Toggle wireframe mode from the <strong>admin bar</strong> at the top of any page.
			When ON, neutral charcoal styles apply and a banner shows to all visitors indicating
			the site is in UX preview mode.</p>
			<p style="margin-bottom:0;">
				Current state: <strong><?php echo $wireframe_on ? '<span style="color:#f0a500;">⬤ ON</span>' : '<span style="color:#4CAF50;">◯ OFF</span>'; ?></strong>
				&nbsp;&nbsp;
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'simply_wireframe_toggle', '1' ), 'simply_wireframe_toggle' ) ); ?>">
					<?php echo $wireframe_on ? 'Turn off' : 'Turn on'; ?> wireframe mode
				</a>
			</p>
		</div>

		<div style="background:#fff; border:1px solid #e2e2e2; border-radius:6px; padding:24px;">
			<h2 style="margin-top:0; font-size:18px;">📋 Notes &amp; Tips</h2>
			<ul style="line-height:2; color:#444;">
				<li>Put <code>[simply_hero]</code> shortcode on <strong>one line</strong> in the editor — line breaks break the shortcode parser</li>
				<li>Typekit fonts require Adobe Fonts privacy disclosure in your Privacy Policy</li>
				<li>Footer Bottom Bar widget area outputs only when it has content — safe to leave empty</li>
				<li>Add <code>full-width</code> class to Toolset container to remove the 1200px constraint</li>
				<li>Add <code>narrow</code> class to Toolset container for 800px width</li>
				<li>Logo supports PNG and SVG — drop SVG in <code>/images/</code> and it auto-detects</li>
			</ul>
		</div>

		<p style="color:#aaa; font-size:12px; margin-top:24px; text-align:center;">
			Simply Starter <?php echo wp_get_theme()->get( 'Version' ); ?> — Built by
			<a href="https://simplydesign.com" target="_blank" style="color:#aaa;">Simply Design</a>
		</p>

	</div>
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

add_action( 'enqueue_block_editor_assets', 'simply_enqueue_editor_script' );

function simply_enqueue_editor_script() {

	wp_enqueue_script(
		'simply-editor',
		get_stylesheet_directory_uri() . '/assets/js/simply-editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-data', 'wp-edit-post' ),
		'1.0.0',
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
