export function formSuccessRedirect() {
	document.addEventListener('wpcf7mailsent', () => {
		const thankYouUrl = `${document.location.origin}/thank-you/`;
		window.location.href = thankYouUrl;
	  }, false);
}
