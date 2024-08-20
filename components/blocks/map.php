<?php 
if (get_field('is_preview')) { 
    $name = $block['name'];
    previewImage($name);
} else {
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style type="text/css">
.acf-map {
    width: 100%;
    height: 506px;
    border: none;
    border-radius: 10px;
    &:after {
        content: '';
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background-color: rgba(101, 168, 217, 0.1);
        z-index: 1;
        pointer-events: none;
    
    }
}
.acf-map img {
   max-width: inherit !important;
}
</style>

<?php
$google_maps_api_key = get_field('google_maps_api_key', 'option') ?: 'AIzaSyAemXQ9Y6TbPpPxjAAwhxt81UFqJvLjjDA';
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_maps_api_key; ?>"></script>
<script type="text/javascript">


(function( $ ) {

/**
 * initMap
 *
 * Renders a Google Map onto the selected jQuery element
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @return  object The map instance.
 */
function initMap( $el ) {

    // Find marker elements within map.
    var $markers = $el.find('.marker');

    // Create gerenic map.
    var mapArgs = {
        zoom        : $el.data('zoom') || 16,
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    // Add markers.
    map.markers = [];
    $markers.each(function(){
        initMarker( $(this), map );
    });

    // Center map based on markers.
    centerMap( map );

    // Return map instance.
    return map;
}

/**
 * initMarker
 *
 * Creates a marker for the given jQuery element and map.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @param   object The map instance.
 * @return  object The marker instance.
 */
function initMarker( $marker, map ) {

    // Get position from marker.
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');
    var latLng = {
        lat: parseFloat( lat ),
        lng: parseFloat( lng )
    };

    // Create a custom SVG icon.
    var icon = {
        url: theme_url + '/assets/icons/location-pin.svg' , // Path to your custom SVG marker
        scaledSize: new google.maps.Size(40, 40), // Size of the marker
        origin: new google.maps.Point(0, 0), // Origin point of the marker
        anchor: new google.maps.Point(40, 40) // Anchor point of the marker
    };

    // Create marker instance.
    var marker = new google.maps.Marker({
        position : latLng,
        map: map,
        icon: icon // Set custom SVG icon
    });

    // Append to reference for later use.
    map.markers.push( marker );

    // If marker contains HTML, add it to an infoWindow.
    if( $marker.html() ){

        // Create info window.
        var infowindow = new google.maps.InfoWindow({
            content: $marker.html()
        });

        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open( map, marker );
        });
    }
}

/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
function centerMap( map ) {

    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });

    // Case: Single marker.
    if( map.markers.length == 1 ){
        map.setCenter( bounds.getCenter() );

    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}

// Render maps on page load.
$(document).ready(function(){
    $('.acf-map').each(function(){
        var map = initMap( $(this) );
    });
});

})(jQuery);
</script>

<?php
if (isset($args)) {
    $location = $args['location'];
} else {
    $location = get_field('location');
}

if( $location ): ?>
    <section class="map">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="acf-map" data-zoom="16">
                        <div class="marker" data-lat="<?= esc_attr($location['lat']); ?>" data-lng="<?= esc_attr($location['lng']); ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php } ?>