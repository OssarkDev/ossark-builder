export function slider() {
	let slider = $('.slider');
	let next = $('.slider__arrows__next');
	let prev = $('.slider__arrows__prev');
	let counter = $('.slider__numbers');


    // Create counter
    slider.on('init reInit', function (event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        counter.html('<span class="current-slide">' + i + '</span><span class="slide-text"> of </span><span class="total-slides">' + slick.slideCount + '</span>');
    }).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        var i = (nextSlide ? nextSlide : 0) + 1;
        counter.find('.current-slide').text(i);
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
