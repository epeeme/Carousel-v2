<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package Carousel
 * @link    http://dankew.me
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Carousel v2
 * Plugin URI:        http://dankew.me
 * Description:       A custom Wordpress plugin that displays 12 featured books from the Dead Good Books web site, a community site for lovers of crime fiction.
 * Version:           1.0.0
 * Author:            Dan kew
 * Author URI:        http://dankew.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       carousel-v2
 * Domain Path:       /
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CAROUSEL_V2_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_carousel_v2() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-carousel-v2-activator.php';
    Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_carousel_v2() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-carousel-v2-deactivator.php';
    Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_carousel_v2' );
register_deactivation_hook( __FILE__, 'deactivate_carousel_v2' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-carousel-v2.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_carousel_v2() {

    $plugin = new Plugin_Name();
    $plugin->run();

}
run_carousel_v2();
