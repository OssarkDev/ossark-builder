<?php

/*
  =====================
    Title
  =====================
*/
function ui_title($title, $classes = '', $heading_size = '') {
    if (!empty($title)) {
        echo '<div class="' . esc_attr($classes) . '">';
        echo '<' . esc_html($heading_size) . '>';
        echo esc_html($title);
        echo '</' . esc_html($heading_size) . '>';
        echo '</div>';
    }
}

/*
  =====================
    Button
  =====================
*/
function get_button($button, $classes) {
    $output = '';

    if(is_array($button)) {
      $link = isset($button['url']) ? $button['url'] : '';
      $target = isset($button['target']) ? $button['target'] : '_self';
      $title = isset($button['title']) ? $button['title'] : 'Read more';
    } else {
      $link = $button;
      $target = '_self';
      $title = 'Read more';
    }
    
    $output .= 
      '<a href="'. $link . '" target="'. $target .'" class="btn '. $classes .'">
      <span>'. $title .'</span>
      </a>';
  
    return $output;
  }

/*
  =====================
    Block Previews
  =====================
*/
function previewImage($block_name) {
  $block_name = str_replace('acf/', '', $block_name);
  echo '<img src="' . get_template_directory_uri() . '/assets/block-previews/' . $block_name . '.jpg" alt="Preview for block" width="100%" height="auto"/>';
}

/*
  =====================
    Get Part
  =====================
*/
function get_part( $template, $args = [] ) {
    $template_path = 'components/parts/' . $template . '.php';
    $template = locate_template( $template_path );

    if ( ! $template ) {
        trigger_error( "Template not found: $template_path", E_USER_WARNING );
        return;
    }

    // Isolate scope and extract args
    extract( $args, EXTR_SKIP );
    include $template;
}

/*
  =====================
    Get Block
  =====================
*/
function get_block( $template, $args = [] ) {
  $template_path = 'components/blocks/' . $template . '.php';
  $template = locate_template( $template_path );

  if ( ! $template ) {
    trigger_error( "Block template not found: $template_path", E_USER_WARNING );
    return;
  }

  extract( $args, EXTR_SKIP );
  include $template;
}