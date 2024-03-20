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
function ossarkButton($button, $classes, $arrowType) {
    // $button is the ACF array for a link. $classes includes an extra classes for .btn. $arrows can be seen in icons
    $output = '';
    $link = $button['url'] ?: '';
    $target = $button['target'] ?: '_self';
    $title = $button['title'] ?: 'Read more';
    $arrow = $arrowType ?: 'black-large';
  
    $mobile = ''; 
  
    if(isMobile()){
        $mobile = ' small';
        $arrow = str_replace('large', 'small', $arrow);
    } else {
        // console_log('not mobile');
    }
    
    $output .= 
        '<a href="'. $link . '" target="'. $target .'" class="btn '. $classes . $mobile .'">
        <span>'. $title .'</span>
        '. get_inline_svg($arrow) .'
        </a>';
  
    return $output;
  }