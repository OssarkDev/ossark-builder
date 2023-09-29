export function hamburger() {
	$('.header__hamburger').on('click', function () {
		$(this).toggleClass('open');
		$('.header__mobile-menu').toggleClass('open');
		$('html').css('overflow', 'hidden');
	  });
}
