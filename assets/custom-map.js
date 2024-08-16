jQuery(document).ready(function($) {
    $('#open-map-modal').click(function() {
        $('#map-modal').show();
        initializeAutocomplete();
    });

    $('#close-map-modal').click(function() {
        $('#map-modal').hide();
    });

    $(window).click(function(event) {
        if (event.target.id == 'map-modal') {
            $('#map-modal').hide();
        }
    });

    function initializeAutocomplete() {
        var input = document.getElementById('location-input');
        var autocomplete = new google.maps.places.Autocomplete(input);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var marker = new google.maps.Marker({
            map: map
        });

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        });
    }
});
