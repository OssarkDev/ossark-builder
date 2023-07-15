<?php
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
 */
header('X-Frame-Options: SAMEORIGIN');
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
 */
header('X-XSS-Protection: 1; mode=block');
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
 */
header("Content-Security-Policy: frame-ancestors 'none'");
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
 */
header('X-Content-Type-Options: nosniff');
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
 */
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
 */
header('Referrer-Policy: no-referrer-when-downgrade');