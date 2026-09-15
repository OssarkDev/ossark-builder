<?php
/*
    Template Name: Cookie Statement
*/

get_header();

/**
 * Editable variables (Theme Options → Cookies). Each falls back to a
 * sensible site default so the statement reads correctly out of the box.
 */
$company = get_field( 'cookie_company_name', 'option' );
$website = get_field( 'cookie_website_url', 'option' );
$email   = get_field( 'cookie_contact_email', 'option' );
$updated = get_field( 'cookie_last_updated', 'option' );
$extra   = get_field( 'cookie_statement_extra', 'option' );

$company = $company ? $company : get_bloginfo( 'name' );
$website = $website ? $website : home_url();
$email   = $email ? $email : get_bloginfo( 'admin_email' );

$company_esc  = esc_html( $company );
$website_host = esc_html( wp_parse_url( $website, PHP_URL_HOST ) ?: $website );
?>

<section class="section cookie-statement">
	<div class="container">
		<div class="row">
			<div class="col-10-offset-1">
				<h1><?php the_title(); ?></h1>

				<?php if ( $updated ) : ?>
					<p><em>Last updated: <?= esc_html( $updated ); ?></em></p>
				<?php endif; ?>

				<?php the_content(); ?>

				<h2>What are cookies?</h2>
				<p>Cookies are small text files that are placed on your device (computer, tablet or mobile) when you visit a website. They are widely used to make websites work, or work more efficiently, as well as to provide information to the site owners.</p>

				<h2>How <?= $company_esc; ?> uses cookies</h2>
				<p>When you use and access <?= $website_host; ?>, we may place a number of cookie files in your web browser. We use cookies to operate our website, remember your preferences, understand how visitors interact with our content and improve your overall experience.</p>

				<h2>The types of cookies we use</h2>
				<ul>
					<li><strong>Strictly necessary cookies.</strong> Required for the operation of our website. They include, for example, cookies that enable core functionality and remember your cookie preferences.</li>
					<li><strong>Functional cookies.</strong> Used to recognise you when you return to our website and to remember choices you make, providing enhanced, more personal features.</li>
					<li><strong>Analytics &amp; performance cookies.</strong> Allow us to recognise and count the number of visitors and see how visitors move around our website, helping us improve the way it works.</li>
					<li><strong>Marketing cookies.</strong> Record your visit to our website, the pages you visit and the links you follow, so we can make our website and any advertising more relevant to your interests.</li>
				</ul>

				<h2>Third-party cookies</h2>
				<p>In addition to our own cookies, we may also use various third-party cookies to report usage statistics and deliver content. These may include services such as analytics providers, embedded media and social platforms, each of which sets its own cookies governed by its own privacy policy.</p>

				<h2>Managing your cookies</h2>
				<p>You can control and manage cookies in your browser settings at any time. Most browsers allow you to refuse or delete cookies; the methods for doing so vary from browser to browser. Please note that if you disable cookies, some parts of <?= $website_host; ?> may not function properly.</p>

				<h2>Changes to this Cookie Statement</h2>
				<p>We may update this Cookie Statement from time to time to reflect changes to the cookies we use or for other operational, legal or regulatory reasons. Please revisit this page regularly to stay informed about our use of cookies.</p>

				<h2>Contact us</h2>
				<p>If you have any questions about our use of cookies, please contact us at <a href="mailto:<?= esc_attr( $email ); ?>"><?= esc_html( $email ); ?></a>.</p>

				<?php if ( $extra ) : ?>
					<div class="cookie-statement__extra">
						<?= wp_kses_post( $extra ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>


<?php get_footer(); ?>