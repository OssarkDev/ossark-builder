<?php

defined( 'ABSPATH' ) || exit;

/*
	=====================
		Register post types
	=====================	
*/
function create_posttypes() {
    // register work custom post type
    register_post_type('work',
       array(
        'labels' => array(
            'name' => __( 'Work', 'ossark-builder' ),
            'all_items' => __( 'All Works', 'ossark-builder' ),
            'singular_name' => __( 'Work', 'ossark-builder' ),
            'add_new_item' => __( 'Add New Work', 'ossark-builder' )
        ),
        'public' => true,
        'show_ui' => true,
        'hierarchical' => false,
        'has_archive' => false,
        'exclude_from_search' => false,
        'capability_type' => 'post',
        'taxonomies' => array('category', 'post_tag'), // which taxonomies to use, natvie or custom
        'menu_icon' => 'dashicons-portfolio', //https://developer.wordpress.org/resource/dashicons/
        'show_in_menu' => true,
        'show_in_rest' => true, // show in rest api, if true it can be seen in blokc editor
        'rewrite' => array('slug' => 'work'), // url structure
        'publicly_queryable'  => true, // if there is no single or archive page, set to false
        'supports' => array(
            'title',
            'editor',
            //'excerpt',
            //'trackbacks',
            //'custom-fields',
            //'comments',
            'revisions',
            'thumbnail',
            //'author',
            //'page-attributes',
        ),
       )
    );

    // register another custom post type...

}
add_action('init', 'create_posttypes');