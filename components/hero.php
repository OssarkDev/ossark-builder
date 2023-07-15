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
                        <h1><?php echo $title; ?></h1>
                    </div>
                <?php endif; ?>
                <?php if($content): ?>
                    <div class="hero__content">
                        <p><?php echo $content; ?></p>
                    </div>
                <?php endif; ?>
                <div class="hero__buttons">
                    <?php if($cta): ?>
                        <a href="<?php echo $cta['url']; ?>" class="btn"><?php echo $cta['title']; ?></a>
                    <?php endif; ?>
                    <?php if($secondary_cta): ?>
                        <a href="<?php echo $secondary_cta['url']; ?>" class="btn"><?php echo $secondary_cta['title']; ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>