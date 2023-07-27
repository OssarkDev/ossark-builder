// libs
import "slick-carousel";
import 'slick-carousel/slick/slick.scss';

// custom JS
import {example} from './components/example'
import { headerAnimation } from "./parts/header";
import { lottie } from "./parts/lottie";

export function runAfterDomLoad() {
 example()
 headerAnimation()
 lottie()
}
