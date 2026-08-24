<?php

defined( 'ABSPATH' ) || exit;

/*
	=====================
		PHP Console.log
	=====================	
*/
// Debug-only: emit data to the browser console. No-op unless WP_DEBUG is on.
function console_log($data) {
  if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
    return;
  }

  $json = wp_json_encode( $data );
  if ( false === $json ) {
    return;
  }

  printf(
    '<script>console.log(%s, %s);</script>',
    wp_json_encode( 'Debug Objects:' ),
    $json
  );
}

/*
	=====================
		Get SVG file content
	=====================	
*/
function get_svg($name){
  if ($name) {
    $file_name = (substr($name, -4) === '.svg') ? $name : $name . '.svg';
    $svg_path  = get_template_directory() . '/assets/icons/' . $file_name;
    if (file_exists($svg_path)) {
      return file_get_contents($svg_path);
    } else {
      console_log('SVG file not found:' . $svg_path);
      return '';
    }
  }
  return '';
}

/*
	=====================
		Limit excerpt length function
	=====================	
*/
function excerpt($limit,$post_id=-1) {
    if($post_id==-1):
      $excerpt = explode(' ', get_the_excerpt(), $limit);
    else:
      $excerpt = explode(' ', get_the_excerpt($post_id), $limit);
    endif;
    if (count($excerpt)>=$limit) {
      array_pop($excerpt);
      $excerpt = implode(" ",$excerpt).'...';
    } else {
      $excerpt = implode(" ",$excerpt);
    } 
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return $excerpt;
}


/*
	=====================
		Get svg icon from sprite usage: icon( 'check' ); or icon( 'check', 'test_mod' );
	=====================	
*/
function icon( $icon_name, $icon_mod = null ) {
  if ( $icon_name ) {
    $out     = '';
    $classes = ( ! $icon_mod ) ? 'icon icon-' . $icon_name : 'icon icon-' . $icon_name . ' ' . $icon_mod;
    $out    .= '<svg class="' . $classes . '"><use xlink:href="#' . $icon_name .'"></use></svg>';

    echo $out;
  } else {
    return false;
  }
}

/*
	=====================
		Hardcode the siturl and home in the DB (if page keeps refreshing)
	=====================	
*/
// update_option( 'siteurl', 'http://example.com' );
// update_option( 'home', 'http://example.com' );




/*
	=====================
		Make youtube embed from share link
	=====================	
*/
function returnYoutubeUrl($url){
  $parts = parse_url($url);
  $videoID = '';
  $query = [];
  if(isset($parts['query'])){
      parse_str($parts['query'], $query);
  }
  if(str_contains($url, 'youtu.be')){
      $videoID = substr($parts['path'], 1);
  }
  if($query && isset($query['v'])){
      $videoID = $query['v'];
  }
  if(str_contains($url, 'embed/')){
      $videoID = explode('embed/', $url)[1];
  }
  return 'https://www.youtube.com/embed/' . $videoID;
}


/*
  =====================
    Get ACF block data from post content
  =====================	
*/
function get_acf_block_data($post_id, $block_name, $field_name) {
  $post = get_post($post_id); // Retrieve the post object
  $content = '';

  if (!$post) {
      return $content;
  }

  if (has_blocks($post->post_content)) {
      $blocks = parse_blocks($post->post_content); // Parse all blocks

      foreach ($blocks as $block) {
          // Check if this is the target block
          if ($block['blockName'] === $block_name) {
              // Check if the desired field exists in the block's attributes
              if (isset($block['attrs']['data'][$field_name])) {
                  $content = $block['attrs']['data'][$field_name];
                  break;
              }
          }
      }
  }

  return $content; // Return the field value
}