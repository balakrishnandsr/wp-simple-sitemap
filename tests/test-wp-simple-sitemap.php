<?php
/**
 * Class SampleTest
 *
 * @package Wp_Simple_Sitemap
 */

/**
 * Sample test case.
 */
class Test_WP_Simple_Sitemap extends WP_UnitTestCase {

	/**
	 * Test for constructor function
	 */
	public function test_construct() {
		$wpss = WP_Simple_Sitemap::get_instance();
		$enqueue_scripts = has_action( 'admin_enqueue_scripts', array($wpss, 'wpss_add_scripts' )  );
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		$wp_filesystem_direct = new WP_Filesystem_Direct( new StdClass() );
		$test_constructor = ( 100 === $enqueue_scripts && is_callable( array( $wp_filesystem_direct, 'put_contents' )) );
		$this->assertTrue( $test_constructor );
	}

	/**
	 * Test for wpss_add_scripts()
	 */
	public function test_wpss_add_scripts(){
		global $wp_scripts;

		$wpss = WP_Simple_Sitemap::get_instance();
		$_GET['page'] = 'wp-simple-sitemap';
		$wpss->wpss_add_scripts();

		// Check if the scripts are enqueued, wp_script_is will return true if they are enqueued.
		$enqueued_wpss_script = wp_script_is( 'wpss-script' );

		// $wp_scripts contains the data for all the registered scripts.
		$wpss_data = $wp_scripts->registered['wpss-script']->extra['data'];

		// Check if the $wpcoPostData contains admin-ajax.php in the url.
		$has_admin_ajax_path = strpos( $wpss_data, 'admin-ajax.php' );

		$test_result = ( $enqueued_wpss_script && $has_admin_ajax_path  );

		$this->assertTrue( $test_result );
	}



}
