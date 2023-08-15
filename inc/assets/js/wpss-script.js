/**
 *  global jQuery, ajaxurl, wpss_data
 */
jQuery(document).ready(function ($) {

	var loader = $('.wpss-loader-container').html();

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
				$('.wpss-results').html(loader);
			},
			complete: function () {
				$('.wpss-loader').remove();
			},
			error: function (request, error) {
				alert('OOPs!! Something Went Wrong, Please try again later.');
			},
			success: function (response) {
				$('.wpss-results').html(response.data);
			}
		});

	});

	/**
	 * The script to show the crawled URL
	 */
	$('body').on( 'click', '#wpss-view', function(){
		let view_nonce = $(this).data('nonce');
		$.ajax({
			data: {method: 'view_sitemap', action: 'wpss_ajax', wpss_nonce: view_nonce},
			type: 'post',
			url: ajaxurl,
			beforeSend: function () {
				$('.wpss-results').html(loader);
			},
			complete: function () {
				$('.wpss-loader').remove();
			},
			error: function (request, error) {
				alert('OOPs!! Something Went Wrong, Please try again later.');
			},
			success: function (response) {
				$('.wpss-results').html(response.data);
			}
		});

	});
});
