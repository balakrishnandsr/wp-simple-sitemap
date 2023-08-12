<?php
/**
 * WP simple sitemap
 *
 * @package wp-simple-sitemap/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( ' WP_Simple_Sitemap' ) ) {

	/**
	 * Main Class for WP Simple Sitemap plugin.
	 */
	class WP_Simple_Sitemap {

		/**
		 * Variable to hold instance of WP Simple Sitemap.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WP Simple Sitemap.
		 *
		 * @return WP_Simple_Sitemap Singleton object of WP_Simple_Sitemap
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Cloning is forbidden.
		 */
		private function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-simple-sitemap' ), '1.0.0' );
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->includes();
		}

		public function includes() {
			include_once 'class-wp-simple-sitemap-admin.php';
			include_once 'class-wp-simple-sitemap-public.php';
		}
	}

}

