<?php
/*
    Single post page template
*/
?>

<?php get_header(); ?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>