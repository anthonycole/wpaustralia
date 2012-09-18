<?php
/*
Plugin Name: BuddyPress Hotfixes
Plugin URI: http://www.sennza.com.au
Description: A group of hot fixes to fix plugins and patches from BuddyPress core that we need on WP Australia
Version: 0.1
Author: Bronson Quick
Author URI: http://www.sennza.com.au
License: GPL2
*/

function sennza_hotfixes(){

	/* A hotfix to fix the autosuggest that wasn't working in BPLabs. Props to @rmccue for finding the bug! :) */
	class BPLabs_Beaker_Hotfix extends BPLabs_Beaker {

		public function __construct() {
			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || DOING_AJAX === false ) )
				return;

			add_action( 'init', array( $this, 'enqueue_script' ) );
			add_action( 'init', array( $this, 'enqueue_style' ) );

			$this->register_actions();
		}
	}
	new BPLabs_Beaker_Hotfix();
}

/* Load this after the the plugins are loaded. As the default action priority is 10 */
add_action( 'plugins_loaded', 'sennza_hotfixes', 11);