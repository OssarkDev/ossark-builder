<?php
/**
 * Thank You (Order Received) Template
 * Overrides: woocommerce/templates/checkout/thankyou.php
 *
 * Shown after successful order placement.
 * Access the full order object for custom confirmation pages.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;
?>

<section class="checkout-thankyou">
    <div class="container">
        <div class="row">
            <div class="col-8-offset-2 col-sm-12">

                <?php if ($order) :

                    do_action('woocommerce_before_thankyou', $order->get_id());

                    if ($order->has_status('failed')) : ?>

                        <!-- Order Failed -->
                        <div class="checkout-thankyou__failed">
                            <h1><?php esc_html_e('Unfortunately your order cannot be processed.', 'woocommerce'); ?></h1>
                            <p>
                                <?php esc_html_e('Please attempt your purchase again.', 'woocommerce'); ?>
                            </p>
                            <p>
                                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="btn">
                                    <?php esc_html_e('Pay', 'woocommerce'); ?>
                                </a>
                            </p>
                        </div>

                    <?php else : ?>

                        <!-- Order Success -->
                        <div class="checkout-thankyou__success">
                            <h1><?php esc_html_e('Thank you. Your order has been received.', 'woocommerce'); ?></h1>

                            <!-- Order Summary -->
                            <ul class="checkout-thankyou__details woocommerce-order-overview woocommerce-thankyou-order-details order_details">
                                <li class="woocommerce-order-overview__order order">
                                    <?php esc_html_e('Order number:', 'woocommerce'); ?>
                                    <strong><?php echo $order->get_order_number(); ?></strong>
                                </li>
                                <li class="woocommerce-order-overview__date date">
                                    <?php esc_html_e('Date:', 'woocommerce'); ?>
                                    <strong><?php echo wc_format_datetime($order->get_date_created()); ?></strong>
                                </li>
                                <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
                                    <li class="woocommerce-order-overview__email email">
                                        <?php esc_html_e('Email:', 'woocommerce'); ?>
                                        <strong><?php echo $order->get_billing_email(); ?></strong>
                                    </li>
                                <?php endif; ?>
                                <li class="woocommerce-order-overview__total total">
                                    <?php esc_html_e('Total:', 'woocommerce'); ?>
                                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                                </li>
                                <?php if ($order->get_payment_method_title()) : ?>
                                    <li class="woocommerce-order-overview__payment-method method">
                                        <?php esc_html_e('Payment method:', 'woocommerce'); ?>
                                        <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                                    </li>
                                <?php endif; ?>
                            </ul>

                        </div>

                    <?php endif; ?>

                    <?php
                    /**
                     * Hook: woocommerce_thankyou.
                     *
                     * @hooked woocommerce_order_details_table - 10 (order items table)
                     */
                    do_action('woocommerce_thankyou', $order->get_id());
                    ?>

                <?php else : ?>

                    <!-- No Order (direct URL access) -->
                    <div class="checkout-thankyou__empty">
                        <h1><?php esc_html_e('Thank you. Your order has been received.', 'woocommerce'); ?></h1>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
