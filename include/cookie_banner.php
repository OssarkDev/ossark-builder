<?php

defined( 'ABSPATH' ) || exit;

/*
	=====================
		Cookie banner
	=====================
	Renders the informational cookie notice via wp_footer, but only when
	the feature is enabled in Theme Options → Cookies AND the visitor has
	not yet dismissed it (server-side cookie check = no flash, and nothing
	output at all for returning visitors who already accepted).
*/
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	if ( ! get_field( 'cookie_banner_enabled', 'option' ) ) {
		return;
	}

	if ( ! empty( $_COOKIE['cookie_consent'] ) ) {
		return;
	}

	get_part( 'cookie-banner' );
} );
