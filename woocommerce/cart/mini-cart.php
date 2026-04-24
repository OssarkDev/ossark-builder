<?php
/**
 * Mini-Cart Template
 * Overrides: woocommerce/templates/cart/mini-cart.php
 *
 * Used in the sidebar widget and can be used for a flyout/dropdown cart.
 * Updated via AJAX using woocommerce_add_to_cart_fragments.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<div class="mini-cart">

    <?php if (!WC()->cart->is_empty()) : ?>

        <ul class="mini-cart__list woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">

            <?php
            do_action('woocommerce_before_mini_cart_contents');

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) :
                    $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
            ?>
                    <li class="mini-cart__item woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

                        <!-- Remove link -->
                        <?php
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                /* translators: %s: product name */
                                esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                                esc_attr($product_id),
                                esc_attr($cart_item_key),
                                esc_attr($_product->get_sku())
                            ),
                            $cart_item_key
                        );
                        ?>

                        <!-- Thumbnail -->
                        <div class="mini-cart__item__image">
                            <?php if (empty($product_permalink)) : ?>
                                <?php echo $thumbnail; ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url($product_permalink); ?>">
                                    <?php echo $thumbnail; ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Name + Quantity x Price -->
                        <div class="mini-cart__item__details">
                            <span class="mini-cart__item__name">
                                <?php if (empty($product_permalink)) : ?>
                                    <?php echo wp_kses_post($product_name); ?>
                                <?php else : ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo wp_kses_post($product_name); ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                            <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="mini-cart__item__quantity quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); ?>
                        </div>

                    </li>

            <?php endif; ?>
            <?php endforeach; ?>

            <?php do_action('woocommerce_mini_cart_contents'); ?>

        </ul>

        <!-- Subtotal -->
        <div class="mini-cart__total">
            <strong><?php esc_html_e('Subtotal', 'woocommerce'); ?>:</strong>
            <?php echo WC()->cart->get_cart_subtotal(); ?>
        </div>

        <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

        <!-- Buttons -->
        <div class="mini-cart__buttons">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn--outline">
                <?php esc_html_e('View cart', 'woocommerce'); ?>
            </a>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn">
                <?php esc_html_e('Checkout', 'woocommerce'); ?>
            </a>
        </div>

        <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

    <?php else : ?>

        <p class="mini-cart__empty woocommerce-mini-cart__empty-message">
            <?php esc_html_e('No products in the cart.', 'woocommerce'); ?>
        </p>

    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_mini_cart'); ?>
