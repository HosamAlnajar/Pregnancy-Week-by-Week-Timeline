<?php

/**
 * @link              https://alnajar.se/
 * @since             1.0.0
 * @package           Post_timeline
 * @wordpress-plugin
 * Plugin Name:       Pregnancy Week by Week Timeline
 * Plugin URI:        https://alnajar.se/
 * Description:       Pregnancy Week by Week Timeline plugin in wordpress will help you to show Pregnancy Week by Week in an awesome way.
 * Version:           1.0.0
 * Author:            Hosam Alnajar
 * Author URI:        https://alnajar.se/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post_timeline
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'POST_TIMELINE_VERSION', '1.0.0' );

if ( ! defined( 'POST_TIMELINE_DIR' ) ) {
	define( 'POST_TIMELINE_DIR', dirname( __FILE__ ) . '/' );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post_timeline-activator.php
 */
function activate_post_timeline() {
	require_once POST_TIMELINE_DIR . 'includes/class-post_timeline-activator.php';
	Post_timeline_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post_timeline-deactivator.php
 */
function deactivate_post_timeline() {
	require_once POST_TIMELINE_DIR . 'includes/class-post_timeline-deactivator.php';
	Post_timeline_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_post_timeline' );
register_deactivation_hook( __FILE__, 'deactivate_post_timeline' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require POST_TIMELINE_DIR . 'includes/class-post_timeline.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_post_timeline() {

	$plugin = new Post_timeline();
	$plugin->run();

}
run_post_timeline();
