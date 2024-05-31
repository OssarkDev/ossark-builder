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