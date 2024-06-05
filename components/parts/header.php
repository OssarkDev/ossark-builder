<header class="header" id="header">
<?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    $header_cta = get_field('header_cta', 'option');
?>
    <div class="container header__container">
        <div class="row">
            <div class="col-2">
                <?php if($logo): ?>
                    <a href="<?= get_site_url(); ?>" class="header__logo">
                        <img src="<?= esc_url($logo['url']); ?>" alt="<?= $logo['alt']; ?>">
                    </a>
                <?php endif; ?>
            </div>
            <?php if($menu): ?>
                <nav class="col-8 header__nav">
                    <div class="header__nav__list">
                        <?php foreach($menu as $item): ?>
                            <?php
                            $name = $item['menu_item']['title'];
                            $link = $item['menu_item']['url'];
                            ?>
                            <a href="<?= $link; ?>" class="header__nav__list__item">
                                <span>
                                    <?= $name; ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </nav>
            <?php endif; ?>
            <?php if($header_cta): ?>
                <div class="col-2 header__cta">
                    <?= ossarkButton($header_cta , ''); ?>
                </div>
            <?php endif; ?>

            <div class="header__hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <div class="header__mobile-menu">
                <?php if($menu): ?>
                    <div class="header__mobile-menu__list">
                        <?php foreach($menu as $item): ?>
                            <?php
                            $name = $item['menu_item']['title'];
                            $link = $item['menu_item']['url'];
                            ?>
                            <a href="<?= $link; ?>" class="header__mobile-menu__list__item">
                                <span>
                                    <?= $name; ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</header>