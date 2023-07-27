<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>
<header class="header" id="header">
<?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    $header_cta = get_field('header_cta', 'option');
    ?>
    <div class="container header__container">
        <div class="row">
            <div class="col-2 header__logo">
                <a href="<?= get_site_url(); ?>">
                    <img src="<?= esc_url($logo['url']); ?>" alt="<?= esc_attr($image['alt']); ?>">
                </a>
            </div>
            <?php if($menu): ?>
                <nav class="col-8 header__nav">
                    <div class="header__nav__list">
                        <?php foreach($menu as $item): ?>
                            <?php
                            $name = $item['menu_item']['title'];
                            $link = $item['menu_item']['link'];
                            ?>
                            <a href="<?= esc_url($link); ?>" class="header__nav__list__item">
                                <span>
                                    <?= esc_html($name); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </nav>
            <?php endif; ?>
            <?php if($header_cta): ?>
                <div class="col-2 header__cta">
                    <a href="<?= $header_cta['link']; ?>" target="<?= $header_cta['target'] ?: '_self'; ?>" class="btn">
                        <span>
                            <?= $header_cta['title']; ?>
                        </span>
                    </a>
                </div>
            <?php endif; ?>
            <!-- Hamburger Menu -->
            <div class="header__hamburger"></div>
        </div>
    </div>
</header>

<main id="main">