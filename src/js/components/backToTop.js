export function backToTop() {
	let scrollIcon = $('.back-to-top-button');
	scrollIcon.on('click', function() {
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
	});
}