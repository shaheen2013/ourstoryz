// (function( $ ) {
// 	'use strict';

// 	/**
// 	 * All of the code for your admin-facing JavaScript source
// 	 * should reside in this file.
// 	 *
// 	 * Note: It has been assumed you will write jQuery code here, so the
// 	 * $ function reference has been prepared for usage within the scope
// 	 * of this function.
// 	 *
// 	 * This enables you to define handlers, for when the DOM is ready:
// 	 *
// 	 * $(function() {
// 	 *
// 	 * });
// 	 *
// 	 * When the window is loaded:
// 	 *
// 	 * $( window ).load(function() {
// 	 *
// 	 * });
// 	 *
// 	 * ...and/or other possibilities.
// 	 *
// 	 * Ideally, it is not considered best practise to attach more than a
// 	 * single DOM-ready or window-load handler for a particular page.
// 	 * Although scripts in the WordPress core, Plugins and Themes may be
// 	 * practising this, we should strive to set a better example in our own work.
// 	 */

// })( jQuery );


jQuery(document).ready(function($) {
    $('.capture-screenshot-button').on('click', function(e) {
        e.preventDefault();
        var post_id = $(this).data('post-id');
        
        // Construct URL with the post ID (instead of title)
        var previewUrl = '/?p=' + post_id; // Example URL format
        
        // Open a new tab with the post preview page
        var newWindow = window.open(previewUrl, '_blank');

        // Wait for the new tab to load completely
        newWindow.addEventListener('load', function() {
            // After the new tab is fully loaded, capture screenshot
            captureFullPageScreenshot(newWindow, post_id);
        });
    });

    function captureFullPageScreenshot(newWindow, post_id) {
        // Capture screenshot using html2canvas in the new tab
		var postSection = newWindow.document.getElementById('post-' + post_id); // Example: Assuming the post content has an element with ID 'post-{post_id}'
        html2canvas(postSection, {
            scrollX: 0,
            scrollY: 0,
            useCORS: false,
            allowTaint: false,
            windowWidth: newWindow.innerWidth,
            windowHeight: newWindow.document.documentElement.scrollHeight
        }).then(function(canvas) {
            var screenshotData = canvas.toDataURL();
            saveScreenshot(post_id, screenshotData);
            
            // Close the new tab after capturing screenshot
            newWindow.close();
        });
    }

    function saveScreenshot(post_id, screenshotData) {
		// AJAX call to save the screenshot
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'save_screenshot',
				post_id: post_id,
				screenshot_data: screenshotData
			},
			success: function(response) {
				console.log(response);
				if (response.success) {
					// Reload the current page
					window.location.reload();
				} else {
					// Handle error if needed
					console.error('Error occurred while saving screenshot.');
				}
				 
	
			}
		});
	}
});
