import LazyLoad from 'vanilla-lazyload';

// lazy loading
export function lazy() {
	LazyLoad = new LazyLoad({
		elements_selector: '.lazy',
		callback_loaded() {

		},
	});
}
