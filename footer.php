</main>

<footer id="footer">
    <?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    $address = get_field('company_address', 'option');
    $contact_phone = get_field('footer_phone', 'option');
    $contact_email = get_field('footer_email', 'option');
    $cookie_statement = get_field('cookie_statement', 'option');
    $privacy_policy = get_field('privacy_policy', 'option');
    ?>
    <div class="footer">
        <div class="container">
            <div class="row">

                <!-- logo -->
                <div class="col-3 footer__logo">
                    <a href="<?= get_site_url(); ?>">
                        <img src="<?= $logo['url']; ?>" alt="<?= $image['alt']; ?>">
                    </a>
                </div>

                <!-- address -->
                <div class="col-3">
                    <div class="footer__address">
                        <?= $address; ?>
                    </div>
                </div>

                <!-- contact -->
                <div class="col-3">
                    <div class="footer__contact">
                        <a href="tel:<?= $contact_phone; ?>">
                            <?= $contact_phone; ?>
                        </a>
                        <a href="mailto:<?= $contact_email; ?>">
                            <?= $contact_email; ?>
                        </a>
                    </div>
                </div>

                <!-- menu -->
                <div class="col-3">
                    <?php if($menu): ?>
                        <nav class="footer__nav">
                            <div class="footer__nav__list">
                                <?php foreach($menu as $item): ?>
                                    <a href="<?= $item['menu_item']['link'] ?>" class="footer__nav__list__item">
                                        <span>
                                            <?= $item['menu_item']['title'];; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
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