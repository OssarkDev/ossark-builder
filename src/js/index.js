// libs
import 'slick-carousel';
import 'slick-carousel/slick/slick.scss';

// custom JS
import { lottie } from './components/parts/lottie';
import { backToTop } from './components/parts/backToTop';
import { shareButton } from './components/parts/shareButton';
import { slider } from './components/blocks/slider';
import { hamburger } from './components/parts/hamburger';
import { formSuccessRedirect } from './components/parts/contact';
import { video } from './components/blocks/video';
import { splitText } from './components/parts/splitText';
import { scrollToAnchor } from './components/parts/scrollToAnchor';
import { scroll } from './components/parts/scroll';
import { activeMenuItem } from './components/parts/activeMenuItem';
import { testAjax } from './components/parts/testAjax';
import { map } from './components/blocks/map';
import { imageDimensions } from './components/parts/imageDimensions';

export function runAfterDomLoad() {
	imageDimensions();
	lottie();
	backToTop();
	shareButton();
	slider();
	hamburger();
	formSuccessRedirect();
	video();
	splitText();
	scrollToAnchor();
	scroll();
	activeMenuItem();
	testAjax();
	map();
}
