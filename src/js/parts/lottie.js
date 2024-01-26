import $ from 'jquery';
import bodymovin from 'lottie-web/build/player/lottie_svg.min.js';

export function lottie() {
	$('.lottie').each(function () {
		const path = $(this).data('path');
		const animation = bodymovin.loadAnimation({
			container: this, // Required
			path, // Required
			renderer: 'svg', // Required
			loop: true, // Optional
			autoplay: true, // Optional
		});
	});
}