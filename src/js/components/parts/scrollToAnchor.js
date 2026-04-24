export function scrollToAnchor() {
	const anchorLinks = document.querySelectorAll('a[href^="#"]');

	if (!anchorLinks.length) return;

	anchorLinks.forEach(link => {
		link.addEventListener('click', (event) => {
			event.preventDefault();
			const target = document.querySelector(link.getAttribute('href'));
			if (target) {
				const offset = 200; // Adjust the offset value as needed
				const targetPosition = target.offsetTop - offset;
				window.scrollTo({
					top: targetPosition,
					behavior: 'smooth',
				});
			}
		});
	});
}