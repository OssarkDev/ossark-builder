<?php

defined( 'ABSPATH' ) || exit;

/*
  =====================
    Theme setup
  =====================
*/
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'ossark-builder', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ] );
    add_theme_support( 'custom-logo', [
        'height'      => 40,
        'width'       => 100,
        'flex-width'  => true,
        'flex-height' => true,
    ] );
} );

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
  $mimes['svgz'] = 'image/svg+xml';
  $mimes['json'] = 'application/json';
  $mimes['webp'] = 'image/webp';
  $mimes['woff'] = 'font/woff';
  $mimes['woff2'] = 'font/woff2';
  return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){
  if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
    return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  if ( 'svg' === $filetype['ext'] || 'image/svg+xml' === $filetype['type'] ) {
    $data['ext']  = 'svg';
    $data['type'] = 'image/svg+xml';
  }

  return $data;
}

// Display SVG thumbnail previews in WordPress Media Library
add_filter( 'wp_prepare_attachment_for_js', 'ossark_svg_media_library_preview', 10, 3 );
function ossark_svg_media_library_preview( $response, $attachment, $meta ) {
  if ( $response['mime'] === 'image/svg+xml' ) {
    $response['image'] = [
      'src' => $response['url'],
    ];
    $response['sizes'] = [
      'full' => [
        'url' => $response['url'],
      ],
    ];
  }
  return $response;
}

/*
	=====================
		Add .wp-text class to WYSIWYG editors (TinyMCE body_class)
	=====================	
*/
add_filter( 'tiny_mce_before_init', 'ossark_tinymce_add_wp_text_class' );
function ossark_tinymce_add_wp_text_class( $mce_init ) {
  if ( empty( $mce_init['body_class'] ) ) {
    $mce_init['body_class'] = 'wp-text';
  } else {
    $mce_init['body_class'] .= ' wp-text';
  }
  return $mce_init;
}



// adds title to <head>
// featured images
// (title-tag, post-thumbnails, custom-logo, html5, etc. registered in after_setup_theme above)

// remove p tags from wysiwyg
// remove_filter ('acf_the_content', 'wpautop');

// disable admin bar
add_filter('show_admin_bar', '__return_false');

//users roles
//add_role( 'member', 'Member', array( 'read' => true, 'level_0' => true ) );

// stop WordPress auto update
if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
  define( 'WP_AUTO_UPDATE_CORE', false );
}

// Remove <p> and <br/> from Contact Form 7
add_filter('wpcf7_autop_or_not', '__return_false');