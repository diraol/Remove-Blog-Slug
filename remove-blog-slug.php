<?php
/**
 * Plugin Name:	Remove Blog Slug
 * Description:	This simple and small plugin removes the /blog/-Slug from your WordPress posts in the main blog of your MultiSite installation
 * Version:		1.0
 * Author:		HerrLlama for Inpsyde GmbH
 * Author URI:	http://inpsyde.com
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// kickoff
add_action( 'generate_rewrite_rules', 'remove_blog_slug' );
function remove_blog_slug( $wp_rewrite ) {

	// check multisite
	if ( ! is_multisite() )
		return;

	// check blogid
	if ( get_current_blog_id() != 1 )
		return;

	// set checkup
	$rewrite = FALSE;

	// update_option
	$wp_rewrite->permalink_structure = preg_replace( '!^(/)?blog/!', '$1', $wp_rewrite->permalink_structure );
	update_option( 'permalink_structure', $wp_rewrite->permalink_structure );

	// update the rest of the rewrite setup
	$wp_rewrite->author_structure = preg_replace( '!^(/)?blog/!', '$1', $wp_rewrite->author_structure );
	$wp_rewrite->date_structure = preg_replace( '!^(/)?blog/!', '$1', $wp_rewrite->date_structure );
	$wp_rewrite->front = preg_replace( '!^(/)?blog/!', '$1', $wp_rewrite->front );

	// walk through the rules
	$new_rules = array();
	foreach ( $wp_rewrite->rules as $key => $rule )
		$new_rules[ preg_replace( '!^(/)?blog/!', '$1', $key ) ] = $rule;
	$wp_rewrite->rules = $new_rules;

	// walk through the extra_rules
	$new_rules = array();
	foreach ( $wp_rewrite->extra_rules as $key => $rule )
		$new_rules[ preg_replace( '!^(/)?blog/!', '$1', $key ) ] = $rule;
	$wp_rewrite->extra_rules = $new_rules;

	// walk through the extra_rules_top
	$new_rules = array();
	foreach ( $wp_rewrite->extra_rules_top as $key => $rule )
		$new_rules[ preg_replace( '!^(/)?blog/!', '$1', $key ) ] = $rule;
	$wp_rewrite->extra_rules_top = $new_rules;

	// walk through the extra_permastructs
	$new_structs = array();
	foreach ( $wp_rewrite->extra_permastructs as $extra_permastruct => $struct ) {
		$struct[ 'struct' ] = preg_replace( '!^(/)?blog/!', '$1', $struct[ 'struct' ] );
		$new_structs[ $extra_permastruct ] = $struct;
	}
	$wp_rewrite->extra_permastructs = $new_structs;
}
