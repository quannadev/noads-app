(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
jQuery(document).ready(function($) {
	$('#get-link-form').submit(function() {
		var linkUrl = $('#link_url').val();

		$.ajax({
			type: 'post',
			url: get_link_ajax_object.ajax_url,
			data: {
				action: 'handle_link_request',
				link_url: linkUrl,
				nonce: $('#get_link_nonce').val()
			},
			success: function(response) {
				$('#link-result').html(response);
			}
		});

		return false; // Prevent the form from submitting in the traditional way
	});
});
