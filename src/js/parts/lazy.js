import LazyLoad from 'vanilla-lazyload';

// lazy loading
export function lazy() {
	const myLazyLoad = new LazyLoad({
		elements_selector: '.lazy',
		callback_loaded() {

		},
	});
}