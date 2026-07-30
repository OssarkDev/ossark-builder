import 'slick-carousel/slick/slick.scss';
import Lenis from 'lenis';

import './scss/index.scss'; // custom styles

// Auto-import every _*.scss inside components/blocks/*/ — colocated block
// styles. Add a new block folder and its stylesheet is picked up automatically.
const blockStyles = require.context('../components/blocks', true, /_[^/]+\.scss$/);
blockStyles.keys().forEach(blockStyles);

import { runAfterDomLoad } from './js';

document.addEventListener('DOMContentLoaded', () => {
	runAfterDomLoad();

	// Smooth scroll
	const lenis = new Lenis();
	function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	}
	requestAnimationFrame(raf);

	// Trigger in-view for elements already visible on load
	window.addEventListener('load', () => {
		document.querySelectorAll('[data-scroll]:not(.in-view)').forEach(el => {
			if (el.getBoundingClientRect().top < window.innerHeight) {
				el.classList.add('in-view');
			}
		});
	});
});