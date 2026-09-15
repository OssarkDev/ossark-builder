<?php
/**
 * Cookie notice bar. Rendered via include/cookie_banner.php on wp_footer.
 * Content is managed in Theme Options → Cookies.
 */

$message      = get_field( 'cookie_banner_message', 'option' );
$button_label = get_field( 'cookie_banner_button_label', 'option' );
$link         = get_field( 'cookie_banner_link', 'option' );

if ( empty( $message ) ) {
	$message = 'We use cookies to enhance your browsing experience and analyse our traffic. By continuing to use this site, you consent to our use of cookies.';
}

if ( empty( $button_label ) ) {
	$button_label = 'Accept';
}
?>
<div class="cookie-banner" role="dialog" aria-live="polite" aria-label="Cookie notice" data-cookie-banner>
	<div class="container">
		<div class="row cookie-banner__row">
			<div class="col-12 cookie-banner__inner">
				<div class="cookie-banner__message">
					<?= wp_kses_post( wpautop( $message ) ); ?>
				</div>

				<div class="cookie-banner__actions">
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<?= get_button( $link, 'cookie-banner__link' ); ?>
					<?php endif; ?>

					<button type="button" class="btn cookie-banner__accept" data-cookie-accept>
						<span><?= esc_html( $button_label ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
