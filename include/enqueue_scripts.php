<?php
/**
 * Enqueue scripts and styles.
 */
function enqueue_scripts() {

    // set path variables for file timestamps
    $js_path = get_template_directory().'/dist/main.min.js';
    $vendors_js_path = get_template_directory().'/dist/vendors.min.js';
    $style_path = get_stylesheet_directory().'/dist/main.min.css';

    // styles
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css', array());
    wp_enqueue_style( 'mincss', get_template_directory_uri() . '/dist/main.min.css',  array(), filemtime( $style_path ), 'all');

    // remove old jquery
    wp_deregister_script( 'jquery' );

    // add latest jquery
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.7.0.min.js', false, null, true );
    wp_enqueue_script( 'jquery' );

    // custom js
    wp_enqueue_script( 'main', get_template_directory_uri() . '/dist/main.min.js', array('jquery'), filemtime( $js_path ) , true);
    
    // vendors
    wp_enqueue_script( 'vendors', get_template_directory_uri() . '/dist/vendors.min.js', false , filemtime( $vendors_js_path ) , true);

    //send PHP variables to JS
    wp_localize_script( 'main', 'customjs_ajax_object',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( "secure_nonce_name" ),
            'site_url' => get_site_url(),
            'theme_url' => get_template_directory_uri()
        )
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );


/*
=====================
	Remove JQuery migrate
=====================
*/
function remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) { 
        // Check whether the script has any dependencies
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    } 
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );