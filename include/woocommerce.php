<?php

/* Make sure to add woocommerce.php to root of theme folder, to be used as single page */

/*
=====================
    Add WooCommerce support
=====================	
*/
function mytheme_add_woocommerce_support() {
    add_theme_support('woocommerce');
  }
  add_action('after_setup_theme', 'mytheme_add_woocommerce_support');
  
  // Enable Gutenberg for WooCommerce
  function activate_gutenberg_product($can_edit, $post_type) {
    if ($post_type == 'product') {
      $can_edit = true;
    }
    return $can_edit;
  }
  add_filter('use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2);
  
  // Enable taxonomy fields for WooCommerce with Gutenberg on
  function enable_taxonomy_rest($args) {
    $args['show_in_rest'] = true;
    return $args;
  }
  add_filter('woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest');
  add_filter('woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest');