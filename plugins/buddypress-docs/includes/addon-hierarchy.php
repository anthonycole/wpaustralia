<?php

/**
 * This file contains the functions used to enable hierarchical docs.
 * Separated into this file so that the feature can be turned off.
 *
 * @package BuddyPress Docs
 */

class BP_Docs_Hierarchy {
	var $parent;
	var $children;

	/**
	 * PHP 4 constructor
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 */
	function bp_docs_hierarchy() {
		$this->__construct();
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 */
	function __construct() {
		// Make sure that the bp_docs post type supports our post taxonomies
		add_filter( 'bp_docs_post_type_args', array( $this, 'register_with_post_type' ) );

		// Hook into post saves to save any taxonomy terms.
		add_action( 'bp_docs_doc_saved', array( $this, 'save_post' ) );

		// Display a doc's parent on its single doc page
		add_action( 'bp_docs_single_doc_meta', array( $this, 'show_parent' ) );

		// Display a doc's children on its single doc page
		add_action( 'bp_docs_single_doc_meta', array( $this, 'show_children' ) );
	}

	/**
	 * Registers the post taxonomies with the bp_docs post type
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 *
	 * @param array The $bp_docs_post_type_args array created in BP_Docs::register_post_type()
	 * @return array $args The modified parameters
	 */
	function register_with_post_type( $args ) {
		$args['hierarchical'] = true;

		return $args;
	}

	/**
	 * Saves post parent to a doc when saved from the front end
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 *
	 * @param object $query The query object created by BP_Docs_Query
	 * @return int $post_id Returns the doc's post_id on success
	 */
	function save_post( $query ) {
		if ( empty( $query->doc_id ) ) {
			return false;
		}

		$post_parent = !empty( $_POST['parent_id'] ) ? $_POST['parent_id'] : 0;

		$args = array(
			'ID' => $query->doc_id,
			'post_parent' => $post_parent
		);

		// Don't let a revision get saved here
		remove_action( 'pre_post_update', 'wp_save_post_revision' );
		$post_id = wp_update_post( $args );
		add_action( 'pre_post_update', 'wp_save_post_revision' );

		if ( $post_id ) {
			return $post_id;
		} else {
			return false;
		}
	}

	/**
	 * Display a link to the doc's parent
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 */
	 function show_parent() {
	 	global $post, $wp_query;

	 	$html = '';
	 	$parent = false;

	 	if ( ! empty( $post->post_parent ) ) {
			$parent = get_post( $post->post_parent );
			if ( !empty( $parent->ID ) ) {
				$parent_url = bp_docs_get_doc_link( $parent->ID );
				$parent_title = $parent->post_title;

				$html = "<p>" . __( 'Parent: ', 'bp-docs' ) . "<a href=\"$parent_url\" title=\"$parent_title\">$parent_title</a></p>";
			}
	 	}

	 	echo apply_filters( 'bp_docs_hierarchy_show_parent', $html, $parent );
	 }

	 /**
	  * Display links to the doc's children
	  *
	  * @package BuddyPress Docs
	  * @since 1.0
	  */
	 function show_children() {
	 	global $bp, $wp_query, $post;

	 	// Get the child posts
	 	$child_posts_args = array(
	 		'post_type'	=> $bp->bp_docs->post_type_name,
	 		'post_parent'	=> get_the_ID()
	 	);

	 	$child_posts = new WP_Query( $child_posts_args );

	 	// Workaround for WP funniness
	 	$wp_query_stash = $wp_query;
	 	$post_stash	= $post;

	 	// Assemble the link data out of the query
	 	$child_data = array();
	 	if ( $child_posts->have_posts() ) {
	 		while ( $child_posts->have_posts() ) {
	 			$child_posts->the_post();

	 			$child_id = get_the_ID();
	 			$child_data[$child_id] = array(
	 				'post_name' => get_the_title(),
	 				'post_link' => bp_docs_get_doc_link( $child_id )
	 			);
	 		}
	 	}

	 	// Workaround for WP funniness
	 	$wp_query = $wp_query_stash;
	 	$post     = $post_stash;

	 	$child_data = apply_filters( 'bp_docs_hierarchy_child_data', $child_data );

	 	// Create the HTML
	 	$html = '';
	 	if ( !empty( $child_data ) ) {
	 		$html .= '<p>' . __( 'Children: ', 'bp-docs' );

	 		$children_html = array();
	 		foreach( $child_data as $child ) {
	 			$children_html[] = '<a href="' . $child['post_link'] . '">' . $child['post_name'] . '</a>';
	 		}

	 		$html .= implode( ', ', $children_html );

	 		$html .= '</p>';
	 	}

	 	echo apply_filters( 'bp_docs_hierarchy_show_children', $html, $child_data );
	 }
}

?>
