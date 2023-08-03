export function formSuccessRedirect() {
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		location = document.location.origin + '/thank-you/';
		console.log( 'Form sent' + location );
	  }, false );
}