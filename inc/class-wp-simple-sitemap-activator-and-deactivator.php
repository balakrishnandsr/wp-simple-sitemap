<?php
/**
 * WP simple sitemap initialize
 *
 * @package wp-simple-sitemap/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( ' WP_Simple_Sitemap_Activator_And_Deactivator' ) ) {

	/**
	 * Class for handling activation and deaction actions.
	 */
	class  WP_Simple_Sitemap_Activator_And_Deactivator {

		/**
		 * Check the plugin requirement
		 *
		 * @return boolean
		 * */
		public static function wpss_plugin_requiremnet_check() {
			if ( self::php_version_check() && self::wp_version_check() ) {
				return true;
			} else {
				if ( ! function_exists( 'wp_get_current_user' ) ) {
					include ABSPATH . 'wp-includes/pluggable.php';
				}
				if ( current_user_can( 'manage_options' ) ) {
					add_action( 'admin_notices', 'Wp_Simple_Sitemap_Activator_And_Deactivator::display_error_notice' );
				}
				return false;
			}
		}

		/**
		 * Display error message on admin screen
		 * */
		public static function display_error_notice() {
			echo '<div class="notice notice-error is-dismissible">';
			if ( ! self::php_version_check() ) {
				echo '<p>' .
						esc_html(
							sprintf(
								/* translators: %1s: current php version; %2s: minimum version */
								__( 'Your PHP version: %1$s, Needs atleast %2$s or higher', 'wp-simple-sitemap' ),
								PHP_VERSION,
								5.6
							)
						) . '</p>';

			}
			if ( ! self::wp_version_check() ) {
				global $wp_version;
				echo '<p>' .
					esc_html(
						sprintf(
						/* translators: %1s: current wp version; %2s: minimum version */
							__( 'Your WordPress version: %1$s, Needs atleast %2$s or higher', 'wp-simple-sitemap' ),
							$wp_version,
							5.0
						)
					) . '</p>';
			}
			echo '</div>';
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
			$timestamp = wp_next_scheduled( 'wpss_cron_schedules' );
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, 'wpss_cron_schedules' );
			}
		}

		/**
		 * Function to add more action on plugins page
		 *
		 * @param array $links Existing links.
		 * @return array|string[]
		 */
		public function wpss_add_plugin_actions( $links ) {
			$action_links = [
				'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=wp-simple-sitemap' ) ) . '">' . __( 'Settings', 'wp-simple-sitemap' ) . '</a>',
			];
			return array_merge( $action_links, $links );
		}

	}

}

