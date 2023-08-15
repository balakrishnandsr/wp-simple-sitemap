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
		//checking the action is registered or not.
		$enqueue_scripts = has_action( 'admin_enqueue_scripts', array($wpss, 'wpss_add_scripts' )  );
		//checking WP_Filesystem_Direct object and put_contents are accessible.
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		$wp_filesystem_direct = new WP_Filesystem_Direct( new StdClass() );
		$test_constructor = ( 100 === $enqueue_scripts && is_callable( array( $wp_filesystem_direct, 'put_contents' )) );
		$this->assertTrue( $test_constructor );
	}

	/**
	 * Test for wpss_schedule_event()
	 */
	public function test_wpss_schedule_event(){
		$wpss = WP_Simple_Sitemap::get_instance();
		$wpss->wpss_schedule_event();
		//checking the action is registered or not.
		$event_scheduled = has_action( 'wpss_cron_schedules', [ 'WP_Simple_Sitemap', 'wpss_refresh_sitemap' ]  );
		//default priority will be 10
		$event_scheduled = ( 10 === $event_scheduled );
		$this->assertTrue( $event_scheduled );
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

	/**
	 * Test for get_homepage_url()
	 */
	public function test_get_homepage_url(){
		$wpss = WP_Simple_Sitemap::get_instance();
		$homepage_url = $wpss::get_homepage_url();
		$test_result = ( home_url() === $homepage_url );
		$this->assertTrue( $test_result );
	}

	/**
	 * Test for get_urls_from_content()
	 */
	public function test_get_urls_from_content(){
		$wpss = WP_Simple_Sitemap::get_instance();
		$content = $wpss::get_urls_from_content();
		$this->assertTrue( is_array($content) );
	}

	/**
	 * Test for wpss_refresh_sitemap(), get_urls_from_content(), create_sitemap_html()
	 */
	public function test_wpss_refresh_sitemap(){
		$wpss = WP_Simple_Sitemap::get_instance();
		$content = $wpss::wpss_refresh_sitemap();
		$has_failed_message = strpos( $content, 'Oops! Unable to create content.' );
		$has_oops_message = strpos( $content, 'Oops! Something Went Wrong, Please try again.' );
		if ( $has_failed_message || $has_oops_message) {
			$this->assertTrue( false );
		}
		$this->assertTrue( true );
	}

	/**
	 * Test for unique_multidimensional_array().
	 *
	 * @return void
	 */
	public function test_unique_multidimensional_array(){
		$wpss = WP_Simple_Sitemap::get_instance();
		$test_array = array(

			0 => array("id"=>"1", "name"=>"Bala",    "num"=>"12345"),

			1 => array("id"=>"2", "name"=>"Bavishna", "num"=>"67890"),

			2 => array("id"=>"1", "name"=>"Kavitha",  "num"=>"74185"),

		);
		$content = $wpss->unique_multidimensional_array( $test_array, 'id' );
		if( isset($content[2]['name']) ){
			$this->assertTrue( false );
		}
		$this->assertTrue( true );
	}

}
