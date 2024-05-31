export function backToTop() {
	const scrollIcon = $('.back-to-top-button');
	scrollIcon.on('click', () => {
		$('html, body').animate({
			scrollTop: 0,
		}, 1000);
	});
}
