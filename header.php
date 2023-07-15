<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
    <!-- <?php // the_field('header_scripts','option'); ?> -->
</head>
<body>
<header class="header" id="header">
<?php
    $menu = get_field('navigation', 'option');
    $logo = get_field('logo', 'option');
    ?>
    <div class="container header__container">
        <div class="row">
            <div class="col-4 header__logo">
                <a href="<?php echo get_site_url(); ?>">
                    <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                </a>
            </div>
            <div class="col-8">
                <?php if($menu): ?>
                    <nav class="header__nav">
                        <ul class="header__nav__list">
                            <?php foreach($menu as $item): ?>
                                <?php
                                $name = $item['menu_item']['title'];
                                $link = $item['menu_item']['link'];
                                ?>
                                <li class="header__nav__list__item">
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
</header>

<main id="main">