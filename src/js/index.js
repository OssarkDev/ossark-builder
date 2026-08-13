// animations
import { lottie } from './modules/animations/lottie';
import { splitText } from './modules/animations/splitText';
import { scroll } from './modules/animations/scroll';
import { splitLines } from './modules/animations/splitLines';
import { numbers } from './modules/animations/numbers';
import { typewriter } from './modules/animations/typewriter';
import { parallax } from './modules/animations/parallax';

// custom JS
import { backToTop } from './modules/ui/backToTop';
import { shareButton } from './modules/ui/shareButton';
import { slider } from './modules/vendor/slider';
import { hamburger } from './modules/ui/hamburger';
import { formSuccessRedirect } from './modules/ui/contact';
import { scrollToAnchor } from './modules/ui/scrollToAnchor';
import { activeMenuItem } from './modules/ui/activeMenuItem';
// import { testAjax } from './modules/ui/testAjax'; // demo code — enable only while testing AJAX
import { map } from './modules/vendor/map';
import { imageDimensions } from './modules/ui/imageDimensions';

// Auto-run colocated block JS: blocks/{slug}/{slug}.js
// Each file must `export default` an init function.
const blockScripts = require.context('../../blocks', true, /\.js$/);

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
	scrollToAnchor();
	activeMenuItem();
	// testAjax();
	map();
	parallax();

	blockScripts.keys().forEach(key => {
		const mod = blockScripts(key);
		if (typeof mod.default === 'function') mod.default();
	});
}
