import 'slick-carousel/slick/slick.scss';
import 'slick-carousel';
import Lenis from 'lenis';

import './scss/index.scss'; // custom styles

import { runAfterDomLoad } from './js';

document.addEventListener('DOMContentLoaded', runAfterDomLoad);

// smooth scroll
const lenis = new Lenis()
function raf(time) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}
requestAnimationFrame(raf)

// on page load scroll 1px down to trigger scroll animations
document.addEventListener('DOMContentLoaded', () => {
  window.addEventListener('load', () => {
	window.scrollBy(0, 1);
  });
});