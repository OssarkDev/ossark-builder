<?php

/*
  =====================
    Add Categories and Tags to pages
  =====================
*/
function add_taxonomies_to_pages() {
	register_taxonomy_for_object_type( 'post_tag', 'page' );
	register_taxonomy_for_object_type( 'category', 'page' );
	}
   add_action( 'init', 'add_taxonomies_to_pages' );
	if ( ! is_admin() ) {
	add_action( 'pre_get_posts', 'category_and_tag_archives' );
	
	}
   function category_and_tag_archives( $wp_query ) {
   $my_post_array = array('post','page');
	
	if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
	$wp_query->set( 'post_type', $my_post_array );
	
	if ( $wp_query->get( 'tag' ) )
	$wp_query->set( 'post_type', $my_post_array );
}

  



/*
  =====================
    Move Yoast to bottom
  =====================	
*/
function yoasttobottom() {
return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');





/*
  =====================
    Remove menu item in WP admin
  =====================	
*/

function remove_menu_items ()
{ 
   remove_menu_page('edit.php'); // Posts
   remove_menu_page('edit-comments.php'); // Comments
}
add_action('admin_menu', 'remove_menu_items'); 


/*
  =====================
    Image references
  =====================	
*/
// Remove Wordpress compression
add_filter('jpeg_quality', function($arg){return 100;});
// Prevent autoscaling of images
add_filter( 'big_image_size_threshold', '__return_false' );
// Disable lazy loading
add_filter( 'wp_lazy_loading_enabled', '__return_false' );
// Custom image sizes
add_image_size( 'figure_1600', 1600, 9999 );


/*
	=====================
		Svg and json support
	=====================	
*/
add_filter('upload_mimes', 'ossark_custom_mime_types');
function ossark_custom_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  $mimes['json'] = 'application/json';
  $mimes['webp'] = 'image/webp';
  $mimes['woff'] = 'font/woff';
  $mimes['woff2'] = 'font/woff2';
  return $mimes;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

  if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) )
    $dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
  else
    $dosvg = ( '.svg' === strtolower( substr($filename, -4) ) );
  if( $dosvg ){

    if( current_user_can('manage_options') ){

      $data['ext']  = 'svg';
      $data['type'] = 'image/svg+xml';
    }
    else {
      $data['ext'] = $type_and_ext['type'] = false;
    }
  }

  return $data;
}



// adds title to <head>
add_theme_support( 'title-tag' );

// featured images
add_theme_support( 'post-thumbnails' );

// remove p tags from wysiwyg
// remove_filter ('acf_the_content', 'wpautop');

//remove extra WP styling (header)
add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

// disable admin bar
add_filter('show_admin_bar', '__return_false');

//users roles
//add_role( 'member', 'Member', array( 'read' => true, 'level_0' => true ) );

// stop WordPress auto update
if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
  define( 'WP_AUTO_UPDATE_CORE', false );
}

// Add theme support for selective refresh for widgets.
add_theme_support('customize-selective-refresh-widgets');

/**
 * Add support for core custom logo.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
add_theme_support(
  'custom-logo',
  array(
    'height' => 40,
    'width' => 100,
    'flex-width' => true,
    'flex-height' => true,
  )
);

// Remove <p> and <br/> from Contact Form 7
add_filter('wpcf7_autop_or_not', '__return_false');