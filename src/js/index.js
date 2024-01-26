// libs
import 'slick-carousel';
import 'slick-carousel/slick/slick.scss';

import AOS from 'aos';
import 'aos/dist/aos.css';

// custom JS
import { headerAnimation } from './parts/header';
import { lottie } from './parts/lottie';
import { backToTop } from './components/backToTop';
import { shareButton } from './components/shareButton';
import { slider } from './components/slider';
import { hamburger } from './components/hamburger';
import { formSuccessRedirect } from './parts/contact';
import { video } from './components/video';
import { splitText } from './animations/splitText';


/*
	Lazy script is not working
*/
// import { lazy } from './parts/lazy';

// initialise libs
AOS.init();

export function runAfterDomLoad() {
	headerAnimation();
	lottie();
	backToTop();
	shareButton();
	slider();
	hamburger();
	formSuccessRedirect();
	video();
	// lazy();
	splitText();

}
