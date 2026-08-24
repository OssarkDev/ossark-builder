import $ from 'jquery';

export function initSlider(context = document) {
	const $context = $(context);
	const $sliders = $context.hasClass('image-slider__slider')
		? $context
		: $context.find('.image-slider__slider');

	if (!$sliders.length) return;

	$sliders.each(function() {
		let slider = $(this);

		// If re-rendered in the editor, teardown existing slick instance first
		if (slider.hasClass('slick-initialized')) {
			slider.slick('unslick');
		}

		let next = slider.siblings('.image-slider__arrows').find('.image-slider__arrows__next');
		let prev = slider.siblings('.image-slider__arrows').find('.image-slider__arrows__prev');
		let counter = slider.siblings('.image-slider__numbers');

		/**
		 * Prevent focus inside aria-hidden slides.
		 * Sets tabindex="-1" on focusable elements within hidden slides
		 * to fix "blocked aria-hidden on an element because its descendant
		 * retained focus" browser warning.
		 */
		function updateSlideFocus() {
			slider.find('.slick-slide').each(function() {
				const $slide = $(this);
				const focusable = $slide.find('a, button, input, select, textarea, [tabindex]');
				if ($slide.attr('aria-hidden') === 'true') {
					focusable.attr('tabindex', '-1');
				} else {
					focusable.removeAttr('tabindex');
				}
			});
		}

		// Create counter
		slider.on('init reInit', function (event, slick, currentSlide, nextSlide) {
			//currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
			var i = (currentSlide ? currentSlide : 0) + 1;
			counter.html('<span class="current-slide">' + i + '</span><span class="slide-text"> of </span><span class="total-slides">' + slick.slideCount + '</span>');
			updateSlideFocus();
		}).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
			var i = (nextSlide ? nextSlide : 0) + 1;
			counter.find('.current-slide').text(i);
		}).on('afterChange', function () {
			updateSlideFocus();
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
			accessibility: false,
		});
	});
}

export function slider() {
	const sliders = document.querySelectorAll('.image-slider__slider');
	if (!sliders.length) return;

	// Slick is lazy-loaded so pages without sliders never pay for it.
	import(/* webpackChunkName: "slick" */ 'slick-carousel').then(() => {
		initSlider();
	});
}
