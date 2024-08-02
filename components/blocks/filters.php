<?php 
    $filter = get_field('filter');
    $posts = get_posts(array(
        'post_type' => $filter,
        'posts_per_page' => -1,
    ));
?>

<section class="filters">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="filters__title">
                    <h2>Filters</h2>
                </div>
                <div class="filters__list">
                    <ul>
                        <li>All</li>
                        <?php 
                        $categories = get_categories();
                        foreach ($categories as $category) :
                            $item = $category->name;
                        ?>
                            <li<?= $item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-9">
                <div class="ajax-container__filter"></div>
            </div>
        </div>
    </div>
</section>