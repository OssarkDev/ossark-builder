<?php
/**
 * My Account Dashboard Template
 * Overrides: woocommerce/templates/myaccount/dashboard.php
 *
 * The main dashboard content shown on /my-account/ (no endpoint).
 * Customize the welcome message and quick links.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
?>

<div class="account-dashboard">

    <!-- Welcome Message -->
    <div class="account-dashboard__welcome">
        <p>
            <?php
            printf(
                /* translators: 1: user display name 2: logout url */
                wp_kses_post(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce')),
                '<strong>' . esc_html($current_user->display_name) . '</strong>',
                esc_url(wc_logout_url())
            );
            ?>
        </p>
        <p>
            <?php
            /* translators: 1: Orders URL 2: Account details URL */
            $orders_url  = esc_url(wc_get_endpoint_url('orders'));
            $account_url = esc_url(wc_get_endpoint_url('edit-account'));
            printf(
                wp_kses_post(__('From your account dashboard you can view your <a href="%1$s">recent orders</a> and <a href="%2$s">edit your account details</a>.', 'woocommerce')),
                $orders_url,
                $account_url
            );
            ?>
        </p>
    </div>

    <!-- Quick Links Grid (optional — customize per project) -->
    <!-- <div class="account-dashboard__links">
        <div class="row">
            <div class="col-4 col-sm-6">
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="account-dashboard__card">
                    <h3>Orders</h3>
                    <p>View your order history</p>
                </a>
            </div>
            <div class="col-4 col-sm-6">
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="account-dashboard__card">
                    <h3>Addresses</h3>
                    <p>Manage billing & shipping</p>
                </a>
            </div>
            <div class="col-4 col-sm-6">
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="account-dashboard__card">
                    <h3>Account Details</h3>
                    <p>Update your info</p>
                </a>
            </div>
        </div>
    </div> -->

    <?php
    /**
     * Hook: woocommerce_account_dashboard.
     *
     * @since 2.6.0
     */
    do_action('woocommerce_account_dashboard');

    /**
     * Deprecated: woocommerce_before_my_account, woocommerce_after_my_account.
     */
    ?>

</div>
