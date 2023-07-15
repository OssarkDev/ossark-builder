<?php
/**
 * Block Name: Hero
 *
 */
?>


<?php // preview image for block
    if (get_field('is_example')) { ?>
        <img src="<?php echo get_template_directory_uri() .'/assets/block-previews/audio-block.jpg'?>" alt="Preview image for block" width="100%" height="auto"/>
<?php
    } else { ?>

<section>
    <div class="hero-block">
        <!-- 
            Your code here
        -->
    </div>
</section>

<?php } ?>