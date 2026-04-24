<?php get_header(); ?>

<section class="search-results">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">

                <div class="search-results__header">
                    <h1>Search results for: <em><?php the_search_query(); ?></em></h1>
                </div>

                <?php if (have_posts()) : ?>
                    <div class="search-results__list">
                        <?php while (have_posts()) : the_post(); ?>
                            <article class="search-results__item">
                                <a href="<?php the_permalink(); ?>">
                                    <h3><?php the_title(); ?></h3>
                                </a>
                                <p><?php echo excerpt(30); ?></p>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="search-results__pagination">
                        <?php the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => '&laquo; Previous',
                            'next_text' => 'Next &raquo;',
                        )); ?>
                    </div>
                <?php else : ?>
                    <div class="search-results__empty">
                        <p>No results found. Please try a different search term.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
