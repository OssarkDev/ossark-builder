// lottie-web (~250KB) is lazy-loaded so pages without .lottie elements never pay for it.
export function lottie() {
	const elements = document.querySelectorAll('.lottie');

	if (!elements.length) return;

	import(/* webpackChunkName: "lottie" */ 'lottie-web/build/player/lottie_svg.min.js').then(({ default: bodymovin }) => {
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
	});
}