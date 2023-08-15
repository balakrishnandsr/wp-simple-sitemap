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
		 * Admin page content
		 * */
		public function admin_page_content() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}?>
			<div id="welcome-panel" class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-header">
						<div class="welcome-panel-header-image">
							<svg preserveAspectRatio="xMidYMin slice" fill="none" viewBox="0 0 1232 240" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
								<g clip-path="url(#a)">
									<path fill="#151515" d="M0 0h1232v240H0z"></path>
									<ellipse cx="616" cy="232" fill="url(#b)" opacity=".05" rx="1497" ry="249"></ellipse>
									<mask id="d" width="1000" height="400" x="232" y="20" maskUnits="userSpaceOnUse" style="mask-type:alpha">
										<path fill="url(#c)" d="M0 0h1000v400H0z" transform="translate(232 20)"></path>
									</mask>
									<g stroke-width="2" mask="url(#d)">
										<path stroke="url(#e)" d="M387 20v1635"></path>
										<path stroke="url(#f)" d="M559.5 20v1635"></path>
										<path stroke="url(#g)" d="M732 20v1635"></path>
										<path stroke="url(#h)" d="M904.5 20v1635"></path>
										<path stroke="url(#i)" d="M1077 20v1635"></path>
									</g>
								</g>
								<defs>
									<linearGradient id="e" x1="387.5" x2="387.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
										<stop stop-color="#3858E9" stop-opacity="0"></stop>
										<stop offset=".297" stop-color="#3858E9"></stop>
										<stop offset=".734" stop-color="#3858E9"></stop>
										<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
										<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
									</linearGradient>
									<linearGradient id="f" x1="560" x2="560" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
										<stop stop-color="#FFFCB5" stop-opacity="0"></stop>
										<stop offset="0" stop-color="#FFFCB5" stop-opacity="0"></stop>
										<stop offset=".297" stop-color="#FFFCB5"></stop>
										<stop offset=".734" stop-color="#FFFCB5"></stop>
										<stop offset="1" stop-color="#FFFCB5" stop-opacity="0"></stop>
									</linearGradient>
									<linearGradient id="g" x1="732.5" x2="732.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
										<stop stop-color="#C7FFDB" stop-opacity="0"></stop>
										<stop offset=".297" stop-color="#C7FFDB"></stop>
										<stop offset=".693" stop-color="#C7FFDB"></stop>
										<stop offset="1" stop-color="#C7FFDB" stop-opacity="0"></stop>
									</linearGradient>
									<linearGradient id="h" x1="905" x2="905" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
										<stop stop-color="#FFB7A7" stop-opacity="0"></stop>
										<stop offset=".297" stop-color="#FFB7A7"></stop>
										<stop offset=".734" stop-color="#FFB7A7"></stop>
										<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
										<stop offset="1" stop-color="#FFB7A7" stop-opacity="0"></stop>
									</linearGradient>
									<linearGradient id="i" x1="1077.5" x2="1077.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
										<stop stop-color="#7B90FF" stop-opacity="0"></stop>
										<stop offset=".297" stop-color="#7B90FF"></stop>
										<stop offset=".734" stop-color="#7B90FF"></stop>
										<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
										<stop offset="1" stop-color="#7B90FF" stop-opacity="0"></stop>
									</linearGradient>
									<radialGradient id="b" cx="0" cy="0" r="1" gradientTransform="matrix(0 249 -1497 0 616 232)" gradientUnits="userSpaceOnUse">
										<stop stop-color="#3858E9"></stop>
										<stop offset="1" stop-color="#151515" stop-opacity="0"></stop>
									</radialGradient>
									<radialGradient id="c" cx="0" cy="0" r="1" gradientTransform="matrix(0 765 -1912.5 0 500 -110)" gradientUnits="userSpaceOnUse">
										<stop offset=".161" stop-color="#151515" stop-opacity="0"></stop>
										<stop offset=".682"></stop>
									</radialGradient>
									<clipPath id="a">
										<path fill="#fff" d="M0 0h1232v240H0z"></path>
									</clipPath>
								</defs>
							</svg>
						</div>
						<h2><?php esc_html_e( 'Welcome to WP Simple Sitemap', 'wp-simple-sitemap' ); ?></h2>
						<p></p>
						<h3>
							<?php esc_html_e( 'Quick Guide', 'wp-simple-sitemap' ); ?>
						</h3>
						<p>
							<ol>
								<li> <?php esc_html_e( 'Click "Run" button for initiate the crawl for home page and view the result .', 'wp-simple-sitemap' ); ?></li>
								<li> <?php esc_html_e( 'Click "View" button for see the saved result.', 'wp-simple-sitemap' ); ?></li>
								<li> <?php esc_html_e( 'Create a new page (for sitemap) and insert the shortcode [wp-simple-sitemap].', 'wp-simple-sitemap' ); ?></li>
								<li> <?php esc_html_e( "Done, yeah that's easy.", 'wp-simple-sitemap' ); ?></li>
							</ol>
						</p>

						<a href="javascript:void(0)" class="button button-primary button-hero" id="wpss-run" data-nonce="<?php echo wp_create_nonce( 'wpss-run' ); //phpcs:ignore ?>"> <?php esc_html_e( 'Run', 'wp-simple-sitemap' ); ?> </a>

						<a href="javascript:void(0)" class="button button-primary button-hero" id="wpss-view" data-nonce="<?php echo wp_create_nonce( 'wpss-view' ); //phpcs:ignore ?>"> <?php esc_html_e( 'View', 'wp-simple-sitemap' ); ?> </a>
					</div>
					<div class="welcome-panel-column-container wpss-results">

					</div>
					<div class="wpss-loader-container" style="display: none;">
						<img class="wpss-loader" src="<?php echo esc_url( includes_url() . 'js/thickbox/loadingAnimation.gif' ); ?>" style="width: 100%; padding: 10% 100% 10% 100%;"/>
					</div>

				</div>
			</div>
			<?php

		}

	}
}

WP_Simple_Sitemap_Admin::get_instance();

