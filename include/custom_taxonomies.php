<?php
/*
	=====================
		Custom Taxonomies
	=====================	
*/

function add_custom_taxonomies() {
    register_taxonomy('work-type', 'work', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Work Types', 'taxonomy general name' ),
            'singular_name' => _x( 'Work Type', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Work Type' ),
            'all_items' => __( 'All Work Types' ),
            'parent_item' => __( 'Parent Work Type' ),
            'parent_item_colon' => __( 'Parent Work Type:' ),
            'edit_item' => __( 'Edit Work Type' ),
            'update_item' => __( 'Update Work Type' ),
            'add_new_item' => __( 'Add New Work Type' ),
            'new_item_name' => __( 'New Work Type Name' ),
            'menu_name' => __( 'Work Types' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'work-types', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies', 0 );