export function formSuccessRedirect() {
	document.addEventListener('wpcf7mailsent', () => {
		const thankYouUrl = `${document.location.origin}/thank-you/`;
		console.log(`Form sent${thankYouUrl}`);
	  }, false);
}
