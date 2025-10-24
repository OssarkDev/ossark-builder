<?php 
if (get_field('is_preview')) { 
    $name = $block['name'];
    previewImage($name);
    return; // Exit early for preview
} else {
    $text = get_field('text');
?>

<section data-scroll class="text">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ($text): ?>
                    <div class="text__content">
                        <?= $text; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php } ?>