<?php
/**
 * My Account Page Template
 * Overrides: woocommerce/templates/myaccount/my-account.php
 *
 * Main account dashboard layout with navigation and content area.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;
?>

<section class="my-account">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h1 class="my-account__title"><?php esc_html_e('My Account', 'woocommerce'); ?></h1>
            </div>
        </div>

        <div class="row">

            <!-- Navigation -->
            <div class="col-3 col-sm-12">
                <div class="my-account__nav">
                    <?php
                    /**
                     * The account navigation.
                     * Override navigation items via woocommerce_account_menu_items filter.
                     */
                    do_action('woocommerce_account_navigation');
                    ?>
                </div>
            </div>

            <!-- Content -->
            <div class="col-9 col-sm-12">
                <div class="my-account__content woocommerce-MyAccount-content">
                    <?php
                    /**
                     * Hook: woocommerce_account_content.
                     *
                     * @hooked woocommerce_account_content - 10 (renders the endpoint content)
                     */
                    do_action('woocommerce_account_content');
                    ?>
                </div>
            </div>

        </div>

    </div>
</section>
