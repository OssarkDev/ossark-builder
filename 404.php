<?php
/*
    404 template
*/
$args = [
    'title' => get_field('404_title', 'option'),
    'button' => get_field('404_button', 'option'),
];
?>

<?php get_header(); ?>

<?php get_block('thank-you', $args); ?>

<?php get_footer(); ?>