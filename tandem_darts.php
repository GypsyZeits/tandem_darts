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
			'title','author', 'thumbnail'
			),
		'register_meta_box_cb' => 'add_dartPlayer_metaboxes'
		);

	register_post_type( 'dartPlayer', $args );
}

function add_dartPlayer_metaboxes(){
	add_meta_box( 'dartPlayerInfo', 'Player Info', 'dartPlayerInfo', 'dartPlayer', 'normal', 'high' );
}

function dartPlayerInfo(){
	global $post;

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$_firstName = get_post_meta( $post->ID, '_firstName', true );
	$_lastName = get_post_meta( $post->ID, '_lastName', true );
	$_phoneNumber = get_post_meta( $post->ID, '_phoneNumber', true );
	$_emailAddress = get_post_meta( $post->ID, '_emailAddress', true );

	echo '<label>First Name: </label><input type="text" name="_firstName" value="' .$_firstName .'"  class="widefat"/>';
	echo '<label>Last Name: </label><input type="text" name="_lastName" value="' .$_lastName .'"  class="widefat"/>';
	echo '<label>Phone Number: </label><input type="text" name="_phoneNumber" value="' .$_phoneNumber .'"  class="widefat"/>';
	echo '<label>Email Address: </label><input type="text" name="_emailAddress" value="'. $_emailAddress .'"  class="widefat"/>';

}

// Save the Metabox Data

function dartPlayer_save_meta($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.

	$events_meta['_firstName'] = $_POST['_firstName'];
	$events_meta['_lastName'] = $_POST['_lastName'];
	$events_meta['_phoneNumber'] = $_POST['_phoneNumber'];
	$events_meta['_emailAddress'] = $_POST['_emailAddress'];


	// Add values of $events_meta as custom fields

	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, $key, $value);
	}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
	$updatePlayerTitle = array(
		'ID'			=> 	$post->ID,
		'post_title' 	=>	$events_meta['_lastName'].', '.$events_meta['_firstName']
		);

	if ( ! wp_is_post_revision( $post->ID ) && get_post_type($post->ID) == 'dartplayer' ){

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'dartPlayer_save_meta',1, 2);

		// update the post, which calls save_post again
		wp_update_post( $updatePlayerTitle);

		// re-hook this function
		add_action('save_post', 'dartPlayer_save_meta',1, 2);
	}

}

add_action('save_post', 'dartPlayer_save_meta', 1, 2); // save the custom fields

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
			'title', 'editor', 'author', 'thumbnail'
			),
		'register_meta_box_cb' => 'add_dartLeague_metaboxes'
		);

	register_post_type( 'dartLeague', $args );
}

function add_dartLeague_metaboxes(){
	add_meta_box( 'dartLeagueInfo', 'Dart League Info', 'dartLeagueInfo', 'dartLeague' ,'normal', 'high' );
}

function dartLeagueInfo(){
	global $post;

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$_leagueStartDate = get_post_meta( $post->ID, '_leagueStartDate', true );
	$_leagueEndDate	= get_post_meta( $post->ID, '_leagueEndDate', true );

	echo '<label>League Start Date</label><input type="date" name="_leagueStartDate" value="' . $_leagueStartDate .'" />';
	echo '<br /><label>League End Date</label><input type="date" name="_leagueEndDate" value="' . $_leagueEndDate .'" />';
}

function dartLeague_save_meta($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.

	$league_meta['_leagueStartDate'] = $_POST['_leagueStartDate'];
	$league_meta['_leagueEndDate'] = $_POST['_leagueEndDate'];


	// Add values of $events_meta as custom fields

	foreach ($league_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, $key, $value);
	}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action( 'save_post', 'dartLeague_save_meta', 1, 2 );

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
			'title','author'
			),
		'register_meta_box_cb' => 'add_dartMatch_metaboxes'
		);

	register_post_type( 'dartMatch', $args );
}

function add_dartMatch_metaboxes(){
	add_meta_box( 'dartMatchInfo', 'Dart Match Info', 'dartMatchInfo', 'dartMatch', 'normal', 'high' );
}

function dartMatchInfo(){
	global $post;
	$savePostId = $post->ID;

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$_leagueID = get_post_meta( $post->ID, '_leagueID', true );
	$_player1 = get_post_meta( $post->ID, '_player1', true );
	$_player2 = get_post_meta( $post->ID, '_player2', true );
	$_matchDate = get_post_meta($post->ID, '_matchDate', true);
	$_matchTime = get_post_meta( $post->ID, '_matchTime', true );
	$_matchWinner = get_post_meta( $post->ID, '_matchWinner', true);


	$args = array(
    		//Type & Status Parameters
		'post_type'   => 'dartLeague',
		'post_status' => 'publish',

    		//Order & Orderby Parameters
		'order'               => 'DESC',
		'orderby'             => 'meta',
		'meta_key'=>'_leagueEndDate',

    		//Pagination Parameters
		'posts_per_page'         => -1,
		'nopaging'               => true,
		);

	$query = new WP_Query( $args );

	echo '<label>League</label><select name="_leagueID">';
	echo '<option value=""></option>';
	if ($query->have_posts()){
		while ($query->have_posts()){
			$query->the_post();

			echo '<option ';
			if (get_the_ID() == $_leagueID){echo 'selected ';}
			echo 'value="'.get_the_ID().'">'.get_the_title().'</option>';
		}
		wp_reset_postdata();
	}
	echo '</select>';

	$args = array(
    		//Type & Status Parameters
		'post_type'   => 'dartPlayer',
		'post_status' => 'publish',

    		//Order & Orderby Parameters
		'order'               => 'ASC',
		'orderby'             => 'meta',
		'meta_key'=>'_lastName',

    		//Pagination Parameters
		'posts_per_page'         => -1,
		'nopaging'               => true,
		);

	$query = new WP_Query( $args );

	echo '<br /><label>Player 1</label><select name="_player1">';
	echo '<option value=""></option>';
	if ($query->have_posts()){
		while ($query->have_posts()){
			$query->the_post();

			echo '<option ';
			if (get_the_ID() == $_player1){echo 'selected ';}
			echo 'value="'.get_the_ID().'">'.get_the_title().'</option>';
		}
		wp_reset_postdata();
	}
	echo '</select>';

	$query = new WP_Query( $args );

	echo '<br /><label>Player 2</label><select name="_player2">';
	echo '<option value=""></option>';
	if ($query->have_posts()){
		while ($query->have_posts()){
			$query->the_post();

			echo '<option ';
			if (get_the_ID() == $_player2){echo 'selected ';}
			echo 'value="'.get_the_ID().'">'.get_the_title().'</option>';
		}
		wp_reset_postdata();
	}
	echo '</select>';


	$post=get_post( $savePostId);


	echo '<br /><label>Match Date</label><input type="date" name="_matchDate" value="' . $_matchDate .'" />';
	echo '<br /><label>Match Time</label><input type="time" name="_matchTime" value="' . $_matchTime .'" />';
	echo '<br /><label>Match Winner</label><select name="_matchWinner">';
	echo '<option value=""></option>';
	if ($_player1 != null) {
		echo '<option ';
		if (get_post_meta( $post->ID, '_matchWinner', true ) == $_player1 ){echo 'selected ';}
		echo 'value="'.$_player1.'">'.get_post($_player1)->post_title.'</option>';
	}
	if ($_player2 != null) {
		echo '<option ';
		if (get_post_meta( $post->ID, '_matchWinner', true ) == $_player2 ){echo 'selected ';}
		echo 'value="'.$_player2.'">'.get_post($_player2)->post_title.'</option>';

	}
	echo '</select>';

}

function dartMatch_save_meta($post_id, $post){

// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.

	$events_meta['_leagueID'] = $_POST['_leagueID'];
	$events_meta['_player1'] = $_POST['_player1'];
	$events_meta['_player2'] = $_POST['_player2'];
	$events_meta['_matchDate'] = $_POST['_matchDate'];
	$events_meta['_matchTime'] = $_POST['_matchTime'];
	$events_meta['_matchWinner'] = $_POST['_matchWinner'];

	// Add values of $events_meta as custom fields

	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, $key, $value);
	}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
	$updateMatchTitle = array(
		'ID'			=> 	$post->ID,
		'post_title' 	=>	$events_meta['_matchDate'].', '.get_the_title($events_meta['_leagueID']).
		', '. get_the_title($events_meta['_player1']).' vs '. get_the_title($events_meta['_player2'])
		);
 	//var_dump(( ! wp_is_post_revision( $post->ID ) && get_post_type($post->ID) == 'dartMatch' ));
	if ($events_meta['_matchTime']){
		$updateMatchTitle['post_title'] .= ' '.date('g:ia',strtotime($events_meta['_matchTime']));
	}
	if ($events_meta['_matchWinner']){
		$updateMatchTitle['post_title'] .= ' Winner: '.get_the_title($events_meta['_matchWinner']);
	}
	if ( ! wp_is_post_revision( $post->ID ) && get_post_type($post->ID) == 'dartmatch' ){

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'dartMatch_save_meta',1, 2);

		// update the post, which calls save_post again
		wp_update_post( $updateMatchTitle);

		// re-hook this function
		add_action('save_post', 'dartMatch_save_meta',1, 2);
	}

}

add_action( 'save_post', 'dartMatch_save_meta', 1, 2 );
add_action( 'init', 'register_dartMatch' );

function schedule_of_matches ($atts){
	$content = null;

	//check that league was supplied then setup new defaults
	if ($atts['league'] == null){
		return "No League Designated";
	}

	$league = get_page_by_title( $atts['league'], "OBJECT",'dartleague');
	$league_meta = get_post_meta( $league->ID );
	//var_dump($league);
	//var_dump($league_meta);

	//figure out start date: Now option, no startdate specified with league start date,
	//no startdate specified and implied no league, finally date is processed and formated if entered by hand
	 if ($atts['startdate'] == 'now' ){
	 	$atts['startdate'] = date('Y-m-d');
 	 	}
  	elseif (!$atts['startdate'] and $league_meta['_leagueStartDate'][0] ){
  		$atts['startdate'] = $league_meta['_leagueStartDate'][0];
  	}

 	elseif (!$atts['startdate']){
 		$atts['startdate'] = date('Y-m-d');
 	}
 	else {
 		$atts['startdate'] = date('Y-m-d', strtotime($atts['startdate']));
 	}

 	//due end date similarly but default is +1 year, supports now for some reason

 	if ($atts['enddate'] == 'now'){
		$atts['enddate'] = date('Y-m-d');
 	}
 	else if (!$atts['enddate'] and $league_meta['_leagueEndDate'][0] ){
 		$atts['enddate'] = $league_meta['_leagueEndDate'][0];
 	}
 	else if (!$atts['enddate']){
 		$atts['enddate'] = date('Y-m-d');
 	}
 	else {
 		$atts['enddate'] = date('Y-m-d', strtotime($atts['enddate']));
 	}
	//var_dump($atts);
		/**
		 * The WordPress Query class.
		 * @link http://codex.wordpress.org/Function_Reference/WP_Query
		 *
		 */
		$args = array(

			//Type & Status Parameters
			'post_type'   => 'dartmatch',
			'post_status' => 'publish',


			//Order & Orderby Parameters
			'order'               => 'ASC',
			'orderby'             => 'meta_value',
			'meta_key'			  => '_matchDate',
			'meta_type'			  => 'DATE',

			//Pagination Parameters
			'nopaging'               => true,

			//Custom Field Parameters
			'meta_query'     => array(
				'relation' 	 => 'AND',

				array(
					'key' => '_matchDate',
					'value' => $atts['startdate'],
					'type' => 'DATE',
					'compare' => '>='
				),
				array(
					'key' => '_matchDate',
					'value' => $atts['enddate'],
					'type' => 'DATE',
					'compare' => '<='
				),
				array(
					'key' => '_leagueID',
					'value' => $league->ID,
					'type' => 'NUM',
					'compare' => '='
				))

		);
	//var_dump($args);
	$query = new WP_Query( $args );
	//var_dump($query->have_posts());
	if ($query->have_posts()){
		while ($query->have_posts()){
			$query->the_post();
			$matchMeta = get_post_meta( get_the_ID() );

			//var_dump($matchMeta);
			$returnContent .= the_title( '<p>', '</p>', false );
			if ($matchMeta[_matchTime][0]){
				$returnContent.= '<p>'.date("g:i a", strtotime($matchMeta[_matchTime][0])).'</p>';
			}
			if ($matchMeta[_matchTime][0]){

			}
		}
		wp_reset_postdata();
	}



	if ($returnContent == null) {
		$returnContent = "Sorry there are no scheduled matches currently.";
	}
	return $returnContent;
}

add_shortcode( "schedule_of_matches", "schedule_of_matches" );

add_filter( 'template_include', 'dart_template' );

function dart_template( $template ) {
	$new_template;

	if ( get_post_type() == "dartleague" && !is_archive() ) {
		$new_template = dirname(__FILE__)."/single-dartleague.php";
	}
	else if ( get_post_type() == "dartleague" && is_archive() ) {
		$new_template = dirname(__FILE__)."/archive-dartleague.php";
	}
	else if ( get_post_type() == "dartplayer" && !is_archive() ) {
		$new_template = dirname(__FILE__)."/single-dartplayer.php";
	}
	else if ( get_post_type() == "dartplayer" && is_archive() ) {
		$new_template = dirname(__FILE__)."/archive-dartplayer.php";
	}
	else if ( get_post_type() == "dartmatch" && !is_archive() ) {
		$new_template = dirname(__FILE__)."/single-dartmatch.php";
	}
	else if ( get_post_type() == "dartmatche" && is_archive() ) {
		$new_template = dirname(__FILE__)."/archive-dartmatch.php";
	}
	//var_dump( get_post_type() == "dartleague" && is_archive() );


	if ( '' != $new_template ) {
			return $new_template ;
		}
	//var_dump( get_post_type() == "dartleague" && is_archive() );
	return $template;
}
