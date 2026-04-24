<?php
/**
 * My Account Navigation Template
 * Overrides: woocommerce/templates/myaccount/navigation.php
 *
 * Renders the account sidebar navigation.
 * Customize menu items via the woocommerce_account_menu_items filter in include/woocommerce.php.
 *
 * @package Ossark Builder
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_navigation');
?>

<nav class="account-nav woocommerce-MyAccount-navigation">
    <ul class="account-nav__list">
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
            <li class="account-nav__item <?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
                   class="account-nav__link"
                   <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
                    <?php echo esc_html($label); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
