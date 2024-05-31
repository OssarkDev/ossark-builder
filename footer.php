</main>

<?php get_template_part('components/parts/footer'); ?>

<?php
    $footer_scripts = get_field('footer_scripts', 'option');
    if ($footer_scripts) {
        echo $footer_scripts;
    }
?>

<?php wp_footer(); ?>

</body>

</html>