/**
 *  global jQuery, ajaxurl, wpss_data
 */
jQuery(document).ready(function ($) {

	var loader = $('.wpss-loader');

	/**
	 * Script for crawl the home page
	 */
	$('body').on( 'click', '#wpss-run', function(){
		let run_nonce = $(this).data('nonce');
		$.ajax({
			data: {method: 'get_home_page_urls', action: 'wpss_ajax', wpss_nonce: run_nonce},
			type: 'post',
			url: ajaxurl,
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (request, error) {
				console.log(error);
				console.log(request);
				alert('OOPs!! Something Went Wrong, Please try again later.');
			},
			success: function (response) {

			}
		});

	});

	/**
	 * The script to show the crawled URL
	 */
	$('body').on( 'click', '#wpss-view', function(){
		let view_nonce = $(this).data('nonce');

		$.ajax({
			data: {method: 'display_crawled_urls', action: 'wpss_ajax', wpss_nonce: view_nonce},
			type: 'post',
			url: ajaxurl,
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (request, error) {
				alert('OOPs!! Something Went Wrong, Please try again later.');
			},
			success: function (response) {

			}
		});

	});
});
