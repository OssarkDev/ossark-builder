// animations
import { lottie } from './components/animations/lottie';
import { splitText } from './components/animations/splitText';
import { scroll } from './components/animations/scroll';
import { splitLines } from './components/animations/splitLines';
import { numbers } from './components/animations/numbers';
import { typewriter } from './components/animations/typewriter';
import { parallax } from './components/animations/parallax';

// custom JS
import { backToTop } from './components/parts/backToTop';
import { shareButton } from './components/parts/shareButton';
import { slider } from './components/blocks/slider';
import { hamburger } from './components/parts/hamburger';
import { formSuccessRedirect } from './components/parts/contact';
import { video } from './components/blocks/video';
import { scrollToAnchor } from './components/parts/scrollToAnchor';
import { activeMenuItem } from './components/parts/activeMenuItem';
import { testAjax } from './components/parts/testAjax';
import { map } from './components/blocks/map';
import { imageDimensions } from './components/parts/imageDimensions';

export function runAfterDomLoad() {
	imageDimensions();
	lottie();
	splitText();
	scroll();
	splitLines();
	numbers();
	typewriter();
	backToTop();
	shareButton();
	slider();
	hamburger();
	formSuccessRedirect();
	video();
	scrollToAnchor();
	activeMenuItem();
	testAjax();
	map();
	parallax();
}
