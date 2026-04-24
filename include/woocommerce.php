
<?php
/**
 * WooCommerce Integration
 * 
 * Custom frontend theme integration with WooCommerce backend.
 * Uses WooCommerce hooks/functions for logic, with fully custom markup.
 * 
 * To enable: uncomment the woocommerce.php include in functions.php
 * and create a woocommerce.php file in the theme root for the shop template.
 * 
 * Template overrides go in: theme/woocommerce/ (mirrors woocommerce/templates/)
 * @see https://woocommerce.com/document/template-structure/
 * 
 * @package Ossark Builder
 */


/* =====================================================
   1. THEME SUPPORT & COMPATIBILITY
   ===================================================== */

/**
 * Declare WooCommerce theme support and feature compatibility.
 */
function mytheme_add_woocommerce_support() {
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 600,
        'gallery_thumbnail_image_width' => 300,
        'single_image_width' => 900,
        'product_grid' => [
            'default_rows'    => 4,
            'min_rows'        => 1,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ],
    ]);

    // Product gallery features (comment out if building fully custom gallery)
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

/**
 * Declare compatibility with WooCommerce HPOS (High-Performance Order Storage).
 * Required for WooCommerce 8.2+ — custom order tables replacing post-based storage.
 * @see https://woocommerce.com/document/high-performance-order-storage/
 */
function mytheme_declare_hpos_compatibility() {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
}
add_action('before_woocommerce_init', 'mytheme_declare_hpos_compatibility');

/**
 * Declare compatibility with the WooCommerce Cart & Checkout Blocks.
 * Ensures the theme works with block-based cart/checkout (WC 8.3+).
 */
function mytheme_declare_cart_checkout_blocks_compatibility() {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
}
add_action('before_woocommerce_init', 'mytheme_declare_cart_checkout_blocks_compatibility');

// Enable Gutenberg block editor for products
function mytheme_activate_gutenberg_product($can_edit, $post_type) {
    if ($post_type === 'product') {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'mytheme_activate_gutenberg_product', 10, 2);

// Expose product taxonomies to REST API (required for Gutenberg)
function mytheme_enable_taxonomy_rest($args) {
    $args['show_in_rest'] = true;
    return $args;
}
add_filter('woocommerce_taxonomy_args_product_cat', 'mytheme_enable_taxonomy_rest');
add_filter('woocommerce_taxonomy_args_product_tag', 'mytheme_enable_taxonomy_rest');


/* =====================================================
   2. STYLES & SCRIPTS
   ===================================================== */

/**
 * Remove ALL default WooCommerce styles for fully custom frontend.
 * Comment this out if you want to extend default WC styles instead.
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Conditionally load WooCommerce scripts only on WC pages.
 * Prevents WC JS/CSS from loading on non-shop pages.
 */
function mytheme_dequeue_woocommerce_scripts() {
    if (!function_exists('is_woocommerce')) return;

    if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !is_product()) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');

        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');
        wp_dequeue_script('wc-add-to-cart-variation');
        wp_dequeue_script('wc-single-product');
    }
}
add_action('wp_enqueue_scripts', 'mytheme_dequeue_woocommerce_scripts', 99);


/* =====================================================
   3. THEME WRAPPER & LAYOUT
   ===================================================== */

/**
 * Replace default WooCommerce content wrappers with theme grid.
 */
function mytheme_woocommerce_wrapper_before() {
    echo '<main id="main"><section class="woocommerce-page"><div class="container"><div class="row"><div class="col-12">';
}
function mytheme_woocommerce_wrapper_after() {
    echo '</div></div></div></section></main>';
}
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'mytheme_woocommerce_wrapper_before');
add_action('woocommerce_after_main_content', 'mytheme_woocommerce_wrapper_after');

// Remove default sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Remove default breadcrumbs (use custom or ACF)
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Add WooCommerce-specific body classes for custom styling.
 */
function mytheme_wc_body_class($classes) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'woocommerce-archive';
    }
    if (is_product()) {
        $classes[] = 'woocommerce-single';
    }
    if (is_cart()) {
        $classes[] = 'woocommerce-cart-page';
    }
    if (is_checkout()) {
        $classes[] = 'woocommerce-checkout-page';
    }
    if (is_account_page()) {
        $classes[] = 'woocommerce-account-page';
    }
    return $classes;
}
add_filter('body_class', 'mytheme_wc_body_class');


/* =====================================================
   4. SHOP / ARCHIVE PAGES
   ===================================================== */

/**
 * Products per page on shop/archive.
 */
function mytheme_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'mytheme_products_per_page');

/**
 * Product columns on shop/archive.
 */
function mytheme_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'mytheme_loop_columns');

/**
 * Remove default shop/archive product elements.
 * Unhook these to build fully custom product cards.
 * After removing, add your own hooks or override the template:
 * woocommerce/content-product.php
 */
// remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
// remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
// remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
// remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
// remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
// remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
// remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
// remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

/**
 * Remove result count and ordering dropdown from shop.
 */
// remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
// remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * Custom product archive sorting options.
 */
// function mytheme_custom_sorting_options($options) {
//     unset($options['popularity']);
//     unset($options['rating']);
//     $options['price']      = 'Price: low to high';
//     $options['price-desc'] = 'Price: high to low';
//     return $options;
// }
// add_filter('woocommerce_catalog_orderby', 'mytheme_custom_sorting_options');


/* =====================================================
   5. SINGLE PRODUCT PAGE
   ===================================================== */

/**
 * Remove default single product elements.
 * Unhook these to build a fully custom product page.
 * After removing, add your own hooks or override the template:
 * woocommerce/single-product/title.php, price.php, meta.php, etc.
 */
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

/**
 * Remove product tabs (Description, Reviews, Additional Information).
 */
// function mytheme_remove_product_tabs($tabs) {
//     unset($tabs['description']);
//     unset($tabs['reviews']);
//     unset($tabs['additional_information']);
//     return $tabs;
// }
// add_filter('woocommerce_product_tabs', 'mytheme_remove_product_tabs');

/**
 * Remove related products.
 */
// remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

/**
 * Remove upsells.
 */
// remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);

/**
 * Customize number of related products.
 */
// function mytheme_related_products_args($args) {
//     $args['posts_per_page'] = 4;
//     $args['columns']        = 4;
//     return $args;
// }
// add_filter('woocommerce_output_related_products_args', 'mytheme_related_products_args');

/**
 * Custom product image sizes.
 * Set dimensions for product thumbnails, catalog images, and single images.
 */
// function mytheme_wc_image_dimensions() {
//     add_image_size('shop_thumbnail', 300, 300, true);
//     add_image_size('shop_catalog', 600, 600, true);
//     add_image_size('shop_single', 900, 900, true);
// }
// add_action('after_setup_theme', 'mytheme_wc_image_dimensions');


/* =====================================================
   6. CART
   ===================================================== */

/**
 * Redirect to cart page after adding a product (instead of staying on current page).
 */
// add_filter('woocommerce_add_to_cart_redirect', function() {
//     return wc_get_cart_url();
// });

/**
 * Remove cross-sells from cart page.
 */
// remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

/**
 * Customize cross-sell display count and columns.
 */
// function mytheme_cross_sells_limit($limit) { return 4; }
// function mytheme_cross_sells_columns($columns) { return 2; }
// add_filter('woocommerce_cross_sells_total', 'mytheme_cross_sells_limit');
// add_filter('woocommerce_cross_sells_columns', 'mytheme_cross_sells_columns');

/**
 * AJAX cart fragments — update custom mini-cart without page reload.
 * Use WC cart fragments to refresh your custom cart count/total.
 * 
 * In your header template, add:
 * <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
 * <span class="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
 */
function mytheme_cart_fragments($fragments) {
    $fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    $fragments['.cart-total'] = '<span class="cart-total">' . WC()->cart->get_cart_total() . '</span>';
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'mytheme_cart_fragments');

/**
 * Ensure cart is not empty message uses theme layout.
 */
// function mytheme_empty_cart_message() {
//     echo '<p class="cart-empty text-center">Your cart is currently empty.</p>';
// }
// remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
// add_action('woocommerce_cart_is_empty', 'mytheme_empty_cart_message');


/* =====================================================
   7. CHECKOUT
   ===================================================== */

/**
 * Remove default checkout fields you don't need.
 * Common for simpler checkouts (digital products, services).
 */
// function mytheme_remove_checkout_fields($fields) {
//     // Billing
//     unset($fields['billing']['billing_company']);
//     unset($fields['billing']['billing_address_2']);
//     unset($fields['billing']['billing_state']);

//     // Shipping
//     unset($fields['shipping']['shipping_company']);
//     unset($fields['shipping']['shipping_address_2']);

//     // Order notes
//     unset($fields['order']['order_comments']);

//     return $fields;
// }
// add_filter('woocommerce_checkout_fields', 'mytheme_remove_checkout_fields');

/**
 * Reorder checkout fields.
 * Lower priority number = appears first.
 */
// function mytheme_reorder_checkout_fields($fields) {
//     $fields['billing']['billing_first_name']['priority'] = 10;
//     $fields['billing']['billing_last_name']['priority']  = 20;
//     $fields['billing']['billing_email']['priority']      = 30;
//     $fields['billing']['billing_phone']['priority']      = 40;
//     $fields['billing']['billing_address_1']['priority']  = 50;
//     $fields['billing']['billing_city']['priority']       = 60;
//     $fields['billing']['billing_postcode']['priority']   = 70;
//     $fields['billing']['billing_country']['priority']    = 80;
//     return $fields;
// }
// add_filter('woocommerce_checkout_fields', 'mytheme_reorder_checkout_fields');

/**
 * Add custom checkout field.
 */
// function mytheme_add_custom_checkout_field($checkout) {
//     woocommerce_form_field('custom_field', [
//         'type'        => 'text',
//         'class'       => ['form-row-wide'],
//         'label'       => 'Custom Field',
//         'placeholder' => 'Enter value',
//         'required'    => false,
//     ], $checkout->get_value('custom_field'));
// }
// add_action('woocommerce_after_order_notes', 'mytheme_add_custom_checkout_field');

// function mytheme_save_custom_checkout_field($order_id) {
//     if (!empty($_POST['custom_field'])) {
//         $order = wc_get_order($order_id);
//         $order->update_meta_data('custom_field', sanitize_text_field($_POST['custom_field']));
//         $order->save();
//     }
// }
// add_action('woocommerce_checkout_update_order_meta', 'mytheme_save_custom_checkout_field');

/**
 * Custom thank-you page redirect after checkout.
 */
// function mytheme_checkout_redirect($order_id) {
//     $order = wc_get_order($order_id);
//     if ($order) {
//         wp_safe_redirect(home_url('/thank-you/'));
//         exit;
//     }
// }
// add_action('woocommerce_thankyou', 'mytheme_checkout_redirect', 1);


/* =====================================================
   8. MY ACCOUNT
   ===================================================== */

/**
 * Customize My Account menu items.
 * Remove or reorder default tabs.
 */
// function mytheme_account_menu_items($items) {
//     // Remove items
//     unset($items['downloads']);
//     // unset($items['edit-address']);

//     // Reorder — rebuild array in desired order
//     $new_items = [];
//     $new_items['dashboard']       = $items['dashboard'];
//     $new_items['orders']          = $items['orders'];
//     $new_items['edit-address']    = $items['edit-address'];
//     $new_items['edit-account']    = $items['edit-account'];
//     $new_items['customer-logout'] = $items['customer-logout'];
//     return $new_items;
// }
// add_filter('woocommerce_account_menu_items', 'mytheme_account_menu_items');

/**
 * Add a custom My Account endpoint.
 * After adding, flush rewrite rules (Settings > Permalinks > Save).
 */
// function mytheme_add_account_endpoint() {
//     add_rewrite_endpoint('my-custom-endpoint', EP_ROOT | EP_PAGES);
// }
// add_action('init', 'mytheme_add_account_endpoint');

// function mytheme_custom_endpoint_menu($items) {
//     $items['my-custom-endpoint'] = 'Custom Page';
//     return $items;
// }
// add_filter('woocommerce_account_menu_items', 'mytheme_custom_endpoint_menu');

// function mytheme_custom_endpoint_content() {
//     echo '<h2>Custom Endpoint</h2><p>Your custom content here.</p>';
// }
// add_action('woocommerce_account_my-custom-endpoint_endpoint', 'mytheme_custom_endpoint_content');


/* =====================================================
   9. PRODUCT DATA & ACF INTEGRATION
   ===================================================== */

/**
 * Display custom ACF fields on single product page.
 * Assumes you have ACF fields assigned to the 'product' post type.
 */
// function mytheme_display_custom_product_fields() {
//     $custom_field = get_field('custom_product_field');
//     if ($custom_field) {
//         echo '<div class="product-custom-field">' . $custom_field . '</div>';
//     }
// }
// add_action('woocommerce_single_product_summary', 'mytheme_display_custom_product_fields', 25);

/**
 * Add custom product data tab using ACF.
 */
// function mytheme_custom_product_tab($tabs) {
//     $content = get_field('custom_tab_content');
//     if ($content) {
//         $tabs['custom_tab'] = [
//             'title'    => 'Details',
//             'priority' => 15,
//             'callback' => 'mytheme_custom_tab_content',
//         ];
//     }
//     return $tabs;
// }
// function mytheme_custom_tab_content() {
//     echo '<div class="custom-tab">' . get_field('custom_tab_content') . '</div>';
// }
// add_filter('woocommerce_product_tabs', 'mytheme_custom_product_tab');


/* =====================================================
   10. AJAX ADD TO CART (SINGLE PRODUCT)
   ===================================================== */

/**
 * Enable AJAX add-to-cart on single product pages.
 * WooCommerce only does this on archives by default.
 */
// function mytheme_single_ajax_add_to_cart() {
//     if (is_product()) {
//         wp_enqueue_script('wc-add-to-cart');
//     }
// }
// add_action('wp_enqueue_scripts', 'mytheme_single_ajax_add_to_cart');


/* =====================================================
   11. EMAILS
   ===================================================== */

/**
 * Custom email header/footer content.
 * Override email templates in: theme/woocommerce/emails/
 */
// function mytheme_email_header($email_heading, $email) {
//     // Custom email header HTML
// }
// add_action('woocommerce_email_header', 'mytheme_email_header', 10, 2);

/**
 * Add custom content to order confirmation emails.
 */
// function mytheme_email_after_order_table($order, $sent_to_admin, $plain_text, $email) {
//     if ($email->id === 'customer_completed_order') {
//         echo '<p>Thank you for your purchase! Your custom message here.</p>';
//     }
// }
// add_action('woocommerce_email_after_order_table', 'mytheme_email_after_order_table', 10, 4);


/* =====================================================
   12. UTILITY FUNCTIONS
   ===================================================== */

/**
 * Check if WooCommerce is active.
 * Use this guard in templates before calling WC functions.
 *
 * @return bool
 */
function mytheme_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get the formatted price for a product.
 *
 * @param int $product_id
 * @return string Formatted price HTML
 */
function mytheme_get_product_price($product_id) {
    $product = wc_get_product($product_id);
    return $product ? $product->get_price_html() : '';
}

/**
 * Get add-to-cart URL for a product.
 *
 * @param int $product_id
 * @return string
 */
function mytheme_get_add_to_cart_url($product_id) {
    $product = wc_get_product($product_id);
    return $product ? $product->add_to_cart_url() : '';
}

/**
 * Check if product is in stock.
 *
 * @param int $product_id
 * @return bool
 */
function mytheme_is_in_stock($product_id) {
    $product = wc_get_product($product_id);
    return $product ? $product->is_in_stock() : false;
}

/**
 * Get cart item count (for custom mini-cart display).
 *
 * @return int
 */
function mytheme_get_cart_count() {
    return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

/**
 * Get cart total (for custom mini-cart display).
 *
 * @return string Formatted total
 */
function mytheme_get_cart_total() {
    return WC()->cart ? WC()->cart->get_cart_total() : wc_price(0);
}