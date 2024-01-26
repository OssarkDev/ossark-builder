export function slider() {
	let slider = $('.image-slider__slider');
	let next = $('.image-slider__arrows__next');
	let prev = $('.image-slider__arrows__prev');
	let counter = $('.image-slider__numbers');


	// Create counter
	slider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        counter.text(i + '/' + slick.slideCount);
    });

	// create slider
	slider.slick({
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		prevArrow: prev,
		nextArrow: next,
		arrows: true,
		easing: true,
		draggable: true,
	});

}
