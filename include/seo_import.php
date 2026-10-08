<?php

defined( 'ABSPATH' ) || exit;

/*
  =====================
    Yoast SEO bulk import
  =====================
    Tools > SEO Import reads seo/seo-data.csv and sets the Yoast SEO title,
    meta description and focus keyphrase on each matching post (matched by
    post type + slug). It previews first and never overwrites existing Yoast
    values unless asked.
    CSV columns: post_type, slug, seo_title, meta_description, focus_keyphrase.
    Generate seo/seo-data.csv locally, then run the import on live. Once the
    import is done, remove 'seo_import' from functions.php and delete the seo/ folder.
*/

const OSSARK_SEO_META = [
    'seo_title'        => '_yoast_wpseo_title',
    'meta_description' => '_yoast_wpseo_metadesc',
    'focus_keyphrase'  => '_yoast_wpseo_focuskw',
];

add_action( 'admin_menu', function () {
    add_management_page( 'SEO Import', 'SEO Import', 'manage_options', 'seo-import', 'ossark_render_seo_import_page' );
} );

function ossark_seo_import_rows() {
    $path = get_template_directory() . '/seo/seo-data.csv';
    if ( ! is_readable( $path ) ) {
        return [];
    }

    $rows   = [];
    $handle = fopen( $path, 'r' );
    $header = fgetcsv( $handle );
    if ( $header ) {
        $header[0] = ltrim( $header[0], "\xEF\xBB\xBF" );
    }

    while ( ( $line = fgetcsv( $handle ) ) !== false ) {
        if ( $header && count( $line ) === count( $header ) ) {
            $rows[] = array_combine( $header, $line );
        }
    }
    fclose( $handle );

    return $rows;
}

function ossark_seo_find_post( $row ) {
    $posts = get_posts( [
        'post_type'      => $row['post_type'],
        'name'           => $row['slug'],
        'post_status'    => [ 'publish', 'draft', 'private', 'pending', 'future' ],
        'posts_per_page' => 1,
    ] );

    return $posts ? $posts[0] : null;
}

function ossark_render_seo_import_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $rows      = ossark_seo_import_rows();
    $applying  = isset( $_POST['ossark_seo_apply'] ) && check_admin_referer( 'ossark_seo_import' );
    $overwrite = $applying && ! empty( $_POST['overwrite'] );
    $applied   = 0;
    $report    = [];

    foreach ( $rows as $row ) {
        $post = ossark_seo_find_post( $row );
        $item = [ 'row' => $row, 'post' => $post, 'status' => $post ? '' : 'Not found' ];

        if ( $post ) {
            $existing = [];
            foreach ( OSSARK_SEO_META as $col => $meta_key ) {
                $existing[ $col ] = (string) get_post_meta( $post->ID, $meta_key, true );
            }
            $item['existing'] = $existing;
            $has_existing     = (bool) array_filter( $existing );

            if ( $applying && ( $overwrite || ! $has_existing ) ) {
                foreach ( OSSARK_SEO_META as $col => $meta_key ) {
                    update_post_meta( $post->ID, $meta_key, wp_slash( $row[ $col ] ) );
                }
                // Re-save so Yoast refreshes its indexable for this post.
                wp_update_post( [ 'ID' => $post->ID ] );
                $item['status'] = 'Updated';
                $applied++;
            } elseif ( $has_existing && ! $overwrite ) {
                $item['status'] = $applying ? 'Skipped (already has Yoast data)' : 'Has Yoast data (will be skipped)';
            } else {
                $item['status'] = $applying ? '' : 'Ready to import';
            }
        }

        $report[] = $item;
    }
    ?>
    <div class="wrap">
        <h1>SEO Import</h1>

        <?php if ( ! $rows ) : ?>
            <div class="notice notice-error"><p>No data found. Expected <code>seo/seo-data.csv</code> in the theme folder.</p></div>
        <?php else : ?>
            <?php if ( $applying ) : ?>
                <div class="notice notice-success"><p><?php echo esc_html( $applied ); ?> post(s) updated.</p></div>
            <?php endif; ?>

            <form method="post">
                <?php wp_nonce_field( 'ossark_seo_import' ); ?>
                <p>
                    <label><input type="checkbox" name="overwrite" value="1"> Overwrite existing Yoast title, description and keyphrase</label>
                </p>
                <p><button type="submit" name="ossark_seo_apply" value="1" class="button button-primary"
                    onclick="return confirm('Apply SEO data to all matching posts?');">Apply SEO data</button></p>
            </form>

            <table class="widefat striped" style="margin-top:16px">
                <thead><tr><th>Type</th><th>Slug</th><th>Post</th><th>SEO title</th><th>Meta description</th><th>Keyphrase</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ( $report as $item ) : $row = $item['row']; ?>
                    <tr>
                        <td><?php echo esc_html( $row['post_type'] ); ?></td>
                        <td><?php echo esc_html( $row['slug'] ); ?></td>
                        <td><?php echo $item['post'] ? esc_html( '#' . $item['post']->ID . ' ' . $item['post']->post_title ) : '&mdash;'; ?></td>
                        <td><?php echo esc_html( $row['seo_title'] ); ?></td>
                        <td><?php echo esc_html( $row['meta_description'] ); ?></td>
                        <td><?php echo esc_html( $row['focus_keyphrase'] ); ?></td>
                        <td><strong><?php echo esc_html( $item['status'] ); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}
