<?php

defined( 'ABSPATH' ) || exit;

/*
  =====================
    Debug logging (always on)
  =====================
    Errors are always captured and written to a log file — they are never
    displayed to visitors. View, copy or clear the log under Tools > Debug Log.

    Note: the log lives inside the theme and may be web-accessible. Block
    public access to debug.log at the server level on production.
*/

// Shared path to the log file.
function ossark_debug_log_path() {
    return get_template_directory() . '/debug.log';
}

// Always log PHP errors to file; never display them.
add_action( 'after_setup_theme', function () {
    error_reporting( E_ALL );
    ini_set( 'display_errors', 0 );
    ini_set( 'log_errors', 1 );
    ini_set( 'error_log', ossark_debug_log_path() );
} );

/*
  =====================
    Admin page — Tools > Debug Log
  =====================
*/
add_action( 'admin_menu', function () {
    add_management_page(
        'Debug Log',
        'Debug Log',
        'manage_options',
        'debug-log',
        'ossark_render_debug_log_page'
    );
} );

// Read the log, tailing to the last 512 KB so large files stay manageable.
function ossark_read_debug_log() {
    $path = ossark_debug_log_path();

    if ( ! file_exists( $path ) || ! filesize( $path ) ) {
        return '';
    }

    $max  = 512 * 1024;
    $size = filesize( $path );
    $fp   = fopen( $path, 'r' );

    if ( ! $fp ) {
        return '';
    }

    if ( $size > $max ) {
        fseek( $fp, -$max, SEEK_END );
        $contents = "…(truncated — showing last " . size_format( $max ) . ")\n\n" . fread( $fp, $max );
    } else {
        $contents = fread( $fp, $size );
    }

    fclose( $fp );

    return $contents;
}

function ossark_render_debug_log_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $path     = ossark_debug_log_path();
    $contents = ossark_read_debug_log();
    $size     = file_exists( $path ) ? size_format( filesize( $path ) ) : '0 B';
    $modified = file_exists( $path ) && filesize( $path )
        ? date_i18n( 'Y-m-d H:i:s', filemtime( $path ) )
        : '—';
    ?>
    <div class="wrap">
        <h1>Debug Log</h1>

        <?php if ( isset( $_GET['cleared'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Debug log cleared.</p></div>
        <?php endif; ?>

        <p>
            <code><?php echo esc_html( $path ); ?></code><br>
            <strong>Size:</strong> <?php echo esc_html( $size ); ?> &nbsp;|&nbsp;
            <strong>Last error:</strong> <?php echo esc_html( $modified ); ?>
        </p>

        <textarea id="ossark-debug-log" readonly rows="24"
            style="width:100%;font-family:Menlo,Consolas,monospace;font-size:12px;white-space:pre;overflow:auto;"><?php
            echo esc_textarea( $contents ?: 'No errors logged.' );
        ?></textarea>

        <p>
            <button type="button" class="button button-primary" onclick="ossarkCopyDebugLog(this)">Copy to clipboard</button>

            <a href="<?php echo esc_url( add_query_arg( 'page', 'debug-log', admin_url( 'tools.php' ) ) ); ?>" class="button">Refresh</a>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;">
                <input type="hidden" name="action" value="ossark_clear_debug_log">
                <?php wp_nonce_field( 'ossark_clear_debug_log' ); ?>
                <button type="submit" class="button" onclick="return confirm('Clear the debug log?');">Clear log</button>
            </form>
        </p>

        <script>
        function ossarkCopyDebugLog( btn ) {
            const ta = document.getElementById( 'ossark-debug-log' );
            const done = () => { const t = btn.textContent; btn.textContent = 'Copied!'; setTimeout( () => btn.textContent = t, 1500 ); };
            if ( navigator.clipboard && navigator.clipboard.writeText ) {
                navigator.clipboard.writeText( ta.value ).then( done, () => { ta.select(); document.execCommand( 'copy' ); done(); } );
            } else {
                ta.select();
                document.execCommand( 'copy' );
                done();
            }
        }
        // Jump to the newest entries.
        ( function () {
            const ta = document.getElementById( 'ossark-debug-log' );
            if ( ta ) { ta.scrollTop = ta.scrollHeight; }
        } )();
        </script>
    </div>
    <?php
}

// Handle the "Clear log" action.
add_action( 'admin_post_ossark_clear_debug_log', function () {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Insufficient permissions.' );
    }

    check_admin_referer( 'ossark_clear_debug_log' );

    $path = ossark_debug_log_path();
    if ( file_exists( $path ) ) {
        file_put_contents( $path, '' );
    }

    wp_safe_redirect( add_query_arg(
        [ 'page' => 'debug-log', 'cleared' => '1' ],
        admin_url( 'tools.php' )
    ) );
    exit;
} );
