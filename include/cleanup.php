<?php

/*
  =====================
    Clean
  =====================
*/
if ( ! function_exists( 'wp_theme_setup' ) ) :
    function wp_theme_setup() {

        // clean WordPress headers
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'parent_post_rel_link', 10);
        remove_action('wp_head', 'start_post_rel_link', 10);
        remove_action('wp_head', 'adjacent_posts_rel_link', 10);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');        
    }
endif;
add_action( 'after_setup_theme', 'wp_theme_setup' );


/*
  =====================
    Disable RSS Feed
  =====================
*/
function fb_disable_feed() {
  wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
  }
   
  add_action('do_feed', 'fb_disable_feed', 1);
  add_action('do_feed_rdf', 'fb_disable_feed', 1);
  add_action('do_feed_rss', 'fb_disable_feed', 1);
  add_action('do_feed_rss2', 'fb_disable_feed', 1);
  add_action('do_feed_atom', 'fb_disable_feed', 1);


/*
  =====================
    Disable XML-RPC in WordPress
  =====================
*/
add_filter('xmlrpc_enabled', '__return_false');


/*
	=====================
		Remove Gutenberg Block Library CSS from loading on the frontend
	=====================	
*/
//REMOVE GUTENBERG BLOCK LIBRARY CSS FROM LOADING ON FRONTEND
function remove_wp_block_library_css() {
  wp_dequeue_style( 'classic-theme-styles-css' );
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
  wp_dequeue_style( 'wc-block-style' );
  wp_dequeue_style( 'global-styles' );
}
  add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );