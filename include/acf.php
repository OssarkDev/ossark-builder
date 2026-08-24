<?php

defined( 'ABSPATH' ) || exit;


/*
  =====================
    Block editor styles (iframed canvas)
  =====================
*/
// Styles that must load INSIDE the block-editor iframe.
//   - assets/editor-styles.css: hand-written editor-only overrides
//     (canvas chrome, ACF field wrappers, forced .in-view visibility,
//      appender fixes)
//   - dist/editor.min.css: SCSS bundle mirroring the frontend design
//     tokens/typography/grid/block styles so the WYSIWYG matches
//     production. Loaded second so it can layer on top.
add_action('after_setup_theme', function () {
    add_theme_support('editor-styles');
    add_editor_style('assets/editor-styles.css');

    if ( file_exists( get_template_directory() . '/dist/editor.min.css' ) ) {
        add_editor_style( 'dist/editor.min.css' );
    }
});

/*
  =====================
    Block editor chrome (outer admin UI) & scripts
  =====================
*/
// Styles + JS that run in the block editor (draggable inspector
// sidebar, ACF block preview JS like Slick slider init).
add_action('enqueue_block_editor_assets', function () {
    $css_path        = get_template_directory() . '/assets/editor.css';
    $js_path         = get_template_directory() . '/assets/editor.js';
    $dist_editor_js  = get_template_directory() . '/dist/editor.min.js';

    if (file_exists($css_path)) {
        wp_enqueue_style(
            'ossark-editor-chrome',
            get_template_directory_uri() . '/assets/editor.css',
            array(),
            filemtime($css_path)
        );
    }

    if (file_exists($js_path)) {
        wp_enqueue_script(
            'ossark-editor-chrome',
            get_template_directory_uri() . '/assets/editor.js',
            array(),
            filemtime($js_path),
            true
        );
    }

    if (file_exists($dist_editor_js)) {
        wp_enqueue_script(
            'ossark-editor-bundle',
            get_template_directory_uri() . '/dist/editor.min.js',
            array('jquery', 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-data', 'wp-element'),
            filemtime($dist_editor_js),
            true
        );
    }
});

/*
=====================
	Add theme options menu
=====================
*/
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'theme-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'position' 		=> 2
	));

	acf_add_options_sub_page([
		'page_title' => 'Header',
		'menu_title' => __( 'Header', 'ossark-builder' ),
		'menu_slug' => 'header',
		'parent' => 'theme-options'
	]);

	acf_add_options_sub_page([
		'page_title' => 'Footer',
		'menu_title' => __( 'Footer', 'ossark-builder' ),
		'menu_slug' => 'footer',
		'parent' => 'theme-options'
	]);

	acf_add_options_sub_page([
		'page_title' => 'Scripts',
		'menu_title' => __( 'Scripts', 'ossark-builder' ),
		'menu_slug' => 'scripts',
		'parent' => 'theme-options'
	]);

}


/*
=====================
	ACF Maps block
=====================
*/
function map_acf_init() {
	$api_key = get_field('google_maps_api_key', 'option');
	acf_update_setting('google_api_key', $api_key);
}
add_action('acf/init', 'map_acf_init');



/*
=====================
	Add custom block category
=====================
*/
add_filter('block_categories_all', function ($categories, $editor_context) {
	$arr = array_merge(
		array(
			array(
				'slug' => 'hero',
				'title' => 'Hero',
			),
			array(
				'slug' => 'slider',
				'title' => 'Slider',
			),
			array(
				'slug' => 'content',
				'title' => 'Content',
			),
		),
		$categories
	);
	return $arr;
}, 10, 2);

if (function_exists('acf_register_block_type')) {
	add_action('init', 'ossark_register_blocks_from_json');
}


/*
=====================
	Gutenberg blocks — auto-discovery from block.json manifests
=====================
	Every folder under blocks/ that contains a block.json
	is auto-registered. Add a new block by dropping a new folder in.
	No PHP changes required.
*/
function ossark_register_blocks_from_json() {
	$blocks_dir = get_template_directory() . '/blocks';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( glob( $blocks_dir . '/*/block.json' ) as $manifest ) {
		register_block_type( dirname( $manifest ) );
	}
}


/*
=====================
	Whitelist allowed blocks
=====================
	Derived from the same block.json manifests the registrar discovers —
	dropping a new block folder in makes it available automatically.
	Add core block names to $extra_blocks per project if needed.
*/
add_filter( 'allowed_block_types_all', 'allowed_block_types', 10, 2 );

function allowed_block_types( $allowed_blocks, $editor_context ) {

	$all_blocks = [];

	foreach ( glob( get_template_directory() . '/blocks/*/block.json' ) as $manifest ) {
		$data = json_decode( file_get_contents( $manifest ), true );
		if ( ! empty( $data['name'] ) ) {
			$all_blocks[] = $data['name'];
		}
	}

	$extra_blocks = [
		// 'core/paragraph',
	];

	return array_merge( $all_blocks, $extra_blocks );
}

