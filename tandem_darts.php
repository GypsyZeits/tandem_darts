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
