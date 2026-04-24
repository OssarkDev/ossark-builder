<?php
/**
 * The Template for displaying product archives (shop page, categories, tags).
 * Overrides: woocommerce/templates/archive-product.php
 *
 * Hooks included (in order):
 * - woocommerce_before_main_content
 * - woocommerce_archive_description
 * - woocommerce_before_shop_loop
 * - woocommerce_before_shop_loop_item (per product)
 * - woocommerce_after_shop_loop
 * - woocommerce_after_main_content
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

get_header(); ?>

<section class="shop-archive">
    <div class="container">

        <?php
        /**
         * Hook: woocommerce_before_main_content.
         * Default: woocommerce_breadcrumb (removed in include/woocommerce.php)
         */
        do_action('woocommerce_before_main_content');
        ?>

        <!-- Archive Header -->
        <div class="row">
            <div class="col-12">
                <header class="shop-archive__header">
                    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                        <h1 class="shop-archive__title"><?php woocommerce_page_title(); ?></h1>
                    <?php endif; ?>

                    <?php
                    /**
                     * Hook: woocommerce_archive_description.
                     * Default: woocommerce_taxonomy_archive_description, woocommerce_product_archive_description
                     */
                    do_action('woocommerce_archive_description');
                    ?>
                </header>
            </div>
        </div>

        <?php if (woocommerce_product_loop()) : ?>

            <!-- Toolbar: Result count & Sorting -->
            <div class="row">
                <div class="col-12">
                    <div class="shop-archive__toolbar">
                        <?php
                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action('woocommerce_before_shop_loop');
                        ?>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row">
                <?php
                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();

                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action('woocommerce_shop_loop');

                        wc_get_template_part('content', 'product');
                    }
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="shop-archive__pagination">
                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                        ?>
                    </div>
                </div>
            </div>

        <?php else : ?>

            <!-- No Products Found -->
            <div class="row">
                <div class="col-12">
                    <?php
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action('woocommerce_no_products_found');
                    ?>
                </div>
            </div>

        <?php endif; ?>

        <?php
        /**
         * Hook: woocommerce_after_main_content.
         */
        do_action('woocommerce_after_main_content');
        ?>

    </div>
</section>

<?php get_footer(); ?>
