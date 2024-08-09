jQuery(document).ready(function ($) {
    // Set Tab 1 as active and display its content by default
    $('.ourstoryz-tab-link[href="#tab1"]').addClass('active');
    $('#tab1').show();

    // Handle tab click events
    var tabs = $('.ourstoryz-tab-link');
    var contents = $('.ourstoryz-tab-content');

    tabs.on('click', function (e) {
        e.preventDefault();

        tabs.removeClass('active');
        contents.hide();

        $(this).addClass('active');
        var activeTabContent = $($(this).attr('href'));
        activeTabContent.show();

        // If clicking on the second tab button
        if ($(this).attr('href') === '#tab2') {
            // Handle click event for the "Generate Token" button
            $('#generateTokenButton').on('click', function () {
                // Prompt the user to enter their password
                $('#error-message').hide();
                var password = prompt("Please enter your password:");

                // Ensure the user entered a password
                if (password) {
                    $.ajax({
                        url: ajaxurl, // assuming ajaxurl is defined by WordPress
                        method: 'POST',
                        data: {
                            action: 'generate_jwt_token',
                            password: password
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Token generation successful, do something with the token
                                var token = response.token;
                                console.log('Token:', token);
                                $('#auth-token-display').val(token);
                                // You can now use the token as needed
                            } else {
                                // Token generation failed, handle the error
                                var errorMessage = response.data;
                                $('#error-message').show(); // Show error message
                            }
                        },
                        error: function (xhr, status, error) {
                            // AJAX request failed
                            console.error('AJAX Error:', error);
                        }
                    });
                } else {
                    console.error('Password is required to generate the token.');
                }
            });
        }
    });

    // Handle API key form submission
    $('#google-maps-api-key-form').on('submit', function(e) {
        e.preventDefault();

        var apiKey = $('#google_maps_api_key').val();
        $('#error-message').text('');
        $('#success-message').hide();

        if (!apiKey) {
            $('#error-message').text('API key cannot be empty.');
            return;
        }

        $.ajax({
            url: ajaxurl, // WordPress provides the 'ajaxurl' variable for AJAX calls
            method: 'POST',
            data: {
                action: 'update_google_maps_api_key',
                google_maps_api_key: apiKey
            },
            success: function(response) {
                if (response.success) {
                    $('#success-message').text(response.data.message).fadeIn();

                    setTimeout(function() {
                        $('#success-message').fadeOut();
                    }, 5000);
                } else {
                    $('#error-message').text(response.data.message);
                }
            },
            error: function() {
                $('#error-message').text('An error occurred. Please try again.');
            }
        });
    });

    // Handle copy token event
    $('#copy-icon').on('click', function () {
        var tokenInput = document.getElementById('auth-token-display');
        tokenInput.select();
        tokenInput.setSelectionRange(0, 99999); // For mobile devices

        try {
            var successful = document.execCommand('copy');
        } catch (err) {
            console.error('Failed to copy token:', err);
            alert('Failed to copy token');
        }
    });
});
