<?php
/**
 * WP simple sitemap initialize
 *
 * @package wp-simple-sitemap/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( ' Wp_Simple_Sitemap_Activator_And_Deactivator' ) ) {

	/**
	 * Class for handling activation and deaction actions.
	 */
	class  Wp_Simple_Sitemap_Activator_And_Deactivator {

		/**
		 * Check the plugin requirement
		 *
		 * @return boolean
		 * */
		public static function wpss_plugin_requiremnet_check() {
			if ( self::php_version_check() && self::wp_version_check() ) {
				return true;
			} else {
				if ( current_user_can( 'manage_options' ) ) {
					add_action( 'admin_notices', [ __CLASS__, 'display_error_notice' ] );
				}
				return false;
			}
		}

		/**
		 * Display error message on admin screen
		 * */
		public static function display_error_notice() {
			echo '<div><ul>';
			if ( ! self::php_version_check() ) {
				echo '<li>' .
						esc_html(
							sprintf(
								/* translators: %1s: current php version; %2s: minimum version */
								__( 'Your PHP version: %1$s, Needs atleast %2$s or higher', 'wp-simple-sitemap' ),
								PHP_VERSION,
								5.6
							)
						) . '</li>';
			}
			if ( ! self::wp_version_check() ) {
				global $wp_version;
				echo '<li>' .
					esc_html(
						sprintf(
						/* translators: %1s: current wp version; %2s: minimum version */
							__( 'Your WordPress version: %1$s, Needs atleast %2$s or higher', 'wp-simple-sitemap' ),
							$wp_version,
							5.0
						)
					) . '</li>';
			}
			echo '</ul></div>';
		}

		/**
		 * Checking minimum PHP version requirement.
		 *
		 * @return boolean
		 * */
		private static function php_version_check() {
			return version_compare( PHP_VERSION, 5.6, '>=' );
		}

		/**
		 * Checking minimum WordPress version requirement.
		 *
		 * @return boolean
		 * */
		private static function wp_version_check() {
			global $wp_version;
			return version_compare( $wp_version, 5.0, '>=' );
		}

		/**
		 * Changes required when install plugin
		 */
		public static function activate_wp_simple_sitemap() {

		}

		/**
		 * Changes required when uninstall plugin
		 */
		public static function deactivate_wp_simple_sitemap() {

		}

	}

}

