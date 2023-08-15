<?php
/**
 * Class SampleTest
 *
 * @package Wp_Simple_Sitemap
 */

/**
 * Sample test case.
 */
class Test_WP_Simple_Sitemap_Ajax extends WP_Ajax_UnitTestCase {
	public function set_up() {
		parent::set_up();

		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Create a user with nicename 'Bala'
		$user_id = $this->factory->user->create( [
			'user_nicename' => 'Bala',
			'role'          => 'administrator',
		] );

		// Set current user as 'Bala' so this user will have capability 'manage_options'
		wp_set_current_user( $user_id );
	}

	/**
	 * Helper to keep it DRY
	 *
	 * @param string $action Action.
	 */
	protected function make_ajax_call( $action ) {
		// Make the request.
		try {
			$this->_handleAjax( $action );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
	}

	/**
	 * Testing successful ajax_get_home_page_urls
	 *
	 */
	function test_ajax_get_home_page_urls() {

		$_POST =  array(
			'method' => 'get_home_page_urls',
			'wpss_nonce' => wp_create_nonce( 'wpss-run' ),
			'action' => 'wpss_ajax',
		);
		$this->make_ajax_call( 'wpss_ajax' );
		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$method_not_accessible = strpos( $response['data'], 'Requested method not exists' );
		$authendication_failed = strpos( $response['data'], 'Oops! Authentication failed, Please try again' );

		if ( $method_not_accessible || $authendication_failed ) {
			$this->assertTrue( false );
		}
		$this->assertTrue( $response['success'] );
	}

	/**
	 * Testing successful ajax_view_sitemap
	 */
	function test_ajax_view_sitemap() {
		$_POST =  array(
			'method' => 'view_sitemap',
			'wpss_nonce' => wp_create_nonce( 'wpss-view' ),
			'action' => 'wpss_ajax',
		);
		$this->make_ajax_call( 'wpss_ajax' );
		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$this->assertTrue( $response['success'] );
	}

	/**
	 * Testing successful ajax_view_sitemap
	 */
	function test_ajax_wp_simple_sitemap() {
		$wpss_ajax = WP_Simple_Sitemap_Ajax::get_instance();
		$content = $wpss_ajax->wp_simple_sitemap();
		$content = !empty($content);
		$this->assertTrue( $content );
	}

}
