<?php


/*
  =====================
    Make Gutenber blocks full width & add styles
  =====================
*/
add_action('admin_head', 'my_custom_width_gutenberg');

function my_custom_width_gutenberg() {
    echo '<style>
    .wp-block{
        max-width: 95% !important;
    }
    .acf-block-body .acf-block-fields {
      border: 2px solid #000 !important;
	  border-radius: 10px;
    }
	.editor-styles-wrapper {
		background-color: #FFF !important;
	}
	.button, .page-title-action {
		border-radius: 40px !important;
	}
  </style>';
}

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
		'page_title' => "Header",
		'menu_title' => __("Header"),
		'menu_slug' => "header",
		'parent' => 'theme-options'
	]);

	acf_add_options_sub_page([
		'page_title' => "Footer",
		'menu_title' => __("Footer"),
		'menu_slug' => "footer",
		'parent' => 'theme-options'
	]);

	acf_add_options_sub_page([
		'page_title' => "Scripts",
		'menu_title' => __("Scripts"),
		'menu_slug' => "scripts",
		'parent' => 'theme-options'
	]);

}


/*
=====================
	ACF Maps block
=====================
*/
function map_acf_init() {
	$api_key = get_field('google_map_api_key', 'option');
	acf_update_setting('google_api_key', $api_key);
}
add_action('acf/init', 'map_acf_init');



/*
=====================
	Add custom block category
=====================
*/
add_filter('block_categories', function ($categories, $post) {
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
	add_action('acf/init', 'register_acf_block_types');
}


/*
=====================
	Gutenberg blocks
=====================
*/
function register_acf_block_types()
{

	// form
	acf_register_block_type(array(
		'name' => 'form',
		'title' => __('Form'),
		'description'   => __('Contact Form'),
		'render_template' => 'components/blocks/form.php',
		'category' => 'content',
		'icon' => 'block-default',
		'keywords' => array('Contact', 'Form'),
		'mode' => 'edit',
		'example'  => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview'    => true
				)
			)
		)
	));

	// map
	acf_register_block_type(array(
		'name' => 'map',
		'title' => __('Map'),
		'description'   => __('Map component'),
		'render_template' => 'components/blocks/map.php',
		'category' => 'content',
		'icon' => 'block-default',
		'keywords' => array('Map', 'Location'),
		'mode' => 'edit',
		'example'  => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview'    => true
				)
			)
		)
	));

	// ajax test
	acf_register_block_type(array(
		'name' => 'ajax-test',
		'title' => __('Ajax Test'),
		'description'   => __('Ajax Test component'),
		'render_template' => 'components/blocks/ajax-test.php',
		'category' => 'content',
		'icon' => 'block-default',
		'keywords' => array('Ajax', 'Test'),
		'mode' => 'edit',
		'example'  => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview'    => true
				)
			)
		)
	));

	// filters
	acf_register_block_type(array(
		'name' => 'filters',
		'title' => __('Filters'),
		'description'   => __('Filters component'),
		'render_template' => 'components/blocks/filters.php',
		'category' => 'content',
		'icon' => 'block-default',
		'keywords' => array('Filters', 'Search'),
		'mode' => 'edit',
		'example'  => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview'    => true
				)
			)
		)
	));

}


/*
=====================
	Remove default blocks / only show:
=====================
*/
add_filter( 'allowed_block_types_all', 'allowed_block_types', 10, 2 );

function allowed_block_types( $allowed_blocks, $post ) {

	$all_blocks = [
		'acf/form',
		'acf/map',
		'acf/ajax-test',
		'acf/filters',
	];

	return $all_blocks;
}

