<footer id="footer">
    <?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    $address = get_field('company_address', 'option');
    $contact_phone = get_field('footer_phone', 'option');
    $contact_email = get_field('footer_email', 'option');
    $footer_bottom_links = get_field('footer_bottom_links', 'option');
    $social = get_field('social', 'option');
    ?>
    <div class="footer">
        <div class="container">
            <div class="row footer__row">

                <!-- logo -->
                <?php if ($logo): ?>
                <div class="col-2 col-md-6 footer__logo">
                    <a href="<?= get_site_url(); ?>">
                        <img src="<?= $logo['url'] ?? ''; ?>" alt="<?= $image['alt'] ?? ''; ?>">
                    </a>
                </div>
                <?php endif; ?>

                <!-- address -->
                <div class="col-2 col-md-6">
                    <?php if($address): ?>
                        <div class="footer__address">
                            <?= $address; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- contact -->
                <div class="col-3 col-sm-6">
                    <div class="footer__contact">
                        <?php if($contact_phone): ?>
                            <a href="tel:<?= $contact_phone; ?>">
                                <?= $contact_phone; ?>
                            </a>
                        <?php endif; ?>
                        <?php if($contact_email): ?>
                            <a href="mailto:<?= $contact_email; ?>">
                                <?= $contact_email; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- social -->
                <div class="col-2 col-md-6">
                    <div class="footer__social">
                        <?php if($social): ?>
                            <?php foreach($social as $item): ?>
                                <?php $icon = $item['icon']; ?>
                                <?php $link = $item['link']; ?>
                                <a href="<?= $link['url']; ?>" target="_blank" class="footer__social__icon">
                                    <?php  echo file_get_contents($icon['url']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- menu -->
                <div class="col-3 col-md-6">
                    <?php if($menu): ?>
                        <nav class="footer__nav">
                            <div class="footer__nav__list">
                                <?php foreach($menu as $item): 
                                    $link = $item['menu_item']['url'];
                                    $title = $item['menu_item']['title'];    
                                    ?>
                                    <a href="<?= $link;?>" class="footer__nav__list__item">
                                        <span>
                                            <?= $title; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="footer__bottom">
                        <div class="footer__bottom__copyright">
                            <p>&copy; <?= date('Y'); ?> <?= get_bloginfo('name'); ?>. All rights reserved.</p>
                            <p>Made by <a href="https://ossark.ie/?utm_source=<?= get_bloginfo('name'); ?>&utm_medium=Client%20Website%20Refferal" target="_blank">Ossark</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>