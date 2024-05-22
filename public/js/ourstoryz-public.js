// (function( $ ) {
// 	'use strict';

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

// })( jQuery );


jQuery(document).ready(function ($) {
	$('.wp-element-button').click(function (e) {
		e.preventDefault(); // Prevent default form submission

		var inputValue = $('#wp-block-search__input-1').val();
		// var newUrl = 'http://adhoc.test/find-your-event/?event=' + encodeURIComponent(inputValue);
		var baseUrl = window.location.origin;
		var newUrl = baseUrl + '/find-your-event/?event=' + encodeURIComponent(inputValue);
		window.open(newUrl, '_blank', 'noopener,noreferrer'); // Open the URL in a new window
	});
});









