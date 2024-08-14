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

jQuery(document).ready(function ($) {

    $('.capture-screenshot-button').on('click', function (e) {
        e.preventDefault();
        var post_id = $(this).data('post-id');

        // Construct URL with the post ID (instead of title)
        var previewUrl = '/wpdev/?p=' + post_id; // Example URL format

        // Open a new tab with the post preview page
        var newWindow = window.open(previewUrl, '_blank');

        // Wait for the new tab to load completely
        newWindow.addEventListener('load', function () {
            // After the new tab is fully loaded, capture screenshot
            captureFullPageScreenshot(newWindow, post_id);
        });
    });

    function captureFullPageScreenshot(newWindow, post_id) {
        // Capture screenshot using html2canvas in the new tab
        var postSection = newWindow.document.getElementById('page'); // Example: Assuming the post content has an element with ID 'post-{post_id}'
        html2canvas(postSection, {
            scrollX: 0,
            scrollY: 0,
            useCORS: false,
            allowTaint: false,
            windowWidth: newWindow.innerWidth,
            windowHeight: newWindow.document.documentElement.scrollHeight
        }).then(function (canvas) {
            var screenshotData = canvas.toDataURL();

            var resizedCanvas = document.createElement('canvas');
            resizedCanvas.width = 140;
            resizedCanvas.height = 200;
            var ctx = resizedCanvas.getContext('2d');

            // Draw the original screenshot resized into the new canvas
            ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, 140, 200);
            var resizedData = resizedCanvas.toDataURL();

            saveScreenshot(post_id, screenshotData);

            cropScreenshot(post_id, resizedData);

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
            success: function (response) {
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

    function cropScreenshot(post_id, resizedData) {
        // AJAX call to save the screenshot
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'cropped_screenshot',
                post_id: post_id,
                screenshot_data: resizedData
            },
            success: function (response) {
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

    $('#submitBtn').on('click', function () {
        var value = $('#options').val();
        console.log(value);
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'save_custom_data',
                value: value
            },
            success: function (response) {
                $('#result').html(response);
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    $('#google-maps-api-key-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
    
        var apiKey = $('#google_maps_api_key').val().trim(); // Get and trim the API key value
        $('#error-message').text('');  // Clear any previous error messages
        $('#success-message').hide();  // Hide the success message
    
        // Check if the API key is empty
        if (!apiKey) {
            alert('API key cannot be empty.'); // Show alert for empty API key
            return; // Stop the form submission
        }
    
        // Make AJAX request
        $.ajax({
            url: ajaxurl, // WordPress provides the 'ajaxurl' variable for AJAX calls
            method: 'POST',
            data: {
                action: 'update_google_maps_api_key',
                google_maps_api_key: apiKey
            },
            success: function (response) {
                if (response.success) {
                    $('#success-message').text(response.data.message).fadeIn();
                    setTimeout(function () {
                        $('#success-message').fadeOut();
                    }, 5000);
                } else {
                    $('#error-message').text(response.data.message);
                }
            },
            error: function () {
                $('#error-message').text('An error occurred. Please try again.');
            }
        });
    });
    
    

});






