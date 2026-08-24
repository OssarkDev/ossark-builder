import 'slick-carousel/slick/slick.scss';
import 'slick-carousel';
import $ from 'jquery';
import { initSlider } from './js/modules/vendor/slider';
import { initEditorTemplateParts } from './js/modules/editor/templateParts';

import './scss/editor.scss';

// Auto-import every _*.scss inside blocks/*/ so block styles
// also cascade inside the block-editor iframe.
const blockStyles = require.context('../blocks', true, /_[^/]+\.scss$/);
blockStyles.keys().forEach(blockStyles);

// Auto-import every _*.scss inside components/*/ so part styles
// also cascade inside the block-editor iframe.
const partStyles = require.context('../components', true, /_[^/]+\.scss$/);
partStyles.keys().forEach(partStyles);

// Initialize slider in Gutenberg editor when an ACF block preview renders
if (window.acf) {
	window.acf.addAction('render_block_preview', function ($block) {
		initSlider($block);
	});
}

// Initial DOM check for existing sliders
$(function () {
	initSlider();
	initEditorTemplateParts();
});
