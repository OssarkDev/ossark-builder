import { setCookie } from './cookie';

// Informational cookie bar. Server-side (include/cookie_banner.php) only
// outputs the markup when consent has not yet been given, so this just
// wires up the dismiss button and remembers the choice for a year.
export function cookieBanner() {
	const banner = document.querySelector('[data-cookie-banner]');
	if (!banner) return;

	const accept = banner.querySelector('[data-cookie-accept]');
	if (!accept) return;

	accept.addEventListener('click', () => {
		setCookie('cookie_consent', 'accepted', 365);
		banner.classList.add('cookie-banner--hidden');
		banner.addEventListener('transitionend', () => banner.remove(), { once: true });
	});
}
