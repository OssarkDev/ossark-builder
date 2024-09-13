<?php

/*
	=====================
		Get SVG file content
	=====================	
*/
function get_inline_svg($name){
    if($name):
    return file_get_contents(esc_url(get_template_directory_uri().'/assets/'.$name));
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


/*
  =====================
    Enable debug mode - uncomment to use
  =====================	
*/
function my_enable_debug_mode() {
  // Turn on error reporting.
  error_reporting( E_ALL );
  // Sets to display errors on screen. Use 0 to turn off.
  ini_set( 'display_errors', 1 );
  // Sets to log errors. Use 0 (or omit) to not log errors.
  ini_set( 'log_errors', 1 );
  // Sets a log file path you can access in the theme editor.
  $log_path = get_template_directory() . '/debug.txt';
  ini_set( 'error_log', $log_path );
}

add_action( 'after_setup_theme', 'my_enable_debug_mode' );


/*
	=====================
		Send all traffic to coming soon page
	=====================	
*/

add_action('init', function() {
  $coming_soon = get_field('coming_soon', 'option');
  if ($coming_soon) {
    add_action( 'template_redirect', function() {
      if ( is_page( 'coming-soon' ) ) {
          return;
      }
      wp_redirect( esc_url_raw( home_url( 'coming-soon' ) ) );
      exit;
    });
  }
});