<?php
/**
 * WP simple sitemap
 *
 * @package wp-simple-sitemap/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Simple_Sitemap_Ajax' ) ) {

	/**
	 * Main Class for WP Simple Sitemap plugin.
	 */
	class WP_Simple_Sitemap_Ajax {

		/**
		 * Variable to hold instance of WP Simple Sitemap.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WP Simple Sitemap Ajax.
		 *
		 * @return WP_Simple_Sitemap_Ajax Singleton object of WP_Simple_Sitemap_Ajax
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_wpss_ajax', [ $this, 'wpss_ajax_requests' ] );
			add_shortcode( 'wp-simple-sitemap', [ $this, 'wp_simple_sitemap' ] );
		}

		/**
		 * Handle call to functions which is not available in this class
		 *
		 * @param string $function_name The function name.
		 * @param array  $arguments Array of arguments passed while calling $function_name.
		 * @return result of function call
		 */
		public function __call( $function_name, $arguments = [] ) {

			if ( ! is_callable( [ 'WP_Simple_Sitemap', $function_name ] ) ) {
				return;
			}

			if ( ! empty( $arguments ) ) {
				return call_user_func_array( [ 'WP_Simple_Sitemap', $function_name ], $arguments );
			} else {
				return call_user_func( [ 'WP_Simple_Sitemap', $function_name ] );
			}
		}

		/**
		 * Ajax Controller
		 *
		 * @return void
		 */
		public function wpss_ajax_requests() {

			$method_name = ! empty( $_POST['method'] ) ? 'wpss_ajax_' . sanitize_text_field( wp_unslash( $_POST['method'] ) ) : ''; //phpcs:ignore

			if ( current_user_can( 'manage_options' ) && method_exists( $this, $method_name ) ) {
				$result = $this->$method_name();
			} else {
				$result = __( 'Requested method not exists', 'wp-simple-sitemap' );
			}
			wp_send_json_success( $result );
		}


		/**
		 * WPSS ajax crawl home page urls
		 *
		 * @return string
		 */
		public function wpss_ajax_get_home_page_urls() {

			$nonce  = ! empty( $_POST['wpss_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpss_nonce'] ) ) : '';
			$method = ! empty( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '';
			if ( wp_verify_nonce( $nonce, 'wpss-run' ) ) {
				return $this->wpss_refresh_sitemap( $method );
			}
			return esc_html__( 'Oops! Authentication failed, Please try again.', 'wp-simple-sitemap' );
		}

		/**
		 * Display URLS
		 *
		 * @return string
		 */
		public function wpss_ajax_view_sitemap() {
			$nonce = ! empty( $_POST['wpss_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpss_nonce'] ) ) : '';
			if ( wp_verify_nonce( $nonce, 'wpss-view' ) ) {
				$sitemap_html = get_transient( 'wp_simple_sitemap_html' );
				if ( ! empty( $sitemap_html ) ) {
					return $sitemap_html;
				}
				return '<h3>' . esc_html__( 'Please click the "Run" button!', 'wp-simple-sitemap' ) . '</h3>';
			}
		}

		/**
		 * Shortcode to view the results in front end.
		 * Use wp_simple_sitemap_customizable_content filter to customize content.
		 *
		 * @return string
		 */
		public function wp_simple_sitemap() {

			$path     = plugin_dir_url( __FILE__ ) . 'sitemap/sitemap.html';
			$response = wp_remote_post( $path );
			$html     = wp_remote_retrieve_body( $response );
			if ( ! empty( $html ) ) {
				return $html;
			}
			return '<h3>' . esc_html__( 'Oops!, seems to be no content available.', 'wp-simple-sitemap' ) . '</h3>';
		}
	}

}

WP_Simple_Sitemap_Ajax::get_instance();
