<?php
/**
 * Class SampleTest
 *
 * @package Wp_Simple_Sitemap
 */

/**
 * Sample test case.
 */
class Test_WP_Simple_Sitemap_Admin extends WP_UnitTestCase
{

	/**
	 * Test for constructor function
	 */
	public function test_construct()
	{
		$wpss_admin = WP_Simple_Sitemap_Admin::get_instance();
		//checking the action is registered or not.
		$add_admin_menu_page = has_action('admin_menu', array($wpss_admin, 'add_admin_menu_page'));
		//default priority will be 10
		$test_constructor = (10 === $add_admin_menu_page);
		$this->assertTrue($test_constructor);
	}
}
