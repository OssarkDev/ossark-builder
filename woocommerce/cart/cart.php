<?php
/**
 * Cart Page Template
 * Overrides: woocommerce/templates/cart/cart.php
 *
 * Full cart page with product table, coupon, totals and proceed to checkout.
 * Uses WooCommerce functions for all cart logic — custom markup only.
 *
 * Hooks included:
 * - woocommerce_before_cart
 * - woocommerce_before_cart_table
 * - woocommerce_before_cart_contents
 * - woocommerce_cart_contents (per item)
 * - woocommerce_cart_coupon
 * - woocommerce_cart_actions
 * - woocommerce_after_cart_contents
 * - woocommerce_after_cart_table
 * - woocommerce_cart_collaterals
 * - woocommerce_after_cart
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<section class="cart-page">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <h1 class="cart-page__title"><?php esc_html_e('Your Cart', 'woocommerce'); ?></h1>

                <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

                    <?php do_action('woocommerce_before_cart_table'); ?>

                    <!-- Cart Items -->
                    <div class="cart-page__items">

                        <?php do_action('woocommerce_before_cart_contents'); ?>

                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>

                            <div class="cart-page__item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                <div class="row">

                                    <!-- Remove -->
                                    <div class="col-1 col-sm-2">
                                        <div class="cart-page__item__remove">
                                            <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                    /* translators: %s: product name */
                                                    esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name()))),
                                                    esc_attr($product_id),
                                                    esc_attr($_product->get_sku())
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="col-2 col-sm-3">
                                        <div class="cart-page__item__image">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                            if (!$product_permalink) {
                                                echo $thumbnail;
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div class="col-3 col-sm-7">
                                        <div class="cart-page__item__name">
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }

                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data($cart_item);

                                            // Backorder notification.
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-2 col-sm-4">
                                        <div class="cart-page__item__price">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-2 col-sm-4">
                                        <div class="cart-page__item__quantity">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                                $min_quantity = 1;
                                                $max_quantity = 1;
                                            } else {
                                                $min_quantity = 0;
                                                $max_quantity = $_product->get_max_purchase_quantity();
                                            }

                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                    'input_value'  => $cart_item['quantity'],
                                                    'max_value'    => $max_quantity,
                                                    'min_value'    => $min_quantity,
                                                    'product_name' => $_product->get_name(),
                                                ),
                                                $_product,
                                                false
                                            );

                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-2 col-sm-4">
                                        <div class="cart-page__item__subtotal">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        <?php endif; ?>
                        <?php endforeach; ?>

                        <?php do_action('woocommerce_cart_contents'); ?>

                    </div>

                    <!-- Actions: Coupon + Update -->
                    <div class="cart-page__actions">
                        <div class="row">
                            <div class="col-6 col-sm-12">
                                <?php if (wc_coupons_enabled()) : ?>
                                    <div class="cart-page__coupon">
                                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
                                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                                        <button type="submit" class="btn" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
                                        <?php do_action('woocommerce_cart_coupon'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="cart-page__update">
                                    <button type="submit" class="btn" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
                                    <?php do_action('woocommerce_cart_actions'); ?>
                                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php do_action('woocommerce_after_cart_contents'); ?>

                    <?php do_action('woocommerce_after_cart_table'); ?>

                </form>

            </div>
        </div>

        <!-- Cart Totals -->
        <div class="row">
            <div class="col-6-offset-6 col-sm-12">
                <div class="cart-page__totals">
                    <?php
                    /**
                     * Hook: woocommerce_cart_collaterals.
                     *
                     * @hooked woocommerce_cross_sell_display
                     * @hooked woocommerce_cart_totals - 10
                     */
                    do_action('woocommerce_cart_collaterals');
                    ?>
                </div>
            </div>
        </div>

    </div>
</section>

<?php do_action('woocommerce_after_cart'); ?>
