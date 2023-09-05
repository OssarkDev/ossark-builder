// libs
import "slick-carousel";
import 'slick-carousel/slick/slick.scss';

// custom JS
import { headerAnimation } from "./parts/header";
import { lottie } from "./parts/lottie";
import { backToTop } from "./components/backToTop";
import { shareButton } from "./components/shareButton";
import { slider } from "./components/slider";
import { hamburger } from "./components/hamburger";
import { formSuccessRedirect } from "./parts/contact";
import { video } from "./components/video";

export function runAfterDomLoad() {
	headerAnimation()
	lottie()
	backToTop()
	shareButton()
	slider()
	hamburger()
	formSuccessRedirect()
	video()
}
