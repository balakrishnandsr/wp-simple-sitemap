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
			/*
			$message = $this->validateAndInitiateSitemapProcess();
			$Cron = Cron::instance();
			$nextCronAt = $Cron->getNextScheduled();
			$sitemap = Sitemap::instance()->getSitemap();
			$updated_at = '';
			if (isset($sitemap['updated_at'])) {
				$updated_at = $Cron->dateFormat($sitemap['updated_at']);
			}
			$filepath = WPS_ASHLIN_PATH . 'src/Admin/templates/dashboard.php';
			$data = [
				'message' => $message,
				'next_cron_at' => $nextCronAt,
				'sitemap' => $sitemap,
				'last_updated_at' => $updated_at,
			];
			$this->render($filepath, $data);*/
		}

		/**
		 * Validate and generate sitemap
		 * */
		private function validateAndInitiateSitemapProcess() {
			/*
			if ('wp_sitemap_crawl' !== sanitize_text_field(wp_unslash($_POST['action'] ?? ''))) {
				return;
			}
			$nonce = sanitize_text_field(wp_unslash($_POST['_nonce'] ?? ''));
			// Verify nonce
			if (!empty($nonce) && wp_verify_nonce($nonce, 'wp_sitemap_crawl')) {
				$result = Sitemap::instance()->initiateSitemapGenerationProcess();
				if ($result['status'] === true) {
					return '<div class="notice notice-success"><p>' . $result['message'] . '</p></div>';
				} else {
					return '<div class="notice notice-warning"><p>' . $result['message'] . '</p></div>';
				}
			} else {
				$message = esc_html__('Invalid request.', 'wp-sitemap-ashlin');
				return '<div class="notice notice-warning"><p>' . $message . '</p></div>';
			}*/
		}
	}
}

WP_Simple_Sitemap_Admin::get_instance();

