<?php
/*
    Template Name: Privacy Policy
*/

get_header();

/**
 * Shared identity variables are reused from Theme Options → Cookies so the
 * company name, website and contact email only need to be entered once. The
 * feature toggles (Theme Options → Privacy Policy) control which sections are
 * rendered, so the policy matches what the website actually does.
 */
$company = get_field( 'cookie_company_name', 'option' );
$website = get_field( 'cookie_website_url', 'option' );
$email   = get_field( 'cookie_contact_email', 'option' );
$updated = get_field( 'privacy_last_updated', 'option' );
$updated = $updated ? $updated : get_field( 'cookie_last_updated', 'option' );
$extra   = get_field( 'privacy_statement_extra', 'option' );

$company = $company ? $company : get_bloginfo( 'name' );
$website = $website ? $website : home_url();
$email   = $email ? $email : get_bloginfo( 'admin_email' );

$company_esc  = esc_html( $company );
$website_host = esc_html( wp_parse_url( $website, PHP_URL_HOST ) ?: $website );

// Feature toggles.
$has_forms     = get_field( 'privacy_feature_forms', 'option' );
$has_analytics = get_field( 'privacy_feature_analytics', 'option' );
$has_marketing = get_field( 'privacy_feature_marketing', 'option' );
$has_cookies   = get_field( 'privacy_feature_cookies', 'option' );
$has_accounts  = get_field( 'privacy_feature_accounts', 'option' );
$has_ecommerce = get_field( 'privacy_feature_ecommerce', 'option' );
$has_comments  = get_field( 'privacy_feature_comments', 'option' );
$has_embeds    = get_field( 'privacy_feature_embeds', 'option' );

$cookie_link = get_field( 'privacy_cookie_statement_link', 'option' );
?>

<section class="section privacy-policy">
	<div class="container">
		<div class="row">
			<div class="col-10-offset-1">
				<h1><?php the_title(); ?></h1>

				<?php if ( $updated ) : ?>
					<p><em>Last updated: <?= esc_html( $updated ); ?></em></p>
				<?php endif; ?>

				<?php the_content(); ?>

				<h2>Introduction</h2>
				<p>This Privacy Policy explains how <?= $company_esc; ?> ("we", "us" or "our") collects, uses and protects the personal information you provide when you visit or interact with <?= $website_host; ?>. We are committed to protecting your privacy and handling your data in an open and transparent manner, in line with applicable data protection law, including the General Data Protection Regulation (GDPR).</p>

				<h2>Information we collect</h2>
				<p>We may collect and process the following types of personal information:</p>
				<ul>
					<li><strong>Contact details</strong> such as your name, email address, telephone number and postal address.</li>
					<li><strong>Technical data</strong> such as your IP address, browser type, device information and pages visited.</li>
					<li><strong>Communications</strong> such as the content of messages you send us and our correspondence with you.</li>
					<?php if ( $has_accounts ) : ?>
						<li><strong>Account data</strong> such as your username, password and profile preferences.</li>
					<?php endif; ?>
					<?php if ( $has_ecommerce ) : ?>
						<li><strong>Transaction data</strong> such as your billing and delivery details and records of products or services you have purchased.</li>
					<?php endif; ?>
				</ul>

				<?php if ( $has_forms ) : ?>
					<h2>Information you provide through forms</h2>
					<p>When you complete a contact, enquiry or booking form on our website, we collect the information you submit so that we can respond to your request and provide the service you have asked for. We use this information only for the purpose for which it was provided and do not use it for unrelated marketing without your consent.</p>
				<?php endif; ?>

				<h2>How we use your information</h2>
				<p>We use the personal information we collect to:</p>
				<ul>
					<li>respond to your enquiries and provide the information, products or services you request;</li>
					<li>operate, maintain and improve our website and services;</li>
					<li>comply with our legal and regulatory obligations;</li>
					<li>protect the security and integrity of our website.</li>
					<?php if ( $has_marketing ) : ?>
						<li>send you newsletters and marketing communications where you have asked us to do so.</li>
					<?php endif; ?>
				</ul>

				<h2>Legal basis for processing</h2>
				<p>We process your personal data where we have a lawful basis to do so. Depending on the circumstances, this may be your consent, the performance of a contract with you, compliance with a legal obligation, or our legitimate interests in operating and improving our business, provided these are not overridden by your rights.</p>

				<?php if ( $has_marketing ) : ?>
					<h2>Marketing communications</h2>
					<p>If you have subscribed to our newsletter or otherwise consented to receive marketing, we may use your contact details to send you updates, offers and news. You can withdraw your consent and unsubscribe at any time by using the link in any marketing email or by contacting us directly. Withdrawing consent does not affect the lawfulness of any processing carried out before you withdrew it.</p>
				<?php endif; ?>

				<?php if ( $has_ecommerce ) : ?>
					<h2>Purchases and payments</h2>
					<p>When you make a purchase, we collect the information needed to process your order, including your billing and delivery details. Payments are handled by secure third-party payment providers, and your full card details are never stored on our servers. We retain records of your transactions to fulfil orders, provide support and meet our legal and accounting obligations.</p>
				<?php endif; ?>

				<?php if ( $has_accounts ) : ?>
					<h2>Your account</h2>
					<p>If you create an account with us, we store the details associated with your account so that you can log in, manage your preferences and access our services. You are responsible for keeping your login details secure. You can update or request deletion of your account information at any time by contacting us.</p>
				<?php endif; ?>

				<?php if ( $has_comments ) : ?>
					<h2>Comments</h2>
					<p>When you leave a comment on our website, we collect the information shown in the comment form, along with your IP address and browser details, to help detect spam and moderate discussion. Comments and their associated metadata may be retained indefinitely so that follow-up comments can be recognised and displayed.</p>
				<?php endif; ?>

				<?php if ( $has_analytics ) : ?>
					<h2>Analytics</h2>
					<p>We use analytics services to understand how visitors use our website, such as which pages are most popular and how people navigate between them. These services collect information such as your IP address, device and browser data, and the pages you view, typically using cookies. This information helps us measure and improve the performance of our website. Analytics data is processed in aggregate and is not used to identify you personally.</p>
				<?php endif; ?>

				<?php if ( $has_embeds ) : ?>
					<h2>Third-party content and embeds</h2>
					<p>Our pages may include embedded content from other websites, such as maps, videos or social media feeds. Embedded content behaves in the same way as if you had visited the other website directly, and those websites may collect data about you, use cookies and monitor your interaction with the embedded content. We do not control these third-party services, and their use of your data is governed by their own privacy policies.</p>
				<?php endif; ?>

				<?php if ( $has_cookies ) : ?>
					<h2>Cookies</h2>
					<p>Our website uses cookies and similar technologies to operate correctly, remember your preferences and understand how our site is used.
						<?php if ( $cookie_link && ! empty( $cookie_link['url'] ) ) : ?>
							For full details of the cookies we use and how to manage them, please see our <a href="<?= esc_url( $cookie_link['url'] ); ?>"<?= ! empty( $cookie_link['target'] ) ? ' target="' . esc_attr( $cookie_link['target'] ) . '"' : ''; ?>><?= esc_html( $cookie_link['title'] ? $cookie_link['title'] : 'Cookie Statement' ); ?></a>.
						<?php else : ?>
							For full details of the cookies we use and how to manage them, please see our Cookie Statement.
						<?php endif; ?>
					</p>
				<?php endif; ?>

				<h2>Sharing your information</h2>
				<p>We do not sell your personal information. We may share it with trusted third-party service providers who help us operate our website and deliver our services, and only to the extent necessary for them to perform those services on our behalf. We may also disclose your information where required to do so by law or to protect our legal rights.</p>

				<h2>Data retention</h2>
				<p>We keep your personal information only for as long as is necessary to fulfil the purposes for which it was collected, including to satisfy any legal, accounting or reporting requirements. When your information is no longer required, we will securely delete or anonymise it.</p>

				<h2>Your rights</h2>
				<p>Subject to applicable law, you have the right to:</p>
				<ul>
					<li>request access to the personal data we hold about you;</li>
					<li>ask us to correct inaccurate or incomplete data;</li>
					<li>request the erasure of your personal data;</li>
					<li>object to or restrict our processing of your data;</li>
					<li>request the transfer of your data to another provider;</li>
					<li>withdraw your consent at any time where processing is based on consent.</li>
				</ul>
				<p>To exercise any of these rights, please contact us using the details below. You also have the right to lodge a complaint with your local data protection authority.</p>

				<h2>Security</h2>
				<p>We take appropriate technical and organisational measures to protect your personal information against unauthorised access, loss, misuse or alteration. However, no method of transmission over the internet is completely secure, and we cannot guarantee the absolute security of information transmitted to our website.</p>

				<h2>Changes to this Privacy Policy</h2>
				<p>We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational, legal or regulatory reasons. Any changes will be posted on this page, so please revisit it regularly to stay informed.</p>

				<h2>Contact us</h2>
				<p>If you have any questions about this Privacy Policy or how we handle your personal information, please contact us at <a href="mailto:<?= esc_attr( $email ); ?>"><?= esc_html( $email ); ?></a>.</p>

				<?php if ( $extra ) : ?>
					<div class="privacy-policy__extra">
						<?= wp_kses_post( $extra ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
