<?php 
if (get_field('is_preview')) { 
    $name = $block['name'];
    previewImage($name);
} else {
?>

<section class="ajax-example">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Test Ajax</h2>
                <button class="ajax-button" data-id="123">Title 1</button>
                <button class="ajax-button" data-id="567">Title 2</button>
                <div class="ajax-container"></div>
            </div>
        </div>
    </div>
</section>

<?php } ?>