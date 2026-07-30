<?php

defined( 'ABSPATH' ) || exit;

/*
  =====================
    Title
  =====================
*/
function ui_title($title, $classes = '', $heading_size = 'h2') {
    if (empty($title)) {
        return;
    }

    $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
    $tag = in_array(strtolower((string) $heading_size), $allowed, true) ? strtolower($heading_size) : 'h2';

    printf(
        '<div class="%s"><%s>%s</%s></div>',
        esc_attr($classes),
        $tag,
        esc_html($title),
        $tag
    );
}

/*
  =====================
    Button
  =====================
*/
function get_button($button, $classes = '') {
    if (is_array($button)) {
      $link = $button['url'] ?? '';
      $target = $button['target'] ?? '_self';
      $title = $button['title'] ?? 'Read more';
    } else {
      $link = $button;
      $target = '_self';
      $title = 'Read more';
    }

    if (empty($link)) {
      return '';
    }

    $rel = ('_blank' === $target) ? ' rel="noopener noreferrer"' : '';

    return sprintf(
      '<a href="%s" target="%s"%s class="btn %s"><span>%s</span></a>',
      esc_url($link),
      esc_attr($target),
      $rel,
      esc_attr($classes),
      esc_html($title)
    );
  }

/*
  =====================
    Image
  =====================
*/
function get_image($image, $classes = '', $size = 'full', $lazy = true) {
    if (empty($image)) {
        return '';
    }

    $attr = [];
    if (!empty($classes)) {
        $attr['class'] = $classes;
    }
    if ($lazy) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }

    if (is_array($image) && !empty($image['ID'])) {
        return wp_get_attachment_image($image['ID'], $size, false, $attr);
    }

    if (is_numeric($image)) {
        return wp_get_attachment_image((int) $image, $size, false, $attr);
    }

    $url = '';
    $alt = '';
    if (is_array($image)) {
        $url = isset($image['sizes'][$size]) ? $image['sizes'][$size] : (isset($image['url']) ? $image['url'] : '');
        $alt = isset($image['alt']) ? $image['alt'] : '';
    } elseif (is_string($image)) {
        $url = $image;
    }

    if (empty($url)) {
        return '';
    }

    $attr_string = '';
    foreach ($attr as $key => $value) {
        $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
    }

    return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '"' . $attr_string . ' />';
}

/*
  =====================
    Get Part
  =====================
*/
function get_part( $template, $args = [] ) {
    $template_path = 'components/parts/' . $template . '.php';
    $resolved = locate_template( $template_path );

    if ( ! $resolved ) {
        trigger_error( "Template not found: $template_path", E_USER_WARNING );
        return;
    }

    include $resolved;
}

/*
  =====================
    Get Block
  =====================
*/
// Includes a block's render.php from components/blocks/{template}/render.php.
function get_block( $template, $args = [] ) {
  $template_path = 'components/blocks/' . $template . '/render.php';
  $resolved = locate_template( $template_path );

  if ( ! $resolved ) {
    trigger_error( "Block template not found: $template_path", E_USER_WARNING );
    return;
  }

  include $resolved;
}