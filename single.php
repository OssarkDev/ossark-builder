<?php
/*
    Single post page template
*/

setup_postdata($post);
?>

<?php get_header(); ?>

<?php the_content(); ?>

<?php get_footer(); ?>