jQuery(document).ready(function($) {
    // Set Tab 1 as active and display its content by default
    $('.ourstoryz-tab-link[href="#tab1"]').addClass('active');
    $('#tab1').show();

    // Handle tab click events
    $('.ourstoryz-tab-link').on('click', function(e) {
        e.preventDefault();

        // Remove 'active' class from all tab links and hide all tab content
        $('.ourstoryz-tab-link').removeClass('active');
        $(this).addClass('active');

        var tab = $(this).attr('href');
        $('.ourstoryz-tab-content').hide();
        $(tab).show();

        // If clicking on the second tab button
        if ($(this).attr('href') === '#tab2') {
            // Handle click event for the "Generate Token" button
            $('#generate-token-button').off('click').on('click', function(e) {
                e.preventDefault();

                // Call the server to generate JWT token for logged-in user
                $.ajax({
                    url: ajaxurl, // WordPress AJAX endpoint
                    type: 'POST',
                    data: {
                        action: 'generate_jwt_token'
                    },
                    success: function(response) {
                        // Display the generated auth token
                        $('#auth-token-display').val(response);
                    },
                    error: function() {
                        console.error('Error generating JWT token');
                    }
                });
            });
        }
    });

    // Handle click event for the "Copy Token" button
    $('#copy-token-button').on('click', function() {
        // Select and copy token value from input field
        $('#auth-token-display').select();
        document.execCommand('copy');
        alert('Token copied to clipboard!');
    });
});
