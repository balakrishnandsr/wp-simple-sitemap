<?php
/**
 * Plugin Name: WP Simple Sitemap
 * Plugin URI: https://github.com/balakrishnandsr/wp-simple-sitemap
 * Description: A simple sitemap generating plugin.
 * Version: 1.0.0
 * Author: Balakrishnan D
 * Author URI: https://github.com/balakrishnandsr
 * Licence: GPLv2 or later
 * Text Domain: wp-simple-sitemap
 * Domain Path: languages
 *
 * @package wp-simple-sitemap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Including the class having function to execute during activation & deactivation of plugin
 */
require_once 'inc/class-wp-simple-sitemap-activator-and-deactivator.php';

/**
 * On activation
 */
register_activation_hook( __FILE__, [ 'WP_Simple_Sitemap_Activator_And_Deactivator', 'activate_wp_simple_sitemap' ] );

/**
 * On deactivation
 */
register_deactivation_hook( __FILE__, [ 'WP_Simple_Sitemap_Activator_And_Deactivator', 'deactivate_wp_simple_sitemap' ] );

/**
 * Start the Plugin if meets the requirements
 */
if ( wp_simple_sitemap_activator_and_deactivator::wpss_plugin_requiremnet_check() ) {

	include_once 'inc/class-wp-simple-sitemap.php';

	WP_Simple_Sitemap::get_instance();
}

