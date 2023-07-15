<?php
/*
=====================
	Add custom block category
=====================
*/
add_filter('block_categories', function ($categories, $post) {
    $arr = array_merge(
        array(
            array(
                'slug' => 'osmo-blocks',
                'title' => 'Osmo Blocks',
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
    acf_register_block_type(array(
        'name' => 'hero-section',
        'title' => __('Hero Section'),
        'description'   => __('Hero block'),
        'render_template' => 'components/hero.php',
        'category' => 'osmo-blocks',
        'icon' => 'admin-comments',
        'keywords' => array('Hero Section', 'Content'),
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
	Add theme options menu
=====================
*/
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' 	=> 'Theme Options',
        'menu_title'	=> 'Theme Options',
        'menu_slug' 	=> 'theme-options',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
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
	Remove default blocks / only show:
=====================
*/
add_filter( 'allowed_block_types', 'allowed_block_types', 10, 2 );

function allowed_block_types( $allowed_blocks, $post ) {

    $all_blocks = [
      'acf/text-block',
    ];
    $article_blocks = [];
    $services_blocks = [];
    $work_blocks = [];
    $vacancy_blocks = [];
  
    switch( $post->post_type ) {
      case 'article':
          return $article_blocks;
          break;
      case 'services':
          return $services_blocks;
          break;
      case 'work':
          return $work_blocks;
          break;
      case 'vacancy':
          return $vacancy_blocks;
          break;
      default:
          return $all_blocks;
    }
  }

