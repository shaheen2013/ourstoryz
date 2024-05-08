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
// jQuery(document).ready(function($) {
//     $('.capture-screenshot-button').on('click', function(e) {
//         e.preventDefault();
//         var post_id = $(this).data('post-id');

//         // Capture screenshot using html2canvas
//         html2canvas(document.querySelector('.post-' + post_id), {
//             scrollX: 0,
//             scrollY: -window.scrollY // Capture entire document regardless of scroll position
//         }).then(function(canvas) {
//             var screenshotData = canvas.toDataURL(); // Convert canvas to base64 encoded image data
//             saveScreenshot(post_id, screenshotData);
//         });
//     });





	

//     function saveScreenshot(post_id, screenshotData) {

// 		console.log(screenshotData)
//         // AJAX call to save the screenshot
//         $.ajax({
//             type: 'POST',
//             url: ajax_object.ajax_url,
//             data: {
//                 action: 'save_screenshot',
//                 post_id: post_id,
//                 screenshot_data: screenshotData
//             },
//             success: function(response) {
// 				console.log(response);
//                 // var data = JSON.parse(response);
//                 // if (data && data.success) {
//                 //     // Append the screenshot image to the custom column
//                 //     $('td.column-custom_screenshot[data-post-id="' + post_id + '"]').html('<img src="' + data.screenshot_url + '" width="100" height="auto" />');
//                 // }
//             }
//         });
//     }
// });


// work 
// jQuery(document).ready(function($) {
//     $('.capture-screenshot-button').on('click', function(e) {
//         e.preventDefault();
//         var post_id = $(this).data('post-id');
		 
//         // Open a new tab with the post preview page
//         var previewUrl = '/imran';
//         var newWindow = window.open(previewUrl, '_blank');

//         // Wait for the new tab to load completely
//         newWindow.addEventListener('load', function() {
//             // After the new tab is fully loaded, capture screenshot
//             captureFullPageScreenshot(newWindow, post_id);
//         });
//     });

//     function captureFullPageScreenshot(newWindow, post_id) {
//         // Capture screenshot using html2canvas in the new tab
//         html2canvas(newWindow.document.body, {
//             scrollX: 0,
//             scrollY: 0, // Ensure no initial scrolling for accurate capture
//             useCORS: false, // Allow cross-origin images to be captured
//             allowTaint: false, // Allow images from other domains to be captured
//             windowWidth: newWindow.innerWidth, // Set canvas width to full page width
//             windowHeight: newWindow.document.documentElement.scrollHeight // Set canvas height to full page height
//         }).then(function(canvas) {
//             var screenshotData = canvas.toDataURL(); // Convert canvas to base64 encoded image data
//             saveScreenshot(post_id, screenshotData);
            
//             // Close the new tab after capturing screenshot
//             newWindow.close();
//         });
//     }

//     function saveScreenshot(post_id, screenshotData) {
//         // AJAX call to save the screenshot
//         $.ajax({
//             type: 'POST',
//             url: ajaxurl,
//             data: {
//                 action: 'save_screenshot',
//                 post_id: post_id,
//                 screenshot_data: screenshotData
//             },
//             success: function(response) {
//                 console.log(response);
//                 var data = JSON.parse(response);
//                 if (data && data.success) {
//                     // Update the post thumbnail with the saved screenshot
//                     updatePostThumbnail(post_id, data.screenshot_url);
//                 }
//             }
//         });
//     }

//     function updatePostThumbnail(post_id, screenshot_url) {
//         // Assuming you want to update a specific post thumbnail in the admin column
//         $('td.column-custom_preview[data-post-id="' + post_id + '"]').html('<img src="' + screenshot_url + '" width="100" height="auto" />');
//     }
// });

jQuery(document).ready(function($) {
    $('.capture-screenshot-button').on('click', function(e) {
        e.preventDefault();
        var post_id = $(this).data('post-id');
        
        // Retrieve the post title via AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_post_title',
                post_id: post_id
            },
            success: function(response) {
                var postTitle = response; // Assuming response is the post title
                var previewUrl = '/' + encodeURIComponent(postTitle); // Construct URL with encoded post title

                // Open a new tab with the post preview page
                var newWindow = window.open(previewUrl, '_blank');

                // Wait for the new tab to load completely
                newWindow.addEventListener('load', function() {
                    // After the new tab is fully loaded, capture screenshot
                    captureFullPageScreenshot(newWindow, post_id);
                });
            }
        });
    });

    function captureFullPageScreenshot(newWindow, post_id) {
        // Capture screenshot using html2canvas in the new tab
        html2canvas(newWindow.document.body, {
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
                
            }
        });
    }

    function updatePostThumbnail(post_id, screenshot_url) {
        // Assuming you want to update a specific post thumbnail in the admin column
        $('td.column-custom_preview[data-post-id="' + post_id + '"]').html('<img src="' + screenshot_url + '" width="100" height="auto" />');
    }
});

