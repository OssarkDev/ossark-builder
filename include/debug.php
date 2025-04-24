<?php

/*
  =====================
    Enable debug mode - uncomment to use
  =====================	
*/

  if ( get_option( 'my_debug_mode' ) ) {
    add_action( 'after_setup_theme', function() {
      error_reporting( E_ALL );
      ini_set( 'display_errors', 1 );
      ini_set( 'log_errors', 1 );
      ini_set( 'error_log', get_template_directory() . '/debug.txt' );
    });
  }

  add_action( 'admin_menu', function() {
    add_options_page(
      'Debug Mode',
      'Debug Mode',
      'manage_options',
      'debug-mode',
      function() {
        ?>
        <div class="wrap">
          <h1>Debug Mode</h1>
          <form method="post" action="options.php">
            <?php
            settings_fields( 'my_debug_mode_options_group' );
            do_settings_sections( 'my_debug_mode_options_group' );
            ?>
            <table class="form-table">
                <tr valign="top">
                <tr valign="top">
                  <th scope="row">Enable Debug Mode (Errors and Warnings)</th>
                  <td><input type="checkbox" name="my_debug_mode" value="1" <?php checked( 1, get_option( 'my_debug_mode' ), true ); ?> /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
          </form>
        </div>
        <?php
      }
    );
  });

  add_action( 'admin_init', function() {
    register_setting( 'my_debug_mode_options_group', 'my_debug_mode' );
  });
