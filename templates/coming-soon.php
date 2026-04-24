<?php
/*
    Template Name: Coming Soon
*/
?>

<?php get_header(); ?>

<section class="coming-soon">
    <div class="container">
        <div class="row">
            <div class="col-8-offset-2">
                <div class="coming-soon__content text-center">
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>