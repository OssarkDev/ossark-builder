</main>

<footer id="footer">
    <?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    $address = get_field('company_address', 'option');
    $cookie_statement = get_field('cookie_statement', 'option');
    $privacy_policy = get_field('privacy_policy', 'option');
    ?>
    <div class="footer">
        <div class="container pt-4 pb-4">
            <div class="row">
                <!-- logo -->
                <div class="col-4 footer__logo">
                    <a href="<?php echo get_site_url(); ?>">
                        <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                    </a>
                </div>
                <!-- contact -->
                <div class="col-4"></div>
                <!-- menu -->
                <div class="col-4">
                    <?php if($menu): ?>
                        <nav class="footer__nav">
                            <ul class="footer__nav__list">
                                <?php foreach($menu as $item): ?>
                                    <?php
                                    $name = $item['menu_item']['title'];
                                    $link = $item['menu_item']['link'];
                                    ?>
                                    <li class="footer__nav__list__item">
                                        <a href="<?php echo esc_url($link); ?>">
                                            <?php echo esc_html($name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php 
    $enable_hotjar = get_field('enable_hotjar', 'option');
    $hotjar_script = get_field('hotjar_script', 'option');
    if($enable_hotjar && $hotjar_script) {
        echo $hotjar_script;
    }
?>

<?php wp_footer(); ?>

</body>

</html>