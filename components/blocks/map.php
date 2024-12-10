<?php 
    $google_maps_api_key = get_field('google_maps_api_key', 'option');
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_maps_api_key; ?>"></script>

<?php
if (isset($args)) {
    $location = $args['location'];
}

if( $location ): ?>
    <div class="acf-map" data-zoom="16">
        <div class="marker" data-lat="<?php echo esc_attr($location['lat']); ?>" data-lng="<?php echo esc_attr($location['lng']); ?>"></div>
    </div>
<?php endif; ?>