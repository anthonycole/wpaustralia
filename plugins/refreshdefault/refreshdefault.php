<?php
/*
Plugin Name: Refresh default
Description: This plugin replaces the theme compatibility CSS with a new experimental CSS
Version: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'bp_theme_compat_actions', 'my_remove_styles' );
add_action( 'wp_enqueue_scripts', 'refreshdefault_load' );

function my_remove_styles( $theme ) {
	remove_action( 'bp_enqueue_scripts', array( $theme, 'enqueue_styles' ) );
}

function refreshdefault_load(){
	wp_register_style( 'newbp', plugins_url('css/buddypress.css', __FILE__) );
    wp_enqueue_style( 'newbp' );
}