export function hamburger() {
	$('.header__hamburger').on('click', function () {
		$(this).toggleClass('open');
		$('.header__mobile-menu').toggleClass('open');
		if ($(this).hasClass('open')) {
			$('html').css('overflow', 'hidden');
		} else {
			$('html').css('overflow', '');
		}
	  });
}
