<?php

/*
	=====================
		Get SVG file content
	=====================	
*/
function get_inline_svg($name){
  if($name):
  return file_get_contents(get_template_directory().'/assets/icons/'.$name);
  endif;
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
		PHP Console.log
	=====================	
*/
function console_log($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . json_encode($output) . "' );</script>";
}




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