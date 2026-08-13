</main>

<?php get_part('footer'); ?>

<?php
    $footer_scripts = get_field('footer_scripts', 'option');
    if ($footer_scripts) {
        echo $footer_scripts;
    }
?>

<?php wp_footer(); ?>

</body>

</html>