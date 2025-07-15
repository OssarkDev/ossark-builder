export function hamburger() {
	const hamburgerButton = document.querySelector('.header__hamburger');
	const mobileMenu = document.querySelector('.header__mobile-menu');
	
	// Early return if elements don't exist
	if (!hamburgerButton || !mobileMenu) return;
	
	// State management
	let isMenuOpen = false;
	
	const toggleMenu = () => {
		isMenuOpen = !isMenuOpen;
		
		// Toggle classes
		hamburgerButton.classList.toggle('open', isMenuOpen);
		mobileMenu.classList.toggle('open', isMenuOpen);
		document.body.classList.toggle('menu-open', isMenuOpen);
		
		// Update ARIA attributes for accessibility
		hamburgerButton.setAttribute('aria-expanded', isMenuOpen);
		mobileMenu.setAttribute('aria-hidden', !isMenuOpen);
		
		// Manage body scroll
		if (isMenuOpen) {
			// Store current scroll position to restore later
			const scrollY = window.pageYOffset;
			document.body.style.position = 'fixed';
			document.body.style.top = `-${scrollY}px`;
			document.body.style.width = '100%';
		} else {
			// Restore scroll position
			const scrollY = document.body.style.top;
			document.body.style.position = '';
			document.body.style.top = '';
			document.body.style.width = '';
			window.scrollTo(0, parseInt(scrollY || '0', 10) * -1);
		}
	};
	
	const closeMenu = () => {
		if (isMenuOpen) {
			toggleMenu();
		}
	};
	
	// Event listeners
	hamburgerButton.addEventListener('click', toggleMenu);
	
	// Close menu when clicking outside
	document.addEventListener('click', (e) => {
		if (isMenuOpen && 
			!hamburgerButton.contains(e.target) && 
			!mobileMenu.contains(e.target)) {
			closeMenu();
		}
	});
	
	// Close menu on escape key
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && isMenuOpen) {
			closeMenu();
			hamburgerButton.focus(); // Return focus to hamburger button
		}
	});
	
	// Close menu on window resize (if screen becomes larger)
	window.addEventListener('resize', () => {
		if (window.innerWidth > 768 && isMenuOpen) { // Adjust breakpoint as needed
			closeMenu();
		}
	});
}
