<?php
/*
 * Time to so some tweakin'
 */

function wpoz_make_the_logo_width_smaller() {
	return 300;
}
add_filter( 'bp_dtheme_header_image_width', 'wpoz_make_the_logo_width_smaller' );

function wpoz_make_the_logo_height_smaller() {
	return 103;
}
add_filter( 'bp_dtheme_header_image_height', 'wpoz_make_the_logo_height_smaller' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function wpoz_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'wpoz' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'wpoz_wp_title', 10, 2 );

/**
 * Enqueues scripts and styles for front-end.
 *
 */
function wpoz_scripts_styles() {

	/*
	 * Loads our special font CSS file.
	 *
	 */

/* translators: If there are characters in your language that are not supported
	 by PT Sans, translate this to 'off'. Do not translate into your own language. */
	$subsets = 'latin,latin-ext';

	/* translators: To add an additional PT Sans character subset specific to your language, translate
		 this to 'cyrillic'. Do not translate into your own language. */
	$subset = _x( 'no-subset', 'PT Sans font: add new subset (cyrillic)', 'wpoz' );

	if ( 'cyrillic' == $subset )
		$subsets .= ',cyrillic,cyrillic-ext';

	$protocol = is_ssl() ? 'https' : 'http';
	$query_args = array(
		'family' => 'PT+Sans:400,700,400italic,700italic',
		'subset' => $subsets,
	);
	wp_enqueue_style( 'wpoz-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );

	wp_enqueue_style( 'wpoz-genericons', get_stylesheet_directory_uri() . '/genericons.css', '', '1.1', 'all' );

}
add_action( 'wp_enqueue_scripts', 'wpoz_scripts_styles' );

function wpoz_moar_icons( $link ){
	$link = str_replace( 'delete-activity', 'delete-activity genericon genericon-close', $link);
	return $link;
}
add_filter( 'bp_get_activity_delete_link', 'wpoz_moar_icons' );

define( 'BP_AVATAR_THUMB_WIDTH', 80 );
define( 'BP_AVATAR_THUMB_HEIGHT', 80 );