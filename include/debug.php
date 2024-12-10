<?php

/*
  =====================
    Enable debug mode - uncomment to use
  =====================	
*/

  add_action( 'after_setup_theme', 'my_enable_debug_mode' );

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