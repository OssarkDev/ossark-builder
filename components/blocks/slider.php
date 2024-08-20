<?php 
$title = get_field('title');
$slider = get_field('slider');

if (get_field('is_preview')) { 
    $name = $block['name'];
    previewImage($name);
} else {
?>



<section class="slider-block">
    <div class="container">
        <?php if ($title) : ?>
            <div class="row">
                <div class="col-12">
                    <h2 class="slider-block__title"><?= $title; ?></h2>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <div class="slider__arrows">
                        <div class="slider__numbers"></div>
                        <div class="slider__arrows__prev">
                            <?php // echo get_inline_svg('slider-prev-arrow.svg'); ?>
                        </div>
                        <div class="slider__arrows__next">
                            <?php // echo get_inline_svg('slider-next-arrow.svg'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($slider) : ?>
            <div class="slider">
                <?php foreach ($slider as $slide) : ?>
                    <div class="slider__item"></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php } ?>