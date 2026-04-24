<?php
/**
 * Checkout Form Template
 * Overrides: woocommerce/templates/checkout/form-checkout.php
 *
 * The main checkout page layout. WooCommerce handles all form logic,
 * validation, and payment processing — this controls the markup.
 *
 * Hooks included:
 * - woocommerce_before_checkout_form
 * - woocommerce_checkout_before_customer_details
 * - woocommerce_checkout_billing
 * - woocommerce_checkout_shipping
 * - woocommerce_checkout_after_customer_details
 * - woocommerce_checkout_before_order_review_heading
 * - woocommerce_checkout_before_order_review
 * - woocommerce_checkout_order_review
 * - woocommerce_checkout_after_order_review
 * - woocommerce_after_checkout_form
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

// Redirect to cart if empty
if (!WC()->cart->is_empty()) :

do_action('woocommerce_before_checkout_form', $checkout);

// Login form (if guest checkout is disabled)
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<section class="checkout-page">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h1 class="checkout-page__title"><?php esc_html_e('Checkout', 'woocommerce'); ?></h1>
            </div>
        </div>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="row">

                <!-- Customer Details: Billing & Shipping -->
                <div class="col-7 col-sm-12">
                    <div class="checkout-page__customer">

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="checkout-page__billing" id="customer_details">
                            <?php
                            /**
                             * Hook: woocommerce_checkout_billing.
                             *
                             * @hooked woocommerce_checkout_payment - 10
                             * Outputs the billing form fields.
                             */
                            do_action('woocommerce_checkout_billing');
                            ?>
                        </div>

                        <div class="checkout-page__shipping">
                            <?php
                            /**
                             * Hook: woocommerce_checkout_shipping.
                             *
                             * Outputs the shipping form fields (if shipping is needed).
                             */
                            do_action('woocommerce_checkout_shipping');
                            ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    </div>
                </div>

                <!-- Order Review: Items, totals, payment -->
                <div class="col-5 col-sm-12">
                    <div class="checkout-page__order-review">

                        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                        <h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php
                            /**
                             * Hook: woocommerce_checkout_order_review.
                             *
                             * @hooked woocommerce_order_review - 10 (order summary table)
                             * @hooked woocommerce_checkout_payment - 20 (payment methods + place order button)
                             */
                            do_action('woocommerce_checkout_order_review');
                            ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>

                    </div>
                </div>

            </div>

        </form>

    </div>
</section>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

<?php endif; ?>
