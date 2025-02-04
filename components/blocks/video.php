<?php

if (get_field('is_preview')) { 
    $name = $block['name'];
    previewImage($name);
} else {

    $link = get_field('link');
    $subtitle = get_field('subtitle');
?>

<section class="video">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ($link): ?>
                    <div data-scroll class="video__container fade-up">
                        <?php
                        // Convert YouTube URL to embed URL
                        if (strpos($link, 'youtube.com') !== false) {
                            parse_str(parse_url($link, PHP_URL_QUERY), $query);
                            $link = 'https://www.youtube.com/embed/' . $query['v'];
                        }
                        ?>
                        <iframe src="<?= $link; ?>" frameborder="0" allow="autoplay" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                <?php if ($subtitle): ?>
                    <div class="video__subtitle pt-40">
                        <?= $subtitle; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php } ?>