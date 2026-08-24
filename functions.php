<?php

defined( 'ABSPATH' ) || exit;

$ossark_theme_includes = [
    'cleanup',
    'setup_theme',
    'acf',
    'custom_post_types',
    'enqueue_scripts',
    'theme_functions',
    'headers',
    'ui_kit',
    'editor_template_parts',
    'coming_soon',
    'debug',
    // 'custom_taxonomies',
    // 'theme_ajax',
    // 'woocommerce',
];

foreach ( $ossark_theme_includes as $ossark_theme_include ) {
    require_once get_template_directory() . '/include/' . $ossark_theme_include . '.php';
}

unset( $ossark_theme_include, $ossark_theme_includes );

