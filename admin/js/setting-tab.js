jQuery(document).ready(function($) {
    // Set Tab 1 as active and display its content by default
    $('.ourstoryz-tab-link[href="#tab1"]').addClass('active');
    $('#tab1').show();

    // Handle tab click events
    $('.ourstoryz-tab-link').on('click', function(e) {
        e.preventDefault();
        $('.ourstoryz-tab-link').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).attr('href');
        $('.ourstoryz-tab-content').hide();
        $(tab).show();
    });
});
