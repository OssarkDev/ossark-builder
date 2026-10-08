<?php

defined( 'ABSPATH' ) || exit;

/*
  =====================
    Unused media cleaner
  =====================
    Tools > Unused Media scans the Media Library and lists every attachment that
    is not referenced anywhere on the site. Nothing is deleted until you tick
    the files and confirm. Deleted files are removed permanently (including all
    generated image sizes) - take a backup first.

    A file counts as "used" when its ID or file name is found in:
    post content/excerpts (blocks, shortcodes, classic HTML), post meta (featured
    images, ACF fields, galleries), term meta, user meta, the options table
    (theme mods, ACF options, widgets, site icon/logo) or theme files that
    hard-code an uploads path. Anything ambiguous is kept.

    Not covered: references stored in custom database tables or hard-coded in
    plugins. Remove 'unused_media' from functions.php when the clean-up is done.
*/

const OSSARK_UNUSED_MEDIA_FILE_REGEX = '~[^\s"\'<>()\\\\/=,;|\[\]{}:?#&]{1,200}\.[a-z0-9]{2,5}(?![a-z0-9])~i';

add_action( 'admin_menu', function () {
    add_management_page( 'Unused Media', 'Unused Media', 'manage_options', 'unused-media', 'ossark_render_unused_media_page' );
} );

/**
 * Normalise a file name so resized, scaled and converted copies match the original.
 */
function ossark_unused_media_key( $file ) {
    $name = strtolower( rawurldecode( basename( str_replace( '\\', '/', $file ) ) ) );
    $name = preg_replace( '/\.[a-z0-9]{2,5}$/', '', $name );
    $name = preg_replace( '/\.(?:jpe?g|png|gif|bmp|tiff?)$/', '', $name );
    $name = preg_replace( '/-\d+x\d+$/', '', $name );
    $name = preg_replace( '/-e\d{8,}$/', '', $name );
    $name = preg_replace( '/-(?:scaled|rotated)$/', '', $name );

    return $name;
}

function ossark_unused_media_add_list( $list, &$ids ) {
    foreach ( preg_split( '/\s*,\s*/', $list ) as $id ) {
        if ( ctype_digit( $id ) ) {
            $ids[ (int) $id ] = true;
        }
    }
}

/**
 * Pull attachment IDs out of serialized data, JSON and block attributes.
 */
function ossark_unused_media_structured_ids( $s, &$ids ) {
    $pattern = '~\bi:(\d+);|\bs:\d+:"(\d+)";|"(\d+(?:\s*,\s*\d+)*)"|"\s*:\s*(\d+)|\[\s*(\d+(?:\s*,\s*\d+)*)\s*\]~';
    if ( ! preg_match_all( $pattern, $s, $matches, PREG_SET_ORDER ) ) {
        return;
    }

    foreach ( $matches as $m ) {
        foreach ( [ 1, 2, 3, 4, 5 ] as $i ) {
            if ( isset( $m[ $i ] ) && $m[ $i ] !== '' ) {
                ossark_unused_media_add_list( $m[ $i ], $ids );
            }
        }
    }
}

function ossark_unused_media_collect_ids( $s, &$ids, $is_content = false ) {
    $s = trim( (string) $s );
    if ( $s === '' ) {
        return;
    }

    if ( preg_match( '/^\d+(?:\s*,\s*\d+)*$/', $s ) ) {
        ossark_unused_media_add_list( $s, $ids );
        return;
    }

    if ( preg_match_all( '~wp-image-(\d+)|attachment_id[=\s"\':]+(\d+)|data-id=["\'](\d+)|\bids=["\']([\d,\s]+)~', $s, $matches, PREG_SET_ORDER ) ) {
        foreach ( $matches as $m ) {
            foreach ( [ 1, 2, 3, 4 ] as $i ) {
                if ( isset( $m[ $i ] ) && $m[ $i ] !== '' ) {
                    ossark_unused_media_add_list( $m[ $i ], $ids );
                }
            }
        }
    }

    if ( ! $is_content ) {
        ossark_unused_media_structured_ids( $s, $ids );
        return;
    }

    // In post content only block comment attributes hold structured data (ACF blocks, galleries, images).
    if ( preg_match_all( '~<!--\s+wp:[\w/-]+\s+(\{.*?\})\s+/?-->~s', $s, $blocks ) ) {
        foreach ( $blocks[1] as $json ) {
            ossark_unused_media_structured_ids( $json, $ids );
        }
    }
}

function ossark_unused_media_collect_names( $s, &$names ) {
    if ( strpos( (string) $s, '.' ) === false ) {
        return;
    }

    if ( preg_match_all( OSSARK_UNUSED_MEDIA_FILE_REGEX, $s, $matches ) ) {
        foreach ( $matches[0] as $token ) {
            $names[ ossark_unused_media_key( $token ) ] = true;
        }
    }
}

/**
 * Walk a table in id order, in small batches, to keep memory flat.
 */
function ossark_unused_media_each_row( $from, $id_col, $columns, $where, callable $callback ) {
    global $wpdb;

    $last = 0;
    do {
        // $where contains literal % characters, so prepare() is not used; $last is always an int.
        $rows = $wpdb->get_results( "SELECT {$id_col} AS row_id, {$columns} FROM {$from} WHERE {$id_col} > {$last} {$where} ORDER BY {$id_col} ASC LIMIT 200" );
        foreach ( $rows as $row ) {
            $last = (int) $row->row_id;
            $callback( $row );
        }
    } while ( $rows );
}

function ossark_unused_media_scan_theme_files( &$names ) {
    $dirs = array_unique( [ get_template_directory(), get_stylesheet_directory() ] );
    $skip = '~[\\\\/](?:node_modules|vendor|\.git)[\\\\/]~';

    foreach ( $dirs as $dir ) {
        $iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) );
        foreach ( $iterator as $file ) {
            $path = $file->getPathname();
            if ( ! $file->isFile() || $file->getSize() > 2 * MB_IN_BYTES || preg_match( $skip, $path ) ) {
                continue;
            }
            if ( ! preg_match( '/\.(?:php|js|css|scss|json|html?|twig)$/i', $path ) ) {
                continue;
            }

            $contents = file_get_contents( $path );
            if ( $contents === false || stripos( $contents, 'uploads' ) === false ) {
                continue;
            }

            if ( preg_match_all( '~uploads[\\\\/][^\s"\'<>()]{1,300}~i', $contents, $matches ) ) {
                foreach ( $matches[0] as $url ) {
                    $base = basename( str_replace( '\\', '/', $url ) );
                    if ( preg_match( '/\.[a-z0-9]{2,5}$/i', $base ) ) {
                        $names[ ossark_unused_media_key( $base ) ] = true;
                    }
                }
            }
        }
    }
}

/**
 * @return array{unused: int[], total: int}
 */
function ossark_unused_media_scan() {
    global $wpdb;

    if ( function_exists( 'set_time_limit' ) ) {
        @set_time_limit( 0 );
    }
    wp_raise_memory_limit( 'admin' );

    $ids   = [];
    $names = [];

    $collect = static function ( $value, $is_content = false ) use ( &$ids, &$names ) {
        ossark_unused_media_collect_ids( $value, $ids, $is_content );
        ossark_unused_media_collect_names( $value, $names );
    };

    // Posts (excluding the attachments themselves and revisions).
    ossark_unused_media_each_row(
        $wpdb->posts,
        'ID',
        'post_content, post_excerpt',
        " AND post_type NOT IN ('attachment','revision') AND post_status <> 'auto-draft'",
        static function ( $row ) use ( $collect ) {
            $collect( $row->post_content, true );
            $collect( $row->post_excerpt, true );
        }
    );

    // Post meta (excluding meta that belongs to attachments and revisions).
    ossark_unused_media_each_row(
        "{$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id",
        'pm.meta_id',
        'pm.meta_value',
        " AND p.post_type NOT IN ('attachment','revision') AND p.post_status <> 'auto-draft'",
        static function ( $row ) use ( $collect ) {
            $collect( $row->meta_value );
        }
    );

    ossark_unused_media_each_row( $wpdb->termmeta, 'meta_id', 'meta_value', '', static function ( $row ) use ( $collect ) {
        $collect( $row->meta_value );
    } );

    $user_skip = '~(?:capabilities|user_level|session_tokens|persisted_preferences|dismissed|metaboxhidden|closedpostboxes|screen_layout|managenav|nav_menu_recently|_per_page|dashboard)~';
    ossark_unused_media_each_row( $wpdb->usermeta, 'umeta_id', 'meta_key, meta_value', '', static function ( $row ) use ( $collect, $user_skip ) {
        if ( ! preg_match( $user_skip, $row->meta_key ) ) {
            $collect( $row->meta_value );
        }
    } );

    $options_where = " AND option_name NOT LIKE '\\_transient\\_%' AND option_name NOT LIKE '\\_site\\_transient\\_%' AND option_name <> 'cron'";
    ossark_unused_media_each_row( $wpdb->options, 'option_id', 'option_value', $options_where, static function ( $row ) use ( $collect ) {
        $collect( $row->option_value );
    } );

    ossark_unused_media_scan_theme_files( $names );

    $attachment_ids = array_map( 'intval', $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment'" ) );
    $unused         = [];

    foreach ( array_chunk( $attachment_ids, 200 ) as $chunk ) {
        update_meta_cache( 'post', $chunk );

        foreach ( $chunk as $id ) {
            if ( isset( $ids[ $id ] ) ) {
                continue;
            }

            $keys = [];
            $file = get_post_meta( $id, '_wp_attached_file', true );
            if ( $file ) {
                $keys[] = ossark_unused_media_key( $file );
            }
            $meta = wp_get_attachment_metadata( $id );
            if ( is_array( $meta ) && ! empty( $meta['original_image'] ) ) {
                $keys[] = ossark_unused_media_key( $meta['original_image'] );
            }

            $is_used = false;
            foreach ( $keys as $key ) {
                if ( isset( $names[ $key ] ) ) {
                    $is_used = true;
                    break;
                }
            }

            if ( ! $is_used ) {
                $unused[] = $id;
            }
        }
    }

    return [ 'unused' => $unused, 'total' => count( $attachment_ids ) ];
}

function ossark_unused_media_transient_key() {
    return 'ossark_unused_media_' . get_current_user_id();
}

function ossark_render_unused_media_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $transient = ossark_unused_media_transient_key();
    $notice    = '';
    $result    = null;

    if ( isset( $_POST['ossark_unused_media_delete'] ) && check_admin_referer( 'ossark_unused_media_delete' ) ) {
        $allowed  = array_map( 'intval', (array) get_transient( $transient ) );
        $selected = isset( $_POST['attachments'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['attachments'] ) ) : [];
        $to_delete = array_values( array_intersect( $selected, $allowed ) );
        $deleted  = 0;

        foreach ( $to_delete as $id ) {
            if ( wp_delete_attachment( $id, true ) ) {
                $deleted++;
            }
        }

        set_transient( $transient, array_values( array_diff( $allowed, $to_delete ) ), HOUR_IN_SECONDS );
        $notice = sprintf( '%d file(s) deleted permanently.', $deleted );
    } elseif ( isset( $_POST['ossark_unused_media_scan'] ) && check_admin_referer( 'ossark_unused_media_scan' ) ) {
        $result = ossark_unused_media_scan();
        set_transient( $transient, $result['unused'], HOUR_IN_SECONDS );
    }
    ?>
    <div class="wrap">
        <h1>Unused Media</h1>

        <?php if ( $notice ) : ?>
            <div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
        <?php endif; ?>

        <p>Scans every file in the Media Library and lists those not referenced in content, meta, options or theme files. Nothing is deleted until you select files and confirm.</p>
        <div class="notice notice-warning inline"><p><strong>Back up the database and uploads folder first.</strong> Deletion is permanent. Files used only by plugins that store references in custom tables, or by external sites, may be listed as unused.</p></div>

        <form method="post" style="margin-top:16px">
            <?php wp_nonce_field( 'ossark_unused_media_scan' ); ?>
            <button type="submit" name="ossark_unused_media_scan" value="1" class="button button-primary">Scan for unused media</button>
        </form>

        <?php if ( $result ) : ?>
            <?php
            $total_size = 0;
            $rows       = [];
            foreach ( $result['unused'] as $id ) {
                $path = get_attached_file( $id );
                $size = $path && file_exists( $path ) ? (int) filesize( $path ) : 0;
                $total_size += $size;
                $rows[]      = [ 'id' => $id, 'path' => $path, 'size' => $size ];
            }
            ?>
            <h2><?php echo esc_html( sprintf( '%d of %d files are unused (%s)', count( $rows ), $result['total'], size_format( $total_size ) ) ); ?></h2>

            <?php if ( $rows ) : ?>
                <form method="post">
                    <?php wp_nonce_field( 'ossark_unused_media_delete' ); ?>
                    <p><button type="submit" name="ossark_unused_media_delete" value="1" class="button button-secondary"
                        onclick="return confirm('Permanently delete the selected files? This cannot be undone.');">Delete selected</button></p>

                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <td class="check-column"><input type="checkbox" id="ossark-um-all" checked></td>
                                <th>Preview</th><th>Title</th><th>File</th><th>Size</th><th>Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $rows as $row ) : $id = $row['id']; ?>
                            <tr>
                                <th class="check-column"><input type="checkbox" class="ossark-um-item" name="attachments[]" value="<?php echo esc_attr( $id ); ?>" checked></th>
                                <td><?php echo wp_get_attachment_image( $id, [ 60, 60 ], true ); ?></td>
                                <td><a href="<?php echo esc_url( get_edit_post_link( $id ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( get_the_title( $id ) ?: '#' . $id ); ?></a></td>
                                <td><?php echo esc_html( $row['path'] ? wp_basename( $row['path'] ) : '' ); ?></td>
                                <td><?php echo esc_html( size_format( $row['size'] ) ); ?></td>
                                <td><?php echo esc_html( get_the_date( 'Y-m-d', $id ) ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
                <script>
                    document.getElementById('ossark-um-all').addEventListener('change', function (e) {
                        document.querySelectorAll('.ossark-um-item').forEach(function (box) { box.checked = e.target.checked; });
                    });
                </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}
