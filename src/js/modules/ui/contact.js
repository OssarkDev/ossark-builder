export function formSuccessRedirect() {
	// Only bind if CF7 forms are on the page
	if (!document.querySelector('.wpcf7')) return;

	document.addEventListener('wpcf7mailsent', () => {
		const thankYouUrl = `${document.location.origin}/thank-you/`;
		window.location.href = thankYouUrl;
	  }, false);
}
