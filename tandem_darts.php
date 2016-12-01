<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              jameszeits.com
 * @since             1.0.0
 * @package           Tandem_darts
 *
 * @wordpress-plugin
 * Plugin Name:       Tandem Darts League
 * Plugin URI:        jameszeits.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            James Zeits
 * Author URI:        jameszeits.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tandem_darts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tandem_darts-activator.php
 */
function activate_tandem_darts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tandem_darts-activator.php';
	Tandem_darts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tandem_darts-deactivator.php
 */
function deactivate_tandem_darts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tandem_darts-deactivator.php';
	Tandem_darts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tandem_darts' );
register_deactivation_hook( __FILE__, 'deactivate_tandem_darts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tandem_darts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tandem_darts() {

	$plugin = new Tandem_darts();
	$plugin->run();

}
run_tandem_darts();

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function registerDartPlayerPostType() {

	$labels = array(
		'name'                => __( 'Players', 'text-domain' ),
		'singular_name'       => __( 'Player', 'text-domain' ),
		'add_new'             => _x( 'Add New Player', 'text-domain', 'text-domain' ),
		'add_new_item'        => __( 'Add New Player', 'text-domain' ),
		'edit_item'           => __( 'Edit Player', 'text-domain' ),
		'new_item'            => __( 'New Player', 'text-domain' ),
		'view_item'           => __( 'View Player', 'text-domain' ),
		'search_items'        => __( 'Search Players', 'text-domain' ),
		'not_found'           => __( 'No Players found', 'text-domain' ),
		'not_found_in_trash'  => __( 'No Players found in Trash', 'text-domain' ),
		'parent_item_colon'   => __( 'Parent Player:', 'text-domain' ),
		'menu_name'           => __( 'Players', 'text-domain' ),
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => false,
		'description'         => 'Dart Players',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'author', 'thumbnail'
			)
	);

	register_post_type( 'dartPlayer', $args );
}

add_action( 'init', 'registerDartPlayerPostType' );

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function register_dartLeague() {

	$labels = array(
		'name'                => __( 'Leagues', 'text-domain' ),
		'singular_name'       => __( 'League', 'text-domain' ),
		'add_new'             => _x( 'Add New League', 'text-domain', 'text-domain' ),
		'add_new_item'        => __( 'Add New League', 'text-domain' ),
		'edit_item'           => __( 'Edit League', 'text-domain' ),
		'new_item'            => __( 'New League', 'text-domain' ),
		'view_item'           => __( 'View League', 'text-domain' ),
		'search_items'        => __( 'Search Leagues', 'text-domain' ),
		'not_found'           => __( 'No Leauges found', 'text-domain' ),
		'not_found_in_trash'  => __( 'No Leauges found in Trash', 'text-domain' ),
		'parent_item_colon'   => __( 'Parent League:', 'text-domain' ),
		'menu_name'           => __( 'Leagues', 'text-domain' ),
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => false,
		'description'         => 'dart leagues',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title', 'editor', 'author', 'thumbnail',
			'excerpt', 'trackbacks'
			)
	);

	register_post_type( 'dartLeague', $args );
}

add_action( 'init', 'register_dartLeague' );

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function register_dartMatch() {

	$labels = array(
		'name'                => __( 'Matches', 'text-domain' ),
		'singular_name'       => __( 'Match', 'text-domain' ),
		'add_new'             => _x( 'Add New Match', 'text-domain', 'text-domain' ),
		'add_new_item'        => __( 'Add New Match', 'text-domain' ),
		'edit_item'           => __( 'Edit Match', 'text-domain' ),
		'new_item'            => __( 'New Match', 'text-domain' ),
		'view_item'           => __( 'View Match', 'text-domain' ),
		'search_items'        => __( 'Search Matches', 'text-domain' ),
		'not_found'           => __( 'No Matches found', 'text-domain' ),
		'not_found_in_trash'  => __( 'No Matches found in Trash', 'text-domain' ),
		'parent_item_colon'   => __( 'Parent Match:', 'text-domain' ),
		'menu_name'           => __( 'Matches', 'text-domain' ),
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'author'
			)
	);

	register_post_type( 'match', $args );
}

add_action( 'init', 'register_dartMatch' );

