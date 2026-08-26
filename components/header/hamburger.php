<?php
$menu = $args['menu'] ?? get_field( 'navigation', 'option' );
?>
<div class="header__hamburger" role="button" aria-label="Toggle navigation menu" aria-expanded="false" tabindex="0">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="header__mobile-menu" aria-hidden="true">
    <?php if ( $menu ) : ?>
        <div class="header__mobile-menu__list">
            <?php foreach ( $menu as $item ) : ?>
                <?php
                $name = $item['menu_item']['title'];
                $link = $item['menu_item']['url'];
                ?>
                <a href="<?= esc_url( $link ); ?>" class="header__mobile-menu__list__item">
                    <span><?= esc_html( $name ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
