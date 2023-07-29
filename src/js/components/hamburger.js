export function hamburger() {
	$('.header__hamburger').on('click', function(){
		$(this).toggleClass('open');
	  });
}