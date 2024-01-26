<?php
/*
    Default Archive page template
*/
?>

<?php get_header(); ?>

<?php wp_reset_query(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="section">
    <div class="row">
        <div class="container">
            <div class="col-12">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</div>

<?php endwhile; endif; ?>
<?php wp_reset_query(); ?>

<?php get_footer(); ?>
