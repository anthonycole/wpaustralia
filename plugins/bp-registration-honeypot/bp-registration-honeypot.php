<?php
/**
 * Plugin Name: BuddyPress Registration Honeypot
 * Plugin URI: http://www.wpaustralia.org
 * Description: This BuddyPress plugin adds an input field on the registration that is hidden for normal users and will be filled out by bots. If the field contains data then the registration will fail. If it's empty then the registration will continue. This plugin has no options because options are overrated!
 * Version: 0.4
 * Author: Bronson Quick, Japheth Thomson
 * Author URI: http://www.sennza.com.au/
 * License: GPL2
 */

/**
 * Constant for the plugins version number
 *
 * @since 0.2
 */
define( 'BUDDYPRESS_HONEYPOT_REGISTRATION_VERSION', '0.4' );

$sennza_buddypress_registration_thepot = new Sennza_BuddyPress_Registration_HoneyPot();

/**
 * Where all the awesome happens of course :)
 *
 * @since 0.2
 */
class Sennza_BuddyPress_Registration_HoneyPot {

	/**
	 * Setup all the magic for our honeypot
	 *
	 * @since 0.2
	 */
	public function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'sennza_buddypress_registration_thepot_css' ) );
		add_action( 'bp_after_account_details_fields', array( $this , 'sennza_buddypress_registration_thepot') );
		add_action( 'bp_signup_validate', array( $this , 'sennza_buddypress_registration_thepot_validation' ) );
		add_filter( 'gettext', array( $this , 'sennza_buddypress_credits_removal') );
	}

	/**
	 * This function adds the honeypot field after the account details in BuddyPress
	 *
	 * @since 0.2
	 */
	public function sennza_buddypress_registration_thepot() {
		?>
		<?php //Lets wrap this and hide it with CSS ?>
		<div id="basic-details-thepot-section" class="thepot_container register-section">
			<?php //Lets give it a dummy label that spam bots will think is real ?>
			<label for="signup_thepot"><?php _e( 'Phone' ); ?></label>
			<?php //I can't see us needing to output errors but I'll add an action here in case accessibility issues come up! ?>
			<?php do_action( 'sennza_buddypress_registration_thepot_errors' ); ?>
			<input type="text" name="signup_thepot" id="signup_thepot" value="" />
			<?php $sennza_thepot_warning = apply_filters( 'sennza_thepot_warning', 'This field is for validation purposes and should be left empty.' );?>
			<p><?php esc_html_e( $sennza_thepot_warning ); ?></p>
		</div>
		<?php
	}

	/**
	 * Fake a successful sign up which will bypass the actual registration process
	 *
	 * @since 0.4
	 * @global array $bp
	 */
	public function sennza_buddypress_registration_thepot_validation() {
		global $bp;
		if ( ! empty( $_POST['signup_thepot'] ) )
			//Fake it 'til we make it!
			$bp->signup->step = 'completed-confirmation';
	}

	/**
	 * Need to add a gettext function to remove the Proudly powered By WordPress and BuddyPress footer
	 */
	public function sennza_buddypress_credits_removal( $translated_text ){
		//TODO: 'Quick' function to remove the default credits as bots search for that
		return $translated_text;
	}

	/**
	 * Enqueues the CSS for this plugin.
	 *
	 * @since 0.2
	 */
	public function sennza_buddypress_registration_thepot_css() {
		/* Props to Boone for picking up on me enqueue the CSS on all the pages :) */
		if ( bp_is_register_page() ) {
			wp_register_style(
				'honeypot-css',
				plugins_url( '/css/bp-honeypot.css', __FILE__ ),
				array(),
				BUDDYPRESS_HONEYPOT_REGISTRATION_VERSION
			);
			wp_enqueue_style( 'honeypot-css' );
		}
	}

}