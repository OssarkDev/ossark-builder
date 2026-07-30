<?php

defined( 'ABSPATH' ) || exit;

/*
	=====================
		Send all traffic to coming soon page
	=====================	
*/
add_action('init', function() {
  if (function_exists('get_field')) {
    $coming_soon = get_field('coming_soon', 'option');
    if ($coming_soon) {
      add_action( 'template_redirect', function() {
        if ( is_page( 'coming-soon' ) ) {
            return;
        }
        wp_redirect( esc_url_raw( home_url( 'coming-soon' ) ) );
        exit;
      });
    }
  }
});