<?php 
if (isset($args)) {
    $title = $args['title'];
    $button = $args['button'];
} else {
    $title = get_field('title');
    $button = get_field('button');
}
?>

<section class="thank-you">
    <div class="container">
        <div class="row">
            <div class="col-8-offset-2">
                <?php if (!empty($title)) : ?>
                    <div class="thank-you__title">
                        <p class="p-large text-center"><?= $title; ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($button)) : ?>
                    <div class="thank-you__button mt-72">
                        <?php echo get_button($button, ''); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>