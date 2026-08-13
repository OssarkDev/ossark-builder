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
                    <a href="<?= get_site_url(); ?>" class="header__logo" aria-label="Home">
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
                    <?= get_button($header_cta , ''); ?>
                </div>
            <?php endif; ?>

            <?php get_template_part('components/header/hamburger', '', ['menu' => $menu]); ?>

        </div>
    </div>
</header>