<?php
/*
Plugin Name: Meetup.com Oembeds for WordPress Australia Meetups
Description: Paste in plain text URL's to Meetup.com groups in Australia and you'll get oEmbed Support
Version: 0.1
Author: Bronson Quick
Author URI: http://www.sennza.com.au
*/

class Meetup_Oz_Oembed {
	private static $instance;

	static function get_instance() {
		if ( ! self::$instance )
			self::$instance = new Meetup_Oz_Oembed;

		return self::$instance;
	}

	private function __construct() {
		/* Let's run the the_content filter as late as we can */
		add_filter( 'the_content', array( $this, 'replace_custom_meetup_urls' ), 7 );
		add_action( 'plugins_loaded', array( $this, 'add_providers' ) );
	}

	public function add_providers() {
		/* Add the default URLs */
		wp_oembed_add_provider( 'http://www.meetup.com/*', 'http://api.meetup.com/oembed?url=' );
		wp_oembed_add_provider( 'http://meetup.com/*', 'http://api.meetup.com/oembed?url=' );
	}

	/* Because Meetup.com is retarded and can't deal with it's custom urls we have to replace the URLs with the default URLs */
	public function replace_custom_meetup_urls( $content ) {
		$search  = array(
			'http://www.wpbrisbane.com.au/',
			'http://www.wpsydney.com.au/',
			'http://www.wpmelb.org/',
			'http://www.wptas.org/',
		);
		$replace = array(
			'http://www.meetup.com/WordPress-Brisbane/',
			'http://www.meetup.com/WordPress-Sydney/',
			'http://www.meetup.com/WordPress-Melbourne/',
			'http://www.meetup.com/WordPress-Tasmania/'
		);
		$content =  str_replace( $search, $replace, $content );
		return $content;
	}

}

Meetup_Oz_Oembed::get_instance();