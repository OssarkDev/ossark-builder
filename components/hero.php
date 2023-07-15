<?php
/**
 * Block Name: Hero
 *
 */

$image = get_field('image');
$title = get_field('title');
$content = get_field('content');
$cta = get_field('cta');
$secondary_cta = get_field('secondary_cta');
?>

<section>
    <?php if($image): ?>
        <div style="background: url('<?= $image['url']; ?>') center center; background-size: cover; background-repeat: no-repeat;" class="hero">
            <div class="container hero__container">
                <?php if($title): ?>
                    <div class="hero__title">
                        <h1><?= $title; ?></h1>
                    </div>
                <?php endif; ?>
                <?php if($content): ?>
                    <div class="hero__content">
                        <p><?= $content; ?></p>
                    </div>
                <?php endif; ?>
                <div class="hero__buttons">
                    <?php if($cta): ?>
                        <a href="<?= $cta['url']; ?>" class="btn"><?= $cta['title']; ?></a>
                    <?php endif; ?>
                    <?php if($secondary_cta): ?>
                        <a href="<?= $secondary_cta['url']; ?>" class="btn"><?= $secondary_cta['title']; ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>