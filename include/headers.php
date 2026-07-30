<?php

defined( 'ABSPATH' ) || exit;

/**
 * Security Hardening
 * ──────────────────
 * 1. Per-request CSP nonce generator
 * 2. Universal security headers (every request)
 * 3. Content Security Policy — nonce-based (frontend only)
 * 4. Output buffer — auto-inject nonces into <script> & <style> tags
 * 5. WordPress native nonce attribute filters (WP 5.7+)
 * 6. Subresource Integrity (SRI) for CDN scripts
 * 7. Google Maps — async loading
 */


/* ═══════════════════════════════════════════════════════════════════
   1. PER-REQUEST NONCE GENERATOR
   ═══════════════════════════════════════════════════════════════════ */

/**
 * Generate and cache a unique cryptographic nonce for this request.
 * Used in the CSP header and injected into every <script> tag.
 */
function ossark_csp_nonce(): string {
    static $nonce = null;
    if ( $nonce === null ) {
        $nonce = bin2hex( random_bytes( 16 ) ); // 32-char hex string
    }
    return $nonce;
}


/* ═══════════════════════════════════════════════════════════════════
   2. UNIVERSAL SECURITY HEADERS (every request)
   ═══════════════════════════════════════════════════════════════════
   Called at file-load time so they apply to ALL requests
   (frontend, admin, AJAX, REST, cron).
*/

header( 'X-Frame-Options: SAMEORIGIN' );
header( 'X-Content-Type-Options: nosniff' );
header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
header( 'Referrer-Policy: strict-origin-when-cross-origin' );
header( 'X-XSS-Protection: 0' );
header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self), payment=()' );


/* ═══════════════════════════════════════════════════════════════════
   3. CONTENT SECURITY POLICY — NONCE-BASED (frontend only)
   ═══════════════════════════════════════════════════════════════════ */

function ossark_send_csp_header(): void {

    // Skip admin, AJAX, cron, REST API
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron()
         || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    $nonce = ossark_csp_nonce();

    // Detect local development (skip upgrade-insecure-requests for self-signed certs)
    $host     = $_SERVER['HTTP_HOST'] ?? '';
    $is_local = str_contains( $host, '.local' )
             || str_contains( $host, 'localhost' )
             || str_starts_with( $host, '127.' )
             || str_starts_with( $host, '192.168.' );

    $directives = [
        "default-src"     => "'none'",
        "script-src"      => "'self' 'nonce-{$nonce}' 'strict-dynamic'"
                           . " https://maps.googleapis.com"
                           . " https://www.google.com"
                           . " https://www.gstatic.com"
                           . " https://www.googletagmanager.com"
                           . " https://www.google-analytics.com",
        "style-src"       => "'self' 'unsafe-inline'"
                           . " https://fonts.googleapis.com",
        "img-src"         => "'self' data: https: blob:",
        "font-src"        => "'self' data:"
                           . " https://fonts.gstatic.com",
        "connect-src"     => "'self'"
                           . " https://maps.googleapis.com"
                           . " https://www.google-analytics.com"
                           . " https://www.googletagmanager.com",
        "frame-src"       => "'self'"
                           . " https://www.youtube.com"
                           . " https://www.google.com"
                           . " https://consent.cookiebot.com",
        "frame-ancestors" => "'none'",
        "object-src"      => "'none'",
        "base-uri"        => "'self'",
        "form-action"     => "'self'",
        "manifest-src"    => "'self'",
    ];

    // Only enforce HTTPS upgrades on production (self-signed certs break it locally)
    if ( ! $is_local ) {
        $directives['upgrade-insecure-requests'] = '';
    }

    // Assemble the header value
    $parts = [];
    foreach ( $directives as $key => $val ) {
        $parts[] = $val !== '' ? "{$key} {$val}" : $key;
    }

    header( 'Content-Security-Policy: ' . implode( '; ', $parts ) );
}
add_action( 'send_headers', 'ossark_send_csp_header' );


/* ═══════════════════════════════════════════════════════════════════
   4. OUTPUT BUFFER — AUTO-INJECT NONCES
   ═══════════════════════════════════════════════════════════════════
   Hooks template_redirect at priority 0 to buffer the full HTML
   output, then regex-injects the nonce into every <script> and
   <style> tag that doesn't already have one.

   Covers: wp_localize_script(), Theme-Options injected scripts,
   wp_add_inline_style(), and any hardcoded <script> tags in templates.
*/

function ossark_start_output_buffer(): void {
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
        return;
    }
    ob_start( 'ossark_inject_nonces' );
}

/**
 * ob_start callback — inject nonce into every <script> and <style>
 * tag that doesn't already carry one.
 */
function ossark_inject_nonces( string $html ): string {
    $nonce = esc_attr( ossark_csp_nonce() );

    // <script> tags without a nonce attribute
    $html = preg_replace_callback(
        '/<script\b(?![^>]*\bnonce\s*=)([^>]*)>/i',
        fn( $m ) => "<script nonce=\"{$nonce}\"{$m[1]}>",
        $html
    );

    // <style> tags without a nonce attribute
    $html = preg_replace_callback(
        '/<style\b(?![^>]*\bnonce\s*=)([^>]*)>/i',
        fn( $m ) => "<style nonce=\"{$nonce}\"{$m[1]}>",
        $html
    );

    return $html;
}
add_action( 'template_redirect', 'ossark_start_output_buffer', 0 );


/* ═══════════════════════════════════════════════════════════════════
   5. WORDPRESS NATIVE NONCE ATTRIBUTE FILTERS (WP 5.7+)
   ═══════════════════════════════════════════════════════════════════
   Belt-and-suspenders: also feed the nonce through WP's own
   wp_get_script_tag() / wp_get_inline_script_tag() helpers.
*/

function ossark_script_nonce_attribute( array $attributes ): array {
    $attributes['nonce'] = ossark_csp_nonce();
    return $attributes;
}
add_filter( 'wp_script_attributes',        'ossark_script_nonce_attribute' );
add_filter( 'wp_inline_script_attributes', 'ossark_script_nonce_attribute' );


/* ═══════════════════════════════════════════════════════════════════
   6. SUBRESOURCE INTEGRITY (SRI)
   ═══════════════════════════════════════════════════════════════════
   Adds integrity + crossorigin="anonymous" to external CDN scripts.

   When changing a CDN script version, regenerate the hash:
   openssl dgst -sha256 -binary <file> | openssl base64 -A
*/

/**
 * Map of WP script handles → SRI hashes.
 * jQuery now served from same-origin (WP bundled) so no hash needed.
 */
function ossark_sri_hashes(): array {
    return [
        // 'handle' => 'sha256-...',
    ];
}

function ossark_add_sri( string $tag, string $handle, string $src ): string {
    $hashes = ossark_sri_hashes();

    if ( ! isset( $hashes[ $handle ] ) || str_contains( $tag, 'integrity=' ) ) {
        return $tag;
    }

    $integrity = esc_attr( $hashes[ $handle ] );

    return str_replace(
        ' src=',
        " integrity=\"{$integrity}\" crossorigin=\"anonymous\" src=",
        $tag
    );
}
add_filter( 'script_loader_tag', 'ossark_add_sri', 10, 3 );


/* ═══════════════════════════════════════════════════════════════════
   7. GOOGLE MAPS — ASYNC LOADING
   ═══════════════════════════════════════════════════════════════════
   Appends &loading=async to the Maps API URL to eliminate the
   "loaded directly without loading=async" console warning.
*/

function ossark_maps_async( string $tag, string $handle, string $src ): string {
    if ( ! str_contains( $src, 'maps.googleapis.com' ) || str_contains( $tag, 'loading=async' ) ) {
        return $tag;
    }

    // Append loading=async before the closing quote of the src attribute
    return preg_replace(
        '/(maps\.googleapis\.com\/[^"\']*?)(["\'])/i',
        '$1&amp;loading=async$2',
        $tag
    );
}
add_filter( 'script_loader_tag', 'ossark_maps_async', 10, 3 );

