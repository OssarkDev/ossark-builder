<?php
/**
 * Empty Cart Template
 * Overrides: woocommerce/templates/cart/cart-empty.php
 *
 * Shown when the cart has no items.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');
?>

<section class="cart-empty">
    <div class="container">
        <div class="row">
            <div class="col-8-offset-2 col-sm-12">
                <div class="cart-empty__content text-center">

                    <h1 class="cart-empty__title"><?php esc_html_e('Your cart is empty', 'woocommerce'); ?></h1>

                    <?php if (wc_get_page_id('shop') > 0) : ?>
                        <p class="cart-empty__message">
                            <?php esc_html_e('Browse our products and add something to your cart.', 'woocommerce'); ?>
                        </p>
                        <div class="cart-empty__button mt-48">
                            <a class="btn" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                                <?php esc_html_e('Return to shop', 'woocommerce'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
