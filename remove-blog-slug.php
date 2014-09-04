<?php
/**
 * Plugin Name:	Remove Blog Slug
 * Description:	This simple and small plugin removes the /blog/-Slug from your WordPress posts in the main blog of your MultiSite installation
 * Version:		1.0.1
 * Author:		HerrLlama for wpcoding.de
 * Author URI:	http://wpcoding.de
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// check multisite
if ( ! is_multisite() )
	return;

/**
 * This function rewrites all the permalinks to remove
 * the /blog from the structure
 *
 * @wp-hook	generate_rewrite_rules
 * @param	object $wp_rewrite
 * @return	void
 */
function remove_blog_slug( $wp_rewrite ) {

	// check multisite and main site
	if ( ! is_main_site() )
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
} add_action( 'generate_rewrite_rules', 'remove_blog_slug' );

/**
 * This function loads the textdomain for this plugin
 *
 * @wp-hook	plugins_loaded
 * @return	void
 */
function rbs_prepare_localization() {

	load_plugin_textdomain( 'remove-blog-slug', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} add_action( 'plugins_loaded', 'rbs_prepare_localization' );

/**
 * This function adds an admin notice to the permalink
 * settings page to describe what the plugin does.
 *
 * @wp-hook	admin_notices
 * @return	void
 */
function rbs_admin_notice() {

	// check if we are on the permalink page
	global $pagenow;
	if ( $pagenow != 'options-permalink.php' )
		return;

	echo '<div class="updated">';
		echo '<p>';
			echo '<strong>';
				_e( 'Please note:', 'remove-blog-slug' );
			echo '</strong> ';
			_e( 'You are using the plugin Remove Blog Slug. Even if here is "blog" in the structure, the plugin works. This is because WordPress has hard-coded "blog". Simply update the structure and the front end is "blog" is no longer displayed.', 'remove-blog-slug' );
		echo '</p>';
	echo '</div>';
} add_action( 'admin_notices', 'rbs_admin_notice' );