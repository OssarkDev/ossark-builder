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
function ossarkButton($button, $classes) {
    $output = '';

    if(is_array($button)) {
      $link = $button['url'] ?: '';
      $target = $button['target'] ?: '_self';
      $title = $button['title'] ?: 'Read more';
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
  Preview Block Images
=====================
*/

function previewImage($block_name) {
  $block_name = str_replace('acf/', '', $block_name);
  echo '<img src="' . get_template_directory_uri() . '/assets/block-previews/' . $block_name . '.jpg" alt="Preview for block" width="100%" height="auto"/>';
}