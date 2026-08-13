<?php
$map  = $args['map'] ?? null;
$zoom = $args['zoom'] ?? ( ! empty( $map['zoom'] ) ? $map['zoom'] : 16 );

if ( empty( $map['lat'] ) || empty( $map['lng'] ) ) {
    return;
}
?>
<div class="acf-map" data-zoom="<?= esc_attr( $zoom ); ?>">
    <div class="marker" data-lat="<?= esc_attr( $map['lat'] ); ?>" data-lng="<?= esc_attr( $map['lng'] ); ?>">
        <?php if ( ! empty( $map['address'] ) ) : ?>
            <?= esc_html( $map['address'] ); ?>
        <?php endif; ?>
    </div>
</div>
