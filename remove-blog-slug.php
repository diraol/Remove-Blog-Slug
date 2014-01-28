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
add_action( 'init', 'remove_blog_slug' );
function remove_blog_slug() {
	
	// check multisite
	if ( ! is_multisite() )
		return;
	
	// check blogid
	if ( get_current_blog_id() != 1 )
		return;
	
	// check option
	$permalink_structure = get_option( 'permalink_structure' );
	if ( strstr( $permalink_structure, 'blog' ) ) {
		$permalink_structure = str_replace( 'blog/', '', $permalink_structure );
		update_option( 'permalink_structure', $permalink_structure );
		flush_rewrite_rules( TRUE );
	}
}