<?php
/**
 * Class SampleTest
 *
 * @package Wp_Simple_Sitemap
 */

/**
 * Sample test case.
 */
class Test_WP_Simple_Sitemap_Activator_And_Deactivator extends WP_UnitTestCase
{

	/**
	 * Test for wpss_plugin_requiremnet_check() function
	 */
	public function test_wpss_plugin_requiremnet_check()
	{
		$test_result = WP_Simple_Sitemap_Activator_And_Deactivator::wpss_plugin_requiremnet_check();

		$this->assertTrue($test_result);
	}

	/**
	 * Test for display_error_notice() function
	 */
	public function test_wpss_display_error_notice()
	{
		$test_result = has_action( 'admin_notices', ['Wp_Simple_Sitemap_Activator_And_Deactivator', 'display_error_notice'] );
		$test_result = ( false === $test_result );
		$this->assertTrue( $test_result );
	}

}
