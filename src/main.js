import 'slick-carousel/slick/slick.scss';
import 'slick-carousel';
import Lenis from '@studio-freight/lenis';

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