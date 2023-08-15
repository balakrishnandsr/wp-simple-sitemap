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
			if ( ! empty( $_GET['page'] ) && 'wp-simple-sitemap' !== $_GET['page'] ) { //phpcs:ignore
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
		 * @param string $html content.
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
		 * @param array $urls lists.
		 * @return string
		 */
		public static function create_sitemap_html( $urls = [] ) {
			$page_lists  = [];
			$post_lists  = [];
			$other_links = [];
			if ( empty( $urls ) ) {
				$urls = self::get_urls_from_content();
			}

			$urls = self::unique_multidimentional_array( $urls, 0 );

			foreach ( $urls as $url ) {
				$permalink = ! empty( $url[0] ) ? $url[0] : '';

				if ( empty( $permalink ) ) {
					continue;
				}

				$post_id   = url_to_postid( $permalink );
				$post      = get_post( $post_id );
				$post_type = isset( $post->post_type ) ? $post->post_type : 'other';
				switch ( $post_type ) {
					case 'page':
						$parent_id                  = ! empty( $post->post_parent ) ? $post->post_parent : 0;
						$page_lists[ $parent_id ][] = $post;
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
				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3><?php esc_html_e( 'Pages', 'wp-simple-sitemap' ); ?></h3>
						<ul>
						<?php
						if ( ! empty( $page_lists[0] ) ) {
							foreach ( $page_lists[0] as $no_parent_page ) {
								if ( is_object( $no_parent_page ) && isset( $no_parent_page->ID ) ) {
									$permalink = get_permalink( $no_parent_page->ID );
									$title     = get_the_title( $no_parent_page->ID );
									?>
										<li>
											<a href="<?php echo esc_url( $permalink ); ?>" target="_blank"><?php echo esc_html( $title ); ?></a>
										</li>
										<?php
								}
							}
						}
							unset( $page_lists[0] );
						?>
						</ul>
						<?php
						if ( ! empty( $page_lists ) ) {
							?>
						<h4><?php esc_html_e( 'Parent - Child Pages', 'wp-simple-sitemap' ); ?></h4>
						<ul>
							<?php
							foreach ( $page_lists as $parent => $child_page ) {
								$parent_post = ! empty( $parent ) ? get_post( $parent ) : '';
								if ( is_object( $parent_post ) && isset( $parent_post->ID ) ) {
									$permalink = get_permalink( $parent_post->ID );
									$title     = get_the_title( $parent_post->ID );
									?>
									<li>
										<a href="<?php echo esc_url( $permalink ); ?>" target="_blank"><?php echo esc_html( $title ); ?></a>
									<?php
									if ( is_array( $child_page ) && ! empty( $child_page ) ) {
										?>
											<ul class="wpss-child-ul">
										<?php
										foreach ( $child_page as  $page ) {
											if ( is_object( $page ) && isset( $page->ID ) ) {
												$permalink = get_permalink( $page->ID );
												$title     = get_the_title( $page->ID );
												?>
													<li class="wpss-child-li"  style="list-style-type: square; list-style-position: inside;">
														<a href="<?php echo esc_url( $permalink ); ?>" target="_blank"><?php echo esc_html( $title ); ?></a>
													</li>
													<?php
											}
										}
										?>
											 </ul>
											<?php
									}
									?>
									</li>
									<?php
								}
							}
						}
						?>
						</ul>
					</div>
				</div>
				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3><?php esc_html_e( 'Posts', 'wp-simple-sitemap' ); ?></h3>
						<ul>
						<?php
						if ( ! empty( $post_lists ) ) {
							foreach ( $post_lists as $list ) {
								if ( ! empty( $list[0] ) && ! empty( $list[1] ) ) {
									?>
										<li>
											<a href="<?php echo esc_url( $list[0] ); ?>" target="_blank"><?php esc_html_e( $list[1], 'wp-simple-sitemap' ); ?></a>
										</li>
										<?php
								}
							}
						}
						?>
						</ul>
					</div>
				</div>
				<div class="welcome-panel-column">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<rect width="48" height="48" rx="4" fill="#1E1E1E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0668 17.0854L28.8221 13.9454L18.2008 24.671L16.8983 29.0827L21.4257 27.8309L32.0668 17.0854ZM16 32.75H24V31.25H16V32.75Z" fill="white"></path>
					</svg>
					<div class="welcome-panel-column-content">
						<h3><?php esc_html_e( 'Others', 'wp-simple-sitemap' ); ?></h3>
						<ul>
						<?php
						if ( ! empty( $other_links ) ) {
							foreach ( $other_links as $link ) {
								if ( ! empty( $link[0] ) && ! empty( $link[1] ) ) {
									?>
										<li>
										<a href="<?php echo esc_url( $link[0] ); ?>" target="_blank"><?php esc_html_e( $link[1], 'wp-simple-sitemap' ); ?></a>
										</li>
										<?php
								}
							}
						}
						?>
						</ul>
					</div>
				</div>
			<?php
			$sitemap_html = ob_get_contents();
			ob_end_clean();
			$sitemap_html = apply_filters(
				'wpss_sitemap_customizable_content',
				$sitemap_html,
				[
					'homepage_urls' => $urls,
					'page_links'    => $page_lists,
					'post_links'    => $post_lists,
					'other_links'   => $other_links,
				]
			);
			file_put_contents( WP_PLUGIN_DIR . '/wp-simple-sitemap/inc/sitemap/sitemap.html', $sitemap_html );
			return $sitemap_html;
		}

		/**
		 * Get unique multidimentional array
		 *
		 * @param array     $array array.
		 * @param array-key $key array key.
		 * @return array
		 */
		public static function unique_multidimentional_array( $array, $key ) {
			$temp_array = [];
			$key_array  = [];
			$i          = 0;
			foreach ( $array as $val ) {
				if ( ! in_array( $val[ $key ], $key_array, true ) ) {
					$key_array[ $i ]  = $val[ $key ];
					$temp_array[ $i ] = $val;
				}
				$i++;
			}
			return $temp_array;
		}
	}

}

