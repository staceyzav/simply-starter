<?php
/**
 * Genesis Sample.
 *
 * This file adds the Customizer additions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Filter Genesis customizer config before it registers — remove controls we don't need
add_filter( 'genesis_customizer_theme_settings_config', 'ss_filter_genesis_customizer_config' );
function ss_filter_genesis_customizer_config( $config ) {
	$remove = [ 'comments_pages', 'trackbacks_posts', 'trackbacks_pages' ];
	foreach ( $remove as $key ) {
		unset( $config['genesis']['sections']['genesis_comments']['controls'][ $key ] );
	}
	return $config;
}

add_action( 'customize_register', 'ss_remove_customizer_sections', 9999 );
function ss_remove_customizer_sections( $wp_customize ) {
	// WP core sections not used in Simply Starter
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_panel( 'widgets' );

	// Genesis Theme Settings sections not applicable to Simply Starter
	$wp_customize->remove_section( 'genesis_layout' );
	$wp_customize->remove_section( 'genesis_breadcrumbs' );
	$wp_customize->remove_section( 'genesis_archives' );

}

add_action( 'customize_register', 'genesis_sample_customizer_register' );
/**
 * Registers settings and controls with the Customizer.
 *
 * @since 2.2.3
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function genesis_sample_customizer_register( $wp_customize ) {

	$appearance = genesis_get_config( 'appearance' );

	$wp_customize->add_setting(
		'genesis_sample_logo_width',
		[
			'default'           => 350,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'genesis_sample_validate_logo_width',
		]
	);

	// Add a control for the logo size.
	$wp_customize->add_control(
		'genesis_sample_logo_width',
		[
			'label'       => __( 'Logo Width', 'genesis-sample' ),
			'description' => __( 'The maximum width of the logo in pixels.', 'genesis-sample' ),
			'priority'    => 9,
			'section'     => 'title_tagline',
			'settings'    => 'genesis_sample_logo_width',
			'type'        => 'number',
			'input_attrs' => [
				'min' => 100,
			],

		]
	);

}

/**
 * Displays a message if the entered width is not numeric or greater than 100.
 *
 * @param object $validity The validity status.
 * @param int    $width The width entered by the user.
 * @return int The new width.
 */
function genesis_sample_validate_logo_width( $validity, $width ) {

	if ( empty( $width ) || ! is_numeric( $width ) ) {
		$validity->add( 'required', __( 'You must supply a valid number.', 'genesis-sample' ) );
	} elseif ( $width < 100 ) {
		$validity->add( 'logo_too_small', __( 'The logo width cannot be less than 100.', 'genesis-sample' ) );
	}

	return $validity;

}
