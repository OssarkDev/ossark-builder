<?php
/**
 * WooCommerce Shop Page Template
 * 
 * This is the main WooCommerce template that WordPress uses for all WC pages.
 * It replaces the need for archive-product.php at the theme root level.
 * WooCommerce will use this template for shop, product archives, and single products.
 *
 * @package Ossark Builder
 */

get_header(); ?>

<?php woocommerce_content(); ?>

<?php get_footer(); ?>
