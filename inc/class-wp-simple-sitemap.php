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
			add_action( 'admin_enqueue_scripts', [ $this, 'add_wpss_scripts' ], 100 );
		}

		/**
		 * Include class Files
		 *
		 * @return void
		 */
		public function includes() {
			include_once 'class-wp-simple-sitemap-admin.php';
			include_once 'class-wp-simple-sitemap-public.php';
			include_once 'class-wp-simple-sitemap-ajax.php';
		}

		/**
		 * Including css/js files to the project.
		 *
		 * @return void
		 */
		public function add_wpss_scripts() {
			if ( ! empty( $_GET['page'] ) && 'wp-simple-sitemap' !== $_GET['page'] ) {
				return;
			}

			/**
			 * Enqueue js
			 */
			wp_register_script( 'wpss-script', plugin_dir_url( __FILE__ ) . 'assets/js/wpss-script.js', [], '1.0.0', true );
			wp_enqueue_script( 'wpss-script' );

			$wpss_data = [
				'home_url'  => home_url(),
				'admin_url' => admin_url( 'admin.php?page=wp-simple-sitemap' ),
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			];

			wp_localize_script( 'wpss-script', 'wpss_data', $wpss_data );
		}

		/**
		 * Get homepage url
		 *
		 * @return string|void
		 */
		public static function get_homepage_url() {
			return function_exists( 'home_url' ) ? home_url() : '';
		}

		/**
		 * Get urls from content.
		 *
		 * @param string $html content
		 * @return array
		 */
		public static function get_urls_from_content( $html = '' ) {
			$urls_from_content = [];
			$path              = plugin_dir_url( __FILE__ ) . 'homepage/homepage.html';
			if ( empty( $html ) && file_exists( $path ) ) {
				$html = file_get_contents( $path );
			}

			if ( preg_match_all( '/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/i', $html, $matches, PREG_SET_ORDER ) ) {
				if ( ! empty( $matches ) ) {
					foreach ( $matches as $match ) {
						$urls_from_content[] = [ $match[1], $match[2] ];
					}
				}
			}

			return $urls_from_content;
		}

		/**
		 * Create html
		 *
		 * @param array $urls lists
		 * @return void
		 */
		public static function create_sitemap_html( $urls = [] ) {
			$page_lists = $post_lists = $other_links = [];
			if ( empty( $urls ) ) {
				$urls = self::get_urls_from_content();
			}

			foreach ( $urls as $url ) {
				$permalink = ! empty( $url[0] ) ? $url[0] : '';
				$title     = ! empty( $url[1] ) ? $url[1] : '';
				if ( empty( $permalink ) ) {
					continue;
				}

				$post_id   = url_to_postid( $permalink );
				$post      = get_post( $post_id );
				$post_type = isset( $post->post_type ) ? $post->post_type : 'other';
				switch ( $post_type ) {
					case 'page':
						$parent_id                  = ! empty( $post->post_parent ) ? $post->post_parent : 0;
						$page_lists[ $parent_id ][] = $url;
						break;
					case 'post':
						$post_lists[] = $url;
						break;
					default:
						$other_links[] = $url;
						break;
				}
			}
			ob_start();?>


			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3>Pages</h3>
						<ol>
							<li>

							</li>
						</ol>
					</div>
				</div>




				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3>Posts</h3>
						<ol>
						<?php
						foreach ( $post_lists as $list ) {
							?>
							<li>
								<a href="<?php echo $list[0]; ?>" target="_blank"><?php echo $list[1]; ?></a>
							</li><?php } ?>
						</ol>
					</div>
				</div>



				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3>Others</h3>
						<ol>
						<?php
						foreach ( $other_links as $link ) {
							?>
								<li>
								<a href="<?php echo $link[0]; ?>" target="_blank"><?php echo $link[1]; ?></a>
								</li><?php } ?>
						</ol>
					</div>
				</div>
			</div>
			<?php
			file_put_contents( WP_PLUGIN_DIR . '/wp-simple-sitemap/inc/sitemap/sitemap.html', ob_get_contents() );
			ob_end_clean();
		}
	}

}

