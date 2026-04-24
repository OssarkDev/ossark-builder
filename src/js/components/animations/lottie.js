import bodymovin from 'lottie-web/build/player/lottie_svg.min.js';

export function lottie() {
	const elements = document.querySelectorAll('.lottie');

	if (!elements.length) return;

	elements.forEach(el => {
		const path = el.dataset.path;
		bodymovin.loadAnimation({
			container: el,
			path,
			renderer: 'svg',
			loop: true,
			autoplay: true,
		});
	});
}