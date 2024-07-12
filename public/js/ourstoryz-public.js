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

        var inputValue = $('#wp-block-search__input-1').val().trim(); // Get the input value and trim any whitespace
        var errorMessage = 'Please enter a value before searching.'; // Error message text

        // Remove any existing error message
        $('#error-message').remove();

        if (inputValue === '') {
            // Create a new error message element
            var errorElement = $('<div id="error-message" style="color: red; margin-top: 5px;">' + errorMessage + '</div>');
            // Append the error message after the input field
            $('#wp-block-search__input-1').after(errorElement);
        } else {
            // var baseUrl = window.location.origin;
            // var newUrl = baseUrl + '/wpdev/find-your-event/?event=' + encodeURIComponent(inputValue);
            // var newUrl = baseUrl + '/find-your-event/?event=' + encodeURIComponent(inputValue);
            var baseUrl = window.location.href; // Example base URL
            var newUrl;
            if (baseUrl.startsWith( "http://adhoc.test/")) {
                newUrl = baseUrl + 'find-your-event/?event=' + encodeURIComponent(inputValue);
            } else if (baseUrl.startsWith("https://ourstoryz.com/wpdev/")) {
                newUrl = baseUrl + 'find-your-event/?event=' + encodeURIComponent(inputValue);
                console.log(newUrl);
            } else {
                // Default case, if needed
                newUrl = baseUrl + 'find-your-event/?event=' + encodeURIComponent(inputValue);
                console.log(newUrl)
            }


            window.open(newUrl, '_blank', 'noopener,noreferrer'); // Open the URL in a new window
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const eventLinks = document.querySelectorAll(".event-link");
    eventLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const eventId = this.getAttribute("data-event-id");

             
            // AJAX request
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: 'fetch_mini_website_template', // AJAX action name
                    security: ajax_object.ajax_nonce, // Nonce for security
                    event_id: eventId // Event ID
                },
                success: function (response) {
                    // Redirect to the second API URL
              
                    var baseUrl = window.location.href;
                    // window.location.href = `http://adhoc.test/our-storyz/?p=${response.data}&event_id=${eventId}`;
                    // window.location.href = `https://ourstoryz.com/wpdev/our-storyz/?p=${response.data}&event_id=${eventId}`
                    if (baseUrl .startsWith("http://adhoc.test/")) {
                        window.location.href = `our-storyz/?p=${response.data}&event_id=${eventId}`;
                    } else if (baseUrl.startsWith("https://ourstoryz.com/wpdev/")) {
                        window.location.href = `wpdev/our-storyz/?p=${response.data}&event_id=${eventId}`;
                    } else {
                        // Default case, if needed
                        window.location.href = `our-storyz/?p=${response.data}&event_id=${eventId}`;
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                }
            });
        });
    });
});



 


 
 
 
