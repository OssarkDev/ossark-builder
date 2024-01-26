// import 'slick-carousel/slick/slick.scss';
// import 'slick-carousel';
// import 'vanilla-lazyload';
import modularScroll from 'modularscroll';
import Lenis from '@studio-freight/lenis';

import './scss/index.scss'; // custom styles

import { runAfterDomLoad } from './js';

document.addEventListener('DOMContentLoaded', runAfterDomLoad);

// init scroll trigger
scroll = new modularScroll({
    el: document,
    name: 'scroll',
    class: 'animate',
    offset: 150,
    repeat: false
});

// smooth scroll
const lenis = new Lenis()
function raf(time) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}
requestAnimationFrame(raf)