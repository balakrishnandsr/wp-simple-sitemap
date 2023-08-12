<?php
/**
 * WP simple sitemap initialize
 *
 * @package wp-simple-sitemap/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Simple_Sitemap_Admin' ) ) {

	/**
	 * Class for handling admin actions.
	 */
	class  WP_Simple_Sitemap_Admin {


		/**
		 * Variable to hold instance of WP_Simple_Sitemap_Admin
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WP_Simple_Sitemap_Admin
		 *
		 * @return WP_Simple_Sitemap_Admin Singleton object of WP_Simple_Sitemap_Admin
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the event
		 * */
		private function __construct() {
			add_action( 'admin_menu', [ $this, 'add_admin_menu_page' ] );
		}

		/**
		 * For adding admin menu
		 * */
		public function add_admin_menu_page() {
			add_menu_page(
				__( 'WP Simple Sitemap', 'wp-simple-sitemap' ),
				'WP Simple Sitemap',
				'manage_options',
				'wp-simple-sitemap',
				[ $this, 'admin_page_content' ],
				'',
				6
			);
		}

		/**
		 * Admin menu content
		 * */
		public function admin_page_content() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			echo 'hello';

		}
	}
}

WP_Simple_Sitemap_Admin::get_instance();

