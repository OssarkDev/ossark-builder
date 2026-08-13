<?php
$menu = $args['menu'] ?? get_field( 'navigation', 'option' );
?>
<div class="header__hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="header__mobile-menu">
    <?php if ( $menu ) : ?>
        <div class="header__mobile-menu__list">
            <?php foreach ( $menu as $item ) : ?>
                <?php
                $name = $item['menu_item']['title'];
                $link = $item['menu_item']['url'];
                ?>
                <a href="<?= $link; ?>" class="header__mobile-menu__list__item">
                    <span><?= $name; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
