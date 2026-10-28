<?php
/**
 * Editor Template Parts Preview System
 *
 * Automatically detects `get_part(...)` calls within the PHP template
 * corresponding to the post currently being edited (e.g. single-service.php,
 * single-work.php, single.php, page.php, or custom templates), and renders
 * those template parts live inside the WordPress Block Editor canvas
 * (above and below the Gutenberg block layout).
 *
 * @package ossark-builder
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve which PHP template file WordPress will use for a given post.
 *
 * Checks explicit page templates, followed by the template hierarchy
 * for custom post types, posts, and pages.
 *
 * @param int|WP_Post $post_or_id   Post ID or WP_Post object.
 * @param string      $template_slug Optional explicit template slug to test.
 * @return string Absolute path to the template file, or empty string if not found.
 */
function ossark_resolve_post_template_file( $post_or_id = 0, string $template_slug = '' ): string {
    $post = $post_or_id ? get_post( $post_or_id ) : null;

    if ( ! $post ) {
        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
        $slug      = '';
        $id        = 0;
    } else {
        $post_type = $post->post_type;
        $slug      = $post->post_name;
        $id        = $post->ID;
    }

    // 1. Explicit or saved page template
    $tpl = $template_slug;
    if ( empty( $tpl ) && $post ) {
        $tpl = get_page_template_slug( $post );
    }

    if ( ! empty( $tpl ) && 'default' !== $tpl ) {
        $located = locate_template( [ $tpl, 'templates/' . $tpl ] );
        if ( $located ) {
            return $located;
        }
    }

    // 2. Post type template hierarchy
    $candidates = [];

    if ( 'page' === $post_type ) {
        if ( ! empty( $slug ) ) {
            $candidates[] = "page-{$slug}.php";
        }
        if ( ! empty( $id ) ) {
            $candidates[] = "page-{$id}.php";
        }
        $candidates[] = 'page.php';
        $candidates[] = 'singular.php';
        $candidates[] = 'index.php';
    } elseif ( 'post' === $post_type ) {
        if ( ! empty( $slug ) ) {
            $candidates[] = "single-post-{$slug}.php";
        }
        $candidates[] = 'single-post.php';
        $candidates[] = 'single.php';
        $candidates[] = 'singular.php';
        $candidates[] = 'index.php';
    } else {
        // Custom post types (e.g. 'service', 'work', 'project', etc.)
        if ( ! empty( $slug ) ) {
            $candidates[] = "single-{$post_type}-{$slug}.php";
        }
        $candidates[] = "single-{$post_type}.php";
        $candidates[] = 'single.php';
        $candidates[] = 'singular.php';
        $candidates[] = 'index.php';
    }

    $located = locate_template( $candidates );
    return $located ?: '';
}

/**
 * Parse a template PHP file and extract all get_part() calls, classifying
 * them as occurring before or after the_content().
 *
 * @param string $file_path Absolute path to the PHP template file.
 * @return array Array with 'before' and 'after' lists of part definitions.
 */
function ossark_parse_template_parts_from_file( string $file_path ): array {
    $result = [
        'before' => [],
        'after'  => [],
    ];

    if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
        return $result;
    }

    $code = file_get_contents( $file_path );
    if ( false === $code || empty( $code ) ) {
        return $result;
    }

    $tokens       = token_get_all( $code );
    $seen_content = false;
    $count        = count( $tokens );

    for ( $i = 0; $i < $count; $i++ ) {
        $token = $tokens[ $i ];

        // Check for the_content() call
        if ( is_array( $token ) && ( T_STRING === $token[0] ) && 'the_content' === strtolower( $token[1] ) ) {
            $j = $i + 1;
            while ( $j < $count && ( is_array( $tokens[ $j ] ) && ( T_WHITESPACE === $tokens[ $j ][0] || T_COMMENT === $tokens[ $j ][0] || T_DOC_COMMENT === $tokens[ $j ][0] ) ) ) {
                $j++;
            }
            if ( $j < $count && '(' === $tokens[ $j ] ) {
                $seen_content = true;
            }
            continue;
        }

        // Check for get_part() call
        if ( is_array( $token ) && ( T_STRING === $token[0] ) && 'get_part' === strtolower( $token[1] ) ) {
            $j = $i + 1;
            while ( $j < $count && ( is_array( $tokens[ $j ] ) && ( T_WHITESPACE === $tokens[ $j ][0] || T_COMMENT === $tokens[ $j ][0] || T_DOC_COMMENT === $tokens[ $j ][0] ) ) ) {
                $j++;
            }

            if ( $j >= $count || '(' !== $tokens[ $j ] ) {
                continue;
            }

            $j++;
            while ( $j < $count && ( is_array( $tokens[ $j ] ) && ( T_WHITESPACE === $tokens[ $j ][0] || T_COMMENT === $tokens[ $j ][0] || T_DOC_COMMENT === $tokens[ $j ][0] ) ) ) {
                $j++;
            }

            if ( $j >= $count || ! is_array( $tokens[ $j ] ) || T_CONSTANT_ENCAPSED_STRING !== $tokens[ $j ][0] ) {
                continue;
            }

            $slug = trim( $tokens[ $j ][1], "'\"" );
            $j++;

            while ( $j < $count && ( is_array( $tokens[ $j ] ) && ( T_WHITESPACE === $tokens[ $j ][0] || T_COMMENT === $tokens[ $j ][0] || T_DOC_COMMENT === $tokens[ $j ][0] ) ) ) {
                $j++;
            }

            $args_code = '';
            if ( $j < $count && ',' === $tokens[ $j ] ) {
                $j++;
                $paren_depth   = 1;
                $bracket_depth = 0;
                $brace_depth   = 0;

                while ( $j < $count ) {
                    $t     = $tokens[ $j ];
                    $t_val = is_array( $t ) ? $t[1] : $t;

                    if ( '(' === $t_val ) {
                        $paren_depth++;
                    } elseif ( ')' === $t_val ) {
                        $paren_depth--;
                        if ( 0 === $paren_depth ) {
                            break;
                        }
                    } elseif ( '[' === $t_val ) {
                        $bracket_depth++;
                    } elseif ( ']' === $t_val ) {
                        $bracket_depth--;
                    } elseif ( '{' === $t_val ) {
                        $brace_depth++;
                    } elseif ( '}' === $t_val ) {
                        $brace_depth--;
                    }

                    $args_code .= $t_val;
                    $j++;
                }
            }

            $args      = [];
            $args_code = trim( $args_code );
            if ( ! empty( $args_code ) && preg_match( '/^(\[|array\s*\()/i', $args_code ) ) {
                try {
                    $evaluated = @eval( 'return ' . $args_code . ';' );
                    if ( is_array( $evaluated ) ) {
                        $args = $evaluated;
                    }
                } catch ( \Throwable $e ) {
                    $args = [];
                }
            }

            $part_entry = [
                'slug'      => $slug,
                'args'      => $args,
                'args_code' => $args_code,
            ];

            if ( $seen_content ) {
                $result['after'][] = $part_entry;
            } else {
                $result['before'][] = $part_entry;
            }

            $i = $j;
        }
    }

    return $result;
}

/**
 * Render a single template part with editor wrapper chrome.
 *
 * @param string $slug          Template part slug (e.g. 'service-hero').
 * @param array  $args          Arguments passed to get_part.
 * @param int    $post_id       Current post ID.
 * @param string $template_name Source template file name.
 * @param string $position      'before' or 'after'.
 * @return string Rendered HTML.
 */
function ossark_render_single_template_part( string $slug, array $args, int $post_id, string $template_name, string $position ): string {
    $template_path = 'components/' . $slug . '/' . $slug . '.php';
    $resolved      = locate_template( $template_path );

    if ( ! $resolved ) {
        return '';
    }

    $is_component = 'component' === $position;

    $level = ob_get_level();
    // Standalone components get empty args, so quieten notices from missing keys.
    $prev_reporting = $is_component ? error_reporting( error_reporting() & ~( E_WARNING | E_NOTICE | E_DEPRECATED ) ) : null;
    ob_start();
    try {
        include $resolved;
        $rendered_content = ob_get_clean();
    } catch ( \Throwable $e ) {
        while ( ob_get_level() > $level ) {
            ob_end_clean();
        }
        $rendered_content = '';
    }
    if ( null !== $prev_reporting ) {
        error_reporting( $prev_reporting );
    }

    if ( false === $rendered_content || '' === trim( $rendered_content ) ) {
        // Standalone components may need arguments or live data to render;
        // still list them so every part is visible in the editor.
        if ( ! $is_component ) {
            return '';
        }
        $rendered_content = '<p class="ossark-editor-template-part__empty">This component renders nothing without arguments or data.</p>';
    }

    $labels = [
        'before'    => [ 'ossark-editor-template-part--before', 'Top Template Part' ],
        'after'     => [ 'ossark-editor-template-part--after', 'Bottom Template Part' ],
        'component' => [ 'ossark-editor-template-part--component', 'Component' ],
    ];

    list( $pos_class, $badge_tag ) = $labels[ $position ] ?? $labels['after'];

    return sprintf(
        '<div class="ossark-editor-template-part %s" data-part="%s">
            <div class="ossark-editor-template-part__badge">
                <span class="ossark-editor-template-part__badge-tag">%s</span>
                <span class="ossark-editor-template-part__badge-name">components/%s/</span>
                <span class="ossark-editor-template-part__badge-file">(from %s)</span>
            </div>
            <div class="ossark-editor-template-part__body">
                %s
            </div>
        </div>',
        esc_attr( $pos_class ),
        esc_attr( $slug ),
        esc_html( $badge_tag ),
        esc_html( $slug ),
        esc_html( $template_name ),
        $rendered_content
    );
}

/**
 * Get rendered template parts data for the Block Editor.
 *
 * @param int    $post_id       Post ID.
 * @param string $template_slug Optional explicit template slug.
 * @return array Template parts payload.
 */
function ossark_get_editor_template_parts_data( int $post_id = 0, string $template_slug = '' ): array {
    $template_file = ossark_resolve_post_template_file( $post_id, $template_slug );
    $template_name = $template_file ? basename( $template_file ) : '';

    $parsed = [
        'before' => [],
        'after'  => [],
    ];
    if ( $template_file ) {
        $parsed = ossark_parse_template_parts_from_file( $template_file );
    }

    // Every get_part() in header.php sits above the content, footer.php below it.
    $header_parts = [];
    $footer_parts = [];
    $header_file  = locate_template( 'header.php' );
    $footer_file  = locate_template( 'footer.php' );
    if ( $header_file ) {
        $found        = ossark_parse_template_parts_from_file( $header_file );
        $header_parts = array_merge( $found['before'], $found['after'] );
    }
    if ( $footer_file ) {
        $found        = ossark_parse_template_parts_from_file( $footer_file );
        $footer_parts = array_merge( $found['before'], $found['after'] );
    }

    // Any component folder not called by the files above (cookie banner, map, ...).
    $used_slugs = array_column(
        array_merge( $header_parts, $parsed['before'], $parsed['after'], $footer_parts ),
        'slug'
    );
    $component_parts = [];
    foreach ( glob( get_template_directory() . '/components/*', GLOB_ONLYDIR ) ?: [] as $dir ) {
        $slug = basename( $dir );
        if ( file_exists( $dir . '/' . $slug . '.php' ) && ! in_array( $slug, $used_slugs, true ) ) {
            $component_parts[] = [
                'slug' => $slug,
                'args' => [],
            ];
        }
    }

    // Set up post context so get_field(), the_title(), etc. receive correct values
    global $post;
    $original_post = $post;
    $editing_post  = $post_id ? get_post( $post_id ) : null;
    if ( $editing_post ) {
        $post = $editing_post;
        setup_postdata( $post );
    }

    $before_html = '';
    $after_html  = '';

    foreach ( $header_parts as $part ) {
        $before_html .= ossark_render_single_template_part( $part['slug'], $part['args'], $post_id, 'header.php', 'before' );
    }

    foreach ( $parsed['before'] as $part ) {
        $before_html .= ossark_render_single_template_part( $part['slug'], $part['args'], $post_id, $template_name, 'before' );
    }

    foreach ( $parsed['after'] as $part ) {
        $after_html .= ossark_render_single_template_part( $part['slug'], $part['args'], $post_id, $template_name, 'after' );
    }

    foreach ( $footer_parts as $part ) {
        $after_html .= ossark_render_single_template_part( $part['slug'], $part['args'], $post_id, 'footer.php', 'after' );
    }

    foreach ( $component_parts as $part ) {
        $after_html .= ossark_render_single_template_part( $part['slug'], $part['args'], $post_id, 'not called by a template', 'component' );
    }

    if ( $editing_post ) {
        $post = $original_post;
        if ( $original_post instanceof \WP_Post ) {
            setup_postdata( $original_post );
        } else {
            wp_reset_postdata();
        }
    }

    return [
        'has_parts'       => ( ! empty( $before_html ) || ! empty( $after_html ) ),
        'template_file'   => $template_name,
        'before_html'     => $before_html,
        'after_html'      => $after_html,
        'before_slugs'    => array_column( array_merge( $header_parts, $parsed['before'] ), 'slug' ),
        'after_slugs'     => array_column( array_merge( $parsed['after'], $footer_parts ), 'slug' ),
        'component_slugs' => array_column( $component_parts, 'slug' ),
    ];
}

/**
 * Enqueue Block Editor template parts configuration and initial data.
 */
function ossark_enqueue_editor_template_parts(): void {
    global $post;
    $post_id = 0;

    if ( isset( $_GET['post'] ) ) {
        $post_id = (int) $_GET['post'];
    } elseif ( $post instanceof \WP_Post ) {
        $post_id = $post->ID;
    }

    $initial_data = ossark_get_editor_template_parts_data( $post_id );

    $payload = [
        'postId'      => $post_id,
        'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'ossark_editor_parts_nonce' ),
        'initialData' => $initial_data,
    ];

    wp_localize_script( 'ossark-editor-bundle', 'ossarkTemplatePartsConfig', $payload );
    wp_localize_script( 'ossark-editor-chrome', 'ossarkTemplatePartsConfig', $payload );
}
add_action( 'enqueue_block_editor_assets', 'ossark_enqueue_editor_template_parts', 20 );

/**
 * AJAX endpoint to dynamically fetch rendered template parts when the
 * template selection changes or when the post is saved.
 */
function ossark_ajax_get_editor_template_parts(): void {
    check_ajax_referer( 'ossark_editor_parts_nonce', 'nonce' );

    $post_id       = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
    $template_slug = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : '';

    $data = ossark_get_editor_template_parts_data( $post_id, $template_slug );

    wp_send_json_success( $data );
}
add_action( 'wp_ajax_ossark_get_editor_template_parts', 'ossark_ajax_get_editor_template_parts' );
