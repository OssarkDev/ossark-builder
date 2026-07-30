<?php
$title = get_field( 'title' );
?>
<section data-scroll class="hero">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ( $title ) : ?>
                    <h2 class="hero__title"><?= esc_html( $title ); ?></h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
