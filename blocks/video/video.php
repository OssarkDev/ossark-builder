<?php
$link     = get_field( 'link' );
$subtitle = get_field( 'subtitle' );
?>

<section class="video">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ( $link ) : ?>
                    <div data-scroll class="video__container fade-up">
                        <?php
                        if ( strpos( $link, 'youtube.com' ) !== false || strpos( $link, 'youtu.be' ) !== false ) {
                            $link = returnYoutubeUrl( $link );
                        }
                        ?>
                        <iframe src="<?= esc_url( $link ); ?>" frameborder="0" allow="autoplay" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                <?php if ( $subtitle ) : ?>
                    <div class="video__subtitle pt-40">
                        <?= $subtitle; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
