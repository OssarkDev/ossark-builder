export function slider() {
	$('.js-slider').slick({
		infinite: true,
		slidesToShow: 2,
		slidesToScroll: 1,
		prevArrow: $('.prev'),
		nextArrow: $('.next'),
		variableWidth: false,
		responsive: [

			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1,
				},
			},
		],
	});

	$('.js-slider').on('beforeChange', (event, slick, currentSlide, nextSlide) => {
		$('.js-count').text(nextSlide + 1);
	});
}
