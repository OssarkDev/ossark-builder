<?php
/*
    404 template
*/

$title = get_field('404_title', 'option');
$subtitle = get_field('404_subtitle', 'option');
$image = get_field('404_image', 'option');
$button = get_field('404_button', 'option');
?>

<?php get_header(); ?>

<section class="page-404">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="page-404__content">
                    <?php if ($title) : ?>
                        <div class="page-404__title">
                            <h1><?= $title; ?></h1>
                        </div>
                    <?php endif; ?>
                    <?php if ($subtitle) : ?>
                        <div class="page-404__subtitle">
                            <p><?= $subtitle; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($button) : ?>
                        <div class="page-404__button">
                            <?= ossarkButton($button, ''); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-6">
                <?php if ($image) : ?>
                    <div class="page-404__image">
                        <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>