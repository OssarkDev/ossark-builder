<?php
/**
 * Product Card Template (used in loops/archives).
 * Overrides: woocommerce/templates/content-product.php
 *
 * This is the individual product card shown in shop/archive grids.
 * Customize the markup here for your product cards.
 *
 * Hooks included (in order):
 * - woocommerce_before_shop_loop_item
 * - woocommerce_before_shop_loop_item_title
 * - woocommerce_shop_loop_item_title
 * - woocommerce_after_shop_loop_item_title
 * - woocommerce_after_shop_loop_item
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div class="col-4 col-md-6 col-sm-12">
    <div <?php wc_product_class('product-card', $product); ?>>

        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <!-- Product Image -->
        <div class="product-card__image">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </div>

        <!-- Product Info -->
        <div class="product-card__info">

            <!-- Title -->
            <div class="product-card__title">
                <?php
                /**
                 * Hook: woocommerce_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action('woocommerce_shop_loop_item_title');
                ?>
            </div>

            <!-- Price & Rating -->
            <div class="product-card__meta">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action('woocommerce_after_shop_loop_item_title');
                ?>
            </div>

        </div>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action('woocommerce_after_shop_loop_item');
        ?>

    </div>
</div>
