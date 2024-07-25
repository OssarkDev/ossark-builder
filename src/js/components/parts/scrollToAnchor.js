export function scrollToAnchor() {
	const anchorLinks = document.querySelectorAll('a[href^="#"]');

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
					duration: 2000 // Adjust the duration value as needed
				});
			}
		});
	});
}