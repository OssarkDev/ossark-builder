<?php
/*
    Template Name: Coming Soon
*/

$title = get_field('coming_soon_title', 'option');
$description = get_field('coming_soon_description', 'option');
$content = get_field('coming_soon_content', 'option');
$logo = get_field('coming_soon_logo', 'option');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>

<main>

<section class="coming-soon">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($title): ?>
                    <div class="coming-soon__title">
                        <?php echo $title; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <?php if ($logo): ?>
                    <div class="coming-soon__logo">
                        <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>">
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-8-offset-4">
                <?php if ($description): ?>
                    <div class="coming-soon__description">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
                <?php if ($content): ?>
                    <div class="coming-soon__content">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="coming-soon__footer">
        <p>Made by</p>
        <a href="https://ossark.ie/?utm_source=coming_soon&utm_medium=malachi_cullen" target="_blank">
            <?php echo get_inline_svg('images/ossark-logo.svg'); ?>
        </a>
    </div>
</section>

</main>
</body>
<?php wp_footer(); ?>
</html>