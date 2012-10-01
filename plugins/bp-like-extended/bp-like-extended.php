<?php
/*
Plugin Name: BuddyPress Like Extended
Plugin URI: http://www.sennza.com.au
Description: A plugin to add BuddyPress Likes to the actvity stream comments. Requires BuddyPress Like
Version: 0.1
Author: Bronson Quick
Author URI: http://www.sennza.com.au
License: GPL2
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'buddypress-like/bp-like.php' )) {
	add_action( 'bp_activity_comment_options', 'bp_like_button_extended' );
	/**
	 * bp_like_button_extended()
	 *
	 * Outputs the 'Like/Unlike' and 'View likes/Hide likes' buttons.
	 *
	 */
	function bp_like_button_extended( $id = '', $type = '' ) {

		$users_who_like = 0;
		$liked_count    = 0;

		/* Set the type if not already set, and check whether we are outputting the button on a blogpost or not. */
		if ( ! $type && ! is_single() )
			$type = 'activity';
		elseif ( ! $type && is_single() )
			$type = 'blogpost';

		if ( $type == 'activity' ) :

			$activity      = bp_activity_get_specific( array( 'activity_ids' => bp_get_activity_id() ) );
			$activity_type = $activity['activities'][0]->type;

			if ( is_user_logged_in() && $activity_type !== 'activity_liked' ) :

				if ( bp_activity_get_meta( bp_get_activity_comment_id(), 'liked_count', true ) ) {
					$users_who_like = array_keys( bp_activity_get_meta( bp_get_activity_comment_id(), 'liked_count', true ) );
					$liked_count    = count( $users_who_like );
				}

				if ( ! bp_like_is_liked( bp_get_activity_comment_id(), 'activity' ) ) : ?>
				<a href="#" class="like" id="like-activity-<?php bp_activity_comment_id(); ?>" title="<?php echo bp_like_get_text( 'like_this_item' ); ?>"><?php echo bp_like_get_text( 'like' ); if ( $liked_count ) echo ' (' . $liked_count . ')'; ?></a>
				<?php else : ?>
				<a href="#" class="unlike" id="unlike-activity-<?php bp_activity_comment_id(); ?>" title="<?php echo bp_like_get_text( 'unlike_this_item' ); ?>"><?php echo bp_like_get_text( 'unlike' ); if ( $liked_count ) echo ' (' . $liked_count . ')'; ?></a>
				<?php endif;

				if ( $users_who_like ): ?>
				<a href="#" class="view-likes" id="view-likes-<?php bp_activity_comment_id(); ?>"><?php echo bp_like_get_text( 'view_likes' ); ?></a>
				<p class="users-who-like" id="users-who-like-<?php bp_activity_comment_id(); ?>"></p>
				<?php
				endif;
			endif; elseif ( $type == 'blogpost' ) :
			global $post;

			if ( ! $id && is_single() )
				$id = $post->ID;

			if ( is_user_logged_in() && get_post_meta( $id, 'liked_count', true ) ) {
				$liked_count = count( get_post_meta( $id, 'liked_count', true ) );
			}

			if ( ! bp_like_is_liked( $id, 'blogpost' ) ) : ?>

			<div class="like-box">
					<a href="#" class="like_blogpost" id="like-blogpost-<?php echo $id; ?>" title="<?php echo bp_like_get_text( 'like_this_item' ); ?>"><?php echo bp_like_get_text( 'like' ); if ( $liked_count ) echo ' (' . $liked_count . ')'; ?></a>
			</div>

			<?php else : ?>

			<div class="like-box">
					<a href="#" class="unlike_blogpost" id="unlike-blogpost-<?php echo $id; ?>" title="<?php echo bp_like_get_text( 'unlike_this_item' ); ?>"><?php echo bp_like_get_text( 'unlike' ); if ( $liked_count ) echo ' (' . $liked_count . ')'; ?></a>
			</div>
			<?php endif;

		endif;
	}
}